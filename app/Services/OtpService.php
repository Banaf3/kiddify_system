<?php

namespace App\Services;

use App\Models\OtpToken;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class OtpService
{
    /**
     * Validate SMTP configuration before sending OTP
     */
    private function validateSmtpConfig(): void
    {
        $mailer = config('mail.default');
        $host = config('mail.mailers.smtp.host');
        $port = config('mail.mailers.smtp.port');
        $username = config('mail.mailers.smtp.username');
        $encryption = config('mail.mailers.smtp.encryption');
        $fromAddress = config('mail.from.address');

        Log::info('OTP Service - Current Mail Configuration', [
            'mailer' => $mailer,
            'host' => $host,
            'port' => $port,
            'encryption' => $encryption,
            'username' => $username,
            'from_address' => $fromAddress,
        ]);

        // Check if mailer is log or array (not suitable for real emails)
        if (in_array($mailer, ['log', 'array'])) {
            throw new \RuntimeException(
                "Mail is not configured for sending real emails. Current mailer: '{$mailer}'. " .
                "Please set MAIL_MAILER=smtp in your .env file and configure Gmail SMTP credentials. " .
                "After updating .env, run: php artisan config:clear"
            );
        }

        // Check if required SMTP settings are present
        if ($mailer === 'smtp') {
            if (empty($host) || $host === '127.0.0.1' || $host === 'localhost') {
                throw new \RuntimeException(
                    "SMTP host is not configured properly. Current host: '{$host}'. " .
                    "Please set MAIL_HOST=smtp.gmail.com in your .env file. " .
                    "After updating .env, run: php artisan config:clear"
                );
            }

            if (empty($username)) {
                throw new \RuntimeException(
                    "SMTP username is not configured. Please set MAIL_USERNAME in your .env file. " .
                    "After updating .env, run: php artisan config:clear"
                );
            }

            if (empty($fromAddress) || $fromAddress === 'hello@example.com') {
                throw new \RuntimeException(
                    "MAIL_FROM_ADDRESS is not configured properly. Current: '{$fromAddress}'. " .
                    "Please set your Gmail address in MAIL_FROM_ADDRESS in your .env file. " .
                    "After updating .env, run: php artisan config:clear"
                );
            }
        }
    }

    /**
     * Generate and store a new OTP for the user
     */
    public function generate(User $user, string $purpose = 'login'): string
    {
        // Validate SMTP configuration before generating OTP
        $this->validateSmtpConfig();

        // Invalidate any existing unused OTP for this user and purpose
        OtpToken::where('user_id', $user->id)
            ->where('purpose', $purpose)
            ->whereNull('used_at')
            ->update(['used_at' => now()]);

        // Generate 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store hashed OTP
        OtpToken::create([
            'user_id' => $user->id,
            'purpose' => $purpose,
            'otp_hash' => Hash::make($otp),
            'sent_to_email' => $user->email,
            'expires_at' => Carbon::now()->addMinutes(10),
            'attempts' => 0,
        ]);

        return $otp;
    }

    /**
     * Verify OTP code
     */
    public function verify(User $user, string $otp, string $purpose = 'login'): array
    {
        // Get the latest unused OTP for this user and purpose
        $otpToken = OtpToken::where('user_id', $user->id)
            ->where('purpose', $purpose)
            ->whereNull('used_at')
            ->latest()
            ->first();

        if (!$otpToken) {
            return [
                'success' => false,
                'message' => 'No valid OTP found. Please request a new one.',
            ];
        }

        // Check if expired
        if ($otpToken->isExpired()) {
            return [
                'success' => false,
                'message' => 'OTP has expired. Please request a new one.',
            ];
        }

        // Increment attempts
        $otpToken->increment('attempts');

        // Check if too many attempts
        if ($otpToken->attempts > 10) {
            $otpToken->update(['used_at' => now()]);
            return [
                'success' => false,
                'message' => 'Too many failed attempts. Please request a new OTP.',
            ];
        }

        // Verify OTP
        if (!Hash::check($otp, $otpToken->otp_hash)) {
            return [
                'success' => false,
                'message' => 'Invalid OTP code. Please try again.',
                'attempts_left' => max(0, 10 - $otpToken->attempts),
            ];
        }

        // Mark as used
        $otpToken->update(['used_at' => now()]);

        return [
            'success' => true,
            'message' => 'OTP verified successfully.',
        ];
    }

    /**
     * Check if user can request a new OTP (rate limiting)
     */
    public function canRequest(User $user, string $purpose = 'login'): array
    {
        $recentOtps = OtpToken::where('user_id', $user->id)
            ->where('purpose', $purpose)
            ->where('created_at', '>=', Carbon::now()->subMinutes(10))
            ->count();

        if ($recentOtps >= 5) {
            return [
                'can_request' => false,
                'message' => 'Too many OTP requests. Please wait 10 minutes before requesting again.',
            ];
        }

        return [
            'can_request' => true,
        ];
    }

    /**
     * Get masked email for display
     */
    public function maskEmail(string $email): string
    {
        $parts = explode('@', $email);
        $name = $parts[0];
        $domain = $parts[1] ?? '';

        if (strlen($name) <= 2) {
            $masked = str_repeat('*', strlen($name));
        } else {
            $masked = substr($name, 0, 2) . str_repeat('*', strlen($name) - 2);
        }

        return $masked . '@' . $domain;
    }
}
