<?php

namespace App\Http\Controllers;

use App\Models\Restoran;
use Illuminate\Http\Request;

class RestoranPublicController extends Controller
{
    /**
     * Menampilkan detail restoran untuk user biasa (pencari)
     * 
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Ambil data restoran dengan relasi yang dibutuhkan
        $restoran = Restoran::with([
            'menus' => function($query) {
                $query->where('tersedia', 1); // Hanya menu yang tersedia
            },
            'kategori',
            'subKategori',
            'verifikasiHalal' // Relasi ke verifikasi halal
        ])->findOrFail($id);
        
        // Kirim ke view pencari.detail_toko
        return view('pencari.detail_toko', compact('restoran'));
    }
}