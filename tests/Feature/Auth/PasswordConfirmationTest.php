<?php

use App\Models\User;

use function Pest\Laravel\actingAs;

it('can render password confirmation page', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->get('/confirm-password')
        ->assertStatus(200);
});

it('can confirm password', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->post('/confirm-password', [
            'password' => 'password',
        ])
        ->assertRedirect()
        ->assertSessionHasNoErrors();
});

it('can not confirm password with invalid password', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->post('/confirm-password', [
            'password' => 'wrong-password',
        ])
        ->assertSessionHasErrors();
});
