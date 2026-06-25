<?php

namespace App\Http\Controllers;

use App\Models\Restoran;
use App\Models\Menu;

class GuestController extends Controller
{
    public function index()
    {
        $populer = Menu::with('restoran')
            ->where('tersedia', 1)
            ->inRandomOrder()
            ->take(8)
            ->get();

        $totalResto = Restoran::count();
        $totalCertified = Restoran::where('status_halal', 'certified')->count();

        return view('guest', compact('populer', 'totalResto', 'totalCertified'));
    }
}