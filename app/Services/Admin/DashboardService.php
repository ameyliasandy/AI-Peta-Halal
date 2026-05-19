<?php

namespace App\Services\Admin;

use App\Models\Restoran;
use App\Models\User;
use App\Models\VerifikasiHalal;

class DashboardService
{
    public function getStats()
    {
        return [
            'perlu_verif' => VerifikasiHalal::where('status', 'pending')->count(),
            'total_usaha' => Restoran::count(),
            'total_user' => User::count(),
        ];
    }

    public function getVerifikasiList()
    {
        return VerifikasiHalal::with('restoran')
            ->where('status', 'pending')
            ->orderBy('id_verifikasi', 'desc')
            ->take(5)
            ->get();
    }
}