<?php

namespace App\Http\Controllers;

use App\Actions\Transactions\ChangeTransactionStatus;
use App\Actions\Transactions\CreateTransaction;
use App\Actions\Transactions\GenerateReceipt;
use App\Http\Requests\Store\TransactionRequest;
use App\Http\Requests\Update\TransactionRequest as UpdateTransactionRequest;
use App\Jobs\PrintReceiptJob;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class TransactionController extends Controller
{
    // Display lists of transactions.
    public function index(): Response
    {
        return Inertia::render('Transactions/Index', [
            'transactions' => Transaction::latest('id')->simplePaginate(10),
        ]);
    }

    // View transactiond details.
    public function show(Transaction $transaction): Response
    {
        return Inertia::render('Transactions/Show', [
            'transaction' => $transaction->load(['user.profile', 'orders.product', 'receipt']),
        ]);
    }

    // Create transaction.
    public function store(TransactionRequest $request): RedirectResponse
    {
        $transaction = CreateTransaction::run($request);

        GenerateReceipt::run($transaction);

        dd(PrintReceiptJob::dispatch($transaction));

        return back();
    }

    // Generate transaction receipt.
    public function generateReceipt(Transaction $transaction): JsonResponse
    {
        if (filled($transaction->receipt))
        {
            Storage::disk()->delete($transaction->receipt->path);
            $transaction->receipt()->delete();
        }

        GenerateReceipt::run($transaction);

        return response()->json([
            'message' => 'Receipt has been created!',
        ], 201);
    }

    // Update transaction status.
    public function update(Transaction $transaction, UpdateTransactionRequest $request): RedirectResponse
    {
        ChangeTransactionStatus::run($request, $transaction);

        return back();
    }
}
