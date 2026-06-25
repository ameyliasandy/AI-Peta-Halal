<?php

namespace App\Http\Controllers\Pencari;

use App\Http\Controllers\Controller;
use App\Models\Restoran;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Redirect berdasarkan role
        $role = $user->role;
        if ($role === 'admin') return redirect('/admin/index');
        if ($role === 'pemilik_usaha') return redirect('/pemilik/dashboard');

        $filter = $request->get('filter', 'Semua');
        $search = $request->get('search', '');

        // ─── REKOMENDASI AI (dari FastAPI, real-time) ─────────────────
        $rekomendasiAI = $this->getRekomendasiAI($user->id);

        // ─── POPULER ────────────────────────────────────────────────
        $populer = Menu::with('restoran')
            ->where('tersedia', 1)
            ->inRandomOrder()
            ->take(6)
            ->get();

        // ─── TERDEKAT ───────────────────────────────────────────────
        $terdekat = Restoran::orderBy('nama_restoran')
            ->take(5)
            ->get();

        // ─── FILTER & PENCARIAN ─────────────────────────────────────
        $restorans = null;
        if ($search || $filter !== 'Semua') {
            $query = Restoran::with('menus')
                ->select(
                    'id_restoran', 'nama_restoran', 'alamat', 'kota',
                    'status_halal', 'rating', 'jumlah_ulasan',
                    'harga_rata_rata_min', 'harga_rata_rata_max',
                    'foto_utama', 'jam_operasional', 'deskripsi',
                    'latitude', 'longitude'
                );

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_restoran', 'like', "%{$search}%")
                      ->orWhere('alamat', 'like', "%{$search}%")
                      ->orWhere('kota', 'like', "%{$search}%")
                      ->orWhereHas('menus', fn($q2) =>
                          $q2->where('nama_menu', 'like', "%{$search}%")
                      );
                });
            }

            switch ($filter) {
                case 'Pedas':
                    $query->whereHas('menus', fn($q) =>
                        $q->where('nama_menu', 'like', '%pedas%')
                          ->orWhere('nama_menu', 'like', '%sambal%')
                          ->orWhere('nama_menu', 'like', '%geprek%')
                          ->orWhere('nama_menu', 'like', '%gepuk%')
                          ->orWhere('deskripsi', 'like', '%pedas%')
                    );
                    break;
                case 'Murah':
                    $query->where(function ($q) {
                        $q->where('harga_rata_rata_min', '<=', 20000)
                          ->orWhereNull('harga_rata_rata_min')
                          ->orWhereHas('menus', fn($q2) =>
                              $q2->where('harga', '<=', 15000)
                          );
                    });
                    break;
                case 'Terdekat':
                    $query->orderBy('kota');
                    break;
                case 'Favorit':
                    $favIds = \App\Models\Favorit::where('user_id', Auth::id())
                        ->pluck('id_restoran');
                    $query->whereIn('id_restoran', $favIds);
                    break;
            }

            $restorans = $query->paginate(12)->appends($request->query());
        }

        return view('dashboard', compact(
            'rekomendasiAI',
            'populer',
            'terdekat',
            'restorans',
            'filter',
            'search'
        ));
    }

    /**
     * Panggil FastAPI untuk dapat rekomendasi resto dari model hybrid AI
     * (CF + CBF + Trend), lalu ambil 1 menu representatif dari resto teratas.
     */
    private function getRekomendasiAI($userId)
    {
        try {
            $client = new Client(['timeout' => 5]);
            $response = $client->get("http://127.0.0.1:8001/rekomendasi/{$userId}", [
                'query' => ['n' => 5]
            ]);

            $data = json_decode($response->getBody(), true);

            if (empty($data['rekomendasi'])) {
                return $this->fallbackRekomendasi();
            }

            // Ambil resto rangking #1 dari hasil AI
            $topResto = $data['rekomendasi'][0];
            $idRestoran = $topResto['id_restoran'];

            // Cari 1 menu representatif dari resto itu
            $menu = Menu::where('id_restoran', $idRestoran)
                ->where('tersedia', 1)
                ->inRandomOrder()
                ->first();

            if (!$menu) {
                return $this->fallbackRekomendasi();
            }

            $menu->load('restoran');
            $menu->ai_score = $topResto['score']; // skor dari model AI

            return $menu;

        } catch (\Exception $e) {
            \Log::warning('FastAPI tidak terjangkau: ' . $e->getMessage());
            return $this->fallbackRekomendasi();
        }
    }

    /**
     * Fallback kalau FastAPI tidak bisa diakses (server mati, dll)
     * supaya dashboard tidak crash.
     */
    private function fallbackRekomendasi()
    {
        return Menu::with('restoran')
            ->whereHas('restoran', fn($q) => $q->where('status_halal', 'certified'))
            ->where('tersedia', 1)
            ->inRandomOrder()
            ->first();
    }
}