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

        if ($showOnboarding) {
            return redirect('/onboarding');
        }

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
        // AI REKOMENDASI (Hybrid + TOPSIS + Explainable AI)
        // ================================
        $rekomendasiAI = $this->getRekomendasiAI($user->id, $userLat, $userLng);


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
                $favRestoIds = Favorit::where('user_id', Auth::id())
                    ->whereNotNull('id_restoran')
                    ->whereNull('id_menu')
                    ->pluck('id_restoran');
                
                $favMenuIds = Favorit::where('user_id', Auth::id())
                    ->whereNotNull('id_menu')
                    ->whereNull('id_restoran')
                    ->pluck('id_menu');
                
                $qResto->whereIn('id_restoran', $favRestoIds);
                
                $qMenu->whereIn('id_menu', $favMenuIds);
                break;
        }

        $restorans = $qResto
            ->paginate(8, ['*'], 'resto_page')
            ->appends($request->query());

        $menus = $qMenu
            ->paginate(8, ['*'], 'menu_page')
            ->appends($request->query());

        $userLat = session('user_lat', 0);
        $userLng = session('user_lng', 0);

        $restorans->getCollection()->transform(function($restoran) use ($userLat,$userLng){
            $restoran->jarak_km = $this->hitungJarak(
                $userLat,
                $userLng,
                $restoran->latitude ?? 0,
                $restoran->longitude ?? 0
            );
            return $restoran;
        });

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
    //  REKOMENDASI AI — Hybrid (FastAPI) + Re-ranking TOPSIS (Flask)
    //  + Explainable AI (alasan rekomendasi per kartu)
    // ══════════════════════════════════════════════════════════

    private function getRekomendasiAI($userId, float $userLat = 0, float $userLng = 0)
    {
        try {
            // Ambil preferensi eksplisit user (dari onboarding)
            $kategoriPreferensi = DB::table('preferensi_users')
                ->where('user_id', $userId)
                ->where('kategori', '!=', 'skip')
                ->pluck('kategori')
                ->toArray();

            // Ambil riwayat pencarian user (untuk Explainable AI)
            $riwayatPencarian = $this->getRiwayatPencarianUser($userId);

            // 1) Ambil kandidat dari Hybrid Recommendation
            $aiUrl = config('services.ai.url', 'http://127.0.0.1:8001');
            $response = Http::timeout(5)
                ->get("{$aiUrl}/rekomendasi/{$userId}", ['n' => 15]);
            $data = $response->json();

            if (empty($data['rekomendasi'])) {
                return $this->fallbackRekomendasiList();
            }

            // 2) Lengkapi tiap kandidat dengan data yang dibutuhkan TOPSIS
            $kandidat = $this->enrichKandidatUntukTopsis($data['rekomendasi'], $userLat, $userLng);

            if ($kandidat->isEmpty()) {
                return $this->fallbackRekomendasiList();
            }

            // 3) Re-ranking pakai TOPSIS (Flask)
            $ranked = $this->callTopsis('/topsis/hybrid', ['restorans' => $kandidat->values()->all()]);

            if (empty($ranked)) {
                $ranked = $kandidat->sortByDesc('hybrid_score')->values()->all();
            } else {
                usort($ranked, fn($a, $b) => $b['topsis_score'] <=> $a['topsis_score']);
            }

            $top5 = array_slice($ranked, 0, 5);

            // 4) Konversi kandidat terpilih menjadi Menu untuk ditampilkan di kartu
            $hasil = collect();
            $usedRestoIds = [];

            foreach ($top5 as $item) {
                if (in_array($item['id_restoran'], $usedRestoIds)) continue;

                $menu = $this->pilihMenuTerbaik($item['id_restoran'], $kategoriPreferensi);

                if (!$menu) continue;

                $menu->load('restoran');
                $menu->ai_score     = $item['hybrid_score'];
                $menu->topsis_score = $item['topsis_score'] ?? null;
                $menu->jarak_km_ai  = $item['jarak_km'] ?? null;

                // ── Explainable AI: alasan rekomendasi ──
                $menu->alasan_rekomendasi = $this->buildAlasanRekomendasi(
                    $menu, $item, $kategoriPreferensi, $riwayatPencarian
                );

                $hasil->push($menu);
                $usedRestoIds[] = $item['id_restoran'];
            }

            if ($hasil->count() < 5) {
                $tambahan = $this->fallbackRekomendasiList($usedRestoIds, 5 - $hasil->count());
                foreach ($tambahan as $menu) {
                    $menu->alasan_rekomendasi = ['Direkomendasikan karena populer di platform kami'];
                }
                $hasil = $hasil->concat($tambahan);
            }

            return $hasil->values();

        } catch (\Exception $e) {
            Log::warning('Hybrid+TOPSIS gagal: ' . $e->getMessage());
            return $this->fallbackRekomendasiList();
        }
    }

    /**
     * Ambil kata kunci pencarian terakhir user (unik, lowercase) — dipakai
     * untuk Explainable AI ("Anda sering mencari ...").
     */
    private function getRiwayatPencarianUser($userId, int $limit = 10): array
    {
        return Pencarian::where('id_pencari', $userId)
            ->orderByDesc('waktu')
            ->limit($limit)
            ->pluck('keyword')
            ->filter()
            ->map(fn($k) => strtolower(trim($k)))
            ->unique()
            ->values()
            ->toArray();
    }

    /**
     * Bangun daftar alasan (maks 3) kenapa menu/restoran ini direkomendasikan.
     * Diprioritaskan dari sinyal paling personal ke paling umum:
     *   1) Preferensi eksplisit (onboarding)
     *   2) Riwayat pencarian
     *   3) Jarak dekat
     *   4) Rating tinggi
     *   5) Populer (jumlah ulasan banyak)
     */
    private function buildAlasanRekomendasi($menu, array $item, array $kategoriPreferensi, array $riwayatPencarian): array
    {
        $alasan = [];
        $teks = strtolower(
            $menu->nama_menu . ' ' .
            ($menu->deskripsi ?? '') . ' ' .
            ($menu->restoran->nama_restoran ?? '')
        );

        // 1) Preferensi eksplisit
        foreach ($kategoriPreferensi as $kategori) {
            if (str_contains($teks, strtolower($kategori))) {
                $alasan[] = "Sesuai preferensi Anda: {$kategori}";
                break;
            }
        }

        // 2) Riwayat pencarian
        foreach ($riwayatPencarian as $keyword) {
            if (strlen($keyword) >= 3 && str_contains($teks, $keyword)) {
                $alasan[] = "Anda sering mencari \"{$keyword}\"";
                break;
            }
        }

        // 3) Jarak dekat
        if (isset($item['jarak_km']) && $item['jarak_km'] < 999 && $item['jarak_km'] <= 3) {
            $alasan[] = 'Dekat dengan lokasi Anda (' . round($item['jarak_km'], 1) . ' km)';
        }

        // 4) Rating tinggi
        if (isset($item['rating']) && $item['rating'] >= 4.5) {
            $alasan[] = "Rating tinggi ({$item['rating']}/5)";
        }

        // 5) Populer
        if (isset($item['jumlah_ulasan']) && $item['jumlah_ulasan'] >= 10) {
            $alasan[] = "Populer, sudah diulas {$item['jumlah_ulasan']} kali";
        }

        // Fallback kalau tidak ada satupun sinyal spesifik yang match
        if (empty($alasan)) {
            $alasan[] = 'Direkomendasikan oleh sistem AI berdasarkan kombinasi preferensi dan tren';
        }

        return array_slice($alasan, 0, 3);
    }

    /**
     * Lengkapi kandidat dari FastAPI dengan data yang dibutuhkan TOPSIS
     * dan Explainable AI: jarak, rating, status halal, jumlah menu,
     * jumlah ulasan.
     */
    private function enrichKandidatUntukTopsis(array $rekomendasi, float $userLat, float $userLng)
    {
        $ids = collect($rekomendasi)->pluck('id_restoran')->all();

        $restorans = Restoran::withAvg('ulasan', 'rating')
            ->withCount([
                'menus as jumlah_menu' => fn($q) => $q->where('tersedia', 1),
                'ulasan as jumlah_ulasan',
            ])
            ->whereIn('id_restoran', $ids)
            ->get()
            ->keyBy('id_restoran');

        $halalScoreMap = ['certified' => 1.0, 'self_claimed' => 0.5];

        return collect($rekomendasi)->map(function ($item) use ($restorans, $userLat, $userLng, $halalScoreMap) {
            $r = $restorans->get($item['id_restoran']);
            if (!$r) return null;

            $jarak = $this->hitungJarak(
                $userLat, $userLng,
                (float) ($r->latitude ?? 0),
                (float) ($r->longitude ?? 0)
            );

            return [
                'id_restoran'    => (int) $item['id_restoran'],
                'nama_restoran'  => $r->nama_restoran,
                'hybrid_score'   => (float) $item['score'],
                'jarak_km'       => $jarak,
                'rating'         => round((float) ($r->ulasan_avg_rating ?? 0), 1),
                'status_halal'   => $halalScoreMap[$r->status_halal] ?? 0.0,
                'jumlah_menu'    => (int) ($r->jumlah_menu ?? 0),
                'jumlah_ulasan'  => (int) ($r->jumlah_ulasan ?? 0),
            ];
        })->filter()->values();
    }

    /**
     * Pilih menu paling RELEVAN dari sebuah restoran untuk user tertentu.
     * Prioritas: 1) match preferensi eksplisit, 2) menu terpopuler
     * (favorit terbanyak), 3) fallback deterministik (bukan acak).
     */
    private function pilihMenuTerbaik(int $idRestoran, array $kategoriPreferensi)
    {
        $menus = Menu::where('id_restoran', $idRestoran)
            ->where('tersedia', 1)
            ->get();

        if ($menus->isEmpty()) return null;

        if (!empty($kategoriPreferensi)) {
            foreach ($menus as $menu) {
                $teks = strtolower($menu->nama_menu . ' ' . ($menu->deskripsi ?? ''));
                foreach ($kategoriPreferensi as $kategori) {
                    if (str_contains($teks, strtolower($kategori))) {
                        return $menu;
                    }
                }
            }
        }

        $jumlahFavorit = DB::table('favorit')
            ->whereIn('id_menu', $menus->pluck('id_menu'))
            ->whereNotNull('id_menu')
            ->select('id_menu', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('id_menu')
            ->pluck('jumlah', 'id_menu');

        $terpopuler = $menus->sortByDesc(fn($m) => $jumlahFavorit[$m->id_menu] ?? 0)->first();
        if (($jumlahFavorit[$terpopuler->id_menu] ?? 0) > 0) {
            return $terpopuler;
        }

        return $menus->sortBy('id_menu')->first();
    }

    /**
     * Fallback rekomendasi berbasis popularitas.
     */
    private function fallbackRekomendasiList(array $excludeRestoIds = [], int $n = 5)
    {
        return Menu::with('restoran')
            ->whereHas('restoran', fn($q) => $q->where('status_halal', 'certified'))
            ->where('tersedia', 1)
            ->when(!empty($excludeRestoIds), fn($q) => $q->whereNotIn('id_restoran', $excludeRestoIds))
            ->inRandomOrder()
            ->take($n)
            ->get();
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