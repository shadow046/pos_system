<?php

namespace App\Actions\Transactions;

use App\Actions\Traits\AsObject;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChangeTransactionStatus
{
    use AsObject;

    /**
     * Create transaction.
     */
    public function handle(Request $request, Transaction $transaction): void
    {
        $transaction->update(['status' => $request->status]);

        if ($request->status === 'void' && $this->hasReceipt($transaction))
        {
            Storage::disk()->delete($transaction->receipt->path);

            $transaction->receipt->delete();

            foreach ($transaction->orders as $order)
            {
                if (filled($order->product))
                {
                    $order->product->update([
                        'quantity' => $order->product->quantity + $order->quantity,
                    ]);

                    $order->product->update(['status' => $order->product->fresh()->quantity <= 0 ? 'not available' : 'available']);
                }
            }
        }
    }

    // Check if transaction has generated receipt.
    protected function hasReceipt(Transaction $transaction): bool
    {
        return filled($transaction->receipt) && Storage::disk()->exists($transaction->receipt->path);
    }
}
