<?php

namespace App\Actions\Transactions;

use App\Actions\Traits\AsObject;
use App\Models\Receipt;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class GenerateReceipt
{
    use AsObject;

    /**
     * Generate receipt.
     * 
     * @param Transaction $transaction
     * @return void
     */
    public function handle(Transaction $transaction): void
    {
        $path = "Transactions/{$transaction->id}/{$transaction->receipt_no}.pdf";

        Storage::disk()->put($path, $this->getPdf($transaction));

        Receipt::updateOrCreate([
            'transaction_id' => $transaction->id,
        ],[
            'path' => $path,
            'file_name' => "{$transaction->receipt_no}.pdf"
        ]);
    }

    /**
     * To load pdf file.
     * 
     * @param Transaction $transaction
     * @param Clinic $clinic
     */
    protected function getPdf(Transaction $transaction)
    {
        return Pdf::loadView('receipt', [
            'transaction' => $transaction,
        ])->output();
    }
}
