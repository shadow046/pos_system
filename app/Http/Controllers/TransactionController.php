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
        // $path = 'D:\xampp\htdocs\pos_system\storage\app\Transactions\96\00000096.pdf';
        // $escapedPath = escapeshellarg($path);
        // dd($path);
        // exec("lp -d Brother_DCP_7040_192_168_0_9 $path");
        // exec('wmic printer where default="TRUE" get name', $output, $returnCode);
        // print /D:"\\192.168.0.9\Brother-DCP-7040" "D:\xampp\htdocs\pos_system\storage\app\Transactions\96\00000096.pdf"
        // $command = 'print /D:"\\\\192.168.0.9\\Brother-DCP-7040" '.$path;
        // exec($command, $output, $returnCode);

        // if ($returnCode === 0) {
        // } else {
        //     dd('palpak');
        // }
        // Create a new Pdf instance
        $pdf = new Pdf($path);

        // Print the PDF file directly
        $pdf->send();
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
