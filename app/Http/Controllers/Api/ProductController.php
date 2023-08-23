<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // 
    public function index(Request $request)
    {
        return ProductResource::collection(
            $request->category === 'all'
        ? Product::with('categories')->available()->get()
        : Product::with('categories')
            ->whereHas('categories', function ($categories) use ($request) {
                $categories->where('category_id', $request->category);
            })
            ->available()
            ->get()
        );
    }
}
