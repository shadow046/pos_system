<?php

namespace App\Actions\Transactions\Reports;

use App\Actions\Traits\AsObject;
use App\Http\Requests\ReportRequest;
use App\Models\Transaction;

class GetSalesTransaction
{
    use AsObject;

    /**
     * Sales transaction report.
     */
    public function handle(ReportRequest $request)
    {
        $startDate = SetDateFrom::run($request);
        $endDate = SetDateTo::run($request);

        $transaction = Transaction::notVoid()
            ->betweenDate("{$startDate} 00:00:00", "{$endDate} 23:59:59");

        return [
            'gross_sales' => round($transaction->sum('amount') + $transaction->sum('discount'), 2),
            'net_sales' => round($transaction->sum('sales') - $transaction->sum('discount'), 2),
            'tax_sales' => (float) $transaction->sum('vat'),
            'discount_sales' => (float) $transaction->sum('discount'),
        ];
    }
}
