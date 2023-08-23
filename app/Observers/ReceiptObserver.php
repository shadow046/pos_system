<?php

namespace App\Observers;

use App\Actions\GenerateActivityLog;
use App\Models\Receipt;

class ReceiptObserver
{
    /**
     * Handle the Receipt "created" event.
     */
    public function created(Receipt $receipt): void
    {
        GenerateActivityLog::run('Created', $receipt, "Generated receipt for transaction {$receipt->transaction_id}");
    }

    /**
     * Handle the Receipt "updated" event.
     */
    public function updated(Receipt $receipt): void
    {
        //
    }

    /**
     * Handle the Receipt "deleted" event.
     */
    public function deleted(Receipt $receipt): void
    {
        GenerateActivityLog::run('Deleted', $receipt, "Deleted receipt for transaction {$receipt->transaction_id}");
    }

    /**
     * Handle the Receipt "restored" event.
     */
    public function restored(Receipt $receipt): void
    {
        //
    }

    /**
     * Handle the Receipt "force deleted" event.
     */
    public function forceDeleted(Receipt $receipt): void
    {
        //
    }
}
