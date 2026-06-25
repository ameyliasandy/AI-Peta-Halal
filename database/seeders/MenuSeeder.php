<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    public function run()
    {
        $restorans = DB::table('restoran')->get();

        foreach ($restorans as $resto) {
            $nama = strtolower($resto->nama_restoran);
            $menuList = $this->getMenuByNama($nama);

            foreach ($menuList as $menu) {
                DB::table('menu')->insert([
                    'id_restoran' => $resto->id_restoran,
                    'nama_menu'   => $menu['nama'],
                    'deskripsi'   => $menu['deskripsi'],
                    'harga'       => $menu['harga'],
                    'tersedia'    => 1,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        }
    }

    private function getMenuByNama(string $nama): array
    {
        // Seafood
        if (str_contains($nama, 'seafood') || str_contains($nama, 'ikan') || str_contains($nama, 'laut')) {
            return [
                ['nama' => 'Ikan Bakar Kecap',    'harga' => 35000, 'deskripsi' => 'Ikan segar dibakar dengan kecap manis dan bumbu rempah.'],
                ['nama' => 'Udang Goreng Tepung',  'harga' => 40000, 'deskripsi' => 'Udang segar digoreng dengan tepung renyah.'],
                ['nama' => 'Cumi Saus Padang',     'harga' => 38000, 'deskripsi' => 'Cumi segar dimasak dengan saus padang pedas.'],
                ['nama' => 'Kepiting Rebus',       'harga' => 65000, 'deskripsi' => 'Kepiting segar direbus dengan bumbu kunyit.'],
                ['nama' => 'Nasi Putih',           'harga' => 5000,  'deskripsi' => 'Nasi putih hangat.'],
                ['nama' => 'Es Jeruk',             'harga' => 8000,  'deskripsi' => 'Jeruk segar diperas dingin.'],
            ];
        }

        // Ayam
        if (str_contains($nama, 'ayam') || str_contains($nama, 'gepuk') || str_contains($nama, 'geprek')) {
            return [
                ['nama' => 'Ayam Gepuk Original', 'harga' => 20000, 'deskripsi' => 'Ayam goreng gepuk dengan sambal merah khas.'],
                ['nama' => 'Ayam Gepuk Pedas',    'harga' => 22000, 'deskripsi' => 'Ayam gepuk dengan level kepedasan tinggi.'],
                ['nama' => 'Ayam Gepuk Keju',     'harga' => 25000, 'deskripsi' => 'Ayam gepuk dengan topping keju leleh.'],
                ['nama' => 'Nasi Putih',           'harga' => 5000,  'deskripsi' => 'Nasi putih hangat.'],
                ['nama' => 'Es Teh Manis',         'harga' => 5000,  'deskripsi' => 'Teh manis dingin segar.'],
                ['nama' => 'Tahu Tempe Goreng',    'harga' => 8000,  'deskripsi' => 'Tahu dan tempe goreng crispy.'],
            ];
        }

        // Mie
        if (str_contains($nama, 'mie') || str_contains($nama, 'noodle') || str_contains($nama, 'tarempa') || str_contains($nama, 'marugame')) {
            return [
                ['nama' => 'Mie Ayam Original',   'harga' => 18000, 'deskripsi' => 'Mie ayam dengan topping ayam cincang dan saus khas.'],
                ['nama' => 'Mie Goreng Spesial',  'harga' => 20000, 'deskripsi' => 'Mie goreng dengan bumbu rahasia dan telur.'],
                ['nama' => 'Mie Rebus Campur',    'harga' => 19000, 'deskripsi' => 'Mie rebus dengan sayuran segar dan bakso.'],
                ['nama' => 'Mie Tarempa Asli',    'harga' => 22000, 'deskripsi' => 'Mie khas Kepulauan Riau dengan bumbu melayu.'],
                ['nama' => 'Es Teh Manis',         'harga' => 5000,  'deskripsi' => 'Teh manis dingin segar.'],
                ['nama' => 'Pangsit Goreng',       'harga' => 10000, 'deskripsi' => 'Pangsit goreng renyah dengan saus kacang.'],
            ];
        }

        // Padang / Sunda / Jawa / Nusantara
        if (str_contains($nama, 'padang') || str_contains($nama, 'sunda') || str_contains($nama, 'jowo') || str_contains($nama, 'jawa') || str_contains($nama, 'nusantara') || str_contains($nama, 'taburai')) {
            return [
                ['nama' => 'Nasi Rendang',        'harga' => 25000, 'deskripsi' => 'Nasi dengan rendang sapi empuk bumbu khas Padang.'],
                ['nama' => 'Nasi Ayam Pop',       'harga' => 20000, 'deskripsi' => 'Nasi dengan ayam pop khas masakan Padang.'],
                ['nama' => 'Nasi Gulai Ikan',     'harga' => 22000, 'deskripsi' => 'Nasi dengan gulai ikan segar berkuah kuning.'],
                ['nama' => 'Nasi Campur',          'harga' => 18000, 'deskripsi' => 'Nasi dengan pilihan lauk campur.'],
                ['nama' => 'Teh Tarik',            'harga' => 8000,  'deskripsi' => 'Teh susu kental khas Melayu.'],
                ['nama' => 'Es Jeruk',             'harga' => 8000,  'deskripsi' => 'Jeruk segar diperas dingin.'],
            ];
        }

        // Soto / Bakso / Gultik / Warung
        if (str_contains($nama, 'soto') || str_contains($nama, 'bakso') || str_contains($nama, 'gultik') || str_contains($nama, 'sego') || str_contains($nama, 'warung')) {
            return [
                ['nama' => 'Soto Ayam',           'harga' => 18000, 'deskripsi' => 'Soto ayam bening dengan tauge dan telur rebus.'],
                ['nama' => 'Bakso Campur',        'harga' => 20000, 'deskripsi' => 'Bakso dengan campuran tahu, mie, dan pangsit.'],
                ['nama' => 'Bakso Urat',          'harga' => 22000, 'deskripsi' => 'Bakso urat kenyal dengan kuah kaldu sapi.'],
                ['nama' => 'Nasi Putih',           'harga' => 5000,  'deskripsi' => 'Nasi putih hangat.'],
                ['nama' => 'Es Teh Manis',         'harga' => 5000,  'deskripsi' => 'Teh manis dingin segar.'],
                ['nama' => 'Kerupuk',              'harga' => 3000,  'deskripsi' => 'Kerupuk renyah pelengkap.'],
            ];
        }

        // Steak / Grill
        if (str_contains($nama, 'steak') || str_contains($nama, 'grill') || str_contains($nama, 'meat') || str_contains($nama, 'holy') || str_contains($nama, 'sambal bakaran')) {
            return [
                ['nama' => 'Beef Steak',          'harga' => 65000, 'deskripsi' => 'Daging sapi panggang dengan saus lada hitam.'],
                ['nama' => 'Chicken Steak',       'harga' => 45000, 'deskripsi' => 'Ayam panggang dengan saus mushroom.'],
                ['nama' => 'Mixed Grill Platter', 'harga' => 85000, 'deskripsi' => 'Kombinasi daging sapi, ayam, dan sosis panggang.'],
                ['nama' => 'French Fries',        'harga' => 15000, 'deskripsi' => 'Kentang goreng crispy.'],
                ['nama' => 'Es Lemon Tea',        'harga' => 12000, 'deskripsi' => 'Teh lemon segar dengan es.'],
            ];
        }

        // Jepang / Ramen
        if (str_contains($nama, 'donburo') || str_contains($nama, 'dondon') || str_contains($nama, 'marugame') || str_contains($nama, 'ramen') || str_contains($nama, 'tjap')) {
            return [
                ['nama' => 'Chicken Katsu Don',   'harga' => 35000, 'deskripsi' => 'Nasi dengan ayam katsu crispy dan saus teriyaki.'],
                ['nama' => 'Beef Teriyaki Don',   'harga' => 42000, 'deskripsi' => 'Nasi dengan daging sapi saus teriyaki manis.'],
                ['nama' => 'Ramen Ayam',          'harga' => 35000, 'deskripsi' => 'Ramen dengan kaldu ayam dan topping telur.'],
                ['nama' => 'Gyoza',               'harga' => 20000, 'deskripsi' => 'Pangsit panggang khas Jepang isi ayam sayur.'],
                ['nama' => 'Matcha Latte',        'harga' => 18000, 'deskripsi' => 'Minuman matcha susu hangat atau dingin.'],
            ];
        }

        // Arab / Kebuli
        if (str_contains($nama, 'kebuli') || str_contains($nama, 'arab') || str_contains($nama, 'saudi')) {
            return [
                ['nama' => 'Nasi Kebuli Ayam',    'harga' => 35000, 'deskripsi' => 'Nasi kebuli dengan ayam dan acar timun.'],
                ['nama' => 'Nasi Kebuli Kambing', 'harga' => 45000, 'deskripsi' => 'Nasi kebuli dengan daging kambing empuk.'],
                ['nama' => 'Roti Maryam',         'harga' => 12000, 'deskripsi' => 'Roti maryam dengan madu atau saus kari.'],
                ['nama' => 'Teh Habbatussauda',   'harga' => 10000, 'deskripsi' => 'Teh hitam dengan habbatussauda menyehatkan.'],
                ['nama' => 'Kurma',               'harga' => 15000, 'deskripsi' => 'Kurma manis pilihan khas Timur Tengah.'],
            ];
        }

        // Bakery / Kue / Roti
        if (str_contains($nama, 'bakery') || str_contains($nama, 'bake') || str_contains($nama, 'roti') || str_contains($nama, 'kue') || str_contains($nama, 'mako') || str_contains($nama, 'morning') || str_contains($nama, 'mula') || str_contains($nama, 'patisserie')) {
            return [
                ['nama' => 'Croissant Butter',    'harga' => 18000, 'deskripsi' => 'Croissant lapis mentega renyah di luar lembut di dalam.'],
                ['nama' => 'Roti Tawar Spesial',  'harga' => 25000, 'deskripsi' => 'Roti tawar premium soft dan lembut.'],
                ['nama' => 'Kue Lapis Legit',     'harga' => 35000, 'deskripsi' => 'Lapis legit tradisional dengan rempah pilihan.'],
                ['nama' => 'Brownies Coklat',     'harga' => 20000, 'deskripsi' => 'Brownies coklat fudgy dengan topping almond.'],
                ['nama' => 'Kopi Susu',           'harga' => 15000, 'deskripsi' => 'Kopi susu hangat atau dingin.'],
                ['nama' => 'Matcha Cake',         'harga' => 28000, 'deskripsi' => 'Kue matcha lembut dengan frosting cream cheese.'],
            ];
        }

        // Martabak
        if (str_contains($nama, 'martabak')) {
            return [
                ['nama' => 'Martabak Coklat Keju',  'harga' => 35000, 'deskripsi' => 'Martabak manis dengan isian coklat dan keju leleh.'],
                ['nama' => 'Martabak Nutella',       'harga' => 40000, 'deskripsi' => 'Martabak dengan isian nutella premium.'],
                ['nama' => 'Martabak Telur Sapi',    'harga' => 40000, 'deskripsi' => 'Martabak telur dengan isian daging sapi dan bawang.'],
                ['nama' => 'Martabak Telur Ayam',    'harga' => 35000, 'deskripsi' => 'Martabak telur dengan isian ayam cincang.'],
                ['nama' => 'Es Teh Manis',           'harga' => 5000,  'deskripsi' => 'Teh manis dingin segar.'],
            ];
        }

        // Default
        return [
            ['nama' => 'Nasi Campur',           'harga' => 18000, 'deskripsi' => 'Nasi dengan pilihan lauk campur khas nusantara.'],
            ['nama' => 'Nasi Goreng Spesial',   'harga' => 20000, 'deskripsi' => 'Nasi goreng dengan telur, ayam, dan sayuran.'],
            ['nama' => 'Ayam Goreng',           'harga' => 22000, 'deskripsi' => 'Ayam goreng crispy dengan bumbu rempah.'],
            ['nama' => 'Tumis Kangkung',        'harga' => 12000, 'deskripsi' => 'Kangkung tumis bumbu bawang putih dan cabai.'],
            ['nama' => 'Es Teh Manis',          'harga' => 5000,  'deskripsi' => 'Teh manis dingin segar.'],
            ['nama' => 'Es Jeruk',              'harga' => 8000,  'deskripsi' => 'Jeruk segar diperas dingin.'],
        ];
    }
}