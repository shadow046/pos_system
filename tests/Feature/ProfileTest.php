<?php

use App\Models\Profile;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('can render profile page', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->get('/profile')
        ->assertOk();
});

it('can update profile information', function () {
    $user = User::factory()->create();
    Profile::factory()->create(['user_id' => $user->id]);

    actingAs($user)
        ->patch('/profile', [
            'first_name' => 'Test',
            'last_name' => 'User',
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    $user->refresh();

    $this->assertSame('Test', $user->profile->first_name);
});

test('user can delete their account', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->delete('/profile', [
            'password' => 'password',
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
    $this->assertNull($user->fresh());
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->from('/profile')
        ->delete('/profile', [
            'password' => 'wrong-password',
        ])
        ->assertSessionHasErrors('password')
        ->assertRedirect('/profile');

    $this->assertNotNull($user->fresh());
});
