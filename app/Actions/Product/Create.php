<?php

namespace App\Actions\Product;

use App\Actions\Traits\AsObject;
use App\Http\Requests\Store\ProductRequest;
use App\Models\Product;

class Create
{
    use AsObject;

    /**
     * Create product.
     * 
     * @param ProductRequest $request
     * 
     * @return void
     */
    public function handle(ProductRequest $request) : void
    {
        $product = $this->create($request);

        $this->upload($request, $product);

        $product->categories()->sync(collect($request->categories)->pluck('id'));
    }

    /**
     * Create product.
     * 
     * @param ProductRequest $request
     * 
     * @return Product
     */
    protected function create(ProductRequest $request) : Product
    {
        return Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'status' => $request->quantity > 0 ? 'available' : 'not available'
        ]);
    }

    /**
     * Upload image.
     * 
     * @param ProductRequest $request
     * @param Product $product
     * 
     * @return void
     */
    protected function upload(ProductRequest $request, Product $product) : void
    {
        if(filled($request->file('image')))
        {
            $product->update(['image' => $request->file('image')->store(SetDirectory::run($product))]);
        }
    }
}
