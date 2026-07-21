<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restoran;
use App\Models\Kategori;
use App\Models\KategoriMenu;
use App\Models\VerifikasiHalal;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

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
        try {
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

                'no_sertifikat'    => 'nullable|string|max:100',
                'lembaga_penerbit' => 'nullable|string|max:150',
                'masa_berlaku'     => 'nullable|date',
                'dokumen_sertifikat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            ], [
                'nama_restoran.required' => 'Nama restoran wajib diisi.',
                'nama_restoran.max' => 'Nama restoran maksimal 150 karakter.',
                'alamat.required' => 'Alamat usaha wajib diisi.',
                'kota.required' => 'Kota wajib diisi.',
                'provinsi.required' => 'Provinsi wajib diisi.',
                'email_usaha.email' => 'Format email usaha tidak valid.',
                'email_usaha.max' => 'Email usaha maksimal 100 karakter.',
                'foto_utama.image' => 'Foto utama harus berupa gambar.',
                'foto_utama.mimes' => 'Foto utama hanya boleh berformat JPG, JPEG, atau PNG.',
                'foto_utama.max' => 'Ukuran foto utama maksimal 2 MB.',
                'dokumen_sertifikat.file' => 'Dokumen sertifikat tidak valid.',
                'dokumen_sertifikat.mimes' => 'Dokumen sertifikat hanya boleh berformat PDF, JPG, JPEG, atau PNG.',
                'dokumen_sertifikat.max' => 'Ukuran dokumen sertifikat maksimal 5 MB.',
                'harga_rata_rata_min.integer' => 'Harga minimum harus berupa angka.',
                'harga_rata_rata_max.integer' => 'Harga maksimum harus berupa angka.',
                'harga_rata_rata_min.min' => 'Harga minimum tidak boleh negatif.',
                'harga_rata_rata_max.min' => 'Harga maksimum tidak boleh negatif.',
                'masa_berlaku.date' => 'Format tanggal masa berlaku tidak valid.',
                'latitude.numeric' => 'Format koordinat latitude tidak valid.',
                'longitude.numeric' => 'Format koordinat longitude tidak valid.',
                'id_kategori.exists' => 'Kategori yang dipilih tidak valid.',
                'no_telepon.max' => 'Nomor telepon maksimal 20 karakter.',
                'jam_operasional.max' => 'Jam operasional maksimal 100 karakter.',
                'no_sertifikat.max' => 'Nomor sertifikat maksimal 100 karakter.',
                'lembaga_penerbit.max' => 'Lembaga penerbit maksimal 150 karakter.',
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

        } catch (ValidationException $e) {
            // Tangkap error validasi dan kembalikan pesan pertama
            return response()->json([
                'success' => false,
                'message' => collect($e->errors())->flatten()->first()
            ], 422);

        } catch (QueryException $e) {
            // Tangkap error database
            $mysqlError = $e->getMessage();

            if (str_contains($mysqlError, 'latitude')) {
                $message = 'Lokasi usaha belum dipilih. Silakan pilih lokasi pada peta.';
            } elseif (str_contains($mysqlError, 'longitude')) {
                $message = 'Koordinat lokasi belum lengkap.';
            } else {
                $message = 'Terjadi kesalahan saat menyimpan data. Silakan periksa kembali data yang diisi.';
            }

            return response()->json([
                'success' => false,
                'message' => $message
            ], 500);

        } catch (\Exception $e) {
            // Tangkap semua error lain yang tidak terduga
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada sistem. Silakan coba lagi.'
            ], 500);
        }
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
        try {
            $restoran = Restoran::where('id_pemilik', auth()->id())->findOrFail($id);

            $request->validate([
                'nama_restoran' => 'required|string|max:150',
                'deskripsi'     => 'nullable|string',
                'alamat'        => 'required|string',
                'kota'          => 'required|string|max:100',
                'provinsi'      => 'required|string|max:100',
                'foto_utama'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ], [
                'nama_restoran.required' => 'Nama restoran wajib diisi.',
                'nama_restoran.max' => 'Nama restoran maksimal 150 karakter.',
                'alamat.required' => 'Alamat usaha wajib diisi.',
                'kota.required' => 'Kota wajib diisi.',
                'provinsi.required' => 'Provinsi wajib diisi.',
                'foto_utama.image' => 'Foto utama harus berupa gambar.',
                'foto_utama.mimes' => 'Foto utama hanya boleh berformat JPG, JPEG, atau PNG.',
                'foto_utama.max' => 'Ukuran foto utama maksimal 2 MB.',
            ]);

            if ($request->hasFile('foto_utama')) {
                if ($restoran->foto_utama) {
                    Storage::disk('public')->delete($restoran->foto_utama);
                }

                $restoran->foto_utama = $request->file('foto_utama')
                    ->store('restoran', 'public');
            }

            $restoran->update([
                'nama_restoran' => $request->nama_restoran,
                'deskripsi' => $request->deskripsi,
                'alamat' => $request->alamat,
                'kota' => $request->kota,
                'provinsi' => $request->provinsi,
                'kecamatan_kelurahan' => $request->kecamatan_kelurahan,
                'kode_pos' => $request->kode_pos,
                'id_kategori' => $request->id_kategori,
                'jam_operasional' => $request->jam_operasional,
                'no_telepon' => $request->no_telepon,
                'email_usaha' => $request->email_usaha,
                'website_sosmed' => $request->website_sosmed,
                'harga_rata_rata_min' => $request->harga_rata_rata_min,
                'harga_rata_rata_max' => $request->harga_rata_rata_max,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Usaha berhasil diperbarui'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => collect($e->errors())->flatten()->first()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * HAPUS TOKO
     */
    public function destroy($id)
    {
        try {
            $restoran = Restoran::where('id_pemilik', auth()->id())->findOrFail($id);
            
            // Hapus foto jika ada
            if ($restoran->foto_utama) {
                Storage::disk('public')->delete($restoran->foto_utama);
            }
            
            // Hapus verifikasi halal terkait
            if ($restoran->verifikasiHalal) {
                if ($restoran->verifikasiHalal->dokumen_sertifikat) {
                    Storage::disk('public')->delete($restoran->verifikasiHalal->dokumen_sertifikat);
                }
                $restoran->verifikasiHalal()->delete();
            }
            
            $restoran->delete();

            return response()->json([
                'success' => true,
                'message' => 'Usaha berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data. Silakan coba lagi.'
            ], 500);
        }
    }
}