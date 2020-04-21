<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\CrawlerJob;
use App\Models\CsvData;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    /**
     * Parse file and save its keywords.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
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

            return response()->json([
                'message'  => 'File uploadeded',
                'keywords' => $keywords,
                'file_id'  => $csv_data_file->id
            ], 200);
        } else {
            return response()->json(['error' => 'Empty file'], 200);
        }
    }

    /**
     * Retrieve keywords, then start cron jobs
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function processFile(Request $request)
    {
        // Retrieve data from csv_data table:
        $data     = CsvData::find($request->file_id);
        $csv_data = json_decode($data->keywords, true);

        // Create job:
        CrawlerJob::dispatch($csv_data);

        return response()->json(['message' => 'We are processing it'], 200);
    }
}
