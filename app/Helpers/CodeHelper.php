<?php

use Carbon\Carbon;
use App\Models\Receive;
use App\Models\Invoice;
use App\Models\SuratJalan;

if (!function_exists('generateReceiveCode')) {
    function generateReceiveCode(): string
    {
        $now = Carbon::now();

        $month = $now->month;
        $year  = $now->year;

        $romanMonths = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
            5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
            9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII',
        ];

        // ambil receive terakhir di bulan & tahun yang sama
        $lastReceive = Receive::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('receive_id', 'desc')
            ->first();

        $nextNumber = $lastReceive
            ? ((int) substr($lastReceive->code, 0, 3)) + 1
            : 1;

        $index = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        return "{$index}/TT/{$romanMonths[$month]}/{$year}";
    }
}

if (!function_exists('generateSuratJalanCode')) {
    function generateSuratJalanCode(): string
    {
        $now = Carbon::now();

        $month = $now->month;
        $year  = $now->year;

        $romanMonths = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
            5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
            9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII',
        ];

        // ambil receive terakhir di bulan & tahun yang sama
        $lastReceive = SuratJalan::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('suratjalan_id', 'desc')
            ->first();

        $nextNumber = $lastReceive
            ? ((int) substr($lastReceive->code, 0, 3)) + 1
            : 1;

        $index = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        return "{$index}/SJ/{$romanMonths[$month]}/{$year}";
    }
}
