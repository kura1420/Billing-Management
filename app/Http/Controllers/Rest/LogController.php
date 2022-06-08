<?php

namespace App\Http\Controllers\Rest;

use App\Helpers\Formatter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LogController extends Controller
{
    //
    public function index()
    {
        if (!file_exists(storage_path('logs'))) {
            return [];
        }

        $files = File::allFiles(storage_path('logs'));

        usort($files, function($a, $b) {
            return -1 * strcmp($a->getMTime(), $b->getMTime());
        });

        $res = [];
        foreach ($files as $file) {
            $res[] = [
                'filename' => $file->getFilename(),
                'size' => Formatter::bytesToHuman($file->getSize()),
                'write_at' => date('Y-m-d H:i:s', $file->getMTime()),
            ];
        }

        return response()->json($res, 200);
    }

    public function show($filename)
    {
        if (file_exists(storage_path('logs/' . $filename))) {
            $path = storage_path('logs/' . $filename);

            return response()->file($path);
        } else {
            return abort(404);
        }
    }

    public function download($filename)
    {
        if (file_exists(storage_path('logs/' . $filename))) {
            $path = storage_path('logs/' . $filename);

            return response()->download($path, $filename);
        } else {
            return abort(404);
        }
    }
}
