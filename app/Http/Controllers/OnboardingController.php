<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OnboardingController extends Controller
{
    public function index()
    {
        return view('onboarding.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required|array|min:3'
        ]);

        if (Auth::check()) {
            // User sudah login → simpan ke DB
            $user = Auth::user();

            DB::table('preferensi_users')->where('user_id', $user->id)->delete();

            foreach ($request->kategori as $item) {
                DB::table('preferensi_users')->insert([
                    'user_id' => $user->id,
                    'kategori' => $item,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return redirect('/dashboard');

        } else {
            // Guest → simpan ke session
            session(['guest_preferensi' => $request->kategori]);
            return redirect('/');
        }
    }
}