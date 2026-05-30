<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\Restoran;
use App\Models\Menu;
use App\Models\Kategori;
use App\Models\SubKategori;
use App\Models\KategoriMenu;
use App\Models\VerifikasiHalal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TokoController extends Controller
{
    // ─── Helper: ambil ID pemilik yang sedang login ──────────
    private function pemilikId(): int
    {
        return session('pemilik')?->id ?? auth()->id();
    }

    private function pemilik(): User
    {
        return User::where('role', 'pemilik_usaha')
            ->findOrFail($this->pemilikId());
    }

    private function restoran(): ?Restoran
    {
        return Restoran::with(['kategori', 'subKategori', 'verifikasiHalal', 'menu'])
                       ->where('id_pemilik', $this->pemilikId())
                       ->first();
    }

    // ─── HALAMAN UTAMA TOKO ─────────────────────────────────
    public function index()
    {
        $pemilik      = $this->pemilik();
        $restoran     = $this->restoran();
        $kategoriMenu = KategoriMenu::all();

        return view('pemilik.toko.index', compact('pemilik', 'restoran', 'kategoriMenu'));
    }

    // ─── FORM TAMBAH USAHA ──────────────────────────────────
    public function create()
    {
        if ($this->restoran()) {
            return redirect()->route('pemilik.toko.index')
                ->with('error', 'Kamu sudah memiliki usaha yang terdaftar.');
        }

        $kategori    = Kategori::all();
        $subKategori = SubKategori::all();

        return view('pemilik.toko.create', compact('kategori', 'subKategori'));
    }

    // ─── SIMPAN USAHA BARU ──────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'nama_restoran'       => 'required|string|max:150',
            'id_kategori'         => 'required|exists:kategori,id_kategori',
            'id_sub_kategori'     => 'nullable|exists:sub_kategori,id_sub_kategori',
            'kapasitas_tempat'    => 'nullable|integer|min:1',
            'jam_operasional'     => 'nullable|string|max:100',
            'deskripsi'           => 'nullable|string|max:200',
            'alamat'              => 'required|string',
            'kecamatan_kelurahan' => 'required|string|max:100',
            'kota'                => 'required|string|max:100',
            'provinsi'            => 'required|string|max:100',
            'kode_pos'            => 'nullable|string|max:10',
            'latitude'            => 'nullable|numeric',
            'longitude'           => 'nullable|numeric',
            'no_telepon'          => 'required|string|max:15',
            'email_usaha'         => 'nullable|email|max:100',
            'website_sosmed'      => 'nullable|string|max:200',
            'harga_rata_rata_min' => 'nullable|integer|min:0',
            'harga_rata_rata_max' => 'nullable|integer|min:0',
            'foto_utama'          => 'nullable|image|max:2048',
            'punya_sertifikat'    => 'required|in:ya,tidak',
            'no_sertifikat'       => 'nullable|string|max:100',
            'lembaga_penerbit'    => 'nullable|string|max:150',
            'masa_berlaku'        => 'nullable|date',
            'file_sertifikat'     => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'alasan_bahan_baku'       => 'nullable|boolean',
            'alasan_daging'           => 'nullable|boolean',
            'alasan_bumbu'            => 'nullable|boolean',
            'alasan_kemasan'          => 'nullable|boolean',
            'alasan_peralatan'        => 'nullable|boolean',
            'alasan_tidak_alkohol'    => 'nullable|boolean',
            'alasan_dapur_bersih'     => 'nullable|boolean',
            'alasan_kebersihan_staff' => 'nullable|boolean',
            'alasan_sop'              => 'nullable|boolean',
        ]);

        $fotoUtama = null;
        if ($request->hasFile('foto_utama')) {
            $fotoUtama = $request->file('foto_utama')->store('restoran', 'public');
        }

        $restoran = Restoran::create([
            'id_pemilik'          => $this->pemilikId(),
            'id_kategori'         => $request->id_kategori,
            'id_sub_kategori'     => $request->id_sub_kategori,
            'nama_restoran'       => $request->nama_restoran,
            'deskripsi'           => $request->deskripsi,
            'alamat'              => $request->alamat,
            'kecamatan_kelurahan' => $request->kecamatan_kelurahan,
            'kota'                => $request->kota,
            'provinsi'            => $request->provinsi,
            'kode_pos'            => $request->kode_pos,
            'latitude'            => $request->latitude,
            'longitude'           => $request->longitude,
            'jam_operasional'     => $request->jam_operasional,
            'kapasitas_tempat'    => $request->kapasitas_tempat,
            'harga_rata_rata_min' => $request->harga_rata_rata_min,
            'harga_rata_rata_max' => $request->harga_rata_rata_max,
            'no_telepon'          => $request->no_telepon,
            'email_usaha'         => $request->email_usaha,
            'website_sosmed'      => $request->website_sosmed,
            'foto_utama'          => $fotoUtama,
            'status_halal'        => $request->punya_sertifikat === 'ya' ? 'certified' : 'self_claimed',
            'status_buka'         => false,
        ]);

        $fileSertifikat = null;
        if ($request->hasFile('file_sertifikat')) {
            $fileSertifikat = $request->file('file_sertifikat')->store('sertifikat', 'public');
        }

        $alasanChecklist = null;
        if ($request->punya_sertifikat === 'tidak') {
            $alasanChecklist = json_encode([
                'bahan_baku'       => (bool) $request->alasan_bahan_baku,
                'daging'           => (bool) $request->alasan_daging,
                'bumbu'            => (bool) $request->alasan_bumbu,
                'kemasan'          => (bool) $request->alasan_kemasan,
                'peralatan'        => (bool) $request->alasan_peralatan,
                'tidak_alkohol'    => (bool) $request->alasan_tidak_alkohol,
                'dapur_bersih'     => (bool) $request->alasan_dapur_bersih,
                'kebersihan_staff' => (bool) $request->alasan_kebersihan_staff,
                'sop'              => (bool) $request->alasan_sop,
            ]);
        }

        DB::table('verifikasi_halal')->insert([
            'id_restoran' => $restoran->id_restoran,

            'no_sertifikat' => $request->no_sertifikat,
            'lembaga_penerbit' => $request->lembaga_penerbit,
            'masa_berlaku' => $request->masa_berlaku,
            'dokumen_sertifikat' => $fileSertifikat,

            'bebas_babi' => $request->boolean('alasan_bahan_baku'),
            'daging_halal' => $request->boolean('alasan_daging'),
            'bumbu_bebas_alkohol' => $request->boolean('alasan_bumbu'),
            'kemasan_halal' => $request->boolean('alasan_kemasan'),
            'peralatan_tidak_najis' => $request->boolean('alasan_peralatan'),
            'tidak_jual_alkohol' => $request->boolean('alasan_tidak_alkohol'),
            'dapur_bersih' => $request->boolean('alasan_dapur_bersih'),
            'karyawan_bersih' => $request->boolean('alasan_kebersihan_staff'),
            'sop_kebersihan' => $request->boolean('alasan_sop'),

            'tanggal_pengajuan' => now(),
            'status' => 'pending',

            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('pemilik.toko.index')
            ->with('success', 'Usaha berhasil didaftarkan! Tim kami akan memverifikasi dalam 2–3 hari kerja.');
    }

    // ─── GET PROFIL (JSON untuk modal edit) ─────────────────
    public function getProfil()
    {
        $restoran = $this->restoran();
        $pemilik  = $this->pemilik()->makeHidden(['password']);

        return response()->json([
            'pemilik'  => $pemilik,
            'restoran' => $restoran,
        ]);
    }

    // ─── UPDATE PROFIL ───────────────────────────────────────
    public function updateProfil(Request $request)
    {
        $pemilik  = $this->pemilik();
        $restoran = $this->restoran();

        $pData = $request->only(['nama', 'no_hp', 'website_sosmed']);
        if ($request->filled('password')) {
            $pData['password'] = Hash::make($request->password);
        }
        if ($request->hasFile('foto_profil')) {
            if ($pemilik->foto_profil) Storage::disk('public')->delete($pemilik->foto_profil);
            $pData['foto_profil'] = $request->file('foto_profil')->store('pemilik', 'public');
        }
        $pemilik->update($pData);
        session(['pemilik' => $pemilik->fresh()]);

        if ($restoran) {
            $rData = $request->only([
                'nama_restoran', 'deskripsi', 'alamat', 'kecamatan_kelurahan',
                'kota', 'provinsi', 'jam_operasional', 'no_telepon',
                'email_usaha', 'harga_rata_rata_min', 'harga_rata_rata_max',
            ]);
            if ($request->hasFile('foto_utama')) {
                if ($restoran->foto_utama) Storage::disk('public')->delete($restoran->foto_utama);
                $rData['foto_utama'] = $request->file('foto_utama')->store('restoran', 'public');
            }
            $restoran->update($rData);
        }

        return response()->json(['success' => true, 'message' => 'Profil berhasil diperbarui']);
    }

    // ─── TOGGLE BUKA/TUTUP ───────────────────────────────────
    public function toggleBuka()
    {
        $restoran = $this->restoran();
        if (!$restoran) return response()->json(['success' => false, 'message' => 'Toko tidak ditemukan']);

        $restoran->update(['status_buka' => !$restoran->status_buka]);
        return response()->json(['success' => true, 'status_buka' => $restoran->status_buka]);
    }

    // ─── MENU CRUD ───────────────────────────────────────────
    public function storeMenu(Request $request)
    {
        $request->validate([
            'nama_menu' => 'required|string|max:150',
            'harga'     => 'required|integer|min:0',
        ]);

        $restoran = $this->restoran();
        if (!$restoran) return response()->json(['success' => false, 'message' => 'Toko tidak ditemukan']);

        $data = $request->only(['nama_menu', 'deskripsi', 'harga', 'id_kategori_menu', 'tersedia']);
        $data['id_restoran'] = $restoran->id_restoran;
        $data['tersedia']    = $request->input('tersedia', 1);

        if ($request->hasFile('foto_menu')) {
            $data['foto_menu'] = $request->file('foto_menu')->store('menu', 'public');
        }

        $menu = Menu::create($data);
        return response()->json(['success' => true, 'menu' => $menu]);
    }

    public function updateMenu(Request $request, $id)
    {
        $restoran = $this->restoran();
        $menu     = Menu::where('id_restoran', $restoran->id_restoran)->findOrFail($id);
        $data     = $request->only(['nama_menu', 'deskripsi', 'harga', 'id_kategori_menu', 'tersedia']);

        if ($request->hasFile('foto_menu')) {
            if ($menu->foto_menu) Storage::disk('public')->delete($menu->foto_menu);
            $data['foto_menu'] = $request->file('foto_menu')->store('menu', 'public');
        }

        $menu->update($data);
        return response()->json(['success' => true, 'menu' => $menu->fresh()]);
    }

    public function destroyMenu($id)
    {
        $restoran = $this->restoran();
        $menu     = Menu::where('id_restoran', $restoran->id_restoran)->findOrFail($id);

        if ($menu->foto_menu) Storage::disk('public')->delete($menu->foto_menu);
        $menu->delete();

        return response()->json(['success' => true]);
    }

    public function toggleMenu($id)
    {
        $restoran = $this->restoran();
        $menu     = Menu::where('id_restoran', $restoran->id_restoran)->findOrFail($id);
        $menu->update(['tersedia' => !$menu->tersedia]);

        return response()->json(['success' => true, 'tersedia' => $menu->tersedia]);
    }
}