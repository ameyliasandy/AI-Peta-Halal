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
        $request->validate([
            'nama_restoran'   => 'required|string|max:150',
            // ✅ FIX: table users, kolom id (sesuai User model)
            'id_pemilik'      => 'required|exists:users,id',
            'id_kategori'     => 'required|exists:kategori,id_kategori',
            'alamat'          => 'required|string',
            'jam_operasional' => 'required|string',
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
            // ✅ FIX: gunakan auth()->id() bukan session('admin')->id_admin
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
    }

    // ─── GET DATA EDIT (JSON) ───────────────────────────────
    public function editData($id)
    {
        $restoran = Restoran::with(['verifikasiHalal', 'kategori.subKategori'])->findOrFail($id);

        return response()->json([
            'restoran'   => $restoran,
            'verifikasi' => $restoran->verifikasiHalal,
        ]);
    }

    // ─── UPDATE ─────────────────────────────────────────────
    public function update(Request $request, $id)
    {
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
            // ✅ FIX: gunakan auth()->id() bukan session('admin')->id_admin
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
    }

    // ─── DESTROY ────────────────────────────────────────────
    public function destroy($id)
    {
        $restoran = Restoran::findOrFail($id);
        if ($restoran->foto_utama) Storage::disk('public')->delete($restoran->foto_utama);
        $restoran->delete();

        return response()->json(['success' => true]);
    }

    // ─── EXPORT CSV ─────────────────────────────────────────
    public function exportCsv()
    {
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
    }

    // ─── MENU CRUD ───────────────────────────────────────────
    public function storeMenu(Request $request, $restoranId)
    {
        $request->validate([
            'nama_menu' => 'required|string|max:150',
            'harga'     => 'required|numeric|min:0',
        ]);

        $data = $request->only(['nama_menu', 'deskripsi', 'harga', 'id_kategori', 'tersedia']);
        $data['id_restoran'] = $restoranId;

        if ($request->hasFile('foto_menu')) {
            $data['foto_menu'] = $request->file('foto_menu')->store('menu', 'public');
        }

        $menu = Menu::create($data);
        return response()->json(['success' => true, 'menu' => $menu]);
    }

    public function updateMenu(Request $request, $restoranId, $menuId)
    {
        $menu = Menu::where('id_restoran', $restoranId)->findOrFail($menuId);
        $data = $request->only(['nama_menu', 'deskripsi', 'harga', 'id_kategori', 'tersedia']);

        if ($request->hasFile('foto_menu')) {
            if ($menu->foto_menu) Storage::disk('public')->delete($menu->foto_menu);
            $data['foto_menu'] = $request->file('foto_menu')->store('menu', 'public');
        }

        $menu->update($data);
        return response()->json(['success' => true, 'menu' => $menu->fresh()]);
    }

    public function destroyMenu($restoranId, $menuId)
    {
        $menu = Menu::where('id_restoran', $restoranId)->findOrFail($menuId);
        if ($menu->foto_menu) Storage::disk('public')->delete($menu->foto_menu);
        $menu->delete();

        return response()->json(['success' => true]);
    }
}