<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class StorageController extends Controller
{
    /**
     * Display roles.
     */
    public function show($path)
    {
        $filePath = storage_path('app/' . $path);

        if (!Storage::exists($path)) {
            abort(404);
        }

        return response()->file($filePath);
    }
}
