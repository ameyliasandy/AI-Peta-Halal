<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OnboardingController;

//HALAMAN UTAMA (GUEST)
Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    return view('guest');
});

//AUTH
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout']);

//ONBOARDING
// Guest (tanpa login)
Route::get('/onboarding', [OnboardingController::class, 'index']);
Route::post('/onboarding', [OnboardingController::class, 'store']);

//DASHBOARD
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');

Route::get('/owner/dashboard', function () {
    return "Dashboard Pemilik Usaha";
})->middleware('auth');

Route::get('/admin/dashboard', function () {
    return "Dashboard Admin";
})->middleware('auth');