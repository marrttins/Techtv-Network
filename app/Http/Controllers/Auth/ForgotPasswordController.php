<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /**
     * Show form to request password reset OTP.
     */
    public function showForgotForm(Request $request)
    {
        $prefilledEmail = $request->query('email', '');
        return view('auth.forgot-password', compact('prefilledEmail'));
    }

    /**
     * Generate & send OTP to user's email.
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'No account found with this email address.'])->withInput();
        }

        // Generate 6-digit OTP
        $otp = (string) random_int(100000, 999999);
        
        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(15),
        ]);

        // Attempt to send email via Laravel Mailer
        try {
            Mail::send('emails.password-otp', ['user' => $user, 'otp' => $otp], function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('TechTV Security: Your Password Reset OTP Code');
            });
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('OTP Email Sending Error: ' . $e->getMessage());
        }

        // In local environment, provide OTP preview for instant local testing
        if (config('app.env') === 'local') {
            session()->flash('dev_otp_code', $otp);
        }

        return redirect('/verify-otp?email=' . urlencode($user->email))
            ->with('success', "A 6-digit OTP verification code has been sent to {$user->email}. (Code is valid for 15 minutes)");
    }

    /**
     * Show form to enter OTP code.
     */
    public function showVerifyForm(Request $request)
    {
        $email = $request->query('email', '');
        if (empty($email)) {
            return redirect('/forgot-password')->withErrors(['email' => 'Please enter your email first.']);
        }
        return view('auth.verify-otp', compact('email'));
    }

    /**
     * Verify the submitted OTP code.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp_code' => 'required|string|size:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'User not found.'])->withInput();
        }

        if (!$user->otp_code || !$user->otp_expires_at || $user->otp_expires_at->isPast()) {
            return back()->withErrors(['otp_code' => 'The OTP code has expired. Please request a new one.'])->withInput();
        }

        if ($user->otp_code !== trim($request->otp_code)) {
            return back()->withErrors(['otp_code' => 'Invalid OTP code. Please check and try again.'])->withInput();
        }

        // OTP is valid! Clear OTP and generate a one-time reset token
        $user->update([
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);

        $resetToken = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => Hash::make($resetToken),
                'created_at' => now(),
            ]
        );

        return redirect('/reset-password?email=' . urlencode($user->email) . '&token=' . $resetToken)
            ->with('success', 'OTP verified successfully! Please enter your new password.');
    }

    /**
     * Show form to set a new password.
     */
    public function showResetForm(Request $request)
    {
        $email = $request->query('email', '');
        $token = $request->query('token', '');

        if (empty($email) || empty($token)) {
            return redirect('/forgot-password')->withErrors(['email' => 'Reset session expired. Please start again.']);
        }

        return view('auth.reset-password', compact('email', 'token'));
    }

    /**
     * Reset the user's password.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $resetRecord = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$resetRecord || !Hash::check($request->token, $resetRecord->token)) {
            return redirect('/forgot-password')->withErrors(['email' => 'Invalid or expired password reset link. Please request a new OTP.']);
        }

        // Check if token created within last 60 minutes
        if (now()->diffInMinutes($resetRecord->created_at) > 60) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return redirect('/forgot-password')->withErrors(['email' => 'Password reset session expired. Please request a new OTP.']);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect('/forgot-password')->withErrors(['email' => 'User account not found.']);
        }

        // Update password and reset all security lockout counters completely
        $user->update([
            'password' => Hash::make($request->password),
            'login_attempts' => 0,
            'locked_until' => null,
            'lockout_count' => 0,
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);

        // Clean up reset token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect('/login')->with('success', 'Your password has been successfully reset! You can now log in.');
    }
}
