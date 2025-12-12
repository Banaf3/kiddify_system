<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
        // If already verified in this session, redirect to edit
        if (session('profile_verified')) {
            return redirect()->route('profile.edit');
        }

        return view('profile.verify');
    }

    /**
     * Verify user credentials before allowing profile access
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

        // Set session flag for verification (valid for 10 minutes)
        session(['profile_verified' => true, 'profile_verified_at' => now()]);

        return redirect()->route('profile.edit');
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        // Check if verification is required
        if (!session('profile_verified') ||
            (session('profile_verified_at') && now()->diffInMinutes(session('profile_verified_at')) > 10)) {
            session()->forget(['profile_verified', 'profile_verified_at']);
            return redirect()->route('profile.verify.show');
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
