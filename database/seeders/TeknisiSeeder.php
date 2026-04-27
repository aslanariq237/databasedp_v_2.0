<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeknisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teknisi = [
            [
                "name"      => "moane",
                "status"    => "1"  
            ],
            [
                "name"      => "man",
                "status"    => "1"  
            ],
            [
                "name"      => "diknas",
                "status"    => "1"  
            ],
            [
                "name"      => "doni",
                "status"    => "1"  
            ],
            [
                "name"      => "ikin",
                "status"    => "1"  
            ],
            [
                "name"      => "reza",
                "status"    => "1"  
            ],
            [
                "name"      => "arul",
                "status"    => "1"  
            ],
            [
                "name"      => "ray",
                "status"    => "1"  
            ],
            [
                "name"      => "eksternal",
                "status"    => "1"  
            ],
            [
                "name"      => "rio",
                "status"    => "1"  
            ],
            [
                "name"      => "oji",
                "status"    => "1"  
            ],
        ];
        foreach($teknisi as $tek)
        {
            \App\Models\Teknisi::create($tek);
        }
    }
}
