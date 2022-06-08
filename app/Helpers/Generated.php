<?php

namespace App\Helpers;

class Generated {

    public static function color()
    {
        return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);        
    }

}