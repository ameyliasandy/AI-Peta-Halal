<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\Admin\RestoranController;
use App\Http\Controllers\Pemilik\TokoController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ProfileController;

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

Route::get('/owner/dashboard', function () {
    return "Dashboard Pemilik Usaha";
})->middleware('auth');


// ADMIN
Route::get('/admin/index', [DashboardController::class, 'index'])
    ->name('admin.index');;
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
 
// PEMILIK
Route::prefix('pemilik')->name('pemilik.')->group(function () {
    Route::get('/toko',                         [TokoController::class, 'index'])->name('toko.index');
    Route::get('/toko/profil',                  [TokoController::class, 'getProfil'])->name('toko.profil');
    Route::post('/toko/profil',                 [TokoController::class, 'updateProfil'])->name('toko.profil.update');
    Route::post('/toko/toggle-buka',            [TokoController::class, 'toggleBuka'])->name('toko.toggle');
    Route::post('/toko/menu',                   [TokoController::class, 'storeMenu'])->name('menu.store');
    Route::post('/toko/menu/{id}',              [TokoController::class, 'updateMenu'])->name('menu.update');
    Route::delete('/toko/menu/{id}',            [TokoController::class, 'destroyMenu'])->name('menu.destroy');
    Route::post('/toko/menu/{id}/toggle',       [TokoController::class, 'toggleMenu'])->name('menu.toggle');
});

Route::middleware('auth')->group(function () {

    // Profil
    Route::get('/profile',           [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile',          [ProfileController::class, 'updateProfil'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile/hapus',  [ProfileController::class, 'hapusAkun'])->name('profile.hapus');

    // Favorit
    Route::get('/favorit',              [ProfileController::class, 'favorit'])->name('profile.favorit');
    Route::delete('/favorit/{id}',      [ProfileController::class, 'hapusFavorit'])->name('profile.favorit.hapus');

    // Preferensi
    Route::get('/preferensi',           [ProfileController::class, 'preferensi'])->name('profile.preferensi');
    Route::post('/preferensi',          [ProfileController::class, 'updatePreferensi'])->name('profile.preferensi.update');

    // Pengaturan
    Route::get('/pengaturan',           [ProfileController::class, 'pengaturan'])->name('profile.pengaturan');
    Route::post('/pengaturan',          [ProfileController::class, 'updatePengaturan'])->name('profile.pengaturan.update');

});