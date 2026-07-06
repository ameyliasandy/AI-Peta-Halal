<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HargaRestoranSeeder extends Seeder
{
    public function run(): void
    {
        $harga = [
            10 => [18000, 35000],
            11 => [25000, 50000],
            12 => [45000, 90000],
            13 => [30000, 70000],
            14 => [15000, 30000],
            15 => [20000, 45000],
            16 => [15000, 30000],
            17 => [25000, 45000],
            18 => [35000, 60000],
            19 => [20000, 50000],
            20 => [25000, 55000],
            21 => [20000, 40000],
            22 => [30000, 70000],
            23 => [12000, 30000],
            24 => [18000, 35000],
            25 => [15000, 30000],
            26 => [25000, 50000],
            27 => [20000, 40000],
            28 => [25000, 50000],
            29 => [35000, 70000],
            30 => [20000, 45000],
            31 => [18000, 35000],
            32 => [15000, 30000],
            33 => [18000, 35000],
            34 => [25000, 50000],
        ];

        foreach ($harga as $id => $range) {

            DB::table('restoran')
                ->where('id_restoran', $id)
                ->update([
                    'harga_rata_rata_min' => $range[0],
                    'harga_rata_rata_max' => $range[1],
                ]);
        }
    }
}