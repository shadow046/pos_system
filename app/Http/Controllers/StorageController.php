<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class StorageController extends Controller
{
    /**
     * Display roles.
     */
    public function show($path)
    {
        $filePath = storage_path('app/' . $path);

        // dd($path);
        if (!Storage::exists($path)) {
            abort(404);
        }

        return response()->file($filePath);
    }
    public function printCount()
    {
        
        // // $response = Http::withBasicAuth(env('PRINT_NODE_API_KEY'), '')
        // //                 ->get('https://api.printnode.com/whoami');

        // // // Check if the request was successful
        // // if ($response->successful()) {
        // //     $data = $response->json(); // Retrieve JSON response
        // //     // Process $data as needed
        // //     return $data;
        // // } else {
        // //     return 'error';
        // //     // Request failed, handle the error
        // //     $statusCode = $response->status();
        // //     $error = $response->body();
        // //     // Handle error response
        // // }

        // $printerName = 'your_printer_name';
        // $documentUrl = 'https://example.com/document.pdf';

        // $response = Http::post("http://localhost:631/printers/{$printerName}", [
        //     'file' => $documentUrl,
        //     // Add any additional print job options as needed
        // ]);

        // if ($response->successful()) {
        //     return 'Print job submitted successfully.';
        // } else {
        //     return 'Failed to submit print job.';
        // }
    }
}
