<?php

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;

use function Pest\Laravel\actingAs;

it('can render email verification page', function () {
    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    actingAs($user)
        ->get('/verify-email')
        ->assertOk();
});

it('can verify email', function () {
    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    Event::fake();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1($user->email)]
    );

    actingAs($user)
        ->get($verificationUrl)
        ->assertRedirect(RouteServiceProvider::HOME.'?verified=1');

    Event::assertDispatched(Verified::class);
    $this->assertTrue($user->fresh()->hasVerifiedEmail());
});

it('can not verify email with invalid hash', function () {
    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1('wrong-email')]
    );

    actingAs($user)->get($verificationUrl);

    $this->assertFalse($user->fresh()->hasVerifiedEmail());
});
