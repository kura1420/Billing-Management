<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    //
    const FOLDER = 'pages.report.';

    public function sample_summary()
    {
        return view(self::FOLDER . 'sample.sampleSummary');
    }
}
