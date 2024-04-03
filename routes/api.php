<?php

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\Transactions\ChangeTransactionStatusApiController;
use App\Http\Controllers\Api\Transactions\CompletedTransactionApiController;
use App\Http\Controllers\Api\Transactions\PendingTransactionApiController;
use App\Http\Controllers\Api\Transactions\PreparingTransactionApiController;
use App\Http\Controllers\Api\Transactions\ServingTransactionApiController;
use App\Http\Controllers\Api\Transactions\VoidTransactionApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('api.products.get');
    Route::get('/reports', [ReportController::class, 'index'])->name('api.reports.get');

    Route::prefix('transactions')->group(function () {
        Route::get('/pending', [PendingTransactionApiController::class, 'index'])->name('api.transactions.pending');
        Route::get('/preparing', [PreparingTransactionApiController::class, 'index'])->name('api.transactions.preparing');
        Route::get('/serving', [ServingTransactionApiController::class, 'index'])->name('api.transactions.serving');
        Route::get('/completed', [CompletedTransactionApiController::class, 'index'])->name('api.transactions.completed');
        Route::get('/void', [VoidTransactionApiController::class, 'index'])->name('api.transactions.void');
        Route::put('/change-status/{transaction:uuid}', [ChangeTransactionStatusApiController::class, 'update'])->name('api.transactions.change-status');
    });
});
