<?php

use App\Models\Product;
use App\Models\Role;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotEquals;

it('validates required fields', function ($key, $value) {
    $user = User::factory()->create();

    actingAs($user)
        ->post(route('transactions.store'), Transaction::factory()->make([$key => $value])->toArray())
        ->assertSessionHasErrors($key);
})->with([
    ['order_type', ''],
    ['total_order', ''],
    ['sales', ''],
    ['discount', ''],
    ['vat', ''],
    ['amount', ''],
    ['cash', ''],
    ['change', ''],
    ['items', ''],
]);

it('can create transaction', function () {
    Storage::fake();
    $user = User::factory()->create();

    $product = Product::factory()->create(['quantity' => 10, 'status' => 'available']);

    $data = [
        'discount' => 0,
        'cash' => 500,
        'change' => 500 - (3*$product->price),
        'order_type' => 'dine in',
        'items' => [
            [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'sub_total' => 3 * $product->price,
                'quantity' => 3
            ],
        ],
        'amount' => 3*$product->price,
        'sales' => round((3*$product->price) - ((3*$product->price / 1.12) * 0.12), 2),
        'vat' => round(((3*$product->price) / 1.12) * 0.12, 2),
        'total_order' => 3,
    ];

    actingAs($user)
        ->post(route('transactions.store'), $data)
        ->assertRedirect('/');

    $transaction = Transaction::first();
    Storage::disk()->assertExists("Transactions/{$transaction->id}/{$transaction->receipt_no}.pdf");
    assertDatabaseHas('transactions', $transaction->toArray());
});

it('can update transaction status to void, and it will remove generated receipt', function () {
    Storage::fake();
    Role::factory()->admin()->create();
    $user = User::factory()->admin();

    $product = Product::factory()->create(['quantity' => 10, 'status' => 'available']);

    $data = [
        'discount' => 0,
        'cash' => 500,
        'change' => 500 - (3*$product->price),
        'order_type' => 'dine in',
        'items' => [
            [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'sub_total' => 3 * $product->price,
                'quantity' => 3
            ],
        ],
        'amount' => 3*$product->price,
        'sales' => round((3*$product->price) - ((3*$product->price / 1.12) * 0.12), 2),
        'vat' => round(((3*$product->price) / 1.12) * 0.12, 2),
        'total_order' => 3,
    ];

    actingAs($user)
        ->post(route('transactions.store'), $data)
        ->assertRedirect('/');

    $transaction = Transaction::first();

    actingAs($user)
        ->put(route('transactions.update', $transaction->uuid), ['status' => 'void'])
        ->assertRedirect('/');

    assertNotEquals('pending', $transaction->fresh()->status);
    assertEquals('void', $transaction->fresh()->status);
    Storage::disk()->assertMissing("Transactions/{$transaction->id}/{$transaction->receipt_no}.pdf");
});
