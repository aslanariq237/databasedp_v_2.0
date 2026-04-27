<?php

if (!function_exists('terbilang_rupiah')) {

    function terbilang_rupiah($nilai)
    {
        $nilai = abs((int)$nilai);

        $angka = [
            '',
            'satu',
            'dua',
            'tiga',
            'empat',
            'lima',
            'enam',
            'tujuh',
            'delapan',
            'sembilan',
            'sepuluh',
            'sebelas'
        ];

        if ($nilai < 12) {
            return $angka[$nilai];
        }

        if ($nilai < 20) {
            return terbilang_rupiah($nilai - 10) . ' belas';
        }

        if ($nilai < 100) {
            return terbilang_rupiah(intval($nilai / 10)) . ' puluh' .
                (($nilai % 10 != 0) ? ' ' . terbilang_rupiah($nilai % 10) : '');
        }

        if ($nilai < 200) {
            return 'seratus' .
                (($nilai - 100 != 0) ? ' ' . terbilang_rupiah($nilai - 100) : '');
        }

        if ($nilai < 1000) {
            return terbilang_rupiah(intval($nilai / 100)) . ' ratus' .
                (($nilai % 100 != 0) ? ' ' . terbilang_rupiah($nilai % 100) : '');
        }

        if ($nilai < 2000) {
            return 'seribu' .
                (($nilai - 1000 != 0) ? ' ' . terbilang_rupiah($nilai - 1000) : '');
        }

        if ($nilai < 1000000) {
            return terbilang_rupiah(intval($nilai / 1000)) . ' ribu' .
                (($nilai % 1000 != 0) ? ' ' . terbilang_rupiah($nilai % 1000) : '');
        }

        if ($nilai < 1000000000) {
            return terbilang_rupiah(intval($nilai / 1000000)) . ' juta' .
                (($nilai % 1000000 != 0) ? ' ' . terbilang_rupiah($nilai % 1000000) : '');
        }

        if ($nilai < 1000000000000) {
            return terbilang_rupiah(intval($nilai / 1000000000)) . ' miliar' .
                (($nilai % 1000000000 != 0) ? ' ' . terbilang_rupiah($nilai % 1000000000) : '');
        }

        if ($nilai < 1000000000000000) {
            return terbilang_rupiah(intval($nilai / 1000000000000)) . ' triliun' .
                (($nilai % 1000000000000 != 0) ? ' ' . terbilang_rupiah($nilai % 1000000000000) : '');
        }

        return '';
    }
}
