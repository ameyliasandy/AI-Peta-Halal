<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restoran;
use App\Models\User;
use App\Models\VerifikasiHalal;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ── STAT CARDS ────────────────────────────────────────────
        $totalUsahaHalal = Restoran::count();

        $perluVerif = VerifikasiHalal::where('status', 'pending')->count();

        $totalPengguna = User::where('role', '!=', 'admin')->count();

        // ── PENDING LIST (untuk kartu "Usaha yang perlu verifikasi") ──
        $pendingRestoran = Restoran::with(['verifikasiHalal', 'pemilik'])
            ->whereHas('verifikasiHalal', fn($q) => $q->where('status', 'pending'))
            ->latest()
            ->take(6)
            ->get();

        // ── CHART: visitor / restoran baru per hari minggu ini ───
        // Gunakan data registrasi restoran sebagai proxy "visitor/aktivitas"
        $startOfWeek = Carbon::now()->startOfWeek(); // Senin
        $chartLabels = [];
        $chartData   = [];

        for ($i = 0; $i < 7; $i++) {
            $day = $startOfWeek->copy()->addDays($i);
            $chartLabels[] = $day->translatedFormat('l'); // Sunday, Monday, ...
            $chartData[]   = Restoran::whereDate('created_at', $day->toDateString())->count();
        }

        // ── PERIODE FILTER (default: bulan ini) ──────────────────
        $periodeLabel = Carbon::now()->startOfMonth()->format('d M Y')
                      . ' - '
                      . Carbon::now()->endOfMonth()->format('d M Y');

        return view('admin.index', compact(
            'totalUsahaHalal',
            'perluVerif',
            'totalPengguna',
            'pendingRestoran',
            'chartLabels',
            'chartData',
            'periodeLabel'
        ));
    }
  
    
    
}