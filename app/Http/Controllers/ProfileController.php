<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Favorit;
use App\Models\PreferensiUser;
use App\Models\Restoran;

class ProfileController extends Controller
{
    // ───────────────────────────────────────
    // PROFIL
    // ───────────────────────────────────────

    public function index()
    {
        $user = Auth::user();
        $restoran = Restoran::where('id_pemilik', $user->id)->first();
        
        return view('profile.index', compact('user', 'restoran'));
    }

    public function updateProfil(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name'        => 'required|string|max:100',
            'email'       => 'required|email|unique:users,email,' . Auth::id(),
            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only('name', 'email');

        if ($request->hasFile('foto_profil')) {
            if ($user->foto_profil) {
                Storage::disk('public')->delete($user->foto_profil);
            }
            $data['foto_profil'] = $request->file('foto_profil')
                                           ->store('foto_profil', 'public');
        }

        $user->update($data);

        // Update no_telepon di tabel restoran jika ada
        if ($request->filled('no_telepon')) {
            $restoran = Restoran::where('id_pemilik', $user->id)->first();
            if ($restoran) {
                $restoran->update(['no_telepon' => $request->no_telepon]);
            }
        }

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required',
            'password_baru' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->withErrors(['password_lama' => 'Password lama salah.']);
        }

        $user->update(['password' => Hash::make($request->password_baru)]);

        return back()->with('success', 'Password berhasil diubah.');
    }

    // ───────────────────────────────────────
    // FAVORIT
    // ───────────────────────────────────────

    public function favorit()
    {
        $favorit = Favorit::with('restoran')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(12);

        return view('profile.favorit', compact('favorit'));
    }

    public function hapusFavorit($id)
    {
        Favorit::where('user_id', Auth::id())
               ->where('id_restoran', $id)
               ->delete();

        return back()->with('success', 'Dihapus dari favorit.');
    }

    // ───────────────────────────────────────
    // PREFERENSI
    // ───────────────────────────────────────

    public function preferensi()
    {
        $dipilih = PreferensiUser::where('user_id', Auth::id())
                    ->pluck('kategori')
                    ->toArray();

        return view('profile.preferensi', compact('dipilih'));
    }

    public function updatePreferensi(Request $request)
    {
        $preferensi = $request->input('preferensi', []);

        PreferensiUser::where('user_id', Auth::id())->delete();

        foreach ($preferensi as $kategori) {
            PreferensiUser::create([
                'user_id'  => Auth::id(),
                'kategori' => $kategori,
            ]);
        }

        return back()->with('success', 'Preferensi berhasil disimpan.');
    }

    // ───────────────────────────────────────
    // PENGATURAN
    // ───────────────────────────────────────

    public function pengaturan()
    {
        return view('profile.pengaturan', ['user' => Auth::user()]);
    }

    public function updatePengaturan(Request $request)
    {
        Auth::user()->update([
            'notif_promo'  => $request->boolean('notif_promo'),
            'notif_ulasan' => $request->boolean('notif_ulasan'),
        ]);

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }

    // ───────────────────────────────────────
    // HAPUS AKUN
    // ───────────────────────────────────────

    public function hapusAkun()
    {
        $user = Auth::user();
        Auth::logout();
        $user->delete();

        return redirect('/')->with('success', 'Akun berhasil dihapus.');
    }
}