@extends('layouts.app')

@php use App\Helpers\ImageHelper; @endphp

@section('title', 'Dashboard - Petha')
@section('content')

{{-- ══════════════════════════════════════════
     HEADER
══════════════════════════════════════════ --}}
<div class="bg-[#2D6A4F] text-white px-4 pt-8 pb-14 md:px-16 lg:px-32">
    <div class="flex items-center justify-between mb-3">
        <p class="text-lg font-semibold tracking-wide">Petha</p>
        {{-- Avatar --}}
        <button onclick="toggleProfile()"
            class="w-10 h-10 rounded-full bg-green-300 text-[#2D6A4F] font-bold flex items-center justify-center text-sm cursor-pointer hover:bg-green-200 transition select-none relative z-10">
            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
        </button>
    </div>
    <h1 class="text-2xl font-bold">Hallo, {{ explode(' ', Auth::user()->name)[0] }}!</h1>
    <p class="text-sm opacity-70 mt-1">Mau makan apa hari ini?</p>

    {{-- Search --}}
    <form method="GET" action="/dashboard" class="mt-4">
        <input type="hidden" name="filter" value="{{ $filter }}">
        <div class="bg-white/15 border border-white/30 rounded-2xl flex items-center px-4 py-3 gap-3">
            {{-- Search icon (CSS) --}}
            <div class="relative w-4 h-4 flex-shrink-0">
                <div class="w-3 h-3 rounded-full border-2 border-white/70 absolute top-0 left-0"></div>
                <div class="w-px h-2 bg-white/70 absolute bottom-0 right-0 rotate-45 origin-top"></div>
            </div>
            <input type="text" name="search" value="{{ $search }}"
                placeholder="Cari restoran atau makanan halal..."
                class="flex-1 bg-transparent outline-none text-sm text-white placeholder-white/50">
            @if($search)
            <a href="/dashboard?filter={{ $filter }}"
               class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0 hover:bg-white/30 transition">
                <div class="relative w-2.5 h-2.5">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-full h-px bg-white rotate-45"></div>
                    </div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-full h-px bg-white -rotate-45"></div>
                    </div>
                </div>
            </a>
            @endif
        </div>
    </form>
</div>

{{-- Filter Chips --}}
<div class="px-4 md:px-16 lg:px-32 mt-4 flex gap-2 overflow-x-auto pb-1 no-scrollbar">
    @foreach(['Semua', 'Pedas', 'Murah', 'Favorit'] as $f)
    <a href="/dashboard?filter={{ $f }}&search={{ $search }}"
       class="px-4 py-1.5 rounded-full text-sm font-medium whitespace-nowrap transition
           {{ $filter === $f
               ? 'bg-[#2D6A4F] text-white'
               : 'bg-white text-gray-600 border border-gray-200 hover:border-[#2D6A4F] hover:text-[#2D6A4F]' }}">
        {{ $f }}
    </a>
    @endforeach
</div>

{{-- ══════════════════════════════════════════
     HASIL PENCARIAN — campuran restoran + menu
══════════════════════════════════════════ --}}
@if($restorans !== null)
<div class="px-4 md:px-16 lg:px-32 mt-5 pb-10 space-y-8">

    {{-- Menu --}}
    <div>
        <div class="flex items-center gap-2 mb-3">
            {{-- Icon piring (CSS) --}}
            <div class="w-5 h-5 rounded-full border-2 border-gray-700 flex items-end justify-center pb-0.5">
                <div class="w-3 h-px bg-gray-700 rounded"></div>
            </div>
            <h2 class="font-bold text-gray-800">
                Menu
                @if($search)<span class="font-normal text-gray-400 text-sm">untuk "{{ $search }}"</span>@endif
                <span class="font-normal text-gray-400 text-sm ml-1">({{ $menuResults->total() }})</span>
            </h2>
        </div>

        @if($menuResults->isEmpty())
        <p class="text-sm text-gray-400 py-4 pl-1">Tidak ada menu ditemukan.</p>
        @else
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($menuResults as $menu)
            @php $st = $menu->restoran->status_halal ?? 'none'; @endphp
            <div onclick="bukaModalMenu(this)"
                 data-nama="{{ $menu->nama_menu }}"
                 data-menu-id="{{ $menu->id_menu }}"
                 data-foto="{{ $menu->foto_menu ?? ImageHelper::restoran($menu->restoran->nama_restoran ?? 'default') }}"
                 data-deskripsi="{{ $menu->deskripsi ?? '' }}"
                 data-harga="{{ $menu->harga }}"
                 data-resto-nama="{{ $menu->restoran->nama_restoran ?? '' }}"
                 data-resto-id="{{ $menu->id_restoran }}"
                 data-halal="{{ $st }}"
                 data-jarak="{{ $menu->restoran->jarak_km ?? '' }}"
                 class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition cursor-pointer group">
                <div class="h-28 bg-gray-100 overflow-hidden">
                    <img src="{{ $menu->foto_menu ?? ImageHelper::restoran($menu->restoran->nama_restoran ?? 'default') }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                         alt="{{ $menu->nama_menu }}">
                </div>
                <div class="p-3">
                    <p class="font-semibold text-sm text-gray-800 leading-tight line-clamp-2">{{ $menu->nama_menu }}</p>
                    <p class="text-xs text-gray-400 mt-0.5 truncate">{{ $menu->restoran->nama_restoran ?? '' }}</p>
                    <p class="text-[#2D6A4F] font-bold text-xs mt-1">Rp {{ number_format($menu->harga, 0, ',', '.') }}</p>
                    @if(isset($menu->jarak_km))
                    <div class="flex items-center gap-1 mt-1">
                        <div class="w-1.5 h-1.5 rounded-full bg-[#2D6A4F]"></div>
                        <span class="text-xs text-gray-500">{{ $menu->jarak_km }} km</span>
                    </div>
                    @endif
                    <span class="inline-flex items-center gap-1 mt-1.5 text-xs px-2 py-0.5 rounded-full
                        {{ $st === 'certified' ? 'bg-green-100 text-green-700' :
                          ($st === 'self_claimed' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-500') }}">
                        <div class="w-1.5 h-1.5 rounded-full {{ $st === 'certified' ? 'bg-green-500' : ($st === 'self_claimed' ? 'bg-yellow-400' : 'bg-gray-400') }}"></div>
                        {{ $st === 'certified' ? 'Bersertifikat' : ($st === 'self_claimed' ? 'Klaim Halal' : 'Belum Terverifikasi') }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $menuResults->links() }}</div>
        @endif
    </div>

    {{-- Restoran --}}
    <div>
        <div class="flex items-center gap-2 mb-3">
            {{-- Icon gedung (CSS) --}}
            <div class="flex flex-col items-center gap-px">
                <div class="w-6 h-1 bg-gray-700 rounded-sm"></div>
                <div class="flex gap-px">
                    <div class="w-1.5 h-4 bg-gray-700 rounded-sm"></div>
                    <div class="w-1.5 h-4 bg-gray-700 rounded-sm"></div>
                    <div class="w-1.5 h-4 bg-gray-700 rounded-sm"></div>
                </div>
            </div>
            <h2 class="font-bold text-gray-800">
                Restoran
                @if($search)<span class="font-normal text-gray-400 text-sm">untuk "{{ $search }}"</span>@endif
                <span class="font-normal text-gray-400 text-sm ml-1">({{ $restorans->total() }})</span>
            </h2>
        </div>

        @if($restorans->isEmpty())
        <p class="text-sm text-gray-400 py-4 pl-1">Tidak ada restoran ditemukan.</p>
        @else
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($restorans as $resto)
            @php $st = $resto->status_halal; @endphp
            <a href="{{ route('restoran.show', $resto->id_restoran) }}"
               class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition block group">
                <div class="h-28 bg-gray-100 overflow-hidden">
                    <img src="{{ ImageHelper::restoran($resto->nama_restoran) }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                         alt="{{ $resto->nama_restoran }}">
                </div>
                <div class="p-3">
                    <p class="font-semibold text-sm text-gray-800 leading-tight line-clamp-2">{{ $resto->nama_restoran }}</p>
                    <p class="text-xs text-gray-400 mt-0.5 truncate">{{ $resto->kota }}</p>
                    @if(isset($resto->jarak_km))
                    <div class="flex items-center gap-1 mt-1">
                        <div class="w-1.5 h-1.5 rounded-full bg-[#2D6A4F]"></div>
                        <span class="text-xs text-gray-500">{{ $resto->jarak_km }} km</span>
                    </div>
                    @endif
                    <div class="flex items-center gap-1 mt-1">
                        {{-- Bintang (CSS) --}}
                        <div class="w-2.5 h-2.5 bg-yellow-400" style="clip-path:polygon(50% 0%,61% 35%,98% 35%,68% 57%,79% 91%,50% 70%,21% 91%,32% 57%,2% 35%,39% 35%)"></div>
                        <span class="text-xs text-gray-500">{{ number_format($resto->ulasan_avg_rating ?? $resto->rating ?? 0,1) }}</span>
                    </div>
                    <span class="inline-flex items-center gap-1 mt-1.5 text-xs px-2 py-0.5 rounded-full
                        {{ $st === 'certified' ? 'bg-green-100 text-green-700' :
                          ($st === 'self_claimed' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-500') }}">
                        <div class="w-1.5 h-1.5 rounded-full {{ $st === 'certified' ? 'bg-green-500' : ($st === 'self_claimed' ? 'bg-yellow-400' : 'bg-gray-400') }}"></div>
                        {{ $st === 'certified' ? 'Bersertifikat' : ($st === 'self_claimed' ? 'Klaim Halal' : 'Belum Terverifikasi') }}
                    </span>
                </div>
            </a>
            @endforeach
        </div>
        <div class="mt-4">{{ $restorans->links() }}</div>
        @endif
    </div>

    @if($restorans->isEmpty() && $menuResults->isEmpty())
    <div class="text-center py-16">
        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
            <div class="w-8 h-8 rounded-full border-2 border-gray-300 flex items-end justify-center pb-1">
                <div class="w-5 h-px bg-gray-300 rounded"></div>
            </div>
        </div>
        <p class="text-gray-500 font-medium">Tidak ada hasil ditemukan</p>
        <p class="text-gray-400 text-sm mt-1">Coba kata kunci atau filter lain</p>
        <a href="/dashboard" class="mt-4 inline-block text-sm text-[#2D6A4F] font-medium hover:underline">Kembali ke semua</a>
    </div>
    @endif
</div>

{{-- ══════════════════════════════════════════
     TAMPILAN NORMAL
══════════════════════════════════════════ --}}
@else
<div class="px-4 md:px-16 lg:px-32 mt-5 grid grid-cols-1 lg:grid-cols-3 gap-6 pb-10">

    {{-- Kolom Kiri --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Rekomendasi AI --}}
        @if($rekomendasiAI)
        @php $restoAI = $rekomendasiAI->restoran; $stAI = $restoAI->status_halal ?? 'none'; @endphp
        <div>
            <div class="flex justify-between items-center mb-3">
                <h2 class="font-bold text-gray-800">Rekomendasi untukmu</h2>
                {{-- Badge AI --}}
                <span class="inline-flex items-center gap-1.5 text-xs bg-green-50 text-[#2D6A4F] border border-green-200 px-2.5 py-1 rounded-full font-medium">
                    <div class="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse"></div>
                    AI Hybrid
                </span>
            </div>
            <div onclick="bukaModalMenu(this)"
                 data-nama="{{ $rekomendasiAI->nama_menu }}"
                 data-menu-id="{{ $rekomendasiAI->id_menu }}"
                 data-foto="{{ $rekomendasiAI->foto_menu ?? ImageHelper::restoran($restoAI->nama_restoran ?? 'default') }}"
                 data-deskripsi="{{ $rekomendasiAI->deskripsi ?? '' }}"
                 data-harga="{{ $rekomendasiAI->harga }}"
                 data-resto-nama="{{ $restoAI->nama_restoran ?? '' }}"
                 data-resto-id="{{ $rekomendasiAI->id_restoran }}"
                 data-halal="{{ $stAI }}"
                 data-jarak="{{ $restoAI->jarak_km ?? '' }}"
                 class="bg-white rounded-2xl overflow-hidden shadow-sm cursor-pointer group hover:shadow-md transition">
                <div class="h-44 md:h-64 bg-gray-200 relative overflow-hidden">
                    <img src="{{ $rekomendasiAI->foto_menu ?? ImageHelper::restoran($restoAI->nama_restoran ?? 'default') }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                         alt="{{ $rekomendasiAI->nama_menu }}">
                    {{-- Halal badge overlay --}}
                    <span class="absolute bottom-3 left-3 inline-flex items-center gap-1.5 bg-white/90 text-xs px-3 py-1.5 rounded-full font-medium shadow-sm
                        {{ $stAI === 'certified' ? 'text-green-700' : ($stAI === 'self_claimed' ? 'text-yellow-700' : 'text-gray-600') }}">
                        <div class="w-1.5 h-1.5 rounded-full {{ $stAI === 'certified' ? 'bg-green-500' : ($stAI === 'self_claimed' ? 'bg-yellow-400' : 'bg-gray-400') }}"></div>
                        {{ $stAI === 'certified' ? 'Bersertifikat Halal' : ($stAI === 'self_claimed' ? 'Klaim Halal' : 'Belum Terverifikasi') }}
                        @if(isset($rekomendasiAI->ai_score))
                        <span class="ml-1 text-[#2D6A4F] font-bold">{{ round($rekomendasiAI->ai_score * 100) }}% match</span>
                        @endif
                    </span>
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-lg text-gray-800">{{ $rekomendasiAI->nama_menu }}</h3>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $restoAI->nama_restoran ?? '' }}</p>
                    <p class="text-xs text-gray-500 mt-2 line-clamp-2 leading-relaxed">{{ $rekomendasiAI->deskripsi }}</p>
                    <p class="text-[#2D6A4F] font-bold text-xl mt-3">Rp {{ number_format($rekomendasiAI->harga, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        @endif

        {{-- Populer Hari Ini — AI TREND --}}
        <div>
            <div class="flex justify-between items-center mb-3">
                <h2 class="font-bold text-gray-800">🔥 Populer hari ini</h2>
                <span class="text-xs bg-green-100 text-[#2D6A4F] px-2 py-1 rounded-full font-medium">
                    AI Trend
                </span>
            </div>

            <div class="flex gap-3 overflow-x-auto pb-2 no-scrollbar">
                @foreach($populer as $menu)
                @php $stm = $menu->restoran->status_halal ?? 'none'; @endphp
                <div onclick="bukaModalMenu(this)"
                     data-nama="{{ $menu->nama_menu }}"
                     data-menu-id="{{ $menu->id_menu }}"
                     data-foto="{{ $menu->foto_menu ?? ImageHelper::restoran($menu->restoran->nama_restoran ?? 'default') }}"
                     data-deskripsi="{{ $menu->deskripsi ?? '' }}"
                     data-harga="{{ $menu->harga }}"
                     data-resto-nama="{{ $menu->restoran->nama_restoran ?? '' }}"
                     data-resto-id="{{ $menu->id_restoran }}"
                     data-halal="{{ $stm }}"
                     data-jarak="{{ $menu->restoran->jarak_km ?? '' }}"
                     class="bg-white rounded-2xl overflow-hidden shadow-sm shrink-0 w-40 cursor-pointer hover:shadow-md transition group">
                    <div class="h-24 bg-gray-100 overflow-hidden">
                        <img src="{{ $menu->foto_menu ?? ImageHelper::restoran($menu->restoran->nama_restoran ?? 'default') }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                             alt="{{ $menu->nama_menu }}">
                    </div>
                    <div class="p-2.5">
                        <p class="font-semibold text-xs text-gray-800 leading-tight line-clamp-2">{{ $menu->nama_menu }}</p>
                        <p class="text-xs text-gray-400 truncate mt-0.5">{{ $menu->restoran->nama_restoran ?? '' }}</p>
                        <p class="text-[#2D6A4F] font-bold text-xs mt-1">Rp {{ number_format($menu->harga, 0, ',', '.') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Terdekat mobile --}}
        <div class="lg:hidden">
            <div class="flex justify-between items-center mb-3">
                <h2 class="font-bold text-gray-800">Terdekat darimu</h2>
                @if(!$hasLokasi)
                <button onclick="mintaLokasi()"
                    class="text-xs text-[#2D6A4F] font-medium border border-[#2D6A4F] px-3 py-1 rounded-full hover:bg-[#2D6A4F] hover:text-white transition">
                    Izinkan Lokasi
                </button>
                @else
                <span class="inline-flex items-center gap-1.5 text-xs bg-blue-50 text-blue-600 border border-blue-200 px-2.5 py-1 rounded-full font-medium">
                    <div class="flex gap-px items-end h-3">
                        <div class="w-1 bg-blue-400 rounded-sm" style="height:30%"></div>
                        <div class="w-1 bg-blue-500 rounded-sm" style="height:60%"></div>
                        <div class="w-1 bg-blue-600 rounded-sm" style="height:100%"></div>
                    </div>
                    TOPSIS
                </span>
                @endif
            </div>
            <div class="space-y-3">
                @foreach($terdekat as $resto)
                @php $str = $resto->status_halal ?? 'none'; @endphp
                <a href="{{ route('restoran.show', $resto->id_restoran) }}"
                   class="bg-white rounded-2xl p-3 flex items-center gap-3 shadow-sm hover:shadow-md transition block">
                    <div class="w-10 h-10 bg-gray-100 rounded-xl flex-shrink-0 overflow-hidden">
                        <img src="{{ ImageHelper::restoran($resto->nama_restoran) }}"
                             class="w-full h-full object-cover" alt="{{ $resto->nama_restoran }}">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-800 text-sm truncate">{{ $resto->nama_restoran }}</p>
                        <div class="flex items-center gap-2 mt-0.5">
                            <div class="flex items-center gap-0.5">
                                <div class="w-2 h-2 bg-yellow-400" style="clip-path:polygon(50% 0%,61% 35%,98% 35%,68% 57%,79% 91%,50% 70%,21% 91%,32% 57%,2% 35%,39% 35%)"></div>
                                <span class="text-xs text-gray-500">{{ number_format($resto->ulasan_avg_rating ?? $resto->rating ?? 0,1) }}</span>
                            </div>
                            <span class="text-gray-300">·</span>
                            @if($hasLokasi && isset($resto->jarak_km) && $resto->jarak_km < 999)
                            <div class="flex items-center gap-0.5">
                                <div class="w-2 h-2 bg-[#2D6A4F] rounded-full"></div>
                                <span class="text-xs text-gray-500">{{ $resto->jarak_km }} km</span>
                            </div>
                            @else
                            <span class="text-xs text-gray-400">{{ $resto->kota }}</span>
                            @endif
                        </div>
                    </div>
                    {{-- Chevron right (CSS) --}}
                    <div class="w-4 h-4 flex items-center justify-center flex-shrink-0">
                        <div class="w-1.5 h-1.5 border-t-2 border-r-2 border-gray-300 rotate-45"></div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>

    </div>

    {{-- Kolom Kanan Desktop — Terdekat --}}
    <div class="hidden lg:block">
        <div class="flex justify-between items-center mb-3">
            <h2 class="font-bold text-gray-800">Terdekat darimu</h2>
            @if(!$hasLokasi)
            <button onclick="mintaLokasi()"
                class="text-xs text-[#2D6A4F] font-medium border border-[#2D6A4F] px-3 py-1 rounded-full hover:bg-[#2D6A4F] hover:text-white transition">
                Izinkan Lokasi
            </button>
            @else
            <span class="inline-flex items-center gap-1.5 text-xs bg-blue-50 text-blue-600 border border-blue-200 px-2.5 py-1 rounded-full font-medium">
                <div class="flex gap-px items-end h-3">
                    <div class="w-1 bg-blue-400 rounded-sm" style="height:30%"></div>
                    <div class="w-1 bg-blue-500 rounded-sm" style="height:60%"></div>
                    <div class="w-1 bg-blue-600 rounded-sm" style="height:100%"></div>
                </div>
                TOPSIS
            </span>
            @endif
        </div>

        @if(!$hasLokasi)
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 text-center mb-4">
            {{-- Lokasi pin (CSS) --}}
            <div class="w-10 h-10 mx-auto mb-3 flex items-center justify-center">
                <div class="relative">
                    <div class="w-6 h-6 rounded-full border-2 border-amber-400 bg-amber-100"></div>
                    <div class="w-0 h-0 border-l-[6px] border-r-[6px] border-t-[8px] border-l-transparent border-r-transparent border-t-amber-400 mx-auto"></div>
                    <div class="w-1.5 h-1.5 rounded-full bg-amber-500 absolute top-1.5 left-1/2 -translate-x-1/2"></div>
                </div>
            </div>
            <p class="text-sm text-amber-700 font-semibold">Aktifkan lokasi</p>
            <p class="text-xs text-amber-600 mt-1">Supaya bisa lihat restoran terdekat dan jaraknya</p>
            <button onclick="mintaLokasi()"
                class="mt-3 bg-[#2D6A4F] text-white text-xs px-5 py-2 rounded-full hover:bg-[#235c42] transition font-medium">
                Izinkan Akses Lokasi
            </button>
        </div>
        @endif

        <div class="space-y-3">
            @foreach($terdekat as $resto)
            @php $str = $resto->status_halal ?? 'none'; @endphp
            <a href="{{ route('restoran.show', $resto->id_restoran) }}"
               class="bg-white rounded-2xl p-4 flex items-center gap-3 shadow-sm hover:shadow-md transition block group">
                <div class="w-12 h-12 bg-gray-100 rounded-xl flex-shrink-0 overflow-hidden">
                    <img src="{{ ImageHelper::restoran($resto->nama_restoran) }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                         alt="{{ $resto->nama_restoran }}">
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-800 text-sm truncate">{{ $resto->nama_restoran }}</p>
                    <div class="flex items-center gap-2 mt-0.5">
                        <div class="flex items-center gap-0.5">
                            <div class="w-2.5 h-2.5 bg-yellow-400" style="clip-path:polygon(50% 0%,61% 35%,98% 35%,68% 57%,79% 91%,50% 70%,21% 91%,32% 57%,2% 35%,39% 35%)"></div>
                            <span class="text-xs text-gray-500">{{ number_format($resto->ulasan_avg_rating ?? $resto->rating ?? 0,1) }}</span>
                        </div>
                        <span class="text-gray-300">·</span>
                        @if($hasLokasi && isset($resto->jarak_km) && $resto->jarak_km < 999)
                        <div class="flex items-center gap-1">
                            <div class="w-1.5 h-1.5 rounded-full bg-[#2D6A4F]"></div>
                            <span class="text-xs text-gray-500 font-medium">{{ $resto->jarak_km }} km</span>
                        </div>
                        @else
                        <span class="text-xs text-gray-400">{{ $resto->kota }}</span>
                        @endif
                    </div>
                    <span class="inline-flex items-center gap-1 mt-1 text-xs px-2 py-0.5 rounded-full
                        {{ $str === 'certified' ? 'bg-green-100 text-green-700' :
                          ($str === 'self_claimed' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-500') }}">
                        <div class="w-1.5 h-1.5 rounded-full {{ $str === 'certified' ? 'bg-green-500' : ($str === 'self_claimed' ? 'bg-yellow-400' : 'bg-gray-400') }}"></div>
                        {{ $str === 'certified' ? 'Bersertifikat' : ($str === 'self_claimed' ? 'Klaim Halal' : 'Belum Terverifikasi') }}
                    </span>
                </div>
                <div class="w-4 h-4 flex items-center justify-center flex-shrink-0">
                    <div class="w-1.5 h-1.5 border-t-2 border-r-2 border-gray-300 rotate-45"></div>
                </div>
            </a>
            @endforeach
        </div>
    </div>

</div>
@endif

{{-- ══════════════════════════════════════════
     MODAL DETAIL MENU
══════════════════════════════════════════ --}}
<div id="modalMenu" class="fixed inset-0 z-50 hidden items-end md:items-center justify-center">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="tutupModalMenu()"></div>

    <div class="relative bg-white w-full md:w-[480px] md:rounded-3xl rounded-t-3xl overflow-hidden shadow-2xl z-10
                max-h-[90vh] flex flex-col" id="modalPanel">

        {{-- Foto --}}
        <div class="h-56 bg-gray-200 relative flex-shrink-0 overflow-hidden">
            <img id="modalFoto" src="" alt="" class="w-full h-full object-cover">

            {{-- Tombol tutup (CSS X) --}}
            <button onclick="tutupModalMenu()"
                class="absolute top-3 right-3 w-8 h-8 rounded-full bg-black/40 hover:bg-black/60 transition flex items-center justify-center">
                <div class="relative w-3 h-3">
                    <div class="absolute inset-0 flex items-center"><div class="w-full h-0.5 bg-white rotate-45"></div></div>
                    <div class="absolute inset-0 flex items-center"><div class="w-full h-0.5 bg-white -rotate-45"></div></div>
                </div>
            </button>

            {{-- Halal badge --}}
            <span id="modalHalalBadge" class="absolute bottom-3 left-3 text-xs px-3 py-1 rounded-full font-medium inline-flex items-center gap-1.5"></span>
        </div>

        {{-- Konten --}}
        <div class="p-5 overflow-y-auto flex-1">
            <h3 id="modalNama" class="font-bold text-xl text-gray-800 leading-tight"></h3>
            <p id="modalRestoNama" class="text-sm text-gray-400 mt-1"></p>
            <p id="modalJarak" class="text-xs text-[#2D6A4F] font-medium mt-0.5 hidden"></p>
            <div class="w-full h-px bg-gray-100 my-3"></div>
            <p id="modalDeskripsi" class="text-sm text-gray-600 leading-relaxed"></p>
            <p id="modalHarga" class="text-[#2D6A4F] font-bold text-2xl mt-4"></p>
        </div>

        {{-- Aksi --}}
        <div class="p-4 border-t border-gray-100 flex-shrink-0">
            <button
                id="btnFavoritMenu"
                onclick="toggleFavoritMenu()"
                class="w-full mb-2 border border-pink-400 text-pink-500 hover:bg-pink-50 py-3 rounded-2xl font-semibold transition">
                Favorit
            </button>
            <a id="modalLihatUsaha" href="#"
               class="w-full bg-[#2D6A4F] text-white text-center py-3 rounded-2xl font-semibold text-sm hover:bg-[#235c42] transition flex items-center justify-center gap-2">
                {{-- Icon gedung kecil --}}
                <div class="flex flex-col items-center gap-px">
                    <div class="w-4 h-0.5 bg-white rounded"></div>
                    <div class="flex gap-px">
                        <div class="w-1 h-2.5 bg-white rounded-sm"></div>
                        <div class="w-1 h-2.5 bg-white rounded-sm"></div>
                        <div class="w-1 h-2.5 bg-white rounded-sm"></div>
                    </div>
                </div>
                Lihat Usaha
            </a>
        </div>
    </div>
</div>

{{-- Profile Overlay & Popup --}}
<div id="profileOverlay" onclick="toggleProfile()" class="fixed inset-0 bg-black/30 z-40 hidden"></div>
<div id="profilePopup"
    class="fixed top-16 right-4 md:right-16 lg:right-32 bg-white rounded-2xl shadow-xl z-50 w-72 hidden">
    <div class="flex items-center gap-4 px-5 py-4 border-b border-gray-100">
        <div class="w-14 h-14 rounded-full bg-[#2D6A4F] text-white font-bold flex items-center justify-center text-lg flex-shrink-0">
            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
        </div>
        <div class="min-w-0">
            <p class="font-bold text-gray-800">{{ explode(' ', Auth::user()->name)[0] }}</p>
            <p class="text-sm text-gray-400 truncate">{{ Auth::user()->email }}</p>
        </div>
    </div>
    <div class="py-2">
        <a href="{{ route('profile.index') }}"     class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50 transition text-sm font-semibold text-gray-700">Profil Saya</a>
        <a href="{{ route('profile.favorit') }}"   class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50 transition text-sm font-semibold text-gray-700">Favorit</a>
        <a href="{{ route('profile.preferensi') }}" class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50 transition text-sm font-semibold text-gray-700">Preferensi Makanan</a>
        <a href="{{ route('profile.pengaturan') }}" class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50 transition text-sm font-semibold text-gray-700">Pengaturan</a>
    </div>
    <div class="border-t border-gray-100 py-2">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-5 py-3 hover:bg-red-50 transition text-left text-sm font-semibold text-red-500">
                Keluar
            </button>
        </form>
    </div>
</div>

<style>
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

#modalPanel {
    animation: slideup 0.28s cubic-bezier(.32,.72,0,1) both;
}
@keyframes slideup {
    from { transform: translateY(48px); opacity: 0; }
    to   { transform: translateY(0);    opacity: 1; }
}
</style>

<script>
// ── MODAL ────────────────────────────────────────────────────

function bukaModalMenu(el) {
    const d = el.dataset;
    window.menuFavoritAktif = d.menuId;

    document.getElementById('modalFoto').src              = d.foto || '';
    document.getElementById('modalFoto').alt              = d.nama || '';
    document.getElementById('modalNama').textContent      = d.nama || '';
    document.getElementById('modalRestoNama').textContent = d.restoNama || '';
    document.getElementById('modalDeskripsi').textContent = d.deskripsi || 'Tidak ada deskripsi.';
    document.getElementById('modalHarga').textContent     =
        'Rp ' + parseInt(d.harga || 0).toLocaleString('id-ID');
    document.getElementById('modalLihatUsaha').href       = `/restoran/${d.restoId}`;

    // Jarak
    const jarakEl = document.getElementById('modalJarak');
    if (d.jarak) {
        jarakEl.textContent = d.jarak + ' km dari lokasimu';
        jarakEl.classList.remove('hidden');
    } else {
        jarakEl.classList.add('hidden');
    }

    // Reset tombol favorit setiap modal dibuka
    const btnFav = document.getElementById('btnFavoritMenu');
    btnFav.innerHTML = '♡ Favorit';
    btnFav.className = 'w-full mb-2 border border-pink-400 text-pink-500 hover:bg-pink-50 py-3 rounded-2xl font-semibold transition';

    // Halal badge
    const badge = document.getElementById('modalHalalBadge');
    const dot   = `<div class="w-1.5 h-1.5 rounded-full `;
    if (d.halal === 'certified') {
        badge.innerHTML   = dot + `bg-green-500"></div> Bersertifikat Halal`;
        badge.className   = 'absolute bottom-3 left-3 text-xs px-3 py-1 rounded-full font-medium inline-flex items-center gap-1.5 bg-green-100 text-green-700';
    } else if (d.halal === 'self_claimed') {
        badge.innerHTML   = dot + `bg-yellow-400"></div> Klaim Halal`;
        badge.className   = 'absolute bottom-3 left-3 text-xs px-3 py-1 rounded-full font-medium inline-flex items-center gap-1.5 bg-yellow-100 text-yellow-700';
    } else {
        badge.innerHTML   = dot + `bg-gray-400"></div> Belum Terverifikasi`;
        badge.className   = 'absolute bottom-3 left-3 text-xs px-3 py-1 rounded-full font-medium inline-flex items-center gap-1.5 bg-gray-100 text-gray-500';
    }

    const modal = document.getElementById('modalMenu');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function tutupModalMenu() {
    const modal = document.getElementById('modalMenu');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = '';
}

function toggleFavoritMenu() {
    if (!window.menuFavoritAktif) return;

    fetch('/favorit/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ id_menu: window.menuFavoritAktif })
    })
    .then(r => r.json())
    .then(data => {
        const btn = document.getElementById('btnFavoritMenu');
        if (data.status === 'tambah') {
            btn.innerHTML = 'Favorit';
            btn.className = 'w-full mb-2 bg-pink-100 border border-pink-400 text-pink-600 py-3 rounded-2xl font-semibold transition';
        } else {
            btn.innerHTML = 'Favorit';
            btn.className = 'w-full mb-2 border border-pink-400 text-pink-500 hover:bg-pink-50 py-3 rounded-2xl font-semibold transition';
        }
    })
    .catch(err => {
        console.error(err);
        alert('Gagal memperbarui favorit, coba lagi.');
    });
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') tutupModalMenu(); });


// ── GPS ──────────────────────────────────────────────────────

function mintaLokasi() {
    if (!navigator.geolocation) { alert('Browser tidak mendukung GPS.'); return; }
    navigator.geolocation.getCurrentPosition(
        pos => {
            const url = new URL(window.location.href);
            url.searchParams.set('lat', pos.coords.latitude);
            url.searchParams.set('lng', pos.coords.longitude);
            window.location.href = url.toString();
        },
        () => localStorage.setItem('lokasiDitolak', '1'),
        { enableHighAccuracy: true, timeout: 8000 }
    );
}

@if(!$hasLokasi)
window.addEventListener('load', () => {
    if (!localStorage.getItem('lokasiDitolak') && navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            pos => {
                const url = new URL(window.location.href);
                url.searchParams.set('lat', pos.coords.latitude);
                url.searchParams.set('lng', pos.coords.longitude);
                window.location.href = url.toString();
            },
            () => localStorage.setItem('lokasiDitolak', '1')
        );
    }
});
@endif


// ── PROFILE ──────────────────────────────────────────────────

function toggleProfile() {
    document.getElementById('profilePopup').classList.toggle('hidden');
    document.getElementById('profileOverlay').classList.toggle('hidden');
}
</script>

@endsection