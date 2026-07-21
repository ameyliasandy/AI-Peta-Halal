<?php

namespace App\Helpers;

class ImageHelper
{
    /**
     * Mapping kategori → file foto. Dipakai bersama oleh restoran() dan menu(),
     * supaya konsisten dan tidak perlu duplikasi daftar kata kunci.
     *
     * PENTING: urutan array ini menentukan prioritas pengecekan. Kategori
     * yang lebih SPESIFIK harus dicek lebih dulu, supaya tidak "kecolongan"
     * match ke kategori yang lebih umum (misal "Matcha Latte" harus kena
     * matcha.jpg dulu, bukan nyasar ke kopi.jpg).
     */
    private static function mapping(): array
    {
        return [
            // ── Minuman: sub-kategori spesifik dicek duluan ──
            'tehtarik.jpg' => ['teh tarik'],
            'esteh.jpg'    => ['es teh', 'teh manis', 'ice tea'],
            'matcha.jpg'   => ['matcha'],
            'kopi.jpg'     => ['kopi', 'coffee'],
            'jus.jpg'      => ['jus', 'juice', 'es jeruk'],

            // Fallback minuman umum (boba, susu, minuman lain yang tidak
            // masuk sub-kategori spesifik di atas)
            'minuman.jpg' => ['minuman', 'boba', 'susu', 'latte'],

            // Ayam & unggas
            'ayam.jpg' => ['ayam', 'kfc', 'gepuk', 'geprek', 'chicken', 'kentucky'],

            // Mie & noodle
            'mie.jpg' => ['mie', 'noodle', 'marugame', 'ramen', 'bakmi', 'kwetiau', 'pangsit'],

            // Seafood
            'seafood.jpg' => ['seafood', 'ikan', 'udang', 'cumi', 'kepiting', 'kerang'],

            // Bakery, kue, dessert
            'bakery.jpg' => ['bakery', 'cake', 'kue', 'brownies', 'roti', 'pastry', 'dessert', 'croissant'],

            // Burger & fast food barat
            'burger.jpg' => ['burger', 'king', 'mcd', 'fast food'],

            // Steak & western
            'steak.jpg' => ['steak', 'western', 'grill', 'sambal bakaran'],

            // Bakso & soto
            'bakso.jpg' => ['bakso', 'soto', 'urat'],

            // Sate & gultik
            'sate.jpg' => ['sate', 'satay', 'gultik', 'gulai'],

            // Seblak & jajanan pedas
            'seblak.jpg' => ['seblak', 'basreng'],

            // Jepang
            'jepang.jpg' => ['jepang', 'japanese', 'sushi', 'gyoza', 'teriyaki', 'katsu', 'dondon'],

            // Martabak & gorengan
            'martabak.jpg' => ['martabak', 'gorengan', 'pisang goreng'],

            // Nasi & masakan nusantara / padang / sunda (paling umum, dicek belakangan)
            'nusantara.jpg' => ['warung', 'sunda', 'padang', 'rm ', 'nasi', 'kampung'],
        ];
    }

    private static function cocokkanKategori(string $teks): ?string
    {
        $teks = strtolower($teks);

        foreach (self::mapping() as $file => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($teks, $keyword)) {
                    return $file;
                }
            }
        }

        return null;
    }

    public static function restoran($nama, $deskripsi = null, $kategori = null)
    {
        $teks = $nama . ' ' . ($deskripsi ?? '') . ' ' . ($kategori ?? '');
        $file = self::cocokkanKategori($teks);

        return asset('images/restoran/' . ($file ?? 'default.jpg'));
    }

    public static function menu($namaMenu, $namaRestoran = null, $deskripsiMenu = null)
    {
        $teksMenu = $namaMenu . ' ' . ($deskripsiMenu ?? '');
        $file = self::cocokkanKategori($teksMenu);

        if ($file) {
            return asset('images/restoran/' . $file);
        }

        if ($namaRestoran) {
            return self::restoran($namaRestoran);
        }

        return asset('images/restoran/default.jpg');
    }
}