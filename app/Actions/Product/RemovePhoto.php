<?php

namespace App\Actions\Product;

use App\Actions\Traits\AsObject;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class RemovePhoto
{
    use AsObject;

    /**
     * Remove photo from storage.
     * 
     * @param Product $product
     * @return void
     */
    public function handle(Product $product) : void
    {
        if(filled($product->image) && Storage::disk()->exists($product->image))
        {
            Storage::disk()->delete($product->image);
        }
    }
}
