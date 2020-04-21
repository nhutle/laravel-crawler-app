<?php

namespace App\Http\Controllers;

use App\Jobs\CrawlerJob;
use App\Models\CsvData;
use App\Http\Requests\CsvImportRequest;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    /**
     * Protected by auth middleware.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Upload view.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function upload()
    {
        return view('upload');
    }

    /**
     * Parse CSV file, then save its keywords.
     * @param CsvImportRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function parseFile(CsvImportRequest $request)
    {
        $path     = $request->file('csv_file')->getRealPath();
        $csv_data = array_map('str_getcsv', file($path));

        if (count($csv_data) > 0) {
            foreach ($csv_data as $data) {
                $keywords[] = $data[0];
            }

            $csv_data_file = CsvData::create([
                'filename' => $request->file('csv_file')->getClientOriginalName(),
                'keywords' => json_encode($keywords)
            ]);

            return view('parse_file', compact('keywords', 'csv_data_file'));
        } else {
            return redirect()->back();
        }
    }

    /**
     * Retrieve keywords, then start cron jobs
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function processFile(Request $request)
    {
        // Retrieve data from csv_data table:
        $data     = CsvData::find($request->file_id);
        $keywords = json_decode($data->keywords, true);

        // Let create a job for each keyword:
        foreach ($keywords as $keyword) {
            // Create job:
            CrawlerJob::dispatch($keyword);
        }

        return view('import_success');
    }
}
