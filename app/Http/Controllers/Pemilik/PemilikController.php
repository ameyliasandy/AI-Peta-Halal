<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\Restoran;
use App\Models\Menu;
use App\Models\VerifikasiHalal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PemilikController extends Controller
{
    /**
     * Dashboard pemilik usaha
     * GET /pemilik/dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();

        $restoran = Restoran::with(['menu', 'verifikasiHalal', 'kategori', 'subKategori'])
            ->where('id_pemilik', $user->id)
            ->first();

        $verifikasi  = $restoran?->verifikasiHalal;
        $jumlahMenu  = $restoran?->menu->count() ?? 0;

        // Statistik tambahan (hanya tampil kalau sudah approved)
        $dilihat  = 0;
        $disimpan = 0;

        if ($verifikasi?->status === 'approved' && $restoran) {
            // Ganti dengan query real kalau ada tabel views/saves
            // $dilihat  = DB::table('restoran_views')
            //     ->where('id_restoran', $restoran->id_restoran)
            //     ->whereBetween('created_at', [now()->startOfWeek(), now()])
            //     ->count();

            // $disimpan = DB::table('hasil_pencarian')
            //     ->where('id_restoran', $restoran->id_restoran)
            //     ->count();
        }

        return view('pemilik.dashboard', compact(
            'restoran',
            'verifikasi',
            'jumlahMenu',
            'dilihat',
            'disimpan',
        ));
    }

    /**
     * Halaman detail toko pemilik
     * GET /pemilik/index
     */
    public function index()
    {
        $user = Auth::user();

        $restoran = Restoran::with(['menu', 'verifikasiHalal', 'kategori', 'subKategori'])
            ->where('id_pemilik', $user->id)
            ->firstOrFail();

        $verifikasi = $restoran->verifikasiHalal;

        return view('pemilik.toko.index', compact('restoran', 'verifikasi'));
    }

    /**
     * Toggle status buka/tutup restoran
     * PATCH /pemilik/restoran/{id}/toggle-buka
     */
    public function toggleBuka(int $id)
    {
        $restoran = Restoran::where('id_restoran', $id)
            ->where('id_pemilik', Auth::id())
            ->firstOrFail();

        $restoran->update([
            'status_buka' => !$restoran->status_buka,
        ]);

        return back()->with('success', $restoran->status_buka ? 'Toko dibuka.' : 'Toko ditutup.');
    }
}