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
        $path = env('APP_URL').$transaction->receipt->file;
        // exec("lp -d Brother_DCP_7040_192_168_0_9 $path");
        exec('wmic printer where default="TRUE" get name', $output, $returnCode);
        if ($returnCode === 0 && count($output) >= 2) {
            // Extract the default printer name from the second line of output
            $defaultPrinter = trim($output[1]);

            // Execute the print command to print the file using the default printer
            exec("print /D:\"$defaultPrinter\" \"$path\"", $printOutput, $printReturnCode);

            // Check if the print command executed successfully
            if ($printReturnCode === 0) {
                return back();
                // return response()->json(['message' => 'File sent to printer successfully']);
            } else {
                return response()->json(['error' => 'Failed to send file to printer']);
            }
        } else {
            return response()->json(['error' => 'Failed to get default printer information']);
        }
        // return back();
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
