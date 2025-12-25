<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Services\GeminiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AiChatController extends Controller
{
    private GeminiService $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    /**
     * Get chat history for the authenticated user.
     *
     * @return JsonResponse
     */
    public function history(): JsonResponse
    {
        $messages = ChatMessage::getRecentMessages(Auth::id(), 30);

        return response()->json([
            'success' => true,
            'messages' => $messages->map(function ($message) {
                return [
                    'id' => $message->id,
                    'role' => $message->role,
                    'content' => $message->content,
                    'created_at' => $message->created_at->format('Y-m-d H:i:s'),
                ];
            })
        ]);
    }

    /**
     * Send a message and get AI response.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function sendMessage(Request $request): JsonResponse
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:800|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'VALIDATION_ERROR',
                'message' => $validator->errors()->first()
            ], 422);
        }

        $userMessage = trim($request->input('message'));
        $userId = Auth::id();

        try {
            // Store user message
            $userChatMessage = ChatMessage::create([
                'user_id' => $userId,
                'role' => 'user',
                'content' => $userMessage,
            ]);

            // Get conversation history for context
            $history = ChatMessage::where('user_id', $userId)
                ->where('id', '<', $userChatMessage->id)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->reverse()
                ->map(function ($msg) {
                    return [
                        'role' => $msg->role,
                        'content' => $msg->content
                    ];
                })
                ->toArray();

            // Get AI response
            $response = $this->geminiService->generateResponse($userMessage, $history);

            if (!$response['success']) {
                $httpCode = $response['code'] ?? 500;

                Log::error('AI Chat Error', [
                    'user_id' => $userId,
                    'endpoint' => '/ai/chat',
                    'error_code' => $response['error'] ?? 'UNKNOWN',
                    'status_code' => $httpCode,
                    'message' => $response['message']
                ]);

                return response()->json([
                    'success' => false,
                    'code' => $httpCode,
                    'error' => $response['error'] ?? 'UNKNOWN_ERROR',
                    'message' => $response['message']
                ], $httpCode >= 400 && $httpCode < 600 ? $httpCode : 500);
            }

            // Store assistant message
            $assistantMessage = ChatMessage::create([
                'user_id' => $userId,
                'role' => 'assistant',
                'content' => $response['message'],
            ]);

            return response()->json([
                'success' => true,
                'user_message' => [
                    'id' => $userChatMessage->id,
                    'role' => 'user',
                    'content' => $userChatMessage->content,
                    'created_at' => $userChatMessage->created_at->format('Y-m-d H:i:s'),
                ],
                'assistant_message' => [
                    'id' => $assistantMessage->id,
                    'role' => 'assistant',
                    'content' => $assistantMessage->content,
                    'created_at' => $assistantMessage->created_at->format('Y-m-d H:i:s'),
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('AI Chat Controller Exception', [
                'user_id' => $userId,
                'endpoint' => '/ai/chat',
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            $isDev = app()->environment('local', 'development');

            return response()->json([
                'success' => false,
                'code' => 500,
                'error' => 'SERVER_ERROR',
                'message' => $isDev
                    ? 'Server error: ' . $e->getMessage() . '. Check storage/logs/laravel.log'
                    : 'An unexpected error occurred. Please try again.'
            ], 500);
        }
    }

    /**
     * Clear chat history for the authenticated user.
     *
     * @return JsonResponse
     */
    public function clearHistory(): JsonResponse
    {
        try {
            $deletedCount = ChatMessage::clearUserMessages(Auth::id());

            return response()->json([
                'success' => true,
                'message' => 'Chat history cleared successfully.',
                'deleted_count' => $deletedCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'SERVER_ERROR',
                'message' => 'Failed to clear chat history. Please try again.'
            ], 500);
        }
    }
}
