<?php

namespace App\Http\Controllers\Pencari;

use App\Http\Controllers\Controller;
use App\Models\Rekomendasi;
use Illuminate\Support\Facades\Auth;

class RekomendasiController extends Controller
{
    public function index()
    {
        $rekomendasi = Rekomendasi::with(['restoran' => function($query) {
                $query->select(
                    'id_restoran',
                    'nama_restoran',
                    'deskripsi',
                    'status_halal',
                    'rating',
                    'foto_utama',
                    'harga_rata_rata_min',
                    'harga_rata_rata_max'
                );
            }])
            ->where('user_id', Auth::id())
            ->orderBy('rank')
            ->paginate(12);

        return view('pencari.rekomendasi', compact('rekomendasi'));
    }
}