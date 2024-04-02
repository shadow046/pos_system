<?php

use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use App\Notifications\NewUserNotification;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function PHPUnit\Framework\assertEquals;

it('validates required the data', function ($key, $value) {
    Role::factory()->admin()->create();
    $user = User::factory()->admin();

    actingAs($user)
        ->post(route('users.store'), User::factory()->make([$key => $value])->toArray())
        ->assertSessionHasErrors($key);
})->with([
    ['first_name', ''],
    ['last_name', ''],
    ['email', ''],
    ['role', ''],
    ['status', ''],
]);

it('can create user', function () {
    Notification::fake();
    Role::factory()->admin()->create();
    $user = User::factory()->admin();

    $role = Role::factory()->cashier()->create();

    $data = User::factory()->profile()->make()->toArray();
    $data['role'] = $role->name;

    actingAs($user)
        ->post(route('users.store'), $data)
        ->assertRedirect(route('users.index'));

    $new_user = User::where('email', $data['email'])->first();

    Notification::assertSentTo($new_user, NewUserNotification::class);

    assertDatabaseHas('users', $new_user->toArray());
});

it('can update user', function () {
    Role::factory()->admin()->create();
    $user = User::factory()->admin();

    $role = Role::factory()->cashier()->create();

    $new_user = User::factory()->create();
    Profile::factory()->create(['user_id' => $new_user->id]);

    $new_user->assignRole($role->name);

    $data = [
        'first_name' => fake()->firstName(),
        'last_name' => fake()->lastName(),
        'email' => $new_user->email,
        'role' => $new_user->roles[0]->name,
        'status' => 'inactive',
    ];

    actingAs($user)
        ->put(route('users.update', $new_user->uuid), $data)
        ->assertRedirect(route('users.index'));

    assertEquals($data['first_name'], $new_user->profile->first_name);
});

it('can resend user credentials', function () {
    Notification::fake();
    Role::factory()->admin()->create();
    $user = User::factory()->admin();

    $role = Role::factory()->cashier()->create();

    $new_user = User::factory()->create();
    Profile::factory()->create(['user_id' => $new_user->id]);

    $new_user->assignRole($role->name);

    actingAs($user)
        ->post(route('users.password.resend', $new_user->uuid))
        ->assertStatus(201);

    Notification::assertSentTo($new_user, NewUserNotification::class);
});
