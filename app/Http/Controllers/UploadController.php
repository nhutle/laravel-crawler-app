<?php

namespace App\Http\Controllers;

use App\Models\CsvData;
use App\Http\Requests\CsvImportRequest;
use App\Models\Task;
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
     * Retrieve csv content, then insert task for each keyword
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function processFile(Request $request)
    {
        // Retrieve data from csv_data table:
        $data     = CsvData::find($request->file_id);
        $keywords = json_decode($data->keywords, true);
        $tasks    = array();

        // Collect tasks:
        foreach ($keywords as $keyword) {
            $tasks[] = array(
                'keyword'     => trim($keyword),
                'status'      => 'pending',
                'attempts'    => 0,
                'created_at'  => date('Y-m-d H:i:s')
            );
        }

        // Insert tasks:
        Task::insert($tasks);

        return view('import_success');
    }
}
