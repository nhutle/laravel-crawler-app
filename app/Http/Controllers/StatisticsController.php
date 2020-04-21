<?php

namespace App\Http\Controllers;

use App\Models\Statistic;

class StatisticsController extends Controller
{
    /**
     * Protected by auth middleware.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get all statistics with pagination.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getStatistics()
    {
        $statistics = Statistic::paginate(5);
        return view('statistics', ['statistics' => $statistics]);
    }

    /**
     * Open a cached page within keyword in new page.
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getHTMLCode(int $id)
    {
        $statistic = Statistic::findOrFail($id);
        $keyword   = $statistic->keyword;
        $content   = $statistic->html_code;
        return view('statistic_content', compact('keyword', 'content'));
    }
}
