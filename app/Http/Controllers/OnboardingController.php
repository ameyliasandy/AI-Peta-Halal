<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OnboardingController extends Controller
{
    // Dipakai kalau ada yang buka /onboarding langsung (fallback, jarang dipakai lagi
    // karena sekarang tampil sebagai modal di guest/dashboard)
    public function index()
    {
        if (Auth::check()) {
            $sudahOnboarding = DB::table('preferensi_users')
                ->where('user_id', Auth::id())
                ->exists();

            if ($sudahOnboarding) return redirect('/');
        } elseif (session()->has('guest_preferensi')) {
            return redirect('/');
        }

        return view('onboarding.index');
    }

    public function store(Request $request)
    {
        // ─── SKIP ───────────────────────────────────────────
        if ($request->boolean('skip')) {
            if (Auth::check()) {
                $user = Auth::user();

                DB::table('preferensi_users')->where('user_id', $user->id)->delete();

                // simpan penanda "sudah ditanya tapi skip" biar tidak muncul
                // lagi tiap kali dashboard di-load, sesuai kesepakatan
                // "pencari cuma ditanya sekali".
                DB::table('preferensi_users')->insert([
                    'user_id'    => $user->id,
                    'kategori'   => 'skip',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                // guest: tandai sesi ini sudah "ditanya" tapi kosong
                session(['guest_preferensi' => []]);
            }

            return $request->wantsJson()
                ? response()->json(['status' => 'skipped'])
                : redirect('/');
        }

        // ─── SUBMIT NORMAL ──────────────────────────────────
        $request->validate([
            'kategori' => 'required|array|size:3'
        ]);

        if (Auth::check()) {
            $user = Auth::user();

            DB::table('preferensi_users')->where('user_id', $user->id)->delete();

            foreach ($request->kategori as $item) {
                DB::table('preferensi_users')->insert([
                    'user_id'    => $user->id,
                    'kategori'   => $item,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } else {
            session(['guest_preferensi' => $request->kategori]);
        }

        return $request->wantsJson()
            ? response()->json(['status' => 'saved'])
            : redirect('/');
    }
}