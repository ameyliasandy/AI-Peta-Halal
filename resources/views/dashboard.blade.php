@extends('layouts.app')
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
        <div class="mt-4 bg-white/20 rounded-full flex items-center px-4 py-2.5">
            <span class="mr-2 text-sm">🔍</span>
            <input type="text" placeholder="Cari makanan halal..."
                class="flex-1 bg-transparent outline-none text-sm text-white placeholder-white/70">
        </div>
    </div>

    {{-- Filter Chips --}}
    <div class="px-4 md:px-16 lg:px-32 mt-4 flex gap-2 overflow-x-auto pb-1 no-scrollbar">
        @foreach(['Semua', 'Pedas', 'Murah', 'Terdekat', 'Favorit'] as $filter)
        <button class="px-4 py-1.5 rounded-full text-sm font-medium whitespace-nowrap transition
            {{ $filter === 'Semua' ? 'bg-[#2D6A4F] text-white' : 'bg-white text-gray-600 border border-gray-200' }}">
            {{ $filter }}
        </button>
        @endforeach
    </div>

    {{-- Konten: 1 kolom di mobile, 3 kolom di desktop --}}
    <div class="px-4 md:px-16 lg:px-32 mt-5 grid grid-cols-1 lg:grid-cols-3 gap-6 pb-10">

        {{-- Kolom Kiri --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Rekomendasi Untukmu --}}
            <div>
                <div class="flex justify-between items-center mb-3">
                    <h2 class="font-bold text-gray-800">✨ Rekomendasi untukmu</h2>
                    <a href="#" class="text-sm text-gray-400">Lihat semua</a>
                </div>
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm">
                    <div class="h-44 md:h-64 bg-gray-200 relative">
                        <div class="w-full h-full flex items-center justify-center text-5xl">🍽️</div>
                        <span class="absolute bottom-3 left-3 bg-white/80 text-xs px-3 py-1 rounded-full">
                            🍽️ Cocok dengan seleramu
                        </span>
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-lg text-gray-800">Ayam Geprek</h3>
                        <div class="flex items-center gap-3 text-xs text-gray-500 mt-1">
                            <span>⭐ 4.8</span>
                            <span>📍 1.2 km</span>
                            <span>🕐 15 menit</span>
                        </div>
                        <div class="flex items-center justify-between mt-3">
                            <span class="text-[#2D6A4F] font-bold text-lg">Rp. 15.000</span>
                            <button class="border border-gray-300 px-4 py-1.5 rounded-full text-sm font-medium hover:bg-[#2D6A4F] hover:text-white transition">
                                Lihat Lokasi
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Populer Hari Ini --}}
            <div>
                <div class="flex justify-between items-center mb-3">
                    <h2 class="font-bold text-gray-800">🔥 Populer hari ini</h2>
                    <a href="#" class="text-sm text-gray-400">Lihat semua</a>
                </div>
                <div class="flex gap-3 overflow-x-auto pb-2 no-scrollbar">
                    <x-food-card name="Nasi Padang Sederhana" price="Rp. 18.000" rating="4.8" />
                    <x-food-card name="Mie Ayam Jamur Spesial" price="Rp. 15.000" rating="4.7" />
                    <x-food-card name="Bakso Malang" price="Rp. 15.000" rating="4.6" />
                </div>
            </div>

            {{-- Terdekat Darimu (mobile: di bawah populer, desktop: kolom kanan) --}}
            <div class="lg:hidden">
                <div class="flex justify-between items-center mb-3">
                    <h2 class="font-bold text-gray-800">📍 Terdekat darimu</h2>
                    <a href="#" class="text-sm text-gray-400">Lihat peta</a>
                </div>
                <div class="space-y-3">
                    @foreach([
                        ['name' => 'Warung Nasi Sederhana', 'rating' => '4.7', 'distance' => '0.4 km'],
                        ['name' => 'Ayam Gepuk Pak Gembus', 'rating' => '4.7', 'distance' => '1.5 km'],
                        ['name' => 'Bakso Barcelona', 'rating' => '4.6', 'distance' => '2.1 km'],
                    ] as $warung)
                    <div class="bg-white rounded-2xl p-3 flex items-center gap-3 shadow-sm">
                        <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center text-xl flex-shrink-0">🍳</div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-800 text-sm truncate">{{ $warung['name'] }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">⭐ {{ $warung['rating'] }} · 📍 {{ $warung['distance'] }}</p>
                        </div>
                        <span class="text-gray-400">→</span>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- Kolom Kanan - hanya tampil di desktop --}}
        <div class="hidden lg:block">
            <div class="flex justify-between items-center mb-3">
                <h2 class="font-bold text-gray-800">📍 Terdekat darimu</h2>
                <a href="#" class="text-sm text-gray-400">Lihat peta</a>
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
            <a href="#" class="flex items-center gap-4 px-5 py-3 hover:bg-gray-50 transition">
                <span class="text-[#2D6A4F] text-xl">👤</span>
                <span class="font-semibold text-gray-800">Profil Saya</span>
            </a>
            <a href="#" class="flex items-center gap-4 px-5 py-3 hover:bg-gray-50 transition">
                <span class="text-[#2D6A4F] text-xl">⭐</span>
                <span class="font-semibold text-gray-800">Favorit</span>
            </a>
            <a href="#" class="flex items-center gap-4 px-5 py-3 hover:bg-gray-50 transition">
                <span class="text-[#2D6A4F] text-xl">🍽️</span>
                <span class="font-semibold text-gray-800">Preferensi Makanan</span>
            </a>
            <a href="#" class="flex items-center gap-4 px-5 py-3 hover:bg-gray-50 transition">
                <span class="text-[#2D6A4F] text-xl">⚙️</span>
                <span class="font-semibold text-gray-800">Pengaturan</span>
            </a>
        </div>

        <div class="border-t border-gray-100 py-2">
            <form method="POST" action="/logout">
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