<?php

namespace App\Http\Controllers\Api\Transactions;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PreparingTransactionApiController extends Controller
{
    // List of preparing transactions
    public function index(): AnonymousResourceCollection
    {
        return TransactionResource::collection(Transaction::with('orders.product')->status('preparing')->get());
    }
}
