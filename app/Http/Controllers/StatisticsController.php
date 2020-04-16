<?php

namespace App\Http\Controllers;

use App\Models\Statistic;

class StatisticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $statistics = Statistic::all();
        return view('statistics', compact('statistics'));
    }

    public function htmlCode(int $id)
    {
        $statistic = Statistic::findOrFail($id);
        $keyword   = $statistic->keyword;
        $content   = $statistic->html_code;
        return view('statistic_content', compact('keyword', 'content'));
    }
}
