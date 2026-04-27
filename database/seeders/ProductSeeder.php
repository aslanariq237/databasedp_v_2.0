<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product = [
            [
                "name"  => "Epson 1165",
                "code"  => "EPS165"
            ],
            [
                "name"  => "Epson 1160",
                "code"  => "EPS160"
            ],            
        ];

        foreach($product as $pro){
            \App\Models\Product::create($pro);
        }
    }
}
