<?php

namespace App\Http\Controllers\Api;

use App\Actions\Transactions\Reports\FilterTransactionReport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReportRequest;
use Illuminate\Http\JsonResponse;

class ReportController extends Controller
{
    //
    public function index(ReportRequest $request): JsonResponse
    {
        $data = FilterTransactionReport::run($request);

        return response()->json([
            'data' => [
                'pending' => $data['pending'],
                'completed' => $data['completed'],
                'void' => $data['void'],
                'total' => $data['total'],
                'average' => round(collect($data['total']['data'])->average()),
                'total_completed' => round(collect($data['completed']['data'])->sum()),
                'total_void' => round(collect($data['void']['data'])->sum()),
                'total_sales' => $data['sales'],
            ],
        ], 200);
    }
}
