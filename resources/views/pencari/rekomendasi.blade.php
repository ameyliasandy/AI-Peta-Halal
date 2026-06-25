@extends('layouts.app')

@php use App\Helpers\ImageHelper; @endphp

@section('title', 'Semua Rekomendasi - Petha')
@section('content')

<div class="bg-[#2D6A4F] text-white px-4 pt-8 pb-14 md:px-16 lg:px-32">
    <div class="flex items-center justify-between mb-3">
        <p class="text-lg font-semibold">Petha</p>
        <div onclick="toggleProfile()"
            class="w-10 h-10 rounded-full bg-green-300 text-[#2D6A4F] font-bold flex items-center justify-center text-sm cursor-pointer hover:bg-green-200 transition select-none relative z-10">
            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
        </div>
    </div>
    <h1 class="text-2xl font-bold">📋 Semua Rekomendasi</h1>
    <p class="text-sm opacity-80 mt-1">Rekomendasi AI berdasarkan preferensimu</p>
</div>

<div class="px-4 md:px-16 lg:px-32 mt-5 pb-10">
    @if($rekomendasi->isEmpty())
        <div class="text-center py-16">
            <p class="text-4xl mb-3">🤖</p>
            <p class="text-gray-500 font-medium">Belum ada rekomendasi</p>
            <p class="text-gray-400 text-sm mt-1">Mulai berikan rating pada restoran untuk mendapatkan rekomendasi</p>
            <a href="/dashboard" class="mt-4 inline-block text-sm text-[#2D6A4F] font-medium">← Kembali ke dashboard</a>
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($rekomendasi as $item)
                @php
                    $resto = $item->restoran;
                @endphp
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition cursor-pointer">
                    <div class="h-28 bg-gray-100 overflow-hidden relative">
                        <img src="{{ ImageHelper::restoran($resto->nama_restoran ?? '') }}"
                             class="w-full h-full object-cover"
                             alt="{{ $resto->nama_restoran ?? '' }}">
                        <span class="absolute top-1 right-1 bg-[#2D6A4F] text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-bold">
                            {{ $item->rank }}
                        </span>
                    </div>
                    <div class="p-3">
                        <p class="font-semibold text-sm text-gray-800 leading-tight line-clamp-2">
                            {{ $resto->nama_restoran ?? '' }}
                        </p>
                        <p class="text-xs text-gray-400 mt-0.5 truncate">{{ $resto->kota ?? '' }}</p>
                        <div class="flex items-center gap-1 mt-1">
                            <span class="text-yellow-400 text-xs">⭐</span>
                            <span class="text-xs text-gray-500">{{ $resto->rating ?? '-' }}</span>
                        </div>
                        @php $status = $resto->status_halal ?? 'none'; @endphp
                        <span class="inline-block mt-1.5 text-xs px-2 py-0.5 rounded-full
                            {{ $status === 'certified' ? 'bg-green-100 text-green-700' :
                              ($status === 'self_claimed' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-500') }}">
                            {{ $status === 'certified' ? 'Bersertifikat' :
                              ($status === 'self_claimed' ? 'Klaim Halal' : 'Belum Terverifikasi') }}
                        </span>
                        {{-- TOMBOL DETAIL --}}
    <a href="{{ route('restoran.show', $resto->id_restoran ?? 0) }}" 
       class="mt-2 block text-center text-xs font-medium text-[#2D6A4F] border border-[#2D6A4F] rounded-full py-1.5 px-3 hover:bg-[#2D6A4F] hover:text-white transition">
        Lihat Detail
    </a>
</div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-6">{{ $rekomendasi->links() }}</div>
    @endif
</div>

{{-- Profile Popup --}}
<div id="profileOverlay" onclick="toggleProfile()"
    class="fixed inset-0 bg-black/30 z-40 hidden"></div>

<div id="profilePopup"
    class="fixed top-16 right-4 md:right-16 lg:right-32 bg-white rounded-2xl shadow-xl z-50 w-72 hidden">

    <div class="flex items-center gap-4 px-5 py-4 border-b border-gray-100">
        <div class="w-14 h-14 rounded-full bg-green-600 text-white font-bold flex items-center justify-center text-lg flex-shrink-0">
            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
        </div>
        <div class="min-w-0">
            <p class="font-bold text-gray-800">{{ explode(' ', Auth::user()->name)[0] }}</p>
            <p class="text-sm text-gray-400 truncate">{{ Auth::user()->email }}</p>
        </div>
    </div>

    <div class="py-2">
        <a href="{{ route('profile.index') }}" class="flex items-center gap-4 px-5 py-3 hover:bg-gray-50 transition">
            <span class="text-[#2D6A4F] text-xl">👤</span>
            <span class="font-semibold text-gray-800">Profil Saya</span>
        </a>
        <a href="{{ route('profile.favorit') }}" class="flex items-center gap-4 px-5 py-3 hover:bg-gray-50 transition">
            <span class="text-[#2D6A4F] text-xl">⭐</span>
            <span class="font-semibold text-gray-800">Favorit</span>
        </a>
        <a href="{{ route('profile.preferensi') }}" class="flex items-center gap-4 px-5 py-3 hover:bg-gray-50 transition">
            <span class="text-[#2D6A4F] text-xl">🍽️</span>
            <span class="font-semibold text-gray-800">Preferensi Makanan</span>
        </a>
        <a href="{{ route('profile.pengaturan') }}" class="flex items-center gap-4 px-5 py-3 hover:bg-gray-50 transition">
            <span class="text-[#2D6A4F] text-xl">⚙️</span>
            <span class="font-semibold text-gray-800">Pengaturan</span>
        </a>
    </div>

    <div class="border-t border-gray-100 py-2">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-4 px-5 py-3 hover:bg-red-50 transition text-left">
                <span class="text-red-500 text-xl">🚪</span>
                <span class="font-semibold text-red-500">Keluar</span>
            </button>
        </form>
    </div>
</div>

<script>
function toggleProfile() {
    const popup = document.getElementById('profilePopup');
    const overlay = document.getElementById('profileOverlay');
    popup.classList.toggle('hidden');
    overlay.classList.toggle('hidden');
}
</script>

@endsection