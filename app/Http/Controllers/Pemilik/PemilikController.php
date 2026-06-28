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

        $restorans = Restoran::with(['menu', 'verifikasiHalal', 'kategori', 'subKategori'])
            ->where('id_pemilik', $user->id)
            ->get();

        $totalUsaha        = $restorans->count();
        $totalTerverifikasi = $restorans->filter(fn($r) =>
            in_array($r->verifikasiHalal?->status, ['approved', 'terverifikasi'])
        )->count();
        $totalPending      = $restorans->filter(fn($r) =>
            in_array($r->verifikasiHalal?->status, ['pending', 'proses'])
        )->count();
        $totalDitolak      = $restorans->filter(fn($r) =>
            $r->verifikasiHalal?->status === 'ditolak'
        )->count();
        $totalMenu         = $restorans->sum(fn($r) => $r->menu->count());

        return view('pemilik.dashboard', compact(
            'restorans', 'totalUsaha', 'totalTerverifikasi',
            'totalPending', 'totalDitolak', 'totalMenu'
        ));
    }

    /**
     * Halaman daftar semua toko pemilik
     * GET /pemilik/toko
     */
    public function index()
    {
        $user = Auth::user();

        $restorans = Restoran::with(['menu', 'verifikasiHalal', 'kategori', 'subKategori'])
            ->where('id_pemilik', $user->id)
            ->get();

        return view('pemilik.toko.index', compact('restorans'));
    }

    /**
     * Halaman detail satu toko
     * GET /pemilik/toko/{id}
     */
    public function show($id)
    {
        $restoran = Restoran::with(['menu', 'verifikasiHalal', 'kategori', 'subKategori'])
            ->where('id_restoran', $id)
            ->where('id_pemilik', Auth::id())
            ->firstOrFail();

        $verifikasi = $restoran->verifikasiHalal;

        return view('pemilik.toko.show', compact('restoran', 'verifikasi'));
    }

    /**
     * Halaman form tambah toko
     * GET /pemilik/toko/create
     */
    public function create()
    {
        return view('pemilik.toko.create');
    }

    /**
     * Simpan toko baru
     * POST /pemilik/toko
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_restoran' => 'required|string|max:255',
            'alamat' => 'required|string',
            'telepon' => 'nullable|string|max:20',
            'deskripsi' => 'nullable|string',
            'id_kategori' => 'nullable|exists:kategoris,id_kategori',
            'id_sub_kategori' => 'nullable|exists:sub_kategoris,id_sub_kategori',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $restoran = Restoran::create([
            'id_pemilik' => Auth::id(),
            'nama_restoran' => $request->nama_restoran,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
            'deskripsi' => $request->deskripsi,
            'id_kategori' => $request->id_kategori,
            'id_sub_kategori' => $request->id_sub_kategori,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status_buka' => true,
        ]);

        return redirect()
            ->route('pemilik.toko.show', $restoran->id_restoran)
            ->with('success', 'Usaha berhasil didaftarkan! Silakan upload sertifikat halal.');
    }

    /**
     * Halaman form edit toko
     * GET /pemilik/toko/{id}/edit
     */
    public function edit($id)
    {
        $restoran = Restoran::where('id_restoran', $id)
            ->where('id_pemilik', Auth::id())
            ->firstOrFail();

        return view('pemilik.toko.edit', compact('restoran'));
    }

    /**
     * Update toko
     * PUT /pemilik/toko/{id}
     */
    public function update(Request $request, $id)
    {
        $restoran = Restoran::where('id_restoran', $id)
            ->where('id_pemilik', Auth::id())
            ->firstOrFail();

        $request->validate([
            'nama_restoran' => 'required|string|max:255',
            'alamat' => 'required|string',
            'telepon' => 'nullable|string|max:20',
            'deskripsi' => 'nullable|string',
            'id_kategori' => 'nullable|exists:kategoris,id_kategori',
            'id_sub_kategori' => 'nullable|exists:sub_kategoris,id_sub_kategori',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $restoran->update($request->all());

        return redirect()
            ->route('pemilik.toko.show', $restoran->id_restoran)
            ->with('success', 'Data usaha berhasil diperbarui.');
    }

    /**
     * Hapus toko
     * DELETE /pemilik/toko/{id}
     */
    public function destroy($id)
    {
        $restoran = Restoran::where('id_restoran', $id)
            ->where('id_pemilik', Auth::id())
            ->firstOrFail();

        // Hapus semua menu terkait
        $restoran->menu()->delete();
        
        // Hapus verifikasi terkait
        if ($restoran->verifikasiHalal) {
            $restoran->verifikasiHalal()->delete();
        }
        
        // Hapus restoran
        $restoran->delete();

        return redirect()
            ->route('pemilik.toko.index')
            ->with('success', 'Usaha berhasil dihapus.');
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