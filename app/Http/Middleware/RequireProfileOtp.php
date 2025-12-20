<?php

namespace App\Http\Middleware;

use App\Mail\OtpCodeMail;
use App\Services\OtpService;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;

class RequireProfileOtp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Check if profile OTP verification is needed
        // Required if: null OR older than 10 minutes
        if (!$user->profile_otp_verified_at ||
            Carbon::parse($user->profile_otp_verified_at)->addMinutes(10)->isPast()) {

            try {
                // Generate and send OTP
                $otpService = new OtpService();
                $otp = $otpService->generate($user, 'profile');

                Mail::to($user->email)->send(new OtpCodeMail($user, $otp, 'profile'));

                Log::info('Profile OTP email sent successfully', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'purpose' => 'profile'
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send profile OTP email', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }

            // Redirect to OTP verification with return URL
            $redirectUrl = route('otp.verify', [
                'purpose' => 'profile',
                'redirect' => $request->fullUrl()
            ]);

            Log::info('Redirecting to OTP verify', [
                'from' => $request->fullUrl(),
                'to' => $redirectUrl,
                'user_id' => $user->id
            ]);

            return redirect($redirectUrl)->with('info', 'For security, please verify your identity to access your profile.');
        }

        return $next($request);
    }
}
