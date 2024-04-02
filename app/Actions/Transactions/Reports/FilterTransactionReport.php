<?php

namespace App\Actions\Transactions\Reports;

use App\Actions\Traits\AsObject;
use App\Http\Requests\ReportRequest;

class FilterTransactionReport
{
    use AsObject;

    /**
     * Filter transaction report.
     */
    public function handle(ReportRequest $request)
    {
        return [
            'pending' => GetPendingTransaction::run($request),
            'completed' => GetCompletedTransaction::run($request),
            'void' => GetVoidTransaction::run($request),
            'total' => GetTotalTransaction::run($request),
            'sales' => GetSalesTransaction::run($request),
        ];
    }
}
