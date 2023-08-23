<?php

use App\Models\User;
use App\Notifications\VerifyDeviceNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

it('will send verification code to user, if the device was not yet verified', function () {
    Notification::fake();
    $user = User::factory()->unverifiedDevice()->create();

    post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();

    Notification::assertSentTo($user, VerifyDeviceNotification::class);
});

it('can resend verification code', function () {
    Notification::fake();
    $user = User::factory()->unverifiedDevice()->create();

    actingAs($user)
    ->post(route('resend.otp'))
    ->assertStatus(201);

    Notification::assertSentTo($user, VerifyDeviceNotification::class);
});

it('will not accept otp that has expired', function () {
    Notification::fake();
    Carbon::setTestNow(now()->subHours(2));
    $user = User::factory()->unverifiedDevice()->create();

    post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    Carbon::setTestNow();

    actingAs($user->fresh())
    ->post(route('device.verify'), ['otp' => $user->fresh()->otp])
    ->assertSessionHasErrors(['otp' => 'The verification code has expired. Please send again.']);
});

it('can not accept invalid otp', function () {
    Notification::fake();
    $user = User::factory()->unverifiedDevice()->create();

    post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    actingAs($user->fresh())
    ->post(route('device.verify'), ['otp' => 123456])
    ->assertSessionHasErrors(['otp' => 'Verification code is not valid.']);
});

it('can verify device and redirect to dashboard', function () {
    Notification::fake();
    $user = User::factory()->unverifiedDevice()->create();

    post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    actingAs($user->fresh())
    ->post(route('device.verify'), ['otp' => $user->fresh()->otp])
    ->assertRedirect(route('dashboard'));

    $this->assertNotNull($user->fresh()->device_verified_at);
});