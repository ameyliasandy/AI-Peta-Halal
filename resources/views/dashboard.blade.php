@extends('layouts.app')

@php use App\Helpers\ImageHelper; @endphp

@section('title', 'Dashboard - Petha')
@section('content')

    {{-- Header Hijau --}}
    <div class="bg-[#2D6A4F] text-white px-4 pt-8 pb-14 md:px-16 lg:px-32">
        <div class="flex items-center justify-between mb-3">
            <p class="text-lg font-semibold">Petha</p>
            <div onclick="toggleProfile()"
                class="w-10 h-10 rounded-full bg-green-300 text-[#2D6A4F] font-bold flex items-center justify-center text-sm cursor-pointer hover:bg-green-200 transition select-none relative z-10">
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
        </div>
        <h1 class="text-2xl font-bold">Hallo, {{ explode(' ', Auth::user()->name)[0] }}! 👋</h1>
        <p class="text-sm opacity-80 mt-1">Mau makan apa hari ini?</p>

        {{-- Search Form --}}
        <form method="GET" action="/dashboard" class="mt-4">
            <input type="hidden" name="filter" value="{{ $filter }}">
            <div class="bg-white/20 rounded-full flex items-center px-4 py-2.5">
                <span class="mr-2 text-sm">🔍</span>
                <input type="text" name="search" value="{{ $search }}"
                    placeholder="Cari makanan halal..."
                    class="flex-1 bg-transparent outline-none text-sm text-white placeholder-white/70">
                @if($search)
                <a href="/dashboard?filter={{ $filter }}" class="text-white/70 hover:text-white text-sm ml-2">✕</a>
                @endif
            </div>
        </form>
    </div>

    {{-- Filter Chips --}}
    <div class="px-4 md:px-16 lg:px-32 mt-4 flex gap-2 overflow-x-auto pb-1 no-scrollbar">
        @foreach(['Semua', 'Pedas', 'Murah', 'Terdekat', 'Favorit'] as $f)
        <a href="/dashboard?filter={{ $f }}&search={{ $search }}"
           class="px-4 py-1.5 rounded-full text-sm font-medium whitespace-nowrap transition
               {{ $filter === $f ? 'bg-[#2D6A4F] text-white' : 'bg-white text-gray-600 border border-gray-200 hover:border-[#2D6A4F]' }}">
            {{ $f }}
        </a>
        @endforeach
    </div>

    {{-- HASIL FILTER / PENCARIAN --}}
    @if($restorans !== null)
    <div class="px-4 md:px-16 lg:px-32 mt-5 pb-10">
        <div class="mb-3">
            <h2 class="font-bold text-gray-800">
                @if($search)
                    🔍 Hasil "{{ $search }}"
                @else
                    🏷️ Filter: {{ $filter }}
                @endif
                <span class="text-sm font-normal text-gray-400 ml-1">({{ $restorans->total() }} restoran)</span>
            </h2>
        </div>

        @if($restorans->isEmpty())
            <div class="text-center py-16">
                <p class="text-4xl mb-3">🍽️</p>
                <p class="text-gray-500 font-medium">Tidak ada restoran ditemukan</p>
                <p class="text-gray-400 text-sm mt-1">Coba kata kunci atau filter lain</p>
                <a href="/dashboard" class="mt-4 inline-block text-sm text-[#2D6A4F] font-medium">← Kembali ke semua</a>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($restorans as $resto)
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition cursor-pointer">
                    <div class="h-28 bg-gray-100 overflow-hidden">
                        <img src="{{ ImageHelper::restoran($resto->nama_restoran) }}"
                             class="w-full h-full object-cover"
                             alt="{{ $resto->nama_restoran }}">
                    </div>
                    <div class="p-3">
                        <p class="font-semibold text-sm text-gray-800 leading-tight line-clamp-2">{{ $resto->nama_restoran }}</p>
                        <p class="text-xs text-gray-400 mt-0.5 truncate">{{ $resto->kota }}</p>
                        <div class="flex items-center gap-1 mt-1">
                            <span class="text-yellow-400 text-xs">⭐</span>
                            <span class="text-xs text-gray-500">{{ $resto->rating ?? '-' }}</span>
                        </div>
                        @php $status = $resto->status_halal; @endphp
                        <span class="inline-block mt-1.5 text-xs px-2 py-0.5 rounded-full
                            {{ $status === 'certified' ? 'bg-green-100 text-green-700' :
                              ($status === 'self_claimed' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-500') }}">
                            {{ $status === 'certified' ? 'Bersertifikat' :
                              ($status === 'self_claimed' ? 'Klaim Halal' : 'Belum Terverifikasi') }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-6">{{ $restorans->links() }}</div>
        @endif
    </div>

    {{-- TAMPILAN NORMAL --}}
    @else
    <div class="px-4 md:px-16 lg:px-32 mt-5 grid grid-cols-1 lg:grid-cols-3 gap-6 pb-10">

        {{-- Kolom Kiri --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Rekomendasi Untukmu — AI BASED --}}
@if($rekomendasiAI)
<div>
    <div class="flex justify-between items-center mb-3">
        <h2 class="font-bold text-gray-800">✨ Rekomendasi untukmu</h2>
        <span class="text-xs bg-green-100 text-[#2D6A4F] px-2 py-1 rounded-full font-medium">
            🤖 AI Hybrid
        </span>
    </div>
    <div class="bg-white rounded-2xl overflow-hidden shadow-sm">
        <div class="h-44 md:h-64 bg-gray-200 relative">
            @php $resto = $rekomendasiAI->restoran; @endphp

            <img src="{{ $rekomendasiAI->foto_menu ?? ImageHelper::restoran($resto->nama_restoran ?? 'default') }}"
                 class="w-full h-full object-cover"
                 alt="{{ $rekomendasiAI->nama_menu }}">

            <span class="absolute bottom-3 left-3 bg-white/80 text-xs px-3 py-1 rounded-full">
                @if($resto && $resto->status_halal === 'certified')
                    ✅ Bersertifikat Halal
                @elseif($resto && $resto->status_halal === 'self_claimed')
                    🟡 Klaim Halal
                @else
                    🍽️ Rekomendasi AI untukmu
                @endif
                @if(isset($rekomendasiAI->ai_score))
                    <span class="ml-1 text-[#2D6A4F] font-bold">{{ round($rekomendasiAI->ai_score * 100) }}% match</span>
                @endif
            </span>
        </div>
        <div class="p-4">
            <h3 class="font-bold text-lg text-gray-800">{{ $rekomendasiAI->nama_menu }}</h3>
            <p class="text-xs text-gray-400 mt-0.5">{{ $resto->nama_restoran ?? '' }}</p>
            <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $rekomendasiAI->deskripsi }}</p>
            <div class="flex items-center justify-between mt-3">
                <span class="text-[#2D6A4F] font-bold text-lg">
                    Rp {{ number_format($rekomendasiAI->harga, 0, ',', '.') }}
                </span>
                @if($resto)
                <a href="{{ route('restoran.show', $resto->id_restoran) }}"
                   class="border border-gray-300 px-4 py-1.5 rounded-full text-sm font-medium hover:bg-[#2D6A4F] hover:text-white transition inline-block">
                    Lihat Restoran
                </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

            {{-- Populer Hari Ini — MENU --}}
            <div>
                <div class="flex justify-between items-center mb-3">
                    <h2 class="font-bold text-gray-800">🔥 Populer hari ini</h2>
                    <a href="#" class="text-sm text-gray-400">Lihat semua</a>
                </div>
                <div class="flex gap-3 overflow-x-auto pb-2 no-scrollbar">
                    @foreach($populer as $menu)
                    <div class="bg-white rounded-2xl overflow-hidden shadow-sm shrink-0 w-40">
                        <div class="h-24 bg-gray-100 overflow-hidden">
                            <img src="{{ $menu->foto_menu ?? ImageHelper::restoran($menu->restoran->nama_restoran) }}"
                                 class="w-full h-full object-cover"
                                 alt="{{ $menu->nama_menu }}">
                        </div>
                        <div class="p-2.5">
                            <p class="font-semibold text-xs text-gray-800 leading-tight line-clamp-2">
                                {{ $menu->nama_menu }}
                            </p>
                            <p class="text-xs text-gray-400 truncate mt-0.5">
                                {{ $menu->restoran->nama_restoran }}
                            </p>
                            <p class="text-[#2D6A4F] font-bold text-xs mt-1">
                                Rp {{ number_format($menu->harga, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Terdekat (mobile) — RESTORAN --}}
            <div class="lg:hidden">
                <div class="flex justify-between items-center mb-3">
                    <h2 class="font-bold text-gray-800">📍 Terdekat darimu</h2>
                    <a href="#" class="text-sm text-gray-400">Lihat peta</a>
                </div>
                <div class="space-y-3">
                    @foreach($terdekat as $resto)
                    <div class="bg-white rounded-2xl p-3 flex items-center gap-3 shadow-sm">
                        <div class="w-10 h-10 bg-gray-100 rounded-xl flex-shrink-0 overflow-hidden">
                            <img src="{{ ImageHelper::restoran($resto->nama_restoran) }}"
                                 class="w-full h-full object-cover"
                                 alt="{{ $resto->nama_restoran }}">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-800 text-sm truncate">{{ $resto->nama_restoran }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                ⭐ {{ $resto->rating ?? '-' }} · 📍 {{ $resto->kota }}
                            </p>
                        </div>
                        <span class="text-gray-400">→</span>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- Kolom Kanan (desktop) — RESTORAN --}}
        <div class="hidden lg:block">
            <div class="flex justify-between items-center mb-3">
                <h2 class="font-bold text-gray-800">📍 Terdekat darimu</h2>
                <a href="#" class="text-sm text-gray-400">Lihat peta</a>
            </div>
            <div class="space-y-3">
                @foreach($terdekat as $resto)
                <div class="bg-white rounded-2xl p-4 flex items-center gap-3 shadow-sm hover:shadow-md transition cursor-pointer">
                    <div class="w-12 h-12 bg-gray-100 rounded-xl flex-shrink-0 overflow-hidden">
                        <img src="{{ ImageHelper::restoran($resto->nama_restoran) }}"
                             class="w-full h-full object-cover"
                             alt="{{ $resto->nama_restoran }}">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-800 text-sm truncate">{{ $resto->nama_restoran }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            ⭐ {{ $resto->rating ?? '-' }} · 📍 {{ $resto->kota }}
                        </p>
                        @php $status = $resto->status_halal; @endphp
                        <span class="inline-block mt-1 text-xs px-2 py-0.5 rounded-full
                            {{ $status === 'certified' ? 'bg-green-100 text-green-700' :
                              ($status === 'self_claimed' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-500') }}">
                            {{ $status === 'certified' ? 'Bersertifikat' :
                              ($status === 'self_claimed' ? 'Klaim Halal' : 'Belum Terverifikasi') }}
                        </span>
                    </div>
                    <span class="text-gray-400 text-lg flex-shrink-0">→</span>
                </div>
                @endforeach
            </div>
        </div>

    </div>
    @endif

    {{-- Overlay --}}
    <div id="profileOverlay" onclick="toggleProfile()"
        class="fixed inset-0 bg-black/30 z-40 hidden"></div>

    {{-- Popup Profile --}}
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

    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    <script>
    function toggleProfile() {
        const popup = document.getElementById('profilePopup');
        const overlay = document.getElementById('profileOverlay');
        popup.classList.toggle('hidden');
        overlay.classList.toggle('hidden');
    }
    </script>

@endsection