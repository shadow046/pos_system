<?php

namespace App\Actions\Transactions\Reports;

use App\Actions\Traits\AsObject;
use App\Http\Requests\ReportRequest;
use App\Models\Transaction;
use Carbon\Carbon;

class GetTotalTransaction
{
    use AsObject;

    /**
     * Total transaction report.
     */
    public function handle(ReportRequest $request)
    {
        $count_data = [];
        $total = 0;

        foreach ($this->setTotalDetails($request) as $key => $summary)
        {
            $count_data[$key] = $summary->count();
            $total += $summary->sum('amount');
        }

        return [
            'data' => $count_data,
            'total_amount' => $total,
        ];
    }

    // Set total details
    protected function setTotalDetails(ReportRequest $request)
    {
        $data = $this->getTotalTransactionSummary($request);

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

    // Get total transaction summary.
    protected function getTotalTransactionSummary(ReportRequest $request)
    {
        $startDate = SetDateFrom::run($request);
        $endDate = SetDateTo::run($request);

        return Transaction::betweenDate("{$startDate} 00:00:00", "{$endDate} 23:59:59")
            ->get()
            ->groupBy(function ($date) {
                return date('F d, Y', strtotime($date->sold_at));
            });
    }
}
