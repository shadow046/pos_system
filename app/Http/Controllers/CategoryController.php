<?php

namespace App\Http\Controllers;

use App\Http\Requests\Store\CategoryRequest;
use App\Http\Requests\Update\CategoryRequest as UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    /**
     * Display categories.
     * 
     * @return Response
     */
    public function index() : Response
    {
        return Inertia::render('Categories/Index', [
            'categories' => Category::latest('id')->simplePaginate(10),
        ]);
    }

    /**
     * Create category.
     * 
     * @param CategoryRequest $request
     * @return RedirectResponse
     */
    public function store(CategoryRequest $request) : RedirectResponse
    {
        Category::create($request->only('name'));

        return redirect()->intended(route('categories.index'));
    }

    /**
     * Update category.
     * 
     * @param Category $category
     * @param UpdateCategoryRequest $request
     * @return RedirectResponse
     */
    public function update(Category $category, UpdateCategoryRequest $request) : RedirectResponse
    {
        $category->update($request->only('name'));

        return redirect()->intended(route('categories.index'));
    }

    /**
     * Delete category.
     * 
     * @param Category $category
     * @return JsonResponse
     */
    public function destroy(Category $category) : JsonResponse
    {
        $category->delete();

        return response()->json([
            'message' => 'Category has been removed.'
        ], 201);
    }
}
