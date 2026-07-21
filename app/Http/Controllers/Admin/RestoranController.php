<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restoran;
use App\Models\Kategori;
use App\Models\User;
use App\Models\VerifikasiHalal;
use App\Models\Menu;
use App\Models\KategoriMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class RestoranController extends Controller
{

    // ─── LIST ───────────────────────────────────────────────
    public function list(Request $request)
    {
        $query = Restoran::with(['pemilik', 'kategori', 'verifikasiHalal']);

        if ($request->filled('search')) {
            $query->where('nama_restoran', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }

        match ($request->get('sort', 'terbaru')) {
            'nama_az' => $query->orderBy('nama_restoran'),
            'rating'  => $query->orderByDesc('rating'),
            default   => $query->orderByDesc('created_at'),
        };

        $restoran = $query->paginate(10)->withQueryString();

        $stats = [
            'terverifikasi' => VerifikasiHalal::where('status', 'terverifikasi')->count(),
            'pending'       => VerifikasiHalal::where('status', 'pending')->count(),
            'ditolak'       => VerifikasiHalal::where('status', 'ditolak')->count(),
            'expire'        => VerifikasiHalal::where('status', 'terverifikasi')
                                ->whereNotNull('masa_berlaku')
                                ->whereBetween('masa_berlaku', [now(), now()->addDays(30)])
                                ->count(),
        ];

        $kategori = Kategori::all();

        // ✅ FIX: assign ke $pemilik dan gunakan kolom yang benar (User model)
        $pemilik = User::where('role', 'pemilik_usaha')->get();

        return view('admin.restoran.list', compact(
            'restoran',
            'stats',
            'kategori',
            'pemilik'
        ));
    }

    // ─── SHOW (detail toko) ─────────────────────────────────
    public function show($id)
    {
        $restoran = Restoran::with([
            'pemilik', 'kategori', 'subKategori',
            'menu', 'verifikasiHalal',
        ])->findOrFail($id);

        $kategoriMenu = KategoriMenu::all();

        return view('admin.restoran.show', compact('restoran', 'kategoriMenu'));
    }

    // ─── STORE (POST /admin/restoran) ───────────────────────
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_restoran'   => 'required|string|max:150',
                'id_pemilik'      => 'required|exists:users,id',
                'id_kategori'     => 'required|exists:kategori,id_kategori',
                'alamat'          => 'required|string',
                'jam_operasional' => 'required|string',
                'kota'            => 'nullable|string|max:100',
                'provinsi'        => 'nullable|string|max:100',
                'kecamatan_kelurahan' => 'nullable|string|max:100',
                'kode_pos'        => 'nullable|string|max:10',
                'no_telepon'      => 'nullable|string|max:20',
                'email_usaha'     => 'nullable|email|max:100',
                'website_sosmed'  => 'nullable|string|max:200',
                'kapasitas_tempat' => 'nullable|integer|min:1',
                'harga_rata_rata_min' => 'nullable|integer|min:0',
                'harga_rata_rata_max' => 'nullable|integer|min:0',
                'latitude'        => 'nullable|numeric',
                'longitude'       => 'nullable|numeric',
                'foto_utama'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'tipe_halal'      => 'nullable|in:certified,self_claimed,none',
                'no_sertifikat'   => 'nullable|string|max:100',
                'lembaga_penerbit' => 'nullable|string|max:150',
                'masa_berlaku'    => 'nullable|date',
                'status_verifikasi' => 'nullable|in:pending,terverifikasi,ditolak',
                'catatan'         => 'nullable|string',
                'dokumen_sertifikat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'bebas_babi'      => 'nullable|boolean',
                'daging_halal'    => 'nullable|boolean',
                'bumbu_bebas_alkohol' => 'nullable|boolean',
                'kemasan_halal'   => 'nullable|boolean',
                'peralatan_tidak_najis' => 'nullable|boolean',
                'tidak_jual_alkohol' => 'nullable|boolean',
                'dapur_bersih'    => 'nullable|boolean',
                'karyawan_bersih' => 'nullable|boolean',
                'sop_kebersihan'  => 'nullable|boolean',
            ], [
                'nama_restoran.required' => 'Nama restoran wajib diisi.',
                'nama_restoran.max' => 'Nama restoran maksimal 150 karakter.',
                
                'id_pemilik.required' => 'Pemilik usaha wajib dipilih.',
                'id_pemilik.exists' => 'Pemilik yang dipilih tidak valid.',
                
                'id_kategori.required' => 'Kategori usaha wajib dipilih.',
                'id_kategori.exists' => 'Kategori yang dipilih tidak valid.',
                
                'alamat.required' => 'Alamat usaha wajib diisi.',
                
                'jam_operasional.required' => 'Jam operasional wajib diisi.',
                
                'kota.max' => 'Kota maksimal 100 karakter.',
                'provinsi.max' => 'Provinsi maksimal 100 karakter.',
                'kecamatan_kelurahan.max' => 'Kecamatan/Kelurahan maksimal 100 karakter.',
                'kode_pos.max' => 'Kode pos maksimal 10 karakter.',
                'no_telepon.max' => 'Nomor telepon maksimal 20 karakter.',
                
                'email_usaha.email' => 'Format email usaha tidak valid.',
                'email_usaha.max' => 'Email usaha maksimal 100 karakter.',
                
                'website_sosmed.max' => 'Website/Sosial media maksimal 200 karakter.',
                
                'kapasitas_tempat.integer' => 'Kapasitas tempat harus berupa angka.',
                'kapasitas_tempat.min' => 'Kapasitas tempat minimal 1 orang.',
                
                'harga_rata_rata_min.integer' => 'Harga minimum harus berupa angka.',
                'harga_rata_rata_min.min' => 'Harga minimum tidak boleh negatif.',
                'harga_rata_rata_max.integer' => 'Harga maksimum harus berupa angka.',
                'harga_rata_rata_max.min' => 'Harga maksimum tidak boleh negatif.',
                
                'latitude.numeric' => 'Format koordinat latitude tidak valid.',
                'longitude.numeric' => 'Format koordinat longitude tidak valid.',
                
                'foto_utama.image' => 'Foto utama harus berupa gambar.',
                'foto_utama.mimes' => 'Foto utama hanya boleh berformat JPG, JPEG, atau PNG.',
                'foto_utama.max' => 'Ukuran foto utama maksimal 2 MB.',
                
                'tipe_halal.in' => 'Tipe halal yang dipilih tidak valid.',
                
                'no_sertifikat.max' => 'Nomor sertifikat maksimal 100 karakter.',
                
                'lembaga_penerbit.max' => 'Lembaga penerbit maksimal 150 karakter.',
                
                'masa_berlaku.date' => 'Format tanggal masa berlaku tidak valid.',
                
                'status_verifikasi.in' => 'Status verifikasi yang dipilih tidak valid.',
                
                'dokumen_sertifikat.file' => 'Dokumen sertifikat tidak valid.',
                'dokumen_sertifikat.mimes' => 'Dokumen sertifikat hanya boleh berformat PDF, JPG, JPEG, atau PNG.',
                'dokumen_sertifikat.max' => 'Ukuran dokumen sertifikat maksimal 5 MB.',
                
                'bebas_babi.boolean' => 'Format data bebas babi tidak valid.',
                'daging_halal.boolean' => 'Format data daging halal tidak valid.',
                'bumbu_bebas_alkohol.boolean' => 'Format data bumbu bebas alkohol tidak valid.',
                'kemasan_halal.boolean' => 'Format data kemasan halal tidak valid.',
                'peralatan_tidak_najis.boolean' => 'Format data peralatan tidak najis tidak valid.',
                'tidak_jual_alkohol.boolean' => 'Format data tidak jual alkohol tidak valid.',
                'dapur_bersih.boolean' => 'Format data dapur bersih tidak valid.',
                'karyawan_bersih.boolean' => 'Format data karyawan bersih tidak valid.',
                'sop_kebersihan.boolean' => 'Format data SOP kebersihan tidak valid.',
            ]);

            $data = $request->except([
                '_token', 'foto_utama', 'dokumen_sertifikat',
                'has_sertifikat', 'status_verifikasi', 'tipe_halal',
                'no_sertifikat', 'lembaga_penerbit', 'masa_berlaku', 'catatan',
                'bebas_babi','daging_halal','bumbu_bebas_alkohol','kemasan_halal',
                'peralatan_tidak_najis','tidak_jual_alkohol',
                'dapur_bersih','karyawan_bersih','sop_kebersihan',
                '_nama_pemilik', '_no_ktp'
            ]);

            $data['status_halal'] = $request->tipe_halal ?? 'none';

            if ($request->hasFile('foto_utama')) {
                $data['foto_utama'] = $request->file('foto_utama')->store('restoran', 'public');
            }

            $restoran = Restoran::create($data);

            // Simpan verifikasi
            $vData = [
                'id_restoran'          => $restoran->id_restoran,
                'id_admin'             => auth()->id() ?? null,
                'no_sertifikat'        => $request->no_sertifikat,
                'lembaga_penerbit'     => $request->lembaga_penerbit,
                'masa_berlaku'         => $request->masa_berlaku ?: null,
                'status'               => $request->status_verifikasi ?? 'pending',
                'catatan'              => $request->catatan,
                'bebas_babi'           => $request->boolean('bebas_babi'),
                'daging_halal'         => $request->boolean('daging_halal'),
                'bumbu_bebas_alkohol'  => $request->boolean('bumbu_bebas_alkohol'),
                'kemasan_halal'        => $request->boolean('kemasan_halal'),
                'peralatan_tidak_najis'=> $request->boolean('peralatan_tidak_najis'),
                'tidak_jual_alkohol'   => $request->boolean('tidak_jual_alkohol'),
                'dapur_bersih'         => $request->boolean('dapur_bersih'),
                'karyawan_bersih'      => $request->boolean('karyawan_bersih'),
                'sop_kebersihan'       => $request->boolean('sop_kebersihan'),
            ];

            if ($request->hasFile('dokumen_sertifikat')) {
                $vData['dokumen_sertifikat'] = $request->file('dokumen_sertifikat')
                                                        ->store('sertifikat', 'public');
            }

            VerifikasiHalal::create($vData);

            return response()->json(['success' => true, 'message' => 'Usaha berhasil ditambahkan']);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => collect($e->errors())->flatten()->first()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambahkan usaha. Silakan coba lagi.'
            ], 500);
        }
    }

    // ─── GET DATA EDIT (JSON) ───────────────────────────────
    public function editData($id)
    {
        try {
            $restoran = Restoran::with(['verifikasiHalal', 'kategori.subKategori'])->findOrFail($id);

            return response()->json([
                'restoran'   => $restoran,
                'verifikasi' => $restoran->verifikasiHalal,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan.'
            ], 404);
        }
    }

    // ─── UPDATE ─────────────────────────────────────────────
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'nama_restoran'   => 'required|string|max:150',
                'id_pemilik'      => 'required|exists:users,id',
                'id_kategori'     => 'required|exists:kategori,id_kategori',
                'alamat'          => 'required|string',
                'jam_operasional' => 'required|string',
                'kota'            => 'nullable|string|max:100',
                'provinsi'        => 'nullable|string|max:100',
                'kecamatan_kelurahan' => 'nullable|string|max:100',
                'kode_pos'        => 'nullable|string|max:10',
                'no_telepon'      => 'nullable|string|max:20',
                'email_usaha'     => 'nullable|email|max:100',
                'website_sosmed'  => 'nullable|string|max:200',
                'kapasitas_tempat' => 'nullable|integer|min:1',
                'harga_rata_rata_min' => 'nullable|integer|min:0',
                'harga_rata_rata_max' => 'nullable|integer|min:0',
                'latitude'        => 'nullable|numeric',
                'longitude'       => 'nullable|numeric',
                'foto_utama'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'tipe_halal'      => 'nullable|in:certified,self_claimed,none',
                'no_sertifikat'   => 'nullable|string|max:100',
                'lembaga_penerbit' => 'nullable|string|max:150',
                'masa_berlaku'    => 'nullable|date',
                'status_verifikasi' => 'nullable|in:pending,terverifikasi,ditolak',
                'catatan'         => 'nullable|string',
                'dokumen_sertifikat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'bebas_babi'      => 'nullable|boolean',
                'daging_halal'    => 'nullable|boolean',
                'bumbu_bebas_alkohol' => 'nullable|boolean',
                'kemasan_halal'   => 'nullable|boolean',
                'peralatan_tidak_najis' => 'nullable|boolean',
                'tidak_jual_alkohol' => 'nullable|boolean',
                'dapur_bersih'    => 'nullable|boolean',
                'karyawan_bersih' => 'nullable|boolean',
                'sop_kebersihan'  => 'nullable|boolean',
            ], [
                'nama_restoran.required' => 'Nama restoran wajib diisi.',
                'nama_restoran.max' => 'Nama restoran maksimal 150 karakter.',
                
                'id_pemilik.required' => 'Pemilik usaha wajib dipilih.',
                'id_pemilik.exists' => 'Pemilik yang dipilih tidak valid.',
                
                'id_kategori.required' => 'Kategori usaha wajib dipilih.',
                'id_kategori.exists' => 'Kategori yang dipilih tidak valid.',
                
                'alamat.required' => 'Alamat usaha wajib diisi.',
                
                'jam_operasional.required' => 'Jam operasional wajib diisi.',
                
                'kota.max' => 'Kota maksimal 100 karakter.',
                'provinsi.max' => 'Provinsi maksimal 100 karakter.',
                'kecamatan_kelurahan.max' => 'Kecamatan/Kelurahan maksimal 100 karakter.',
                'kode_pos.max' => 'Kode pos maksimal 10 karakter.',
                'no_telepon.max' => 'Nomor telepon maksimal 20 karakter.',
                
                'email_usaha.email' => 'Format email usaha tidak valid.',
                'email_usaha.max' => 'Email usaha maksimal 100 karakter.',
                
                'website_sosmed.max' => 'Website/Sosial media maksimal 200 karakter.',
                
                'kapasitas_tempat.integer' => 'Kapasitas tempat harus berupa angka.',
                'kapasitas_tempat.min' => 'Kapasitas tempat minimal 1 orang.',
                
                'harga_rata_rata_min.integer' => 'Harga minimum harus berupa angka.',
                'harga_rata_rata_min.min' => 'Harga minimum tidak boleh negatif.',
                'harga_rata_rata_max.integer' => 'Harga maksimum harus berupa angka.',
                'harga_rata_rata_max.min' => 'Harga maksimum tidak boleh negatif.',
                
                'latitude.numeric' => 'Format koordinat latitude tidak valid.',
                'longitude.numeric' => 'Format koordinat longitude tidak valid.',
                
                'foto_utama.image' => 'Foto utama harus berupa gambar.',
                'foto_utama.mimes' => 'Foto utama hanya boleh berformat JPG, JPEG, atau PNG.',
                'foto_utama.max' => 'Ukuran foto utama maksimal 2 MB.',
                
                'tipe_halal.in' => 'Tipe halal yang dipilih tidak valid.',
                
                'no_sertifikat.max' => 'Nomor sertifikat maksimal 100 karakter.',
                
                'lembaga_penerbit.max' => 'Lembaga penerbit maksimal 150 karakter.',
                
                'masa_berlaku.date' => 'Format tanggal masa berlaku tidak valid.',
                
                'status_verifikasi.in' => 'Status verifikasi yang dipilih tidak valid.',
                
                'dokumen_sertifikat.file' => 'Dokumen sertifikat tidak valid.',
                'dokumen_sertifikat.mimes' => 'Dokumen sertifikat hanya boleh berformat PDF, JPG, JPEG, atau PNG.',
                'dokumen_sertifikat.max' => 'Ukuran dokumen sertifikat maksimal 5 MB.',
                
                'bebas_babi.boolean' => 'Format data bebas babi tidak valid.',
                'daging_halal.boolean' => 'Format data daging halal tidak valid.',
                'bumbu_bebas_alkohol.boolean' => 'Format data bumbu bebas alkohol tidak valid.',
                'kemasan_halal.boolean' => 'Format data kemasan halal tidak valid.',
                'peralatan_tidak_najis.boolean' => 'Format data peralatan tidak najis tidak valid.',
                'tidak_jual_alkohol.boolean' => 'Format data tidak jual alkohol tidak valid.',
                'dapur_bersih.boolean' => 'Format data dapur bersih tidak valid.',
                'karyawan_bersih.boolean' => 'Format data karyawan bersih tidak valid.',
                'sop_kebersihan.boolean' => 'Format data SOP kebersihan tidak valid.',
            ]);

            $restoran = Restoran::findOrFail($id);

            $data = $request->except([
                '_token', '_method', 'foto_utama', 'dokumen_sertifikat',
                'status_verifikasi', 'tipe_halal', 'catatan',
                'no_sertifikat', 'lembaga_penerbit', 'masa_berlaku',
                'bebas_babi','daging_halal','bumbu_bebas_alkohol','kemasan_halal',
                'peralatan_tidak_najis','tidak_jual_alkohol',
                'dapur_bersih','karyawan_bersih','sop_kebersihan'
            ]);

            if ($request->filled('tipe_halal')) {
                $data['status_halal'] = $request->tipe_halal;
            }

            if ($request->hasFile('foto_utama')) {
                if ($restoran->foto_utama) Storage::disk('public')->delete($restoran->foto_utama);
                $data['foto_utama'] = $request->file('foto_utama')->store('restoran', 'public');
            }

            $restoran->update($data);

            // Update / buat verifikasi
            $verif = VerifikasiHalal::where('id_restoran', $id)->latest('id_verifikasi')->first()
                     ?? new VerifikasiHalal(['id_restoran' => $id]);

            $vData = [
                'id_restoran'          => $id,
                'no_sertifikat'        => $request->no_sertifikat,
                'lembaga_penerbit'     => $request->lembaga_penerbit,
                'masa_berlaku'         => $request->masa_berlaku ?: null,
                'catatan'              => $request->catatan,
                'bebas_babi'           => $request->boolean('bebas_babi'),
                'daging_halal'         => $request->boolean('daging_halal'),
                'bumbu_bebas_alkohol'  => $request->boolean('bumbu_bebas_alkohol'),
                'kemasan_halal'        => $request->boolean('kemasan_halal'),
                'peralatan_tidak_najis'=> $request->boolean('peralatan_tidak_najis'),
                'tidak_jual_alkohol'   => $request->boolean('tidak_jual_alkohol'),
                'dapur_bersih'         => $request->boolean('dapur_bersih'),
                'karyawan_bersih'      => $request->boolean('karyawan_bersih'),
                'sop_kebersihan'       => $request->boolean('sop_kebersihan'),
            ];

            if ($request->filled('status_verifikasi')) {
                $vData['status']   = $request->status_verifikasi;
                $vData['id_admin'] = auth()->id() ?? null;
                if ($request->status_verifikasi === 'terverifikasi') {
                    $vData['tanggal_verifikasi'] = now()->toDateString();
                }
            }

            if ($request->hasFile('dokumen_sertifikat')) {
                if ($verif->dokumen_sertifikat) Storage::disk('public')->delete($verif->dokumen_sertifikat);
                $vData['dokumen_sertifikat'] = $request->file('dokumen_sertifikat')
                                                        ->store('sertifikat', 'public');
            }

            $verif->fill($vData)->save();

            return response()->json(['success' => true, 'message' => 'Usaha berhasil diperbarui']);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => collect($e->errors())->flatten()->first()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui usaha. Silakan coba lagi.'
            ], 500);
        }
    }

    // ─── DESTROY ────────────────────────────────────────────
    public function destroy($id)
    {
        try {
            $restoran = Restoran::findOrFail($id);
            
            // Hapus foto restoran
            if ($restoran->foto_utama) {
                Storage::disk('public')->delete($restoran->foto_utama);
            }
            
            // Hapus verifikasi halal dan dokumennya
            $verifikasi = VerifikasiHalal::where('id_restoran', $id)->first();
            if ($verifikasi) {
                if ($verifikasi->dokumen_sertifikat) {
                    Storage::disk('public')->delete($verifikasi->dokumen_sertifikat);
                }
                $verifikasi->delete();
            }
            
            // Hapus semua menu dan fotonya
            $menus = Menu::where('id_restoran', $id)->get();
            foreach ($menus as $menu) {
                if ($menu->foto_menu) {
                    Storage::disk('public')->delete($menu->foto_menu);
                }
                $menu->delete();
            }
            
            $restoran->delete();

            return response()->json([
                'success' => true,
                'message' => 'Usaha berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus usaha. Silakan coba lagi.'
            ], 500);
        }
    }

    // ─── EXPORT CSV ─────────────────────────────────────────
    public function exportCsv()
    {
        try {
            $rows = Restoran::with(['kategori', 'verifikasiHalal'])->get();

            $csv = "No,Nama Usaha,Kategori,Status Halal,Kota,Status Verifikasi,Sertifikat Exp\n";
            foreach ($rows as $i => $r) {
                $v    = $r->verifikasiHalal;
                $csv .= implode(',', [
                    $i + 1,
                    '"' . str_replace('"', '""', $r->nama_restoran) . '"',
                    $r->kategori?->nama_kategori ?? '-',
                    $r->status_halal_label,
                    $r->kota ?? '-',
                    $v?->status ?? '-',
                    $v?->masa_berlaku?->format('d/m/Y') ?? '-',
                ]) . "\n";
            }

            return response($csv, 200, [
                'Content-Type'        => 'text/csv',
                'Content-Disposition' => 'attachment; filename="usaha_halal_' . date('Ymd') . '.csv"',
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengekspor data. Silakan coba lagi.');
        }
    }

    // ─── MENU CRUD ───────────────────────────────────────────
    public function storeMenu(Request $request, $restoranId)
    {
        try {
            $request->validate([
                'nama_menu' => 'required|string|max:150',
                'harga'     => 'required|numeric|min:0',
                'deskripsi' => 'nullable|string|max:255',
                'id_kategori_menu' => 'nullable|exists:kategori_menu,id_kategori_menu',
                'tersedia'  => 'nullable|boolean',
                'foto_menu' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ], [
                'nama_menu.required' => 'Nama menu wajib diisi.',
                'nama_menu.max' => 'Nama menu maksimal 150 karakter.',
                'harga.required' => 'Harga menu wajib diisi.',
                'harga.numeric' => 'Harga harus berupa angka.',
                'harga.min' => 'Harga tidak boleh negatif.',
                'deskripsi.max' => 'Deskripsi menu maksimal 255 karakter.',
                'id_kategori_menu.exists' => 'Kategori menu yang dipilih tidak valid.',
                'tersedia.boolean' => 'Status ketersediaan tidak valid.',
                'foto_menu.image' => 'Foto menu harus berupa gambar.',
                'foto_menu.mimes' => 'Foto menu hanya boleh berformat JPG, JPEG, atau PNG.',
                'foto_menu.max' => 'Ukuran foto menu maksimal 2 MB.',
            ]);

            // Cek apakah restoran ada
            $restoran = Restoran::find($restoranId);
            if (!$restoran) {
                return response()->json([
                    'success' => false,
                    'message' => 'Restoran tidak ditemukan'
                ], 404);
            }

            $data = $request->only(['nama_menu', 'deskripsi', 'harga', 'id_kategori_menu', 'tersedia']);
            $data['id_restoran'] = $restoranId;
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

    public function updateMenu(Request $request, $restoranId, $menuId)
    {
        try {
            $request->validate([
                'nama_menu' => 'required|string|max:150',
                'harga'     => 'required|numeric|min:0',
                'deskripsi' => 'nullable|string|max:255',
                'id_kategori_menu' => 'nullable|exists:kategori_menu,id_kategori_menu',
                'tersedia'  => 'nullable|boolean',
                'foto_menu' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ], [
                'nama_menu.required' => 'Nama menu wajib diisi.',
                'nama_menu.max' => 'Nama menu maksimal 150 karakter.',
                'harga.required' => 'Harga menu wajib diisi.',
                'harga.numeric' => 'Harga harus berupa angka.',
                'harga.min' => 'Harga tidak boleh negatif.',
                'deskripsi.max' => 'Deskripsi menu maksimal 255 karakter.',
                'id_kategori_menu.exists' => 'Kategori menu yang dipilih tidak valid.',
                'tersedia.boolean' => 'Status ketersediaan tidak valid.',
                'foto_menu.image' => 'Foto menu harus berupa gambar.',
                'foto_menu.mimes' => 'Foto menu hanya boleh berformat JPG, JPEG, atau PNG.',
                'foto_menu.max' => 'Ukuran foto menu maksimal 2 MB.',
            ]);

            $menu = Menu::where('id_restoran', $restoranId)->findOrFail($menuId);
            $data = $request->only(['nama_menu', 'deskripsi', 'harga', 'id_kategori_menu', 'tersedia']);

            if ($request->hasFile('foto_menu')) {
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

    public function destroyMenu($restoranId, $menuId)
    {
        try {
            $menu = Menu::where('id_restoran', $restoranId)->findOrFail($menuId);
            
            if ($menu->foto_menu) {
                Storage::disk('public')->delete($menu->foto_menu);
            }
            
            $menu->delete();

            return response()->json([
                'success' => true,
                'message' => 'Menu berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus menu. Silakan coba lagi.'
            ], 500);
        }
    }
}