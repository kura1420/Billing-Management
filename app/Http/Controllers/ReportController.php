<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    //
    const FOLDER = 'report.';

    public function summary()
    {
        return view(self::FOLDER . 'summary');
    }
}
