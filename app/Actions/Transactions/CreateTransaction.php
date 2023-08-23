<?php

namespace App\Actions\Transactions;

use App\Actions\Traits\AsObject;
use App\Http\Requests\Store\TransactionRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;

class CreateTransaction
{
    use AsObject;

    /**
     * Create transaction.
     * 
     * @param Transaction $transaction
     * @return Transaction
     */
    public function handle(TransactionRequest $request): Transaction
    {
        $latest_transaction = Transaction::latest('id')->first();

        $receipt_number = filled($latest_transaction)
        ? str_pad($latest_transaction->id + 1, 8, "0", STR_PAD_LEFT)
        : str_pad(1, 8, "0", STR_PAD_LEFT);

        $transaction = $this->create($request, $receipt_number);

        $this->storeDetails($request, $transaction);

        return $transaction->fresh();
    }

    // Create transaction.
    protected function create(TransactionRequest $request, string $receipt_number): Transaction
    {
        return Transaction::create([
            'order_type' => $request->order_type,
            'receipt_no' => $receipt_number,
            'user_id' => auth()->user()->id,
            'sold_at' => now()->format('Y-m-d H:i:s'),
            'total_order' => $request->total_order,
            'sales' => $request->sales,
            'discount' => $request->discount,
            'vat' => $request->vat,
            'amount' => $request->amount,
            'amount_paid' => $request->cash,
            'change' => $request->change,
            'status' => 'pending'
        ]);
    }

    // Store order details.
    protected function storeDetails(TransactionRequest $request, Transaction $transaction): void
    {
        foreach($request->items as $item)
        {
            Order::create([
                'transaction_id' => $transaction->id,
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['sub_total'],
            ]);

            $this->consumeStock($item);
        }
    }

    // Reduce stocks.
    protected function consumeStock(array $item)
    {
        $product = Product::find($item['id']);
        if(filled($product))
        {
            $product->update([
                'quantity' => $product->quantity - $item['quantity']
            ]);

            $product->update(['status' => $product->fresh()->quantity <= 0 ? 'not available' : 'available']);
        }
    }
}
