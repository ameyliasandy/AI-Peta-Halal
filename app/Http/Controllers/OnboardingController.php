<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OnboardingController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $sudahOnboarding = DB::table('preferensi_users')
                ->where('user_id', Auth::id())
                ->exists();

            // Kalau sudah pernah isi, tidak perlu isi lagi — balik ke dashboard
            if ($sudahOnboarding) return redirect()->route('dashboard');
        } elseif (session()->has('guest_preferensi')) {
            return redirect('/');
        }

        return view('onboarding.index');
    }

    public function store(Request $request)
    {
        // Wajib pilih tepat 3 kategori — tidak ada lagi opsi "skip"
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
            : redirect()->route('dashboard');
    }
}