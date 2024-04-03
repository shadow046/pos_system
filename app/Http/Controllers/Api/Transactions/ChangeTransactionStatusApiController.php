<?php

namespace App\Http\Controllers\Api\Transactions;

use App\Actions\Transactions\ChangeTransactionStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Update\TransactionRequest;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;

class ChangeTransactionStatusApiController extends Controller
{
    // Update transaction status.
    public function update(Transaction $transaction, TransactionRequest $request): JsonResponse
    {
        ChangeTransactionStatus::run($request, $transaction);

        return response()->json([
            'message' => 'Transaction has been updated!',
        ], 201);
    }
}
