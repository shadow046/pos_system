<?php

use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

it('validates required the data', function ($key, $value) {
    Role::factory()->admin()->create();
    $user = User::factory()->admin();

    actingAs($user)
        ->post(route('products.store'), Product::factory()->make([$key => $value])->toArray())
        ->assertSessionHasErrors($key);
})->with([
    ['name', ''],
    ['price', ''],
    ['quantity', '']
]);

it('can create product', function () {
    Storage::fake();
    Role::factory()->admin()->create();
    $user = User::factory()->admin();
    
    $product = Product::factory()->make()->toArray();

    actingAs($user)
        ->post(route('products.store'), $product)
        ->assertRedirect(route('products.index'));
        
    unset($product['photo']);

    assertDatabaseHas('products', $product);
});

it('can update product', function () {
    Storage::fake();
    Role::factory()->admin()->create();
    $user = User::factory()->admin();

    $product = Product::factory()->create();
    $edited = Product::factory()->make()->toArray();
        
    actingAs($user)
        ->post(route('products.update', $product->uuid), $edited)
        ->assertRedirect(route('products.index'));

    $product = $product->toArray();
    unset($product['photo']);
    unset($edited['photo']);

    assertDatabaseMissing('products', $product);
    assertDatabaseHas('products', $edited);
});

it('can delete product', function () {
    Storage::fake();
    Role::factory()->admin()->create();
    $user = User::factory()->admin();

    $product = Product::factory()->create();

    actingAs($user)
        ->delete(route('products.delete', $product->uuid))
        ->assertStatus(201);

    $product = $product->toArray();
    unset($product['photo']);
        
    assertDatabaseMissing('products', $product);
});

it('can fetch product from api', function () {
    Role::factory()->admin()->create();
    $user = User::factory()->admin();
    
    Product::factory()->create(['status' => 'available']);

    actingAs($user)
        ->json('GET', route('api.products.get'), ['category' => 'all'])
        ->assertOk();
});