<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\OtpCodeMail;
use App\Models\User;

class DebugGmailQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug:gmail-queue {action : dispatch or work} {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Debug Gmail Queue: Dispatch job or Run worker once';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');
        $email = $this->argument('email') ?? 'khaledqmhan@gmail.com';

        $this->info("Starting Debug: Action = {$action}");
        $this->info("Current Mail Config:");
        $this->table(
            ['Config', 'Value'],
            [
                ['MAIL_MAILER', config('mail.default')],
                ['MAIL_HOST', config('mail.mailers.smtp.host')],
                ['MAIL_PORT', config('mail.mailers.smtp.port')],
                ['MAIL_ENCRYPTION', config('mail.mailers.smtp.encryption')],
                ['QUEUE_CONNECTION', config('queue.default')],
            ]
        );

        if ($action === 'dispatch') {
            $user = User::where('email', $email)->first();
            if (!$user) {
                // Create temp user if not exists for testing
                $user = new User();
                $user->email = $email;
                $user->name = 'Debug User';
            }

            $this->info("Dispatching email to {$email}...");

            try {
                // Force connection to database just to be safe
                Mail::to($user)->queue(new OtpCodeMail($user, '123456', 'debug'));
                $this->info('✅ Job Dispatched to Queue!');
                $this->info('Now run: php artisan debug:gmail-queue work');
            } catch (\Exception $e) {
                $this->error('❌ Dispatch Failed: ' . $e->getMessage());
            }

        } elseif ($action === 'work') {
            $this->info("Running queue worker (once)...");

            // We use call() to run the artisan command and capture output if possible, 
            // but queue:work writes to stdout usually.
            $exitCode = $this->call('queue:work', [
                '--connection' => 'database',
                '--once' => true,
                '--tries' => 3,
                '--timeout' => 60
            ]);

            if ($exitCode === 0) {
                $this->info('✅ Worker finished successfully (check log for actual email status).');
            } else {
                $this->error('❌ Worker returned error code: ' . $exitCode);
            }
        } else {
            $this->error("Unknown action. Use 'dispatch' or 'work'.");
        }
    }
}
