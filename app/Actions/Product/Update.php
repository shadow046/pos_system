<?php

namespace App\Actions\Product;

use App\Actions\Traits\AsObject;
use App\Http\Requests\Update\ProductRequest;
use App\Models\Product;

class Update
{
    use AsObject;

    /**
     * Create product.
     * 
     * @param Product $product
     * @param ProductRequest $request
     * 
     * @return void
     */
    public function handle(Product $product, ProductRequest $request) : void
    {
        $this->update($product, $request);
        
        $this->reupload($request, $product);

        $product->categories()->sync(collect($request->categories)->pluck('id'));
    }

    /**
     * Create product.
     * 
     * @param Product $product
     * @param ProductRequest $request
     * 
     * @return void
     */
    protected function update(Product $product, ProductRequest $request) : void
    {
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'status' => $request->quantity > 0 ? 'available' : 'not available'
        ]);
    }

    /**
     * Update image.
     * 
     * @param ProductRequest $request
     * @param Product $product
     * 
     * @return void
     */
    protected function reupload(ProductRequest $request, Product $product) : void
    {
        if(filled($request->file('image')))
        {
            RemovePhoto::run($product);

            $product->update(['image' => $request->file('image')->store(SetDirectory::run($product))]);
        }
    }
}
