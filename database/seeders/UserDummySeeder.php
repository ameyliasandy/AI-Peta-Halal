<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserDummySeeder extends Seeder
{
    public function run()
    {
        $namaUser = [
            'Andi Pratama', 'Budi Santoso', 'Citra Dewi', 'Dian Permata', 'Eka Putri',
            'Fajar Nugroho', 'Gita Lestari', 'Hadi Wijaya', 'Indah Sari', 'Joko Susilo',
            'Kartika Sari', 'Lukman Hakim', 'Maya Anggraini', 'Nanda Pratiwi', 'Oki Setiawan',
            'Putri Ramadhani', 'Qori Amalia', 'Rian Hidayat', 'Siti Nurhaliza', 'Taufik Akbar',
            'Umar Faruk', 'Vina Melati', 'Wahyu Hidayat', 'Yulia Rahmawati', 'Zaki Maulana',
            'Ahmad Fauzan', 'Bella Safira', 'Chandra Wijaya', 'Devi Kusuma', 'Eko Prasetyo',
        ];

        foreach ($namaUser as $i => $nama) {
            $email = 'dummy' . ($i + 1) . '@petha.test';

            // skip kalau sudah ada
            if (DB::table('users')->where('email', $email)->exists()) {
                continue;
            }

            DB::table('users')->insert([
                'name'       => $nama,
                'email'      => $email,
                'password'   => Hash::make('password123'),
                'role'       => 'pencari',
                'no_telepon' => '08' . rand(1000000000, 9999999999),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        echo "✅ " . count($namaUser) . " user dummy berhasil dibuat (atau sudah ada sebelumnya).\n";
    }
}