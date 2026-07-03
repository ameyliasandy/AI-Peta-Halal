<?php

namespace App\Http\Controllers;

use App\Models\Restoran;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth; // ⬅️ tambahkan ini

class GuestController extends Controller
{
    public function index(Request $request)
    {
        // ─── Kalau sudah login, jangan tampilkan homepage guest ───
        // Ini yang bikin "nabrak": pencari yg login masih bisa lihat halaman guest.
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'admin')         return redirect('/admin/index');
            if ($user->role === 'pemilik_usaha') return redirect('/pemilik/dashboard');
            if ($user->role === 'pencari')       return redirect()->route('dashboard');
        }

        // ─── Guest SELALU ditanya onboarding kalau belum isi di sesi ini ───
        $showOnboarding = !session()->has('guest_preferensi');

        // GPS
        if ($request->has('lat') && $request->has('lng')) {
            session([
                'user_lat' => (float) $request->lat,
                'user_lng' => (float) $request->lng
            ]);
        }

        $userLat = (float) session('user_lat', 0);
        $userLng = (float) session('user_lng', 0);
        $hasLokasi = ($userLat != 0.0 || $userLng != 0.0);

        // Populer
        $populer = Menu::with('restoran')
            ->where('tersedia', 1)
            ->inRandomOrder()
            ->take(8)
            ->get();

        $populer->transform(function($menu) use ($userLat, $userLng) {
            $menu->jarak_km = $this->hitungJarak(
                $userLat,
                $userLng,
                $menu->restoran->latitude ?? 0,
                $menu->restoran->longitude ?? 0
            );
            return $menu;
        });

        $terdekat = $this->getTerdekatTopsis($userLat, $userLng);

        $totalResto = Restoran::count();
        $totalCertified = Restoran::where('status_halal', 'certified')->count();

        $filter = $request->get('filter', 'Semua');
        $search = $request->input('search') ?? '';

        $restorans = null;
        $menuResults = null;

        if ($search || $filter !== 'Semua') {
            [$restorans, $menuResults] = $this->getSearchResults($search, $filter, $request);
        }

        return view('guest', compact(
            'populer',
            'terdekat',
            'totalResto',
            'totalCertified',
            'hasLokasi',
            'filter',
            'search',
            'restorans',
            'menuResults',
            'showOnboarding'
        ));
    }

    // ══════════════════════════════════════════════════════════
    //  DETAIL RESTO — dispatch sesuai role (guest vs pencari)
    // ══════════════════════════════════════════════════════════

    public function showRestoran($id)
    {
        $restoran = Restoran::with(['menu', 'ulasan.user', 'verifikasiHalal'])
            ->withAvg('ulasan', 'rating')
            ->withCount('ulasan')
            ->findOrFail($id);

        $userLat = (float) session('user_lat', 0);
        $userLng = (float) session('user_lng', 0);

        $restoran->jarak_km = $this->hitungJarak(
            $userLat,
            $userLng,
            (float) ($restoran->latitude ?? 0),
            (float) ($restoran->longitude ?? 0)
        );

        // ⬇️ INI KUNCI FIX-NYA:
        // Route 'restoran.show' dipakai bareng-bareng oleh dashboard (pencari)
        // dan guest.blade. Daripada bikin route terpisah (yang berisiko lupa
        // diganti di salah satu view), kita cek role di sini dan render
        // view yang sesuai.
        if (Auth::check() && Auth::user()->role === 'pencari') {
            return view('pencari.detail_toko', compact('restoran'));
        }

        // Guest (atau role lain yang nyasar ke sini) → view read-only
        return view('guest_detail', compact('restoran'));
    }

    // ══════════════════════════════════════════════════════════
    //  TERDEKAT — Haversine + TOPSIS
    // ══════════════════════════════════════════════════════════

    private function getTerdekatTopsis(float $userLat, float $userLng)
    {
        $restorans = Restoran::withCount('menus as jumlah_menu')
            ->withAvg('ulasan', 'rating')
            ->take(20)
            ->get();

        if ($restorans->isEmpty()) return collect();

        $restorans->each(function ($r) use ($userLat, $userLng) {
            $r->jarak_km = $this->hitungJarak(
                $userLat, $userLng,
                (float) ($r->latitude ?? 0),
                (float) ($r->longitude ?? 0)
            );
        });

        $payload = $restorans->map(fn($r) => [
            'id_restoran'   => $r->id_restoran,
            'nama_restoran' => $r->nama_restoran,
            'kota'          => $r->kota ?? '',
            'rating' => round((float) ($r->ulasan_avg_rating ?? 0), 1),
            'status_halal'  => $r->status_halal ?? 'none',
            'jarak_km'      => $r->jarak_km,
            'jumlah_menu'   => (int) ($r->jumlah_menu ?? 0),
        ])->toArray();

        $ranked = $this->callTopsis('/topsis/terdekat', ['restorans' => $payload]);

        if (empty($ranked)) return $restorans->take(5);

        $orderedIds = collect($ranked)->take(5)->pluck('id_restoran');
        return $restorans
            ->whereIn('id_restoran', $orderedIds->all())
            ->sortBy(fn($r) => $orderedIds->search($r->id_restoran))
            ->values();
    }

    // ══════════════════════════════════════════════════════════
    //  PENCARIAN CAMPURAN (GUEST VERSION)
    // ══════════════════════════════════════════════════════════

    private function getSearchResults(?string $search, string $filter, Request $request): array
    {
        $search = $search ?? '';
        $qResto = Restoran::select(
            'id_restoran',
            'nama_restoran',
            'alamat',
            'kota',
            'status_halal',
            'rating',
            'jumlah_ulasan',
            'harga_rata_rata_min',
            'harga_rata_rata_max',
            'foto_utama',
            'latitude',
            'longitude'
        )
        ->with('menus')
        ->withAvg('ulasan', 'rating');
        $qMenu = Menu::with('restoran')->where('tersedia', 1);

        if ($search) {
            $qResto->where(function ($q) use ($search) {
                $q->where('nama_restoran', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%")
                  ->orWhere('kota', 'like', "%{$search}%");
            });
            $qMenu->where(function ($q) use ($search) {
                $q->where('nama_menu', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%")
                  ->orWhereHas('restoran', fn($q2) =>
                      $q2->where('nama_restoran', 'like', "%{$search}%")
                  );
            });
        }

        switch ($filter) {
            case 'Pedas':
                $qResto->whereHas('menus', fn($q) =>
                    $q->where('nama_menu', 'like', '%pedas%')
                      ->orWhere('nama_menu', 'like', '%sambal%')
                      ->orWhere('nama_menu', 'like', '%geprek%')
                      ->orWhere('deskripsi', 'like', '%pedas%')
                );
                $qMenu->where(fn($q) =>
                    $q->where('nama_menu', 'like', '%pedas%')
                      ->orWhere('nama_menu', 'like', '%sambal%')
                      ->orWhere('nama_menu', 'like', '%geprek%')
                      ->orWhere('deskripsi', 'like', '%pedas%')
                );
                break;
            case 'Murah':
                $qResto->where(fn($q) =>
                    $q->where('harga_rata_rata_min', '<=', 20000)->orWhereNull('harga_rata_rata_min')
                );
                $qMenu->where('harga', '<=', 15000);
                break;
            case 'Favorit':
                // Guest tidak punya favorit
                break;
        }

        $restorans = $qResto
            ->paginate(8, ['*'], 'resto_page')
            ->appends($request->query());

        $menus = $qMenu
            ->paginate(8, ['*'], 'menu_page')
            ->appends($request->query());

        // ambil lokasi user
        $userLat = session('user_lat', 0);
        $userLng = session('user_lng', 0);

        // hitung jarak restoran
        $restorans->getCollection()->transform(function($restoran) use ($userLat,$userLng){
            $restoran->jarak_km = $this->hitungJarak(
                $userLat,
                $userLng,
                $restoran->latitude ?? 0,
                $restoran->longitude ?? 0
            );
            return $restoran;
        });

        // hitung jarak menu langsung dari restorannya
        $menus->getCollection()->transform(function($menu) use ($userLat, $userLng){
            $menu->jarak_km = $this->hitungJarak(
                $userLat,
                $userLng,
                $menu->restoran->latitude ?? 0,
                $menu->restoran->longitude ?? 0
            );
            return $menu;
        });

        return [
            $restorans,
            $menus
        ];
    }

    // ══════════════════════════════════════════════════════════
    //  HELPERS
    // ══════════════════════════════════════════════════════════

    private function callTopsis(string $endpoint, array $body): array
    {
        try {
            $url = config('services.topsis.url', 'http://127.0.0.1:5001');
            $response = Http::timeout(3)->post($url . $endpoint, $body);
            return $response->successful() ? ($response->json()['ranked'] ?? []) : [];
        } catch (\Exception $e) {
            Log::warning("TOPSIS API ({$endpoint}): " . $e->getMessage());
            return [];
        }
    }

    private function hitungJarak(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        if (($lat1 === 0.0 && $lng1 === 0.0) || ($lat2 === 0.0 && $lng2 === 0.0)) return 999.0;
        $R = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;
        return round($R * 2 * atan2(sqrt($a), sqrt(1 - $a)), 1);
    }
}