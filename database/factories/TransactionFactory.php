<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'receipt_no' => str_pad(1, 8, '0', STR_PAD_LEFT),
            'order_type' => 'dine in',
            'sold_at' => now()->format('Y-m-d H:i:s'),
            'total_order' => 4,
            'sales' => 1706.61,
            'discount' => 0,
            'vat' => 204.79,
            'amount' => 1911.40,
            'amount_paid' => 2000.00,
            'change' => 88.60,
            'status' => 'pending',
        ];
    }
}
