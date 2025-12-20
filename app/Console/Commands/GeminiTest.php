<?php

namespace App\Console\Commands;

use App\Services\GeminiClient;
use Illuminate\Console\Command;

class GeminiTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gemini:test {prompt : The prompt to send to Gemini}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Gemini API with a prompt';

    /**
     * Execute the console command.
     */
    public function handle(GeminiClient $client): int
    {
        $prompt = $this->argument('prompt');

        $this->info('🧪 Testing Gemini API...');
        $this->newLine();

        // Display configuration
        $this->info('Configuration:');
        $this->line("  Base URL: {$client->getBaseUrl()}");
        $this->line("  API Key: " . ($client->hasApiKey() ? '✓ Configured' : '✗ Missing'));
        $this->line("  Preferred Model: " . config('services.gemini.model', 'gemini-1.5-flash'));
        $this->line("  Prompt: \"{$prompt}\"");
        $this->newLine();

        if (!$client->hasApiKey()) {
            $this->error('❌ GEMINI_API_KEY not set in .env');
            $this->line('Add to .env: GEMINI_API_KEY=your-key-here');
            return self::FAILURE;
        }

        // Build simple content array
        $contents = [
            [
                'role' => 'user',
                'parts' => [['text' => $prompt]]
            ]
        ];

        $this->info('📤 Sending request...');

        // Generate content
        $result = $client->generateContent($contents);

        $this->newLine();

        if (!$result['success']) {
            $this->error('❌ Request failed');
            $this->newLine();

            if (isset($result['code'])) {
                $this->line("HTTP Status: {$result['code']}");
            }

            if (isset($result['error'])) {
                $this->line("Error Type: {$result['error']}");
            }

            if (isset($result['message'])) {
                $this->line("Message: {$result['message']}");
            }

            $this->newLine();
            $this->comment('💡 Check storage/logs/laravel.log for detailed error information');

            return self::FAILURE;
        }

        // Success
        $this->info('✅ Success!');

        if (isset($result['model'])) {
            $this->line("Model Used: {$result['model']}");
        }

        $this->newLine();
        $this->info('📥 Response:');
        $this->newLine();
        $this->line($result['message']);
        $this->newLine();

        return self::SUCCESS;
    }
}
