<?php

namespace App\Actions\Transactions\Reports;

use App\Actions\Traits\AsObject;
use App\Http\Requests\ReportRequest;

class SetDateFrom
{
    use AsObject;

    /**
     * Set date from.
     */
    public function handle(ReportRequest $request)
    {
        return filled($request->from) ? $request->from : now()->subDays(6)->format('Y-m-d');
    }
}
