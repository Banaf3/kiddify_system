<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Core Gemini API Client for Google AI Studio keys
 * Handles API communication, model discovery, and automatic fallback
 */
class GeminiClient
{
    private ?string $apiKey;
    private string $baseUrl;
    private int $timeout;
    private array $fallbackModels;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
        $this->baseUrl = config('services.gemini.base_url', 'https://generativelanguage.googleapis.com/v1beta');
        $this->timeout = config('services.gemini.timeout', 20);
        $this->fallbackModels = [
            'gemini-1.5-flash',
            'gemini-1.5-pro',
            'gemini-pro'
        ];
    }

    /**
     * List all available models from Gemini API
     *
     * @return array{success: bool, models?: array, error?: string, status?: int, body?: string}
     */
    public function listModels(): array
    {
        if (empty($this->apiKey)) {
            return [
                'success' => false,
                'error' => 'API key not configured'
            ];
        }

        try {
            $url = "{$this->baseUrl}/models?key={$this->apiKey}";

            $response = Http::timeout($this->timeout)->get($url);

            if ($response->failed()) {
                Log::error('Gemini listModels failed', [
                    'status' => $response->status(),
                    'url' => $this->baseUrl . '/models',
                    'body' => $response->body()
                ]);

                return [
                    'success' => false,
                    'error' => 'Failed to fetch models',
                    'status' => $response->status(),
                    'body' => $response->body()
                ];
            }

            $data = $response->json();
            $models = collect($data['models'] ?? [])
                ->pluck('name')
                ->map(fn($name) => str_replace('models/', '', $name))
                ->toArray();

            return [
                'success' => true,
                'models' => $models
            ];

        } catch (\Exception $e) {
            Log::error('Gemini listModels exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Generate content with automatic model fallback
     *
     * @param array $contents Message contents in Gemini format
     * @param string|null $preferredModel Model to try first (from .env)
     * @return array{success: bool, message?: string, model?: string, code?: int, error?: string}
     */
    public function generateContent(array $contents, ?string $preferredModel = null): array
    {
        if (empty($this->apiKey)) {
            return $this->errorResponse('CONFIG_ERROR', 'GEMINI_API_KEY not set in .env', 500);
        }

        // Get working model from cache or determine new one
        $cachedModel = Cache::get('gemini_working_model');
        $model = $preferredModel ?? config('services.gemini.model', 'gemini-1.5-flash');

        // Try cached model first if available
        if ($cachedModel && $cachedModel !== $model) {
            $result = $this->tryGenerateContent($cachedModel, $contents);
            if ($result['success']) {
                return $result;
            }
        }

        // Try preferred model
        $result = $this->tryGenerateContent($model, $contents);
        if ($result['success']) {
            Cache::put('gemini_working_model', $model, now()->addHour());
            return $result;
        }

        // If 404, try fallback models
        if (isset($result['code']) && $result['code'] === 404) {
            foreach ($this->fallbackModels as $fallbackModel) {
                if ($fallbackModel === $model) {
                    continue; // Already tried
                }

                Log::info("Trying fallback model: {$fallbackModel}");

                $result = $this->tryGenerateContent($fallbackModel, $contents);
                if ($result['success']) {
                    Cache::put('gemini_working_model', $fallbackModel, now()->addHour());
                    Log::info("Fallback successful with model: {$fallbackModel}");
                    return $result;
                }
            }
        }

        // All attempts failed
        return $result;
    }

    /**
     * Try to generate content with a specific model
     *
     * @param string $model Model name
     * @param array $contents Message contents
     * @return array
     */
    private function tryGenerateContent(string $model, array $contents): array
    {
        try {
            $url = "{$this->baseUrl}/models/{$model}:generateContent?key={$this->apiKey}";

            $payload = [
                'contents' => $contents,
                'generationConfig' => [
                    'temperature' => 0.7,
                    'topK' => 40,
                    'topP' => 0.95,
                    'maxOutputTokens' => 500,
                ],
                'safetySettings' => [
                    [
                        'category' => 'HARM_CATEGORY_HARASSMENT',
                        'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                    ],
                    [
                        'category' => 'HARM_CATEGORY_HATE_SPEECH',
                        'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                    ],
                    [
                        'category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT',
                        'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                    ],
                    [
                        'category' => 'HARM_CATEGORY_DANGEROUS_CONTENT',
                        'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                    ],
                ],
            ];

            $response = Http::timeout($this->timeout)->post($url, $payload);

            if ($response->failed()) {
                $statusCode = $response->status();
                $responseBody = $response->body();

                Log::error('Gemini generateContent failed', [
                    'status' => $statusCode,
                    'model' => $model,
                    'endpoint' => "{$this->baseUrl}/models/{$model}:generateContent",
                    'response_body' => $responseBody
                ]);

                return $this->parseErrorResponse($statusCode, $responseBody, $model);
            }

            $data = $response->json();

            // Extract text from response
            $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if (!$text) {
                Log::error('Gemini returned empty response', [
                    'model' => $model,
                    'data' => $data
                ]);

                return $this->errorResponse('EMPTY_RESPONSE', 'Empty response from Gemini', 500);
            }

            return [
                'success' => true,
                'message' => trim($text),
                'model' => $model
            ];

        } catch (\Exception $e) {
            Log::error('Gemini generateContent exception', [
                'model' => $model,
                'exception' => get_class($e),
                'message' => $e->getMessage()
            ]);

            return $this->errorResponse('EXCEPTION', $e->getMessage(), 500);
        }
    }

    /**
     * Parse error response from Gemini API
     */
    private function parseErrorResponse(int $statusCode, string $responseBody, string $model): array
    {
        $isDev = app()->environment('local', 'development');

        return match ($statusCode) {
            401, 403 => $this->errorResponse(
                'AUTH_ERROR',
                $isDev
                ? 'Invalid Gemini API key or API not enabled. Check GEMINI_API_KEY in .env'
                : 'Authentication error',
                $statusCode
            ),
            404 => $this->errorResponse(
                'NOT_FOUND',
                $isDev
                ? "Model '{$model}' not found OR wrong base URL. Check GEMINI_BASE_URL and run: php artisan gemini:models"
                : 'Service configuration error',
                $statusCode
            ),
            429 => $this->errorResponse(
                'RATE_LIMIT',
                $isDev
                ? 'Rate limit exceeded. Gemini API allows 60 req/min. Wait 60 seconds.'
                : 'Too many requests. Please wait.',
                $statusCode
            ),
            500, 503 => $this->errorResponse(
                'SERVER_ERROR',
                $isDev
                ? "Gemini server error ({$statusCode}). Try again later or check logs."
                : 'Service temporarily unavailable',
                $statusCode
            ),
            default => $this->errorResponse(
                'API_ERROR',
                $isDev
                ? "Gemini API error (Status: {$statusCode}). Check logs for details."
                : 'An error occurred',
                $statusCode
            )
        };
    }

    /**
     * Create standardized error response
     */
    private function errorResponse(string $error, string $message, int $code): array
    {
        return [
            'success' => false,
            'error' => $error,
            'message' => $message,
            'code' => $code
        ];
    }

    /**
     * Get base URL for debugging
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * Check if API key is configured
     */
    public function hasApiKey(): bool
    {
        return !empty($this->apiKey);
    }
}
