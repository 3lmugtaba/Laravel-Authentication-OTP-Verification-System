<?php

use App\Models\PasswordResetToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

function makeOtpUser(array $overrides = []): User
{
    return User::create(array_merge([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'phone' => '0912345678',
        'password' => Hash::make('OldPassword123'),
        'role' => 'customer',
        'telegram_chat_id' => '123456789',
        'is_Verified' => true,
    ], $overrides));
}

test('registration creates a customer and stores a hashed expiring OTP', function () {
    config(['services.telegram.bot_token' => 'test-token']);
    Http::fake(['api.telegram.org/*' => Http::response(['ok' => true], 200)]);

    $response = $this->post(route('register.store'), [
        'name' => 'New User',
        'email' => 'new@example.com',
        'phone' => '0911111111',
        'password' => 'StrongPass123',
        'telegram_chat_id' => '123456789',
        'role' => 'admin',
    ]);

    $user = User::where('email', 'new@example.com')->firstOrFail();
    expect($user->role)->toBe('customer')
        ->and($user->is_Verified)->toBeFalse();

    $otp = $user->resetTokens()->where('purpose', 'registration')->firstOrFail();
    expect(Hash::check('000000', $otp->token))->toBeFalse()
        ->and($otp->expires_at)->not->toBeNull()
        ->and($otp->attempts)->toBe(0);

    $response->assertRedirect(route('registration.otp.form'));
    $this->assertDatabaseHas('users', ['email' => 'new@example.com', 'role' => 'customer']);
    Http::assertSent(fn ($request) => str_contains($request->url(), 'api.telegram.org')
        && str_contains($request['text'], 'registration OTP'));
});

test('password reset OTP is bound to the user in the session and changes only that password', function () {
    $user = makeOtpUser();
    $user->resetTokens()->create([
        'token' => Hash::make('246810'),
        'purpose' => 'password_reset',
        'created_at' => now(),
        'expires_at' => now()->addMinutes(5),
        'attempts' => 0,
        'last_sent_at' => now(),
    ]);

    $this->withSession(['password_reset_otp_user_id' => $user->id])
        ->post(route('setNewPassword'), ['resetOTP' => '246810'])
        ->assertRedirect(route('reset.password.form'));

    $this->post(route('checkPassword'), [
        'newPassword' => 'BrandNewPass123',
        'newPassword_confirmation' => 'BrandNewPass123',
        'phone' => '0199999999',
        'token' => 'attacker-controlled',
    ])->assertRedirect(route('login'));

    expect(Hash::check('BrandNewPass123', $user->fresh()->password))->toBeTrue()
        ->and(Hash::check('OldPassword123', $user->fresh()->password))->toBeFalse();
});

test('expired OTP is rejected', function () {
    $user = makeOtpUser(['email' => 'expired@example.com', 'phone' => '0922222222']);
    $user->resetTokens()->create([
        'token' => Hash::make('135790'),
        'purpose' => 'password_reset',
        'created_at' => now()->subMinutes(10),
        'expires_at' => now()->subMinute(),
        'attempts' => 0,
        'last_sent_at' => now()->subMinutes(10),
    ]);

    $this->withSession(['password_reset_otp_user_id' => $user->id])
        ->post(route('setNewPassword'), ['resetOTP' => '135790'])
        ->assertSessionHas('error', 'OTP expired. Request a new one.');

    expect($user->resetTokens()->where('purpose', 'password_reset')->exists())->toBeFalse();
});
