<?php

namespace App\Actions\Transactions\Reports;

use App\Actions\Traits\AsObject;
use App\Http\Requests\ReportRequest;
use App\Models\Transaction;
use Carbon\Carbon;

class GetVoidTransaction
{
    use AsObject;

    /**
     * Void transaction report.
     * 
     * @param ReportRequest $request
     */
    public function handle(ReportRequest $request)
    {
        $count_data = [];
        $total = 0;

        foreach($this->setVoidDetails($request) as $key => $summary)
        {
            $count_data[$key] = $summary->count();
            $total += $summary->sum('amount');
        }

        return [
            'data' => $count_data,
            'total_amount' => $total
        ];
    }
    
    // Set void details
    protected function setVoidDetails(ReportRequest $request)
    {
        $data = $this->getVoidTransactionSummary($request);

        foreach(SetDateRange::run($request) as $date)
        {
            if(!isset($data[$date]))
            {
                $data[$date] = collect([]);
            }
        }
        
        return $data->keyBy(function($item, $key) {
            return Carbon::parse($key)->format('Y-m-d');
        })->sortKeys();
    }

    // Get void transaction summary.
    protected function getVoidTransactionSummary(ReportRequest $request)
    {
        $startDate = SetDateFrom::run($request);
        $endDate = SetDateTo::run($request);
        
        return Transaction::void()
            ->betweenDate("{$startDate} 00:00:00", "{$endDate} 23:59:59")
            ->get()
            ->groupBy(function($date) {
                return date('F d, Y', strtotime($date->sold_at));
            });
    }
}
