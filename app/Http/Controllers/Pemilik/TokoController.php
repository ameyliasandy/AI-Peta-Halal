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
use Illuminate\Validation\ValidationException;

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
        $pemilik = $this->pemilik();
        $restoran = $this->restoran();
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

        $kategori = Kategori::all();
        $subKategori = SubKategori::all();

        return view('pemilik.toko.create', compact('kategori', 'subKategori'));
    }

    // ─── SIMPAN USAHA BARU ──────────────────────────────────
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama_restoran' => 'required|string|max:150',
                'id_kategori' => 'required|exists:kategori,id_kategori',
                'id_sub_kategori' => 'nullable|exists:sub_kategori,id_sub_kategori',
                'kapasitas_tempat' => 'nullable|integer|min:1',
                'jam_operasional' => 'nullable|string|max:100',
                'deskripsi' => 'nullable|string|max:200',
                'alamat' => 'required|string',
                'kecamatan_kelurahan' => 'required|string|max:100',
                'kota' => 'required|string|max:100',
                'provinsi' => 'required|string|max:100',
                'kode_pos' => 'nullable|string|max:10',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'no_telepon' => 'required|string|max:15',
                'email_usaha' => 'nullable|email|max:100',
                'website_sosmed' => 'nullable|string|max:200',
                'harga_rata_rata_min' => 'nullable|integer|min:0',
                'harga_rata_rata_max' => 'nullable|integer|min:0',
                'foto_utama' => 'nullable|image|max:2048',
                'punya_sertifikat' => 'required|in:ya,tidak',
                'no_sertifikat' => 'nullable|string|max:100',
                'lembaga_penerbit' => 'nullable|string|max:150',
                'masa_berlaku' => 'nullable|date',
                'file_sertifikat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'alasan_bahan_baku' => 'nullable|boolean',
                'alasan_daging' => 'nullable|boolean',
                'alasan_bumbu' => 'nullable|boolean',
                'alasan_kemasan' => 'nullable|boolean',
                'alasan_peralatan' => 'nullable|boolean',
                'alasan_tidak_alkohol' => 'nullable|boolean',
                'alasan_dapur_bersih' => 'nullable|boolean',
                'alasan_kebersihan_staff' => 'nullable|boolean',
                'alasan_sop' => 'nullable|boolean',
            ], [
                // Pesan error untuk validasi
                'nama_restoran.required' => 'Nama restoran wajib diisi.',
                'nama_restoran.max' => 'Nama restoran maksimal 150 karakter.',
                
                'id_kategori.required' => 'Kategori usaha wajib dipilih.',
                'id_kategori.exists' => 'Kategori yang dipilih tidak valid.',
                
                'id_sub_kategori.exists' => 'Sub kategori yang dipilih tidak valid.',
                
                'kapasitas_tempat.integer' => 'Kapasitas tempat harus berupa angka.',
                'kapasitas_tempat.min' => 'Kapasitas tempat minimal 1 orang.',
                
                'jam_operasional.max' => 'Jam operasional maksimal 100 karakter.',
                
                'deskripsi.max' => 'Deskripsi maksimal 200 karakter.',
                
                'alamat.required' => 'Alamat usaha wajib diisi.',
                
                'kecamatan_kelurahan.required' => 'Kecamatan/Kelurahan wajib diisi.',
                'kecamatan_kelurahan.max' => 'Kecamatan/Kelurahan maksimal 100 karakter.',
                
                'kota.required' => 'Kota wajib diisi.',
                'kota.max' => 'Kota maksimal 100 karakter.',
                
                'provinsi.required' => 'Provinsi wajib diisi.',
                'provinsi.max' => 'Provinsi maksimal 100 karakter.',
                
                'kode_pos.max' => 'Kode pos maksimal 10 karakter.',
                
                'latitude.numeric' => 'Format koordinat latitude tidak valid.',
                'longitude.numeric' => 'Format koordinat longitude tidak valid.',
                
                'no_telepon.required' => 'Nomor telepon wajib diisi.',
                'no_telepon.max' => 'Nomor telepon maksimal 15 karakter.',
                
                'email_usaha.email' => 'Format email usaha tidak valid.',
                'email_usaha.max' => 'Email usaha maksimal 100 karakter.',
                
                'website_sosmed.max' => 'Website/Sosial media maksimal 200 karakter.',
                
                'harga_rata_rata_min.integer' => 'Harga minimum harus berupa angka.',
                'harga_rata_rata_min.min' => 'Harga minimum tidak boleh negatif.',
                'harga_rata_rata_max.integer' => 'Harga maksimum harus berupa angka.',
                'harga_rata_rata_max.min' => 'Harga maksimum tidak boleh negatif.',
                
                'foto_utama.image' => 'Foto utama harus berupa gambar.',
                'foto_utama.max' => 'Ukuran foto utama maksimal 2 MB.',
                
                'punya_sertifikat.required' => 'Pilihan kepemilikan sertifikat wajib dipilih.',
                'punya_sertifikat.in' => 'Pilihan kepemilikan sertifikat tidak valid.',
                
                'no_sertifikat.max' => 'Nomor sertifikat maksimal 100 karakter.',
                
                'lembaga_penerbit.max' => 'Lembaga penerbit maksimal 150 karakter.',
                
                'masa_berlaku.date' => 'Format tanggal masa berlaku tidak valid.',
                
                'file_sertifikat.file' => 'Dokumen sertifikat tidak valid.',
                'file_sertifikat.mimes' => 'Dokumen sertifikat hanya boleh berformat PDF, JPG, JPEG, atau PNG.',
                'file_sertifikat.max' => 'Ukuran dokumen sertifikat maksimal 5 MB.',
                
                'alasan_bahan_baku.boolean' => 'Format data bahan baku tidak valid.',
                'alasan_daging.boolean' => 'Format data daging tidak valid.',
                'alasan_bumbu.boolean' => 'Format data bumbu tidak valid.',
                'alasan_kemasan.boolean' => 'Format data kemasan tidak valid.',
                'alasan_peralatan.boolean' => 'Format data peralatan tidak valid.',
                'alasan_tidak_alkohol.boolean' => 'Format data alkohol tidak valid.',
                'alasan_dapur_bersih.boolean' => 'Format data dapur bersih tidak valid.',
                'alasan_kebersihan_staff.boolean' => 'Format data kebersihan staff tidak valid.',
                'alasan_sop.boolean' => 'Format data SOP tidak valid.',
            ]);

            $fotoUtama = null;
            if ($request->hasFile('foto_utama')) {
                $fotoUtama = $request->file('foto_utama')->store('restoran', 'public');
            }

            $restoran = Restoran::create([
                'id_pemilik' => $this->pemilikId(),
                'id_kategori' => $request->id_kategori,
                'id_sub_kategori' => $request->id_sub_kategori,
                'nama_restoran' => $request->nama_restoran,
                'deskripsi' => $request->deskripsi,
                'alamat' => $request->alamat,
                'kecamatan_kelurahan' => $request->kecamatan_kelurahan,
                'kota' => $request->kota,
                'provinsi' => $request->provinsi,
                'kode_pos' => $request->kode_pos,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'jam_operasional' => $request->jam_operasional,
                'kapasitas_tempat' => $request->kapasitas_tempat,
                'harga_rata_rata_min' => $request->harga_rata_rata_min,
                'harga_rata_rata_max' => $request->harga_rata_rata_max,
                'no_telepon' => $request->no_telepon,
                'email_usaha' => $request->email_usaha,
                'website_sosmed' => $request->website_sosmed,
                'foto_utama' => $fotoUtama,
                'status_halal' => $request->punya_sertifikat === 'ya' ? 'certified' : 'self_claimed',
                'status_buka' => false,
            ]);

            $fileSertifikat = null;
            if ($request->hasFile('file_sertifikat')) {
                $fileSertifikat = $request->file('file_sertifikat')->store('sertifikat', 'public');
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

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.')
                ->withInput();
        }
    }

    // ─── MENU CRUD ───────────────────────────────────────────
    public function storeMenu(Request $request)
    {
        try {
            $request->validate([
                'nama_menu' => 'required|string|max:150',
                'harga' => 'required|integer|min:0',
                'deskripsi' => 'nullable|string|max:255',
                'id_kategori_menu' => 'nullable|exists:kategori_menu,id_kategori_menu',
                'tersedia' => 'nullable|boolean',
                'foto_menu' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ], [
                'nama_menu.required' => 'Nama menu wajib diisi.',
                'nama_menu.max' => 'Nama menu maksimal 150 karakter.',
                'harga.required' => 'Harga menu wajib diisi.',
                'harga.integer' => 'Harga harus berupa angka.',
                'harga.min' => 'Harga tidak boleh negatif.',
                'deskripsi.max' => 'Deskripsi menu maksimal 255 karakter.',
                'id_kategori_menu.exists' => 'Kategori menu yang dipilih tidak valid.',
                'tersedia.boolean' => 'Status ketersediaan tidak valid.',
                'foto_menu.image' => 'Foto menu harus berupa gambar.',
                'foto_menu.mimes' => 'Foto menu hanya boleh berformat JPG, JPEG, atau PNG.',
                'foto_menu.max' => 'Ukuran foto menu maksimal 2 MB.',
            ]);

            $restoran = $this->restoran();
            if (!$restoran) {
                return response()->json(['success' => false, 'message' => 'Toko tidak ditemukan'], 404);
            }

            $data = $request->only(['nama_menu', 'deskripsi', 'harga', 'id_kategori_menu', 'tersedia']);
            $data['id_restoran'] = $restoran->id_restoran;
            $data['tersedia'] = $request->input('tersedia', 1);

            if ($request->hasFile('foto_menu')) {
                $data['foto_menu'] = $request->file('foto_menu')->store('menu', 'public');
            }

            $menu = Menu::create($data);
            return response()->json(['success' => true, 'menu' => $menu]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => collect($e->errors())->flatten()->first()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambahkan menu. Silakan coba lagi.'
            ], 500);
        }
    }

    public function updateMenu(Request $request, $id)
    {
        try {
            $request->validate([
                'nama_menu' => 'required|string|max:150',
                'harga' => 'required|integer|min:0',
                'deskripsi' => 'nullable|string|max:255',
                'id_kategori_menu' => 'nullable|exists:kategori_menu,id_kategori_menu',
                'tersedia' => 'nullable|boolean',
                'foto_menu' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ], [
                'nama_menu.required' => 'Nama menu wajib diisi.',
                'nama_menu.max' => 'Nama menu maksimal 150 karakter.',
                'harga.required' => 'Harga menu wajib diisi.',
                'harga.integer' => 'Harga harus berupa angka.',
                'harga.min' => 'Harga tidak boleh negatif.',
                'deskripsi.max' => 'Deskripsi menu maksimal 255 karakter.',
                'id_kategori_menu.exists' => 'Kategori menu yang dipilih tidak valid.',
                'tersedia.boolean' => 'Status ketersediaan tidak valid.',
                'foto_menu.image' => 'Foto menu harus berupa gambar.',
                'foto_menu.mimes' => 'Foto menu hanya boleh berformat JPG, JPEG, atau PNG.',
                'foto_menu.max' => 'Ukuran foto menu maksimal 2 MB.',
            ]);

            $restoran = $this->restoran();
            if (!$restoran) {
                return response()->json(['success' => false, 'message' => 'Toko tidak ditemukan'], 404);
            }

            $menu = Menu::where('id_restoran', $restoran->id_restoran)->findOrFail($id);
            $data = $request->only(['nama_menu', 'deskripsi', 'harga', 'id_kategori_menu', 'tersedia']);

            if ($request->hasFile('foto_menu')) {
                // Hapus foto lama jika ada
                if ($menu->foto_menu) {
                    Storage::disk('public')->delete($menu->foto_menu);
                }
                $data['foto_menu'] = $request->file('foto_menu')->store('menu', 'public');
            }

            $menu->update($data);
            return response()->json(['success' => true, 'menu' => $menu->fresh()]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => collect($e->errors())->flatten()->first()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui menu. Silakan coba lagi.'
            ], 500);
        }
    }

    public function destroyMenu($id)
    {
        try {
            $restoran = $this->restoran();
            if (!$restoran) {
                return response()->json(['success' => false, 'message' => 'Toko tidak ditemukan'], 404);
            }

            $menu = Menu::where('id_restoran', $restoran->id_restoran)->findOrFail($id);

            // Hapus foto jika ada
            if ($menu->foto_menu) {
                Storage::disk('public')->delete($menu->foto_menu);
            }
            $menu->delete();

            return response()->json(['success' => true, 'message' => 'Menu berhasil dihapus']);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus menu. Silakan coba lagi.'
            ], 500);
        }
    }

    public function toggleMenu($id)
    {
        try {
            $restoran = $this->restoran();
            if (!$restoran) {
                return response()->json(['success' => false, 'message' => 'Toko tidak ditemukan'], 404);
            }

            $menu = Menu::where('id_restoran', $restoran->id_restoran)->findOrFail($id);
            $menu->update(['tersedia' => !$menu->tersedia]);

            return response()->json([
                'success' => true,
                'tersedia' => $menu->tersedia,
                'message' => $menu->tersedia ? 'Menu berhasil diaktifkan' : 'Menu berhasil dinonaktifkan'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengubah status menu. Silakan coba lagi.'
            ], 500);
        }
    }
}