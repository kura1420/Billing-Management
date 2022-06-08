<?php

namespace App\Helpers;

use App\Helpers\Terbilang;

class Formatter {

    public static function rupiah($angka)
    {
        $hasil_rupiah = "Rp " . number_format($angka,2,',','.');

	    return $hasil_rupiah;
    }

	public static function rupiahSpeakOnBahasa($number)
	{
		$terbilang = new Terbilang();

		$result = $terbilang->parse($number)->getResult();

		return ucwords($result) . " Rupiah";
	}

	public static function bytesToHuman($bytes)
	{
        $units = ['B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
	}
}