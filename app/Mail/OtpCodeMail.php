<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpCodeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    // Force queue connection to database
    public $connection = 'database';

    public string $otp;
    public string $purpose;
    public User $user;
    public string $maskedEmail;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $otp, string $purpose = 'login')
    {
        $this->user = $user;
        $this->otp = $otp;
        $this->purpose = $purpose;
        $this->maskedEmail = $this->maskEmail($user->email);
    }

    /**
     * Mask email address for privacy
     */
    private function maskEmail(string $email): string
    {
        $parts = explode('@', $email);
        $name = $parts[0];
        $domain = $parts[1];

        $nameLength = strlen($name);
        if ($nameLength <= 2) {
            $maskedName = str_repeat('*', $nameLength);
        } else {
            $visibleChars = min(2, floor($nameLength / 3));
            $maskedName = substr($name, 0, $visibleChars) . str_repeat('*', $nameLength - $visibleChars);
        }

        return $maskedName . '@' . $domain;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Kiddify OTP Code - ' . ucfirst($this->purpose) . ' Verification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.otp-code',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
