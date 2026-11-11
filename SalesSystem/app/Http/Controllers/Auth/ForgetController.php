<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\TelegramOtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ForgetController extends Controller
{
    private const OTP_TTL_MINUTES = 5;
    private const RESEND_COOLDOWN_SECONDS = 60;
    private const MAX_ATTEMPTS = 5;
    private const RESET_SESSION_MINUTES = 10;

    public function showFormReset()
    {
        return view('auth.forgetPassword.reset');
    }

    public function resetPassword(Request $request, TelegramOtpService $telegram)
    {
        $request->validate(['number' => ['required', 'regex:/^(09|01)\\d{8}$/']]);
        $this->clearResetSession($request);
        $user = User::where('phone', $request->number)->first();

        // Do not start a reset for unverified accounts or accounts without Telegram destination.
        if (!$user || !$user->is_Verified || !$user->telegram_chat_id) {
            return back()->with('status', 'If the account is eligible, an OTP will be sent to its linked Telegram account.');
        }

        $request->session()->forget(['password_reset_user_id', 'password_reset_verified_at']);
        $request->session()->put('password_reset_otp_user_id', $user->id);

        if (!$this->issueOtp($user, $telegram)) {
            return redirect()->route('reset.otp.form')->with('otp_delivery_error', 'Telegram could not send the OTP. You can try again.');
        }

        return redirect()->route('reset.otp.form')->with('status', 'OTP sent to the Telegram account linked to this user.');
    }

    public function showOtpForm(Request $request)
    {
        if (!$request->session()->has('password_reset_otp_user_id')) {
            return redirect()->route('formReset')->with('status', 'Start the password reset process first.');
        }
        return view('auth.register.formOtp');
    }

    public function verifyResetOtp(Request $request)
    {
        $request->validate(['resetOTP' => ['required', 'digits:6']]);
        $userId = $request->session()->get('password_reset_otp_user_id');
        $user = $userId ? User::find($userId) : null;
        if (!$user) {
            $request->session()->forget('password_reset_otp_user_id');
            return redirect()->route('formReset')->with('status', 'Reset session expired. Start again.');
        }

        $record = $user->resetTokens()->where('purpose', 'password_reset')->latest('id')->first();
        $result = $this->validateOtp($record, $request->resetOTP);
        if ($result !== 'valid') {
            return back()->with('error', $this->otpErrorMessage($result));
        }

        $record->delete();
        $request->session()->forget('password_reset_otp_user_id');
        $request->session()->regenerate();
        $request->session()->put([
            'password_reset_user_id' => $user->id,
            'password_reset_verified_at' => now()->timestamp,
        ]);

        return redirect()->route('reset.password.form');
    }

    public function resendResetOtp(Request $request, TelegramOtpService $telegram)
    {
        $userId = $request->session()->get('password_reset_otp_user_id');
        $user = $userId ? User::find($userId) : null;
        if (!$user) return redirect()->route('formReset')->with('status', 'Reset session expired. Start again.');

        $existing = $user->resetTokens()->where('purpose', 'password_reset')->latest('id')->first();
        if ($existing?->last_sent_at && $existing->last_sent_at->gt(now()->subSeconds(self::RESEND_COOLDOWN_SECONDS))) {
            return back()->with('error', 'Please wait 60 seconds before requesting another OTP.');
        }

        if (!$this->issueOtp($user, $telegram)) {
            return back()->with('otp_delivery_error', 'Telegram could not send the OTP. Please try again later.');
        }
        return back()->with('status', 'A new OTP was sent. The previous OTP is no longer valid.');
    }

    public function showNewPasswordForm(Request $request)
    {
        if (!$this->hasValidResetSession($request)) {
            $this->clearResetSession($request);
            return redirect()->route('formReset')->with('status', 'Reset authorization expired. Start again.');
        }
        return view('auth.formNewPassword');
    }

    public function checkPassword(Request $request)
    {
        $request->validate([
            'newPassword' => ['required', 'string', 'min:8', 'max:64', 'confirmed'],
        ]);

        if (!$this->hasValidResetSession($request)) {
            $this->clearResetSession($request);
            return redirect()->route('formReset')->with('status', 'Reset authorization expired. Start again.');
        }

        $user = User::find($request->session()->get('password_reset_user_id'));
        if (!$user) {
            $this->clearResetSession($request);
            return redirect()->route('formReset')->with('status', 'Account not found. Start again.');
        }

        $user->update(['password' => Hash::make($request->newPassword)]);
        $user->resetTokens()->where('purpose', 'password_reset')->delete();
        $this->clearResetSession($request);
        $request->session()->regenerate();

        return redirect()->route('login')->with('status', 'Password changed successfully. Please log in.');
    }

    private function issueOtp(User $user, TelegramOtpService $telegram): bool
    {
        $otp = (string) random_int(100000, 999999);
        $user->resetTokens()->where('purpose', 'password_reset')->delete();
        $record = $user->resetTokens()->create([
            'token' => Hash::make($otp),
            'purpose' => 'password_reset',
            'created_at' => now(),
            'expires_at' => now()->addMinutes(self::OTP_TTL_MINUTES),
            'attempts' => 0,
            'last_sent_at' => now(),
        ]);

        $sent = $telegram->send($user->telegram_chat_id, "Hello {$user->name}, your password reset OTP is: {$otp}. It expires in 5 minutes.");
        if (!$sent) $record->delete();
        return $sent;
    }

    private function validateOtp($record, string $otp): string
    {
        if (!$record) return 'missing';
        if (!$record->expires_at || $record->expires_at->isPast()) {
            $record->delete();
            return 'expired';
        }
        if ($record->attempts >= self::MAX_ATTEMPTS) {
            return 'locked';
        }
        if (!Hash::check($otp, $record->token)) {
            $record->increment('attempts');
            if ($record->fresh()->attempts >= self::MAX_ATTEMPTS) {
                return 'locked';
            }
            return 'wrong';
        }
        return 'valid';
    }

    private function otpErrorMessage(string $result): string
    {
        return match ($result) {
            'expired' => 'OTP expired. Request a new one.',
            'locked' => 'Too many incorrect attempts. Request a new OTP.',
            'missing' => 'No active OTP. Request a new one.',
            default => 'Incorrect OTP. Please try again.',
        };
    }

    private function hasValidResetSession(Request $request): bool
    {
        $verifiedAt = (int) $request->session()->get('password_reset_verified_at', 0);
        return $request->session()->has('password_reset_user_id')
            && $verifiedAt > 0
            && $verifiedAt >= now()->subMinutes(self::RESET_SESSION_MINUTES)->timestamp;
    }

    private function clearResetSession(Request $request): void
    {
        $request->session()->forget([
            'password_reset_otp_user_id',
            'password_reset_user_id',
            'password_reset_verified_at',
        ]);
    }
}
