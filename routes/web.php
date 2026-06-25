<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\Admin\RestoranController;
use App\Http\Controllers\Pemilik\TokoController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Pemilik\PemilikController;
use App\Http\Controllers\Pemilik\DaftarUsahaController;
use App\Http\Controllers\Pencari\DashboardController as PencariDashboardController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\Pencari\RekomendasiController;
use App\Http\Controllers\RestoranPublicController;
use App\Http\Controllers\UlasanController;

//HALAMAN UTAMA (GUEST)
Route::get('/', function () {
    if (Auth::check()) {
        $role = Auth::user()->role;
        if ($role === 'admin') return redirect('/admin/index');
        if ($role === 'pemilik_usaha') return redirect('/pemilik/dashboard');
        return redirect('/dashboard');
    }
    return app(GuestController::class)->index();
});

//AUTH
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//ONBOARDING
Route::get('/onboarding', [OnboardingController::class, 'index']);
Route::post('/onboarding', [OnboardingController::class, 'store']);

//DASHBOARD
Route::get('/dashboard', [PencariDashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// ─── RESTORAN PUBLIK (PENCARI) ─────────────────────────────────────────────
Route::get('/restoran/{id}', [RestoranPublicController::class, 'show'])
    ->name('restoran.show');

// ─── REKOMENDASI ────────────────────────────────────────────────────────────
Route::get('/rekomendasi', [RekomendasiController::class, 'index'])
    ->middleware('auth')
    ->name('rekomendasi.index');

// ADMIN
Route::get('/admin/index', [DashboardController::class, 'index'])
    ->middleware('auth')->name('admin.index');
    
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/restoran',                                     [RestoranController::class, 'list'])->name('restoran.list');
    Route::get('/restoran/export-csv',                          [RestoranController::class, 'exportCsv'])->name('restoran.export');
    Route::post('/restoran',                                    [RestoranController::class, 'store'])->name('restoran.store');
    Route::get('/restoran/{id}',                                [RestoranController::class, 'show'])->name('restoran.show');
    Route::get('/restoran/{id}/data',                           [RestoranController::class, 'editData'])->name('restoran.data');
    Route::post('/restoran/{id}/update',                        [RestoranController::class, 'update'])->name('restoran.update');
    Route::delete('/restoran/{id}',                             [RestoranController::class, 'destroy'])->name('restoran.destroy');
    Route::post('/restoran/{restoranId}/menu', [RestoranController::class, 'storeMenu']);
    Route::put('/restoran/{restoranId}/menu/{menuId}', [RestoranController::class, 'updateMenu']);
    Route::delete('/restoran/{restoranId}/menu/{menuId}', [RestoranController::class, 'destroyMenu']);
});

// ─── PEMILIK ────────────────────────────────────────────────────────────────
Route::prefix('pemilik')
    ->middleware('auth')
    ->name('pemilik.')
    ->group(function () {

    Route::get('/dashboard', function () {
        return view('pemilik.dashboard');
    })->name('dashboard');

    // ── Toko (DaftarUsahaController) ──────────────────────────
    Route::get('/toko',             [DaftarUsahaController::class, 'index'])->name('toko.index');
    Route::get('/toko/create',      [DaftarUsahaController::class, 'create'])->name('toko.create');
    Route::post('/toko',            [DaftarUsahaController::class, 'store'])->name('toko.store');
    Route::get('/toko/{id}',        [DaftarUsahaController::class, 'show'])->name('toko.show');
    Route::get('/toko/{id}/edit',   [DaftarUsahaController::class, 'edit'])->name('toko.edit');
    Route::put('/toko/{id}',        [DaftarUsahaController::class, 'update'])->name('toko.update');
    Route::delete('/toko/{id}',     [DaftarUsahaController::class, 'destroy'])->name('toko.destroy');

    // ── Profil toko (TokoController) ─────────────────────────
    Route::get('/toko/profil', [TokoController::class, 'getProfil'])->name('toko.profil');
    Route::post('/toko/profil', [TokoController::class, 'updateProfil'])->name('toko.profil.update');

    // ── Status buka/tutup ────────────────────────────────────
    Route::post('/toko/toggle-buka', [TokoController::class, 'toggleBuka'])
        ->name('toko.toggle-buka');

    // ── Menu ─────────────────────────────────────────────────
    Route::post('/toko/menu', [TokoController::class, 'storeMenu'])
        ->name('toko.menu.store');

    Route::post('/toko/menu/{id}', [TokoController::class, 'updateMenu'])
        ->name('toko.menu.update');

    Route::delete('/toko/menu/{id}', [TokoController::class, 'destroyMenu'])
        ->name('toko.menu.destroy');

    Route::post('/toko/menu/{id}/toggle', [TokoController::class, 'toggleMenu'])
        ->name('toko.menu.toggle');
});

// ─── PROFILE USER ───────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Profil
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile', [ProfileController::class, 'updateProfil'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile/hapus', [ProfileController::class, 'hapusAkun'])->name('profile.hapus');

    // Favorit
    Route::get('/favorit', [ProfileController::class, 'favorit'])->name('profile.favorit');
    Route::delete('/favorit/{id}', [ProfileController::class, 'hapusFavorit'])->name('profile.favorit.hapus');

    // Preferensi
    Route::get('/preferensi', [ProfileController::class, 'preferensi'])->name('profile.preferensi');
    Route::post('/preferensi', [ProfileController::class, 'updatePreferensi'])->name('profile.preferensi.update');

    // Pengaturan
    Route::get('/pengaturan', [ProfileController::class, 'pengaturan'])->name('profile.pengaturan');
    Route::post('/pengaturan', [ProfileController::class, 'updatePengaturan'])->name('profile.pengaturan.update');

    // Ulasan
    Route::post('/ulasan', [UlasanController::class, 'store'])->name('ulasan.store');
});