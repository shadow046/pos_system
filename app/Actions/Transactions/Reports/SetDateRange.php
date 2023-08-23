<?php

namespace App\Actions\Transactions\Reports;

use App\Actions\Traits\AsObject;
use App\Http\Requests\ReportRequest;
use Carbon\CarbonPeriod;

class SetDateRange
{
    use AsObject;

    /**
     * Set date from.
     * 
     * @param ReportRequest $request
     */
    public function handle(ReportRequest $request)
    {
        $startDate = SetDateFrom::run($request);
        $endDate = SetDateTo::run($request);
        $dates = [];
        
        $label = 'days';
        $dateRange = CarbonPeriod::create($startDate, "1 {$label}", $endDate);
        
        if($label == 'hours')
        {
            $dateRange = CarbonPeriod::create("{$startDate} 00:00:00", "1 {$label}", "{$endDate} 23:59:59");
        }
        
        foreach($dateRange as $date)
        {
            $dates[] = date('F d, Y', strtotime($date));
        }

        return $dates;
    }
}
