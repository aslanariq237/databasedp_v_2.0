<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KeluhanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $keluhan = [
            [
                "name"=> "Cable Head",
                "price"=> "5000",
            ],
            [
                "name"=> "Gear RDA",
                "price"=> "5000",
            ],
            [
                "name"=> "Gear Combinasi",
                "price"=> "5000",
            ],
            [
                "name"=> "Exch Carr-Unit",
                "price"=> "5000",
            ],
            [
                "name"=> "Switch Panel",
                "price"=> "5000",
            ],
            [
                "name"=> "Ganti Carr-Unit",
                "price"=> "5000",
            ],
            [
                "name"=> "Sticker Panel",
                "price"=> "5000",
            ],
            [
                "name"=> "Knop",
                "price"=> "5000",
            ],
            [
                "name"=> "Spull Head",
                "price"=> "5000",
            ],
            [
                "name"=> "Driver Head",
                "price"=> "5000",
            ],
            [
                "name"=> "Traktor Head",
                "price"=> "5000",
            ],
            [
                "name"=> "Cable Head",
                "price"=> "5000",
            ],
            [
                "name"=> "Head Service",
                "price"=> "5000",
            ],
            [
                "name"=> "Paper Release",
                "price"=> "5000",
            ],
            [
                "name"=> "Tombol Casing Bolong",
                "price"=> "5000",
            ],
            [
                "name"=> "Pala Head",
                "price"=> "5000",
            ],
            [
                "name"=> "PGS",
                "price"=> "5000",
            ],
            [
                "name"=> "Kuping Traktor",
                "price"=> "5000",
            ],
            [
                "name"=> "Gear Traktor",
                "price"=> "5000",
            ],
            [
                "name"=> "Bussing",
                "price"=> "5000",
            ],
            [
                "name"=> "Release Sensor",
                "price"=> "5000",
            ],
            [
                "name"=> "Gear Platen",
                "price"=> "5000",
            ],
            [
                "name"=> "Power Supply Service",
                "price"=> "5000",
            ],
            [
                "name"=> "Ganti Pin",
                "price"=> "5000",
            ],
            [
                "name"=> "Tongkat Paper",
                "price"=> "5000",
            ],
        ];

        foreach($keluhan as $kel){
            \App\Models\Keluhan::create($kel);
        }
    }
}
