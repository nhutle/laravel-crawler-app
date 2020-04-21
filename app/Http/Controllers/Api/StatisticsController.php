<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Statistic;

class StatisticsController extends Controller
{
    /**
     * Get statistic with pagination.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStatistics()
    {
        $statistics = Statistic::paginate(5);
        return response()->json([
            'statistics'  => $statistics
        ], 200);
    }
}
