<?php

use App\Models\Category;
use App\Models\Role;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

it('validates required the data', function ($key, $value) {
    Role::factory()->admin()->create();
    $user = User::factory()->admin();

    actingAs($user)
        ->post(route('categories.store'), Category::factory()->make([$key => $value])->toArray())
        ->assertSessionHasErrors($key);
})->with([
    ['name', '']
]);

it('can create category', function () {
    Role::factory()->admin()->create();
    $user = User::factory()->admin();
    
    $category = Category::factory()->make()->toArray();

    actingAs($user)
        ->post(route('categories.store'), $category)
        ->assertRedirect(route('categories.index'));

    assertDatabaseHas('categories', $category);
});

it('can update category', function () {
    Role::factory()->admin()->create();
    $user = User::factory()->admin();

    $category = Category::factory()->create();
    $edited = Category::factory()->make()->toArray();
        
    actingAs($user)
        ->put(route('categories.update', $category->uuid), $edited)
        ->assertRedirect(route('categories.index'));

    assertDatabaseMissing('categories', $category->toArray());
    assertDatabaseHas('categories', $edited);
});

it('can delete category', function () {
    Role::factory()->admin()->create();
    $user = User::factory()->admin();

    $category = Category::factory()->create();

    actingAs($user)
        ->delete(route('categories.delete', $category->uuid))
        ->assertStatus(201);
        
    assertDatabaseMissing('categories', $category->toArray());
});