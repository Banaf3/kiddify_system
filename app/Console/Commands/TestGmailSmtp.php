<?php

namespace App\Console\Commands;

use App\Mail\OtpCodeMail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class TestGmailSmtp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gmail:test {email : The email address to send test to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Gmail SMTP configuration by sending a test OTP email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        $this->info('Testing Gmail SMTP configuration...');
        $this->info('Target email: ' . $email);
        $this->newLine();

        // Check current mail configuration
        $mailer = config('mail.default');
        $host = config('mail.mailers.smtp.host');
        $port = config('mail.mailers.smtp.port');
        $encryption = config('mail.mailers.smtp.encryption');
        $username = config('mail.mailers.smtp.username');
        $fromAddress = config('mail.from.address');
        $fromName = config('mail.from.name');

        $this->line('Current Configuration:');
        $this->table(
            ['Setting', 'Value'],
            [
                ['Default Mailer', $mailer],
                ['SMTP Host', $host ?? 'NOT SET'],
                ['SMTP Port', $port ?? 'NOT SET'],
                ['Encryption', $encryption ?? 'NOT SET'],
                ['Username', $username ?? 'NOT SET'],
                ['From Address', $fromAddress ?? 'NOT SET'],
                ['From Name', $fromName ?? 'NOT SET'],
            ]
        );
        $this->newLine();

        // Validate configuration before attempting to send
        $errors = [];

        if ($mailer === 'log') {
            $errors[] = "Mailer is set to 'log' - emails will be logged, not sent!";
        }

        if ($mailer === 'array') {
            $errors[] = "Mailer is set to 'array' - emails will not be sent!";
        }

        if (empty($host) || $host === '127.0.0.1' || $host === 'localhost') {
            $errors[] = "SMTP host is not configured for Gmail (current: {$host})";
        }

        if (empty($username)) {
            $errors[] = "SMTP username (MAIL_USERNAME) is not set";
        }

        if (empty($fromAddress) || $fromAddress === 'hello@example.com') {
            $errors[] = "From address is not configured (current: {$fromAddress})";
        }

        if (!empty($errors)) {
            $this->error('✗ Configuration errors detected:');
            foreach ($errors as $error) {
                $this->line('   • ' . $error);
            }
            $this->newLine();
            $this->line('Please update your .env file with Gmail SMTP settings:');
            $this->line('   MAIL_MAILER=smtp');
            $this->line('   MAIL_HOST=smtp.gmail.com');
            $this->line('   MAIL_PORT=587');
            $this->line('   MAIL_USERNAME=your-email@gmail.com');
            $this->line('   MAIL_PASSWORD=your-16-char-app-password');
            $this->line('   MAIL_ENCRYPTION=tls');
            $this->line('   MAIL_FROM_ADDRESS=your-email@gmail.com');
            $this->newLine();
            $this->line('Then run: php artisan config:clear');
            return self::FAILURE;
        }

        // Find or create test user
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->warn('User not found with email: ' . $email);
            $this->warn('Using a temporary user object for testing...');
            $user = new User([
                'name' => 'Test User',
                'email' => $email,
            ]);
        } else {
            $this->info('Found user: ' . $user->name);
        }

        $this->newLine();
        $this->info('Sending test OTP email via SMTP...');

        try {
            // Test OTP code
            $testOtp = '123456';

            // Force use of SMTP mailer (not log or array)
            Mail::mailer('smtp')->to($email)->send(new OtpCodeMail($user, $testOtp, 'login'));

            $this->newLine();
            $this->info('✓ SUCCESS - Email sent successfully!');
            $this->info('Check inbox (and spam folder) for email with OTP: ' . $testOtp);
            $this->newLine();
            $this->info('SMTP Configuration Used:');
            $this->line('  Mailer: smtp (forced)');
            $this->line('  Host: ' . $host);
            $this->line('  Port: ' . $port);
            $this->line('  Encryption: ' . $encryption);
            $this->line('  Username: ' . $username);
            $this->line('  From: ' . $fromAddress);

            return self::SUCCESS;

        } catch (\Exception $e) {
            $this->newLine();
            $this->error('✗ FAILED - Email sending failed!');
            $this->newLine();
            $this->error('Error: ' . $e->getMessage());
            $this->newLine();
            $this->warn('Common fixes:');
            $this->line('1. Run: php artisan optimize:clear');
            $this->line('2. Check .env file has correct Gmail credentials');
            $this->line('3. Use Gmail App Password (not regular password)');
            $this->line('4. Enable 2FA and generate app password at:');
            $this->line('   https://myaccount.google.com/apppasswords');
            $this->line('5. Check firewall allows port 587 (or try port 465)');
            $this->newLine();
            $this->error('Full error trace:');
            $this->line($e->getTraceAsString());

            return self::FAILURE;
        }
    }
}
