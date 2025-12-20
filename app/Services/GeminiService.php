<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class GeminiService
{
    private GeminiClient $client;
    private string $systemPrompt;

    public function __construct(GeminiClient $client)
    {
        $this->client = $client;
        $this->systemPrompt = $this->buildSystemPrompt();
    }

    /**
     * Build the system prompt with constraints.
     */
    private function buildSystemPrompt(): string
    {
        return <<<PROMPT
You are "Kiddify Assistant", a friendly AI helper for the Kiddify preschool learning management system.

**YOUR SCOPE - YOU MAY ONLY HELP WITH:**

1. **Preschool Learning Content:**
   - Letters (A-Z), numbers (0-9), colors, shapes
   - Basic English and Arabic vocabulary
   - Simple educational activities for ages 3-6
   - Songs, rhymes, and age-appropriate stories

2. **Kiddify System Help:**
   - Login & OTP verification process
   - Managing courses and timetables
   - Creating and grading assessments
   - Managing sections and questions
   - Parent-child account linking
   - Admin user management features
   - Understanding icons, buttons, and navigation
   - How to reset passwords
   - How to approve new users

**STRICT RULES:**
- Always answer in simple, clear language suitable for teachers and parents
- If asked about topics outside preschool learning or Kiddify system (politics, hacking, adult content, violence, etc.), politely refuse and redirect:
  "I'm designed to help only with preschool learning and using the Kiddify system. Would you like to know about letters, numbers, or how to use a specific Kiddify feature?"
- Keep responses concise (2-4 sentences when possible)
- Be encouraging and friendly
- Never provide code, technical implementations, or administrative credentials

**KIDDIFY SYSTEM MODULES:**
- **Authentication:** Secure login with OTP email verification
- **Courses:** Create and manage courses with names and descriptions
- **Timetable:** Schedule classes and activities
- **Sections:** Organize students into classroom sections
- **Assessments:** Create quizzes with multiple-choice questions
- **Student Scores:** View and grade student performance
- **Parent Portal:** Parents can view their child's progress
- **Admin Approvals:** Admins approve new teacher and parent registrations
PROMPT;
    }

    /**
     * Generate a response from Gemini API.
     *
     * @param string $userMessage
     * @param array $conversationHistory
     * @return array{success: bool, message: string, code?: int, error?: string}
     */
    public function generateResponse(string $userMessage, array $conversationHistory = []): array
    {
        // Check if client has API key
        if (!$this->client->hasApiKey()) {
            $isDev = app()->environment('local', 'development');

            Log::error('Gemini API Key Missing', [
                'environment' => app()->environment(),
                'message' => 'GEMINI_API_KEY not configured in .env'
            ]);

            return [
                'success' => false,
                'code' => 500,
                'error' => 'CONFIG_ERROR',
                'message' => $isDev
                    ? 'GEMINI_API_KEY is missing. Set it in .env and run: php artisan optimize:clear'
                    : 'AI Assistant configuration error. Please contact support.'
            ];
        }

        // Build conversation context
        $contents = $this->buildConversationContents($userMessage, $conversationHistory);

        // Use client with automatic model fallback
        $result = $this->client->generateContent($contents);

        if ($result['success']) {
            return [
                'success' => true,
                'message' => $result['message']
            ];
        }

        // Return error from client
        return [
            'success' => false,
            'code' => $result['code'] ?? 500,
            'message' => $result['message'] ?? 'An error occurred',
            'error' => $result['error'] ?? 'UNKNOWN'
        ];
    }

    /**
     * Build conversation contents for API request.
     */
    private function buildConversationContents(string $userMessage, array $conversationHistory): array
    {
        $contents = [];

        // Add system prompt as first user message
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $this->systemPrompt]]
        ];

        $contents[] = [
            'role' => 'model',
            'parts' => [['text' => 'I understand. I am Kiddify Assistant, and I will only help with preschool learning content and Kiddify system features. I will politely refuse any out-of-scope requests.']]
        ];

        // Add conversation history (last 5 exchanges to keep context manageable)
        $recentHistory = array_slice($conversationHistory, -10);

        foreach ($recentHistory as $msg) {
            $role = $msg['role'] === 'user' ? 'user' : 'model';
            $contents[] = [
                'role' => $role,
                'parts' => [['text' => $msg['content']]]
            ];
        }

        // Add current user message
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $userMessage]]
        ];

        return $contents;
    }
}
