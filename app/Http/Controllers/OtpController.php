<?php

namespace App\Http\Controllers;

use App\Mail\OtpCodeMail;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

class OtpController extends Controller
{
    protected OtpService $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Show OTP verification form
     */
    public function showVerify(Request $request)
    {
        $purpose = $request->query('purpose', 'login');
        $redirect = $request->query('redirect', null);

        Log::info('OTP showVerify called', [
            'purpose' => $purpose,
            'redirect' => $redirect,
            'has_pending_login' => session()->has('pending_user_id'),
            'is_authenticated' => Auth::check()
        ]);

        // Check if there's a pending login
        if ($purpose === 'login' && !session()->has('pending_user_id')) {
            Log::info('No pending login, redirecting to login');
            return redirect()->route('login')->with('error', 'Session expired. Please login again.');
        }

        // For profile OTP, ensure user is logged in
        if ($purpose === 'profile' && !Auth::check()) {
            Log::info('Profile OTP but not authenticated, redirecting to login');
            return redirect()->route('login');
        }

        $user = $purpose === 'login'
            ? User::find(session('pending_user_id'))
            : Auth::user();

        if (!$user) {
            Log::info('No user found, redirecting to login');
            return redirect()->route('login')->with('error', 'Invalid session.');
        }

        $maskedEmail = $this->otpService->maskEmail($user->email);

        Log::info('Showing OTP verify view', [
            'user_id' => $user->id,
            'purpose' => $purpose,
            'masked_email' => $maskedEmail
        ]);

        return view('auth.otp-verify', compact('purpose', 'redirect', 'maskedEmail'));
    }

    /**
     * Verify OTP code
     */
    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
            'purpose' => 'required|in:login,profile',
        ]);

        $purpose = $request->purpose;

        // Rate limiting for verify attempts
        $key = 'otp-verify:' . $request->ip() . ':' . $purpose;

        if (RateLimiter::tooManyAttempts($key, 10)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors(['otp' => "Too many attempts. Please try again in {$seconds} seconds."]);
        }

        RateLimiter::hit($key, 600); // 10 minutes

        // Get user based on purpose
        if ($purpose === 'login') {
            if (!session()->has('pending_user_id')) {
                return redirect()->route('login')->with('error', 'Session expired. Please login again.');
            }
            $user = User::find(session('pending_user_id'));
        } else {
            $user = Auth::user();
        }

        if (!$user) {
            return back()->withErrors(['otp' => 'Invalid session.']);
        }

        // Verify OTP
        $result = $this->otpService->verify($user, $request->otp, $purpose);

        if (!$result['success']) {
            return back()->withErrors(['otp' => $result['message']]);
        }

        // Handle successful verification based on purpose
        if ($purpose === 'login') {
            // Complete login
            Auth::loginUsingId($user->id, remember: session('remember_me', false));
            session()->forget(['pending_user_id', 'remember_me']);

            RateLimiter::clear($key);

            // Redirect to dashboard based on role
            return $this->redirectToDashboard($user);
        } else {
            // Profile OTP verified
            $user->update(['profile_otp_verified_at' => now()]);

            RateLimiter::clear($key);

            $redirect = $request->query('redirect', route('profile.edit'));
            return redirect($redirect)->with('success', 'Profile access verified.');
        }
    }

    /**
     * Resend OTP code
     */
    public function resend(Request $request)
    {
        $request->validate([
            'purpose' => 'required|in:login,profile',
        ]);

        $purpose = $request->purpose;

        // Get user based on purpose
        if ($purpose === 'login') {
            if (!session()->has('pending_user_id')) {
                return redirect()->route('login')->with('error', 'Session expired. Please login again.');
            }
            $user = User::find(session('pending_user_id'));
        } else {
            $user = Auth::user();
        }

        if (!$user) {
            return back()->with('error', 'Invalid session.');
        }

        // Rate limiting for OTP requests
        $key = 'otp-request:' . $user->id . ':' . $purpose . ':' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->with('error', "Too many OTP requests. Please try again in " . ceil($seconds / 60) . " minutes.");
        }

        // Check service-level rate limiting
        $canRequest = $this->otpService->canRequest($user, $purpose);
        if (!$canRequest['can_request']) {
            return back()->with('error', $canRequest['message']);
        }

        RateLimiter::hit($key, 600); // 10 minutes

        // Generate and send new OTP
        $otp = $this->otpService->generate($user, $purpose);

        try {
            // Use send() not queue() - requires no worker
            Mail::to($user->email)->send(new OtpCodeMail($user, $otp, $purpose));

            Log::info('OTP resend successful', [
                'user_id' => $user->id,
                'email' => $user->email,
                'purpose' => $purpose
            ]);

            return back()->with('success', 'A new OTP code has been sent to your email. Check spam folder if not received.');
        } catch (\Exception $e) {
            Log::error('Failed to resend OTP email', [
                'user_id' => $user->id,
                'email' => $user->email,
                'purpose' => $purpose,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Failed to send OTP: ' . ($e->getMessage() ?: 'Unknown error'));
        }
    }

    /**
     * Redirect to appropriate dashboard based on user role
     */
    protected function redirectToDashboard(User $user)
    {
        // Use existing dashboard routing logic
        return redirect()->intended(route('dashboard'));
    }
}
