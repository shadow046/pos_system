<?php

namespace App\Actions\Transactions\Reports;

use App\Actions\Traits\AsObject;
use App\Http\Requests\ReportRequest;

class SetDateTo
{
    use AsObject;

    /**
     * Set date from.
     * 
     * @param ReportRequest $request
     */
    public function handle(ReportRequest $request)
    {
        return filled($request->to) ? $request->to : now()->format('Y-m-d');
    }
}
