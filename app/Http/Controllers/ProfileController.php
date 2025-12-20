<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Mail\OtpCodeMail;
use App\Services\OtpService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show the verification page before accessing profile
     */
    public function showVerify()
    {
        return view('profile.verify');
    }

    /**
     * Verify user credentials and send OTP
     */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = $request->user();

        // Verify email matches
        if ($request->email !== $user->email) {
            return back()->withErrors(['email' => 'Email address does not match.']);
        }

        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password is incorrect.']);
        }

        // Password verified, now generate and send OTP
        $otpService = new OtpService();
        $otp = $otpService->generate($user, 'profile');

        try {
            Mail::to($user->email)->send(new OtpCodeMail($user, $otp, 'profile'));

            Log::info('Profile OTP email sent after password verification', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send profile OTP after password verification', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            return back()->withErrors(['email' => 'Failed to send OTP. Please try again.']);
        }

        // Redirect to OTP verification
        return redirect()->route('otp.verify', ['purpose' => 'profile', 'redirect' => route('profile.edit')])
            ->with('success', 'OTP code sent to your email. Check spam folder if not received.');
    }

    /**
     * Display the user's profile form.
     * Protected by OTP verification - must verify via /profile/verify first
     */
    public function edit(Request $request)
    {
        // Check if OTP was verified recently
        $user = $request->user();

        if (!$user->profile_otp_verified_at ||
            Carbon::parse($user->profile_otp_verified_at)->addMinutes(10)->isPast()) {
            return redirect()->route('profile.verify.show')
                ->with('error', 'Please verify your identity to access your profile.');
        }

        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Get validated data and exclude password
        $validated = $request->validated();
        $password = $validated['password'] ?? null;
        unset($validated['password']);

        // Fill user with data except password
        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Update password only if provided
        if (!empty($password)) {
            $user->password = Hash::make($password);
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'email' => [
                'required',
                'email',
                Rule::in([$request->user()->email]),
            ],
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
