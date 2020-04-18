<?php

namespace App\Http\Controllers;

use App\Jobs\CrawlerJob;
use App\Models\CsvData;
use App\Http\Requests\CsvImportRequest;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function upload()
    {
        return view('upload');
    }

    public function parseFile(CsvImportRequest $request)
    {
        $path     = $request->file('csv_file')->getRealPath();
        $csv_data = array_map('str_getcsv', file($path));

        if (count($csv_data) > 0) {
            foreach ($csv_data as $data) {
                $keywords[] = $data[0];
            }

            $csv_data_file   = CsvData::create([
                'filename' => $request->file('csv_file')->getClientOriginalName(),
                'keywords' => json_encode($keywords)
            ]);

            return view('parse_file', compact('keywords', 'csv_data_file'));
        } else {
            return redirect()->back();
        }
    }

    public function processFile(Request $request)
    {
        // Retrieve data from csv_data table:
        $data     = CsvData::find($request->csv_data_file_id);
        $csv_data = json_decode($data->keywords, true);

        // Create job:
        CrawlerJob::dispatch($csv_data);

        return view('import_success');
    }
}
