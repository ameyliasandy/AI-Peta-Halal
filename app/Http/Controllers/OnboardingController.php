<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OnboardingController extends Controller
{
    public function index()
    {
        // kalau sudah onboarding jangan tampil lagi
        if (Auth::check()) {

            $sudahOnboarding = DB::table('preferensi_users')
                ->where('user_id', Auth::id())
                ->exists();

            if ($sudahOnboarding) {
                return redirect('/');
            }
        }

        return view('onboarding.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required|array|size:3'
        ]);

        // USER LOGIN
        if (Auth::check()) {

            $user = Auth::user();

            // hapus lama
            DB::table('preferensi_users')
                ->where('user_id', $user->id)
                ->delete();

            // simpan baru
            foreach ($request->kategori as $item) {

                DB::table('preferensi_users')->insert([
                    'user_id'    => $user->id,
                    'kategori'   => $item,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // selesai onboarding
            return redirect('/');
        }

        // GUEST
        session([
            'guest_preferensi' => $request->kategori
        ]);

        return redirect('/');
    }
}