<?php

namespace App\Http\Controllers;

use App\Jobs\CrawlerJob;
use App\Models\CsvData;
use App\Models\Keyword;
use App\Http\Requests\CsvImportRequest;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('import');
    }

    public function parseImport(CsvImportRequest $request)
    {
        $path = $request->file('csv_file')->getRealPath();
        $data = array_map('str_getcsv', file($path));

        if (count($data) > 0) {
            $csv_data_sample = array_slice($data, 0, 2);
            $csv_data_file   = CsvData::create([
                'csv_filename' => $request->file('csv_file')->getClientOriginalName(),
                'csv_data'     => json_encode($data)
            ]);

            return view('import_fields', compact('csv_data_sample', 'csv_data_file'));
        } else {
            return redirect()->back();
        }
    }

    public function processImport(Request $request)
    {
        // Retrieve data from csv_data table:
        $data     = CsvData::find($request->csv_data_file_id);
        $csv_data = json_decode($data->csv_data, true);

        // Create job:
        CrawlerJob::dispatch($csv_data);

        return view('import_success');
    }
}
