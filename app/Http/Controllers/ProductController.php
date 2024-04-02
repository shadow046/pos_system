<?php

namespace App\Http\Controllers;

use App\Actions\Product\Create;
use App\Actions\Product\RemovePhoto;
use App\Actions\Product\Update;
use App\Http\Requests\Store\ProductRequest;
use App\Http\Requests\Update\ProductRequest as UpdateProductRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    /**
     * Display products.
     */
    public function index(): Response
    {
        return Inertia::render('Products/Index', [
            'products' => Product::with('categories')->latest('id')->simplePaginate(10),
            'categories' => CategoryResource::collection(Category::all()),
        ]);
    }

    /**
     * Create product.
     */
    public function store(ProductRequest $request): RedirectResponse
    {
        Create::run($request);

        return redirect()->intended(route('products.index'));
    }

    /**
     * Update product.
     */
    public function update(Product $product, UpdateProductRequest $request): RedirectResponse
    {
        Update::run($product, $request);

        return redirect()->intended(route('products.index'));
    }

    /**
     * Delete product.
     */
    public function destroy(Product $product): JsonResponse
    {
        RemovePhoto::run($product);

        $product->delete();

        return response()->json([
            'message' => 'Product has been removed.',
        ], 201);
    }
}
