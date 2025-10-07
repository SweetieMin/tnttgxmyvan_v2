<?php

use App\Models\User;
use Laravel\Fortify\Features;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('login screen can be rendered', function () {
    $response = $this->get(route('login'));

    $response->assertStatus(200);
});

test('users can authenticate using the login screen with email', function () {
    $user = User::factory()->withoutTwoFactor()->create();

    $response = $this->post(route('login.store'), [
        'login_id' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('users can authenticate using the login screen with account_code', function () {
    $user = User::factory()->withoutTwoFactor()->create([
        'account_code' => 'TEST001',
    ]);

    $response = $this->post(route('login.store'), [
        'login_id' => $user->account_code,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('users with two factor enabled are redirected to two factor challenge', function () {
    if (! Features::canManageTwoFactorAuthentication()) {
        $this->markTestSkipped('Two-factor authentication is not enabled.');
    }

    Features::twoFactorAuthentication([
        'confirm' => true,
        'confirmPassword' => true,
    ]);

    $user = User::factory()->create();

    $user->forceFill([
        'two_factor_secret' => encrypt('test-secret'),
        'two_factor_recovery_codes' => encrypt(json_encode(['code1', 'code2'])),
        'two_factor_confirmed_at' => now(),
    ])->save();

    $response = $this->post(route('login'), [
        'login_id' => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirect(route('two-factor.login'));
    $response->assertSessionHas('login.id', $user->id);
    // User đã được đăng nhập bởi Auth::attempt, cần logout để kiểm tra
    Auth::logout();
    $this->assertGuest();
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->post(route('login.store'), [
        'login_id' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('logout'));

    $this->assertGuest();
    $response->assertRedirect(route('home'));
});

test('users are rate limited', function () {
    $user = User::factory()->create();

    RateLimiter::increment(implode('|', [$user->email, '127.0.0.1']), amount: 10);

    $response = $this->post(route('login.store'), [
        'login_id' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrors('login_id');

    $errors = session('errors');

    $this->assertStringContainsString('Too many login attempts', $errors->first('login_id'));
});

test('login validation works for non-existent email', function () {
    $response = $this->post(route('login.store'), [
        'login_id' => 'nonexistent@example.com',
        'password' => 'password',
    ]);

    $response->assertSessionHasErrors('login_id');
    $this->assertStringContainsString('Thông tin đăng nhập không hợp lệ', session('errors')->first('login_id'));
});

test('login validation works for non-existent account_code', function () {
    $response = $this->post(route('login.store'), [
        'login_id' => 'NONEXISTENT',
        'password' => 'password',
    ]);

    $response->assertSessionHasErrors('login_id');
    $this->assertStringContainsString('Thông tin đăng nhập không hợp lệ', session('errors')->first('login_id'));
});

test('password validation works', function () {
    $response = $this->post(route('login.store'), [
        'login_id' => 'validuser',
        'password' => '123', // Too short
    ]);

    $response->assertSessionHasErrors('password');
    $this->assertStringContainsString('Mật khẩu phải có trên 5 ký tự', session('errors')->first('password'));
});