<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Statistic;

class StatisticsController extends Controller
{
    public function getStatistics()
    {
        $statistics = Statistic::paginate(5);
        return response()->json([
            'statistics'  => $statistics
        ], 200);
    }
}
