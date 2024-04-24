<?php

namespace App\Jobs;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Rawilk\Printing\Facades\Printing;

class PrintReceiptJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Transaction $transaction)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $printers = Printing::printers();

        if (isset($printers[0]))
        {
            Printing::newPrintTask()
                ->printer($printers[0]->id())
                ->url($this->transaction->receipt->file)
                ->send();
        }
    }
}
