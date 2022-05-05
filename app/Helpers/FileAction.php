<?php

namespace App\Helpers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class FileAction {

    public static function createPDF(Array $params)
    {
        $objParam = $params;
        $filepath = $objParam['filepath'];
        $template_html = $params['template_html'];
        $template_variable = $objParam['template_variable'];
    
        $pdf = PDF::loadView($template_html, $template_variable);
    
        Storage::disk('local')->put($filepath, $pdf->output());
    }

}