<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RatingDummySeeder extends Seeder
{
    public function run()
    {
        $users = DB::table('users')->where('role', 'pencari')->pluck('id')->toArray();
        $restoran = DB::table('restoran')->get();

        if (empty($users) || $restoran->isEmpty()) {
            echo "❌ User atau restoran kosong, seeder dibatalkan.\n";
            return;
        }

        // Kelompokkan resto berdasarkan kata kunci nama (sama seperti logic MenuSeeder)
        $kategoriResto = [];
        foreach ($restoran as $r) {
            $nama = strtolower($r->nama_restoran);
            $kategoriResto[$r->id_restoran] = $this->deteksiKategori($nama);
        }

        // Setiap user punya 1-2 "selera favorit" secara acak
        $semuaKategori = array_unique(array_values($kategoriResto));
        $inserted = 0;

        foreach ($users as $userId) {
            // Tentukan selera favorit user ini (1-2 kategori)
            $jumlahSelera = rand(1, 2);
            $seleraUser = (array) array_rand(array_flip($semuaKategori), min($jumlahSelera, count($semuaKategori)));

            // User akan kasih rating ke 5-10 resto secara acak
            $jumlahRating = rand(5, 10);
            $restoDipilih = $restoran->random(min($jumlahRating, $restoran->count()));

            foreach ($restoDipilih as $r) {
                $kategoriResto_ini = $kategoriResto[$r->id_restoran];

                // Kalau resto sesuai selera favorit user -> rating tinggi (4-5)
                // Kalau tidak sesuai -> rating lebih random (2-4)
                if (in_array($kategoriResto_ini, $seleraUser)) {
                    $rating = rand(4, 5);
                } else {
                    $rating = rand(2, 4);
                }

                // Skip kalau sudah ada (hindari duplikat karena unique constraint)
                $exists = DB::table('ulasan')
                    ->where('user_id', $userId)
                    ->where('id_restoran', $r->id_restoran)
                    ->exists();

                if ($exists) continue;

                DB::table('ulasan')->insert([
                    'user_id'     => $userId,
                    'id_restoran' => $r->id_restoran,
                    'rating'      => $rating,
                    'komentar'    => null,
                    'created_at'  => now()->subDays(rand(1, 60)),
                    'updated_at'  => now(),
                ]);

                $inserted++;
            }
        }

        echo "✅ {$inserted} rating dummy berhasil dibuat.\n";
    }

    private function deteksiKategori(string $nama): string
    {
        if (str_contains($nama, 'seafood') || str_contains($nama, 'ikan') || str_contains($nama, 'laut')) return 'seafood';
        if (str_contains($nama, 'ayam') || str_contains($nama, 'gepuk') || str_contains($nama, 'geprek')) return 'ayam';
        if (str_contains($nama, 'mie') || str_contains($nama, 'noodle') || str_contains($nama, 'tarempa')) return 'mie';
        if (str_contains($nama, 'padang') || str_contains($nama, 'sunda') || str_contains($nama, 'jowo') || str_contains($nama, 'nusantara')) return 'nusantara';
        if (str_contains($nama, 'soto') || str_contains($nama, 'bakso') || str_contains($nama, 'gultik')) return 'soto_bakso';
        if (str_contains($nama, 'grill') || str_contains($nama, 'meat') || str_contains($nama, 'holy')) return 'grill';
        if (str_contains($nama, 'jepang') || str_contains($nama, 'ramen') || str_contains($nama, 'don')) return 'jepang';
        if (str_contains($nama, 'kebuli') || str_contains($nama, 'saudi') || str_contains($nama, 'arab')) return 'arab';
        if (str_contains($nama, 'bakery') || str_contains($nama, 'roti') || str_contains($nama, 'kue')) return 'bakery';
        if (str_contains($nama, 'martabak')) return 'martabak';

        return 'umum';
    }
}