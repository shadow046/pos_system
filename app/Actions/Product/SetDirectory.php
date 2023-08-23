<?php

namespace App\Actions\Product;

use App\Actions\Traits\AsObject;
use App\Models\Product;

class SetDirectory
{
    use AsObject;

    /**
     * Set directory.
     * 
     * @param Product $product
     * @return string
     */
    public function handle(Product $product) : string
    {
        return "Products/{$product->id}/Images";
    }
}
