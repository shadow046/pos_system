<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Dashboard.
     */
    public function index(): Response
    {
        return Inertia::render('Dashboard', [
            'user' => auth()->user(),
            'categories' => Category::with('products')->get(),
        ]);
    }
}
