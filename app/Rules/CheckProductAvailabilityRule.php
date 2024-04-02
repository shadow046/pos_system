<?php

namespace App\Rules;

use App\Models\Product;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckProductAvailabilityRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $product = Product::find($value['id']);

        if (! $this->hasEnoughStock($product, $value))
        {
            $fail('This product does not have sufficient stock.');
        }
    }

    // Check if product exist and has sufficient quantity.
    protected function hasEnoughStock(Product $product, array $value): bool
    {
        return filled($product) &&
            $product->quantity != 0 &&
            $product->isAvailable() &&
            $product->quantity >= $value['quantity'];
    }
}
