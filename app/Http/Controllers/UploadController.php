<?php

namespace App\Http\Controllers;

use App\Jobs\CrawlerJob;
use App\Models\CsvData;
use App\Models\Keyword;
use App\Http\Requests\CsvImportRequest;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('upload');
    }

    public function parseFile(CsvImportRequest $request)
    {
        $path     = $request->file('csv_file')->getRealPath();
        $csv_data = array_map('str_getcsv', file($path));

        if (count($csv_data) > 0) {
            $csv_data_file   = CsvData::create([
                'csv_filename' => $request->file('csv_file')->getClientOriginalName(),
                'csv_data'     => json_encode($csv_data)
            ]);

            return view('parse_file', compact('csv_data', 'csv_data_file'));
        } else {
            return redirect()->back();
        }
    }

    public function processFile(Request $request)
    {
        // Retrieve data from csv_data table:
        $data     = CsvData::find($request->csv_data_file_id);
        $csv_data = json_decode($data->csv_data, true);

        // Create job:
        CrawlerJob::dispatch($csv_data);

        return view('import_success');
    }
}
