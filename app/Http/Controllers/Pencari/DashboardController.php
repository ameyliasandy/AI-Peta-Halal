<?php

namespace App\Http\Controllers\Pencari;

use App\Http\Controllers\Controller;
use App\Models\Restoran;
use App\Models\Menu;
use App\Models\Pencarian;
use App\Models\Favorit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB; 


class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'admin') return redirect('/admin/index');
        if ($user->role === 'pemilik_usaha') return redirect('/pemilik/dashboard');

        $showOnboarding = !DB::table('preferensi_users')
        ->where('user_id', $user->id)
        ->exists();

        $filter = $request->get('filter', 'Semua');
        $search = $request->input('search') ?? '';

        // ─── LOG RIWAYAT PENCARIAN (untuk fitur CBF AI) ─────────────
        if ($search) {
        Pencarian::create([
        'id_pencari' => $user->id,
        'keyword'    => $search,
        'lokasi'     => '', 
        'waktu'      => now(),
    ]);
}

        // GPS
        if ($request->has('lat') && $request->has('lng')) {
            session([
                'user_lat' => (float) $request->lat,
                'user_lng' => (float) $request->lng
            ]);
        }

        $userLat = (float) session('user_lat', $user->latitude ?? 0);
        $userLng = (float) session('user_lng', $user->longitude ?? 0);

        $hasLokasi = ($userLat != 0.0 || $userLng != 0.0);

        // ── Ambil ID favorit user ──
        $userId = Auth::id();
        $favoritRestoranIds = Favorit::where('user_id', $userId)
            ->whereNotNull('id_restoran')
            ->whereNull('id_menu')
            ->pluck('id_restoran')
            ->toArray();

        $favoritMenuIds = Favorit::where('user_id', $userId)
            ->whereNotNull('id_menu')
            ->whereNull('id_restoran')
            ->pluck('id_menu')
            ->toArray();
    
        // ================================
        // AI REKOMENDASI
        // ================================
        $rekomendasiAI = $this->getRekomendasiAI($user->id);


        // ================================
        // POPULER (AI TREND LAMA)
        // ================================
        $populer = Menu::with('restoran')
            ->where('tersedia', 1)
            ->inRandomOrder()
            ->take(6)
            ->get();


        // ================================
        // TERDEKAT (TOPSIS)
        // ================================
        $terdekat = $this->getTerdekatTopsis(
            $userLat,
            $userLng
        );


        $restorans = null;
        $menuResults = null;


        if ($search || $filter !== 'Semua') {

            [$restorans, $menuResults] =
                $this->getSearchResults(
                    $search,
                    $filter,
                    $request
                );
        }


        return view('dashboard', compact(
            'rekomendasiAI',
            'populer',
            'terdekat',
            'restorans',
            'menuResults',
            'filter',
            'search',
            'hasLokasi',
            'showOnboarding',
            'favoritRestoranIds',
            'favoritMenuIds'
        ));
    }

    // ══════════════════════════════════════════════════════════
    //  TERDEKAT — Haversine + TOPSIS
    // ══════════════════════════════════════════════════════════

    private function getTerdekatTopsis(float $userLat, float $userLng)
    {
       $restorans = Restoran::withAvg('ulasan', 'rating')
        ->where('status_halal', 'certified')
        ->take(20)
        ->get();
            if ($restorans->isEmpty()) return collect();

        $restorans->each(function ($r) use ($userLat, $userLng) {
            $r->jarak_km = $this->hitungJarak(
                $userLat, $userLng,
                (float) ($r->latitude  ?? 0),
                (float) ($r->longitude ?? 0)
            );
        });

        $payload = $restorans->map(function ($r) {

            return [

                'id_restoran' => $r->id_restoran,

                'nama_restoran' => $r->nama_restoran,

                'jarak_km' => $r->jarak_km,

                'harga' => (int)(
    (
        ($r->harga_rata_rata_min ?? 0) +
        ($r->harga_rata_rata_max ?? 0)
    ) / 2
),

                'rating' => round((float)($r->ulasan_avg_rating ?? 0),1),

                'jam_operasional' => $this->nilaiJamOperasional($r->jam_operasional)

            ];

    })->toArray();

        $ranked = $this->callTopsis('/topsis/terdekat', ['restorans' => $payload]);

        if (empty($ranked)) return $restorans->take(5);

        $orderedIds = collect($ranked)->take(5)->pluck('id_restoran');
        return $restorans
            ->whereIn('id_restoran', $orderedIds->all())
            ->sortBy(fn($r) => $orderedIds->search($r->id_restoran))
            ->values();
    }

    // ══════════════════════════════════════════════════════════
    //  PENCARIAN CAMPURAN
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
                ->orWhere('alamat',       'like', "%{$search}%")
                ->orWhere('kota',         'like', "%{$search}%");
            });
            $qMenu->where(function ($q) use ($search) {
                $q->where('nama_menu',   'like', "%{$search}%")
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
                // Ambil favorit restoran
                $favRestoIds = Favorit::where('user_id', Auth::id())
                    ->whereNotNull('id_restoran')
                    ->whereNull('id_menu')
                    ->pluck('id_restoran');
                
                // Ambil favorit menu
                $favMenuIds = Favorit::where('user_id', Auth::id())
                    ->whereNotNull('id_menu')
                    ->whereNull('id_restoran')
                    ->pluck('id_menu');
                
                // Filter restoran yang difavoritkan
                $qResto->whereIn('id_restoran', $favRestoIds);
                
                // Filter menu yang difavoritkan (berdasarkan id_menu, bukan id_restoran)
                $qMenu->whereIn('id_menu', $favMenuIds);
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

        // HITUNG JARAK MENU LANGSUNG DARI RESTORANNYA
        $menus->getCollection()->transform(function($menu) use ($userLat, $userLng){
            // Ambil jarak dari restoran menu
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

    private function nilaiJamOperasional($jam)
    {
        if(!$jam) return 0;

        $jam = strtolower($jam);

        if(str_contains($jam,'24'))
            return 24;

        preg_match_all('/(\d{2})[:.]\d{2}/',$jam,$hasil);

        if(count($hasil[1])>=2){

            $buka = (int)$hasil[1][0];

            $tutup = (int)$hasil[1][1];

            if($tutup < $buka){
                $tutup += 24;
            }

            return $tutup-$buka;
        }

        return 12;
    }

    // ══════════════════════════════════════════════════════════
    //  REKOMENDASI AI — FastAPI
    // ══════════════════════════════════════════════════════════

    private function getRekomendasiAI($userId)
    {
        try {
            $response = Http::timeout(5)
                ->get("http://127.0.0.1:8001/rekomendasi/{$userId}", ['n' => 5]);
            $data = $response->json();

            if (empty($data['rekomendasi'])) return $this->fallbackRekomendasi();

            $topResto = $data['rekomendasi'][0];
            $menu = Menu::where('id_restoran', $topResto['id_restoran'])
                ->where('tersedia', 1)->inRandomOrder()->first();

            if (!$menu) return $this->fallbackRekomendasi();

            $menu->load('restoran');
            $menu->ai_score = $topResto['score'];
            return $menu;

        } catch (\Exception $e) {
            Log::warning('FastAPI tidak terjangkau: ' . $e->getMessage());
            return $this->fallbackRekomendasi();
        }
    }

    private function fallbackRekomendasi()
    {
        return Menu::with('restoran')
            ->whereHas('restoran', fn($q) => $q->where('status_halal', 'certified'))
            ->where('tersedia', 1)->inRandomOrder()->first();
    }

    // ══════════════════════════════════════════════════════════
    //  HELPERS
    // ══════════════════════════════════════════════════════════

    private function callTopsis(string $endpoint, array $body): array
    {
        try {
            $url      = config('services.topsis.url', 'http://127.0.0.1:5001');
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
        $R    = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a    = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;
        return round($R * 2 * atan2(sqrt($a), sqrt(1 - $a)), 1);
    }
}