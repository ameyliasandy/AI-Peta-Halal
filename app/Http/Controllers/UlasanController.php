<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ulasan;

class UlasanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'id_restoran' => 'required|exists:restoran,id_restoran',
            'rating'      => 'required|integer|min:1|max:5',
            'komentar'    => 'nullable|string|max:500',
        ]);

        Ulasan::updateOrCreate(
            [
                'user_id'     => Auth::id(),
                'id_restoran' => $request->id_restoran,
            ],
            [
                'rating'   => $request->rating,
                'komentar' => $request->komentar,
            ]
        );

        return back()->with('success', 'Terima kasih atas ulasanmu!');
    }
}