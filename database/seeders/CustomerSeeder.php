<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customer = [
            [
                "name"      => "SENTRA NIAGA SQUARE",
                "code"      => "CF55",
                "company"   => "CV.HARAPAN INTI SEJAHTERA",
                "category"  => "0",
                "pic"       => "Bpk. Wicaksono",
                "alamat"    => "jln. merdeka 10 tanggal 15"                
            ],
            [
                "name"      => "BEKASI TIMUR PERMAI",
                "code"      => "CG75",
                "company"   => "CV.MIKUSUTA ABADI",
                "category"  => "0",
                "pic"       => "Bpk. Wicaksono",
                "alamat"    => "jln. merdeka 10 tanggal 15"                
            ]       
        ];
        foreach($customer as $cust){
            \App\Models\Customer::create($cust);
        }
    }
}
