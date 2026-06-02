<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restoran;
use App\Models\Kategori;
use App\Models\KategoriMenu;
use App\Models\VerifikasiHalal;

class DaftarUsahaController extends Controller
{
    /**
     * LIST TOKO PEMILIK
     */
    public function index()
    {
        $user = auth()->user();

        $restoran = Restoran::with(['kategori', 'verifikasiHalal', 'menu'])
            ->where('id_pemilik', $user->id)
            ->latest()
            ->get();

        $kategoriMenu = KategoriMenu::all();

        return view('pemilik.toko.index', compact('restoran', 'kategoriMenu'));
    }

    /**
     * FORM TAMBAH TOKO
     */
    public function create()
    {
        $kategori = Kategori::all();
        return view('pemilik.toko.form', compact('kategori'));
    }

    /**
     * SIMPAN TOKO — return JSON agar fetch di blade bisa parse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_restoran'   => 'required|string|max:150',
            'deskripsi'       => 'nullable|string',
            'alamat'          => 'required|string',
            'kota'            => 'required|string|max:100',
            'provinsi'        => 'required|string|max:100',
            'latitude'        => 'nullable|numeric',
            'longitude'       => 'nullable|numeric',
            'id_kategori'     => 'nullable|exists:kategori,id_kategori',
            'jam_operasional' => 'nullable|string|max:100',
            'no_telepon'      => 'nullable|string|max:20',
            'email_usaha'     => 'nullable|email|max:100',
            'foto_utama'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'harga_rata_rata_min' => 'nullable|integer|min:0',
            'harga_rata_rata_max' => 'nullable|integer|min:0',

            // Sertifikat halal
            'no_sertifikat'    => 'nullable|string|max:100',
            'lembaga_penerbit' => 'nullable|string|max:150',
            'masa_berlaku'     => 'nullable|date',
            'dokumen_sertifikat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $foto = null;

        if ($request->hasFile('foto_utama')) {
            $foto = $request->file('foto_utama')
                ->store('restoran', 'public');
        }

        $restoran = Restoran::create([
            'id_pemilik'      => auth()->id(),
            'nama_restoran'   => $request->nama_restoran,
            'deskripsi'       => $request->deskripsi,
            'alamat'          => $request->alamat,
            'kota'            => $request->kota,
            'provinsi'        => $request->provinsi,
            'latitude'        => $request->latitude,
            'longitude'       => $request->longitude,
            'id_kategori'     => $request->id_kategori,
            'jam_operasional' => $request->jam_operasional,
            'no_telepon'      => $request->no_telepon,
            'email_usaha'     => $request->email_usaha,
            'status_halal'    => 'self_claimed',
            'foto_utama'      => $foto,
            'harga_rata_rata_min' => $request->harga_rata_rata_min,
            'harga_rata_rata_max' => $request->harga_rata_rata_max,
        ]);

        // Upload sertifikat halal
        $dokumen = null;

        if ($request->hasFile('dokumen_sertifikat')) {
            $dokumen = $request->file('dokumen_sertifikat')
                ->store('sertifikat', 'public');
        }

        // Simpan verifikasi halal
        VerifikasiHalal::create([
            'id_restoran' => $restoran->id_restoran,

            'no_sertifikat'    => $request->no_sertifikat,
            'lembaga_penerbit' => $request->lembaga_penerbit,
            'masa_berlaku'     => $request->masa_berlaku,
            'dokumen_sertifikat' => $dokumen,

            'tanggal_pengajuan' => now(),
            'status' => 'pending',

            'bebas_babi'            => $request->boolean('bebas_babi'),
            'daging_halal'          => $request->boolean('daging_halal'),
            'bumbu_bebas_alkohol'   => $request->boolean('bumbu_bebas_alkohol'),
            'kemasan_halal'         => $request->boolean('kemasan_halal'),
            'peralatan_tidak_najis' => $request->boolean('peralatan_tidak_najis'),
            'tidak_jual_alkohol'    => $request->boolean('tidak_jual_alkohol'),
            'dapur_bersih'          => $request->boolean('dapur_bersih'),
            'karyawan_bersih'       => $request->boolean('karyawan_bersih'),
            'sop_kebersihan'        => $request->boolean('sop_kebersihan'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Usaha berhasil didaftarkan!',
        ]);
    }

    /**
     * DETAIL TOKO
     */
    public function show($id)
    {
        $restoran = Restoran::with(['kategori', 'verifikasiHalal', 'menu'])
            ->where('id_pemilik', auth()->id())
            ->findOrFail($id);

        $kategoriMenu = KategoriMenu::all();

        return view('pemilik.toko.show', compact('restoran', 'kategoriMenu'));
    }

    /**
     * FORM EDIT TOKO
     */
    public function edit($id)
    {
        $restoran = Restoran::where('id_pemilik', auth()->id())->findOrFail($id);
        $kategori = Kategori::all();

        return view('pemilik.toko.edit', compact('restoran', 'kategori'));
    }

    /**
     * UPDATE TOKO
     */
    public function update(Request $request, $id)
    {
        $restoran = Restoran::where('id_pemilik', auth()->id())->findOrFail($id);

        $request->validate([
            'nama_restoran' => 'required|string|max:150',
            'deskripsi'     => 'nullable|string',
            'alamat'        => 'required|string',
            'kota'          => 'required|string|max:100',
            'provinsi'      => 'required|string|max:100',
            'foto_utama'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('foto_utama')) {
            $restoran->foto_utama = $request->file('foto_utama')
                ->store('restoran', 'public');
        }

        $restoran->update([
            'nama_restoran'   => $request->nama_restoran,
            'deskripsi'       => $request->deskripsi,
            'alamat'          => $request->alamat,
            'kota'            => $request->kota,
            'provinsi'        => $request->provinsi,
            'latitude'        => $request->latitude,
            'longitude'       => $request->longitude,
            'id_kategori'     => $request->id_kategori,
            'jam_operasional' => $request->jam_operasional,
        ]);

        return response()->json(['success' => true, 'message' => 'Usaha berhasil diperbarui']);
    }

    /**
     * HAPUS TOKO
     */
    public function destroy($id)
    {
        $restoran = Restoran::where('id_pemilik', auth()->id())->findOrFail($id);
        $restoran->delete();

        return response()->json(['success' => true, 'message' => 'Usaha berhasil dihapus']);
    }
}