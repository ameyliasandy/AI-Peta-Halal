@extends('layouts.app')
@section('title', 'Dashboard - Petha')
@section('content')

    {{-- Header Hijau --}}
    <div class="bg-[#2D6A4F] text-white px-6 md:px-16 lg:px-32 pt-10 pb-16 rounded-b-3xl">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xl font-semibold">Petha</p>
            <div class="w-10 h-10 rounded-full bg-green-300 text-[#2D6A4F] font-bold flex items-center justify-center text-sm">
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
        </div>
        <h1 class="text-2xl md:text-3xl font-bold">Hallo, {{ explode(' ', Auth::user()->name)[0] }}! 👋</h1>
        <p class="text-sm opacity-80 mt-1">Mau makan apa hari ini?</p>

        {{-- Search --}}
        <div class="mt-5 bg-white/20 rounded-full flex items-center px-5 py-3 max-w-2xl">
            <span class="mr-3">🔍</span>
            <input type="text" placeholder="Cari makanan halal..."
                class="flex-1 bg-transparent outline-none text-sm text-white placeholder-white/70">
        </div>
    </div>

    {{-- Filter Chips --}}
    <div class="px-6 md:px-16 lg:px-32 mt-5 flex gap-2 overflow-x-auto pb-1">
        @foreach(['Semua', 'Pedas', 'Murah', 'Terdekat', 'Favorit'] as $filter)
        <button class="px-5 py-2 rounded-full text-sm font-medium whitespace-nowrap transition
            {{ $filter === 'Semua' ? 'bg-[#2D6A4F] text-white' : 'bg-white text-gray-600 border border-gray-200 hover:border-[#2D6A4F] hover:text-[#2D6A4F]' }}">
            {{ $filter }}
        </button>
        @endforeach
    </div>

    {{-- Main Content Grid (2 kolom di desktop) --}}
    <div class="px-6 md:px-16 lg:px-32 mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kolom Kiri (2/3) --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Rekomendasi Untukmu --}}
            <div>
                <div class="flex justify-between items-center mb-3">
                    <h2 class="font-bold text-gray-800 text-lg">✨ Rekomendasi untukmu</h2>
                    <a href="#" class="text-sm text-gray-400 hover:text-[#2D6A4F]">Lihat semua</a>
                </div>
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm">
                    <div class="h-56 md:h-72 bg-gray-200 overflow-hidden relative">
                        <div class="w-full h-full flex items-center justify-center text-gray-400 text-6xl">🍽️</div>
                        <span class="absolute bottom-3 left-3 bg-white/80 text-xs px-3 py-1 rounded-full">
                            🍽️ Cocok dengan seleramu
                        </span>
                    </div>
                    <div class="p-5">
                        <h3 class="font-bold text-2xl text-gray-800">Ayam Geprek</h3>
                        <div class="flex items-center gap-4 text-sm text-gray-500 mt-2">
                            <span>⭐ 4.8</span>
                            <span>📍 1.2 km</span>
                            <span>🕐 15 menit</span>
                        </div>
                        <div class="flex items-center justify-between mt-4">
                            <span class="text-[#2D6A4F] font-bold text-2xl">Rp. 15.000</span>
                            <button class="border border-gray-300 px-6 py-2 rounded-full text-sm font-medium hover:bg-[#2D6A4F] hover:text-white hover:border-[#2D6A4F] transition">
                                Lihat Lokasi
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Populer Hari Ini --}}
            <div>
                <div class="flex justify-between items-center mb-3">
                    <h2 class="font-bold text-gray-800 text-lg">🔥 Populer hari ini</h2>
                    <a href="#" class="text-sm text-gray-400 hover:text-[#2D6A4F]">Lihat semua</a>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 gap-4">
                    <x-food-card name="Nasi Padang Sederharana" price="Rp. 18.000" rating="4.8" />
                    <x-food-card name="Mie Ayam Jamur Spesial" price="Rp. 15.000" rating="4.7" />
                    <x-food-card name="Bakso Malang" price="Rp. 15.000" rating="4.6" />
                </div>
            </div>

        </div>

        {{-- Kolom Kanan (1/3) - Terdekat Darimu --}}
        <div>
            <div class="flex justify-between items-center mb-3">
                <h2 class="font-bold text-gray-800 text-lg">📍 Terdekat darimu</h2>
                <a href="#" class="text-sm text-gray-400 hover:text-[#2D6A4F]">Lihat peta</a>
            </div>
            <div class="space-y-3">
                @foreach([
                    ['name' => 'Warung Nasi Sederhana', 'rating' => '4.7', 'distance' => '0.4 km'],
                    ['name' => 'Ayam Gepuk Pak Gembus', 'rating' => '4.7', 'distance' => '1.5 km'],
                    ['name' => 'Bakso Barcelona', 'rating' => '4.6', 'distance' => '2.1 km'],
                    ['name' => 'Sate Madura Pak Dul', 'rating' => '4.5', 'distance' => '2.8 km'],
                    ['name' => 'Nasi Goreng Bang Udin', 'rating' => '4.4', 'distance' => '3.2 km'],
                ] as $warung)
                <div class="bg-white rounded-2xl p-4 flex items-center gap-3 shadow-sm hover:shadow-md transition cursor-pointer">
                    <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center text-2xl flex-shrink-0">🍳</div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-800 text-sm truncate">{{ $warung['name'] }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">⭐ {{ $warung['rating'] }} · 📍 {{ $warung['distance'] }}</p>
                    </div>
                    <span class="text-gray-400 text-lg flex-shrink-0">→</span>
                </div>
                @endforeach
            </div>
        </div>

    </div>

    {{-- Bottom spacing --}}
    <div class="h-10"></div>

@endsection