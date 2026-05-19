<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
            'no_hp'    => 'nullable|string|max:15',
            'role'     => 'required|in:pencari,pemilik_usaha'
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'no_hp'    => $request->no_hp,
            'role'     => $request->role
        ]);

        // 🔥 simpan preferensi guest jika ada
        if (session()->has('guest_preferensi')) {

            foreach (session('guest_preferensi') as $item) {

                DB::table('preferensi_users')->insert([
                    'user_id'    => $user->id,
                    'kategori'   => $item,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            session()->forget('guest_preferensi');
        }

        return redirect('/login')
            ->with('success', 'Registrasi berhasil, silakan login');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {

            $user = Auth::user();

            // 🔥 ADMIN
            if ($user->role === 'admin') {
                return redirect('/admin/dashboard');
            }

            // 🔥 OWNER
            if ($user->role === 'pemilik_usaha') {
                return redirect('/owner/dashboard');
            }

            // 🔥 CEK ONBOARDING USER
            $sudahOnboarding = DB::table('preferensi_users')
                ->where('user_id', $user->id)
                ->exists();

            // BELUM onboarding
            if (!$sudahOnboarding) {
                return redirect('/onboarding');
            }

            // SUDAH onboarding
            return redirect('/');
        }

        return back()->with('error', 'Login gagal');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}