<?php

use App\Models\Role;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

it('validates required the data', function ($key, $value) {
    Role::factory()->admin()->create();
    $user = User::factory()->admin();

    actingAs($user)
        ->post(route('roles.store'), Role::factory()->make([$key => $value])->toArray())
        ->assertSessionHasErrors($key);
})->with([
    ['name', '']
]);

it('can create role', function () {
    Role::factory()->admin()->create();
    $user = User::factory()->admin();
    
    $role = Role::factory()->make()->toArray();

    actingAs($user)
        ->post(route('roles.store'), $role)
        ->assertRedirect(route('roles.index'));

    assertDatabaseHas('roles', $role);
});

it('can update role', function () {
    Role::factory()->admin()->create();
    $user = User::factory()->admin();

    $role = Role::factory()->create();
    $edited = Role::factory()->make()->toArray();
        
    actingAs($user)
        ->put(route('roles.update', $role->id), $edited)
        ->assertRedirect(route('roles.index'));

    assertDatabaseMissing('roles', $role->toArray());
    assertDatabaseHas('roles', $edited);
});

it('can delete role', function () {
    Role::factory()->admin()->create();
    $user = User::factory()->admin();

    $role = Role::factory()->create();

    actingAs($user)
        ->delete(route('roles.delete', $role->id))
        ->assertStatus(201);
        
    assertDatabaseMissing('roles', $role->toArray());
});