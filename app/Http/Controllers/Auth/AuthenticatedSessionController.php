<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Mail\OtpCodeMail;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Validate credentials but don't log in yet
        $request->authenticate();

        // Get the authenticated user
        $user = Auth::user();

        // Log them out immediately (we'll log in after OTP)
        Auth::logout();

        // Store pending login info in session
        $request->session()->put('pending_user_id', $user->id);
        $request->session()->put('remember_me', $request->boolean('remember'));

        // Generate and send OTP
        $otpService = new OtpService();
        $otp = $otpService->generate($user, 'login');

        try {
            // Send synchronously (works fast with Resend API)
            Mail::to($user->email)->send(new OtpCodeMail($user, $otp, 'login'));

            Log::info('OTP email sent successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'purpose' => 'login'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to queue OTP email', [
                'user_id' => $user->id,
                'email' => $user->email,
                'purpose' => 'login',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // ALWAYS show error to user so we can diagnose the issue
            return back()->withErrors(['email' => 'Failed to send OTP: ' . $e->getMessage()]);
        }

        return redirect()->route('otp.verify', ['purpose' => 'login'])
            ->with('success', 'OTP code sent to your email. Check spam folder if not received.');
    }

    /**
     * Show role selection page
     */
    public function showRoleSelection(): View
    {
        return view('auth.select-role');
    }

    /**
     * Handle role selection
     */
    public function selectRole(Request $request): RedirectResponse
    {
        $request->validate([
            'role' => 'required|in:teacher,parent'
        ]);

        // Store selected role in session
        $request->session()->put('selected_role', $request->role);

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
