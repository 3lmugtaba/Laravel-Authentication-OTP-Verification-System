<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\TelegramOtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    private const OTP_TTL_MINUTES = 5;
    private const RESEND_COOLDOWN_SECONDS = 60;
    private const MAX_ATTEMPTS = 5;

    public function register(Request $request, TelegramOtpService $telegram)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:60'],
            'email' => ['required', 'email', 'max:30', 'unique:users,email'],
            'phone' => ['required', 'regex:/^(09|01)\\d{8}$/', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:8', 'max:16'],
            'telegram_chat_id' => ['required', 'string', 'max:40'],
        ]);

        // Role is deliberately assigned on the server. Never accept an admin role from a public form.
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
            'telegram_chat_id' => $validated['telegram_chat_id'],
            'is_Verified' => false,
        ]);

        $request->session()->put('registration_otp_user_id', $user->id);
        $sent = $this->issueOtp($user, $telegram);

        if (!$sent) {
            return redirect()->route('registration.otp.form')
                ->with('otp_delivery_error', 'Could not send the OTP. Check the Telegram chat ID and try again.');
        }

        return redirect()->route('registration.otp.form')
            ->with('status', 'OTP sent to your Telegram account. It expires in 5 minutes.');
    }

    public function showOtpForm(Request $request)
    {
        if (!$request->session()->has('registration_otp_user_id')) {
            return redirect()->route('register')->with('err', 'Start registration first.');
        }

        return view('auth.register.otp');
    }

    public function verifyOTP(Request $request)
    {
        $request->validate(['otp' => ['required', 'digits:6']]);
        $userId = $request->session()->get('registration_otp_user_id');
        $user = $userId ? User::find($userId) : null;

        if (!$user || $user->is_Verified) {
            $request->session()->forget('registration_otp_user_id');
            return redirect()->route('register')->with('err', 'Registration verification session expired.');
        }

        $record = $user->resetTokens()->where('purpose', 'registration')->latest('id')->first();
        $result = $this->validateOtp($record, $request->otp);

        if ($result !== 'valid') {
            return back()->with('error', $this->otpErrorMessage($result));
        }

        $user->forceFill(['is_Verified' => true])->save();
        $user->resetTokens()->where('purpose', 'registration')->delete();
        $request->session()->forget('registration_otp_user_id');
        $request->session()->regenerate();

        return redirect()->route('login')->with('status', 'Your account has been verified. You can log in now.');
    }

    public function resendOtp(Request $request, TelegramOtpService $telegram)
    {
        $userId = $request->session()->get('registration_otp_user_id');
        $user = $userId ? User::find($userId) : null;

        if (!$user || $user->is_Verified) {
            return redirect()->route('register')->with('err', 'Registration verification session expired.');
        }

        $existing = $user->resetTokens()->where('purpose', 'registration')->latest('id')->first();
        if ($existing?->last_sent_at && $existing->last_sent_at->gt(now()->subSeconds(self::RESEND_COOLDOWN_SECONDS))) {
            return back()->with('error', 'Please wait 60 seconds before requesting another OTP.');
        }

        if (!$this->issueOtp($user, $telegram)) {
            return back()->with('otp_delivery_error', 'Telegram could not send the OTP. Please try again later.');
        }

        return back()->with('status', 'A new OTP was sent. The previous OTP is no longer valid.');
    }

    private function issueOtp(User $user, TelegramOtpService $telegram): bool
    {
        $otp = (string) random_int(100000, 999999);
        $user->resetTokens()->where('purpose', 'registration')->delete();

        $record = $user->resetTokens()->create([
            'token' => Hash::make($otp),
            'purpose' => 'registration',
            'created_at' => now(),
            'expires_at' => now()->addMinutes(self::OTP_TTL_MINUTES),
            'attempts' => 0,
            'last_sent_at' => now(),
        ]);

        $sent = $telegram->send($user->telegram_chat_id, "Hello {$user->name}, your registration OTP is: {$otp}. It expires in 5 minutes.");
        if (!$sent) {
            // Keep the user so they can retry; remove the unsent challenge.
            $record->delete();
        }

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

    public function profileIndex($id)
    {
        // Keep profile output private; only the authenticated owner can view it.
        abort_unless(auth()->check() && (int) auth()->id() === (int) $id, 403);
        return view('auth.profile.index', ['user' => auth()->user()]);
    }
}
