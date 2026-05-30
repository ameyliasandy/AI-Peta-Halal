<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\Admin\RestoranController;
use App\Http\Controllers\Pemilik\TokoController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Pemilik\PemilikController;
use App\Http\Controllers\Pemilik\DaftarUsahaController;


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

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//ONBOARDING
// Guest (tanpa login)
Route::get('/onboarding', [OnboardingController::class, 'index']);
Route::post('/onboarding', [OnboardingController::class, 'store']);

//DASHBOARD
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');

// ADMIN
Route::get('/admin/index', [DashboardController::class, 'index'])
->middleware('auth')->name('admin.index');;
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

    // ── Toko (DaftarUsahaController — registrasi & list) ──────────────────
    Route::get('/toko',             [DaftarUsahaController::class, 'index'])  ->name('toko.index');
    Route::get('/toko/create',      [DaftarUsahaController::class, 'create']) ->name('toko.create');
    Route::post('/toko',            [DaftarUsahaController::class, 'store'])  ->name('toko.store');
    Route::get('/toko/{id}',        [DaftarUsahaController::class, 'show'])   ->name('toko.show');
    Route::get('/toko/{id}/edit',   [DaftarUsahaController::class, 'edit'])   ->name('toko.edit');
    Route::put('/toko/{id}',        [DaftarUsahaController::class, 'update']) ->name('toko.update');
    Route::delete('/toko/{id}',     [DaftarUsahaController::class, 'destroy'])->name('toko.destroy');

    // ── Toko aktif pemilik (TokoController) ───────────────────────────────
    Route::post('/toko/update',      [TokoController::class, 'updateProfil']) ->name('toko.update-profil');
    Route::post('/toko/toggle-buka', [TokoController::class, 'toggleBuka'])  ->name('toko.toggle-buka');

    // ── Menu (TokoController) ─────────────────────────────────────────────
    Route::post('/toko/menu',          [TokoController::class, 'storeMenu'])  ->name('toko.menu.store');
    Route::post('/toko/menu/{id}',     [TokoController::class, 'updateMenu']) ->name('toko.menu.update');
    Route::delete('/toko/menu/{id}',   [TokoController::class, 'destroyMenu'])->name('toko.menu.destroy');
    Route::post('/toko/menu/{id}/toggle', [TokoController::class, 'toggleMenu'])->name('toko.menu.toggle');

});