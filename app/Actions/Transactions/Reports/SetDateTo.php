<?php

namespace App\Actions\Transactions\Reports;

use App\Actions\Traits\AsObject;
use App\Http\Requests\ReportRequest;

class SetDateTo
{
    use AsObject;

    /**
     * Set date from.
     */
    public function handle(ReportRequest $request)
    {
        return filled($request->to) ? $request->to : now()->format('Y-m-d');
    }
}
