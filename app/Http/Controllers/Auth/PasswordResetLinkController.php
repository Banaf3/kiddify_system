<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Validate mail configuration
        $mailer = config('mail.default');
        if (in_array($mailer, ['log', 'array'])) {
            Log::error('Password reset attempted with invalid mailer', [
                'mailer' => $mailer,
                'email' => $request->email
            ]);

            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Mail system is not configured. Please contact support.']);
        }

        // Log the password reset attempt
        Log::info('Password reset link requested', [
            'email' => $request->email,
            'ip' => $request->ip()
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        try {
            $status = Password::sendResetLink(
                $request->only('email')
            );

            if ($status == Password::RESET_LINK_SENT) {
                Log::info('Password reset link sent successfully', [
                    'email' => $request->email
                ]);
            }

            return $status == Password::RESET_LINK_SENT
                        ? back()->with('status', 'We have emailed your password reset link!')
                        : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);
        } catch (\Exception $e) {
            Log::error('Failed to send password reset email', [
                'email' => $request->email,
                'error' => $e->getMessage()
            ]);

            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Failed to send reset link. Please try again or contact support.']);
        }
    }
}
