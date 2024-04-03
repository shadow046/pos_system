<?php

namespace App\Actions\Transactions\Reports;

use App\Actions\Traits\AsObject;
use App\Http\Requests\ReportRequest;
use App\Models\Transaction;
use Carbon\Carbon;

class GetPendingTransaction
{
    use AsObject;

    /**
     * Pending transaction report.
     */
    public function handle(ReportRequest $request)
    {
        $count_data = [];
        $total = 0;

        foreach ($this->setPendingDetails($request) as $key => $summary)
        {
            $count_data[$key] = $summary->count();
            $total += $summary->sum('amount');
        }

        return [
            'data' => $count_data,
            'total_amount' => $total,
        ];
    }

    // Set pending details
    protected function setPendingDetails(ReportRequest $request)
    {
        $data = $this->getPendingTransactionSummary($request);

        foreach (SetDateRange::run($request) as $date)
        {
            if (! isset($data[$date]))
            {
                $data[$date] = collect([]);
            }
        }

        return $data->keyBy(function ($item, $key) {
            return Carbon::parse($key)->format('Y-m-d');
        })->sortKeys();
    }

    // Get pending transaction summary.
    protected function getPendingTransactionSummary(ReportRequest $request)
    {
        $startDate = SetDateFrom::run($request);
        $endDate = SetDateTo::run($request);

        return Transaction::pending()
            ->betweenDate("{$startDate} 00:00:00", "{$endDate} 23:59:59")
            ->get()
            ->groupBy(function ($date) {
                return date('F d, Y', strtotime($date->sold_at));
            });
    }
}
