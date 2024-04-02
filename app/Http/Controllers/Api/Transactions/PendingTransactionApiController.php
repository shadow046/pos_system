<?php

namespace App\Http\Controllers\Api\Transactions;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PendingTransactionApiController extends Controller
{
    // List of pending transactions
    public function index(): AnonymousResourceCollection
    {
        // dd(TransactionResource::collection(Transaction::with('orders.product')->status('pending')->get()));
        return TransactionResource::collection(Transaction::with('orders.product')->status('pending')->get());
    }
}
