<?php

namespace App\Console\Commands;

use App\Services\GeminiClient;
use Illuminate\Console\Command;

class GeminiModels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gemini:models';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all available Gemini models from the API';

    /**
     * Execute the console command.
     */
    public function handle(GeminiClient $client): int
    {
        $this->info('🔍 Fetching available Gemini models...');
        $this->newLine();

        // Display configuration
        $this->info('Configuration:');
        $this->line("  Base URL: {$client->getBaseUrl()}");
        $this->line("  API Key: " . ($client->hasApiKey() ? '✓ Configured' : '✗ Missing'));
        $this->line("  Preferred Model: " . config('services.gemini.model', 'gemini-1.5-flash'));
        $this->newLine();

        if (!$client->hasApiKey()) {
            $this->error('❌ GEMINI_API_KEY not set in .env');
            $this->line('Add to .env: GEMINI_API_KEY=your-key-here');
            return self::FAILURE;
        }

        // Fetch models
        $result = $client->listModels();

        if (!$result['success']) {
            $this->error('❌ Failed to fetch models');
            $this->newLine();

            if (isset($result['status'])) {
                $this->line("HTTP Status: {$result['status']}");
            }

            if (isset($result['body'])) {
                $this->line("Response Body:");
                $this->line($result['body']);
            }

            if (isset($result['error'])) {
                $this->line("Error: {$result['error']}");
            }

            $this->newLine();
            $this->comment('💡 Troubleshooting:');
            $this->line('  1. Verify GEMINI_API_KEY is correct');
            $this->line('  2. Check GEMINI_BASE_URL (default: https://generativelanguage.googleapis.com/v1beta)');
            $this->line('  3. Ensure API is enabled in Google AI Studio');

            return self::FAILURE;
        }

        // Display models
        $models = $result['models'];

        if (empty($models)) {
            $this->warning('⚠️  No models found');
            return self::SUCCESS;
        }

        $this->info('✅ Available Models:');
        $this->newLine();

        $preferredModel = config('services.gemini.model', 'gemini-1.5-flash');

        foreach ($models as $model) {
            $isPreferred = $model === $preferredModel ? ' ⭐ (configured in .env)' : '';
            $this->line("  • {$model}{$isPreferred}");
        }

        $this->newLine();
        $this->info("Total: " . count($models) . " models");

        $this->newLine();
        $this->comment('💡 To test a model, run:');
        $this->line('  php artisan gemini:test "Hello"');

        return self::SUCCESS;
    }
}
