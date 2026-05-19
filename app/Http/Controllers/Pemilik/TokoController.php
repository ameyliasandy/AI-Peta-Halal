<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\Restoran;
use App\Models\Menu;
use App\Models\Kategori;
use App\Models\KategoriMenu;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class TokoController extends Controller
{
    private function pemilik(): User
    {
        return User::where('role', 'pemilik_usaha')
            ->findOrFail(session('pemilik')->id);
    }

    private function restoran(): ?Restoran
    {
        return Restoran::with(['kategori', 'subKategori', 'verifikasiHalal', 'menu'])
                       ->where('id_pemilik', session('pemilik')->id)
                       ->first();
    }

    private function authCheck()
    {
        if (!session('pemilik')) abort(redirect('/pemilik/login'));
    }

    // ─── HALAMAN UTAMA TOKO ─────────────────────────────────
    public function index()
    {
        $this->authCheck();

        $pemilik  = $this->pemilik();
        $restoran = $this->restoran();
        $kategoriMenu = KategoriMenu::all();

        return view('pemilik.toko.index', compact('pemilik', 'restoran', 'kategoriMenu'));
    }

    // ─── GET PROFIL (JSON untuk modal edit) ─────────────────
    public function getProfil()
    {
        $this->authCheck();

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
        $this->authCheck();

        $pemilik  = $this->pemilik();
        $restoran = $this->restoran();

        // Update pemilik
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

        // Update restoran
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
        $this->authCheck();

        $restoran = $this->restoran();
        if (!$restoran) return response()->json(['success' => false]);

        $restoran->update(['status_buka' => !$restoran->status_buka]);
        return response()->json(['success' => true, 'status_buka' => $restoran->status_buka]);
    }

    // ─── MENU CRUD ───────────────────────────────────────────
    public function storeMenu(Request $request)
    {
        $this->authCheck();

        $request->validate([
            'nama_menu' => 'required|string|max:150',
            'harga'     => 'required|integer|min:0',
        ]);

        $restoran = $this->restoran();
        if (!$restoran) return response()->json(['success' => false, 'message' => 'Toko tidak ditemukan']);

        $data = $request->only(['nama_menu', 'deskripsi', 'harga', 'id_kategori']);
        $data['id_restoran'] = $restoran->id_restoran;
        $data['tersedia'] = 1;

        if ($request->hasFile('foto_menu')) {
            $data['foto_menu'] = $request->file('foto_menu')->store('menu', 'public');
        }

        $menu = Menu::create($data);
        return response()->json(['success' => true, 'menu' => $menu]);
    }

    public function updateMenu(Request $request, $id)
    {
        $this->authCheck();

        $restoran = $this->restoran();
        $menu = Menu::where('id_restoran', $restoran->id_restoran)->findOrFail($id);

        $data = $request->only(['nama_menu', 'deskripsi', 'harga', 'id_kategori', 'tersedia']);

        if ($request->hasFile('foto_menu')) {
            if ($menu->foto_menu) Storage::disk('public')->delete($menu->foto_menu);
            $data['foto_menu'] = $request->file('foto_menu')->store('menu', 'public');
        }

        $menu->update($data);
        return response()->json(['success' => true, 'menu' => $menu->fresh()]);
    }

    public function destroyMenu($id)
    {
        $this->authCheck();

        $restoran = $this->restoran();
        $menu = Menu::where('id_restoran', $restoran->id_restoran)->findOrFail($id);

        if ($menu->foto_menu) Storage::disk('public')->delete($menu->foto_menu);
        $menu->delete();

        return response()->json(['success' => true]);
    }

    public function toggleMenu($id)
    {
        $this->authCheck();

        $restoran = $this->restoran();
        $menu = Menu::where('id_restoran', $restoran->id_restoran)->findOrFail($id);
        $menu->update(['tersedia' => !$menu->tersedia]);

        return response()->json(['success' => true, 'tersedia' => $menu->tersedia]);
    }
}