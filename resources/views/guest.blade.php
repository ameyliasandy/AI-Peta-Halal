@extends('layouts.app')

@php use App\Helpers\ImageHelper; @endphp

@section('title', 'Petha - Temukan Makanan Halal')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

{{-- NAVBAR --}}
<nav class="bg-white shadow-sm px-4 sm:px-8 md:px-12 lg:px-24 py-3 flex items-center justify-between sticky top-0 z-40 w-full">
    <h1 class="text-xl font-bold text-[#2D6A4F]">Petha</h1>
    <a href="/login"
        class="text-xs sm:text-sm font-medium text-[#2D6A4F] border border-[#2D6A4F] px-3 sm:px-4 py-1.5 sm:py-2 rounded-full hover:bg-[#2D6A4F] hover:text-white transition whitespace-nowrap">
        Masuk
    </a>
</nav>

{{-- HERO --}}
<section class="bg-[#2D6A4F] text-white px-4 sm:px-8 md:px-12 lg:px-24 pt-8 pb-10 w-full">
    <div class="max-w-3xl">
        <h1 class="text-2xl sm:text-4xl md:text-5xl font-bold leading-tight mb-2">
            Temukan Makanan<br>Halal Favoritmu
        </h1>
        <p class="text-xs sm:text-base opacity-90 mb-5">
            Jelajahi {{ $totalResto }} restoran halal di sekitarmu, {{ $totalCertified }} sudah bersertifikat MUI
        </p>
        <form method="GET" action="/login" class="bg-white rounded-full flex items-center px-4 py-2.5 shadow-md w-full max-w-xl">
            <i class="fa-solid fa-magnifying-glass text-gray-400 mr-3 text-sm"></i>
            <input type="text" placeholder="Cari makanan halal... (login untuk mencari)"
                class="flex-1 outline-none text-sm text-gray-700 bg-transparent min-w-0" readonly
                onclick="window.location.href='/login'">
        </form>
    </div>
</section>

{{-- CONTENT --}}
<section class="px-4 sm:px-8 md:px-12 lg:px-24 py-6 pb-40">

    {{-- KATEGORI --}}
    <div class="mb-8">
        <h2 class="font-bold text-gray-800 text-base sm:text-lg mb-4">Kategori</h2>
        <div class="flex gap-3 overflow-x-auto pb-2 no-scrollbar">
            @foreach([
                ['icon' => 'fa-pepper-hot',   'label' => 'Pedas'],
                ['icon' => 'fa-fish',         'label' => 'Seafood'],
                ['icon' => 'fa-drumstick-bite','label' => 'Ayam & Daging'],
                ['icon' => 'fa-bowl-rice',    'label' => 'Nusantara'],
                ['icon' => 'fa-bread-slice',  'label' => 'Bakery'],
                ['icon' => 'fa-bowl-food',    'label' => 'Mie & Jepang'],
            ] as $kat)
            <div class="flex flex-col items-center shrink-0 w-16 sm:w-20">
                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-[#F5F5F5] flex items-center justify-center shadow-sm">
                    <i class="fa-solid {{ $kat['icon'] }} text-[#2D6A4F] text-base sm:text-lg"></i>
                </div>
                <p class="text-[10px] sm:text-xs text-gray-600 mt-1.5 text-center leading-tight">
                    {{ $kat['label'] }}
                </p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- MAKANAN POPULER --}}
    <div>
        <h2 class="font-bold text-gray-800 text-base sm:text-lg mb-4">Makanan Populer</h2>

        @if($populer->isEmpty())
            <p class="text-sm text-gray-400 text-center py-10">Belum ada menu tersedia.</p>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4">
                @foreach($populer as $menu)
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm">
                    <div class="h-28 sm:h-32 bg-gray-100 overflow-hidden">
                        <img src="{{ $menu->foto_menu ?? ImageHelper::restoran($menu->restoran->nama_restoran) }}"
                             class="w-full h-full object-cover"
                             alt="{{ $menu->nama_menu }}">
                    </div>
                    <div class="p-2.5 sm:p-3">
                        <p class="font-semibold text-xs sm:text-sm text-gray-800 leading-tight line-clamp-2">
                            {{ $menu->nama_menu }}
                        </p>
                        <p class="text-[10px] sm:text-xs text-gray-400 truncate mt-0.5">
                            {{ $menu->restoran->nama_restoran }}
                        </p>
                        <p class="text-green-700 font-bold text-xs sm:text-sm mt-1">
                            Rp {{ number_format($menu->harga, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

</section>

{{-- CTA BOTTOM --}}
<div class="fixed bottom-0 left-0 right-0 bg-[#2D6A4F] px-4 sm:px-8 py-3 sm:py-4 z-40 shadow-lg">
    <div class="max-w-4xl mx-auto flex gap-2 sm:gap-3">
        <a href="/register?role=pencari"
            class="flex-1 text-center bg-white text-[#2D6A4F] py-2.5 sm:py-3 rounded-full text-xs sm:text-sm font-semibold hover:opacity-90 transition">
            Dapatkan Rekomendasi
        </a>
        <a href="/register?role=pemilik_usaha"
            class="flex-1 text-center border border-white text-white py-2.5 sm:py-3 rounded-full text-xs sm:text-sm font-semibold hover:bg-white hover:text-[#2D6A4F] transition">
            Daftarkan Usaha
        </a>
    </div>
</div>

{{-- ONBOARDING --}}
@if(!session('guest_preferensi'))
<div id="onboardingPopup"
    class="fixed inset-0 bg-black/50 flex items-end sm:items-center justify-center z-50 px-0 sm:px-4">

    <div class="bg-[#F5F5F5] w-full sm:max-w-[360px] rounded-t-[28px] sm:rounded-[28px] p-5 pb-8 sm:pb-5 max-h-[90vh] overflow-y-auto">

        <div class="w-10 h-1 bg-gray-300 rounded-full mx-auto mb-4 sm:hidden"></div>

        <span class="text-[11px] bg-green-100 text-[#2D6A4F] px-3 py-1 rounded-full">
            Preferensi Awal
        </span>

        <h2 class="text-lg sm:text-xl font-bold text-[#2D6A4F] mt-3">
            Pilih 3 makanan favoritmu
        </h2>

        <p class="text-xs sm:text-sm text-gray-500 mt-1 mb-2">
            Rekomendasi akan menyesuaikan pilihanmu
        </p>

        <p id="counter" class="text-sm text-[#2D6A4F] mb-3">0/3 dipilih</p>

        <form method="POST" action="/onboarding">
            @csrf
            <div class="grid grid-cols-2 gap-2 mb-4">
                @foreach([
                    'Ayam Geprek', 'Nasi Padang', 'Mie Ayam', 'Bakso',
                    'Sate', 'Seafood', 'Martabak', 'Soto',
                    'Burger Halal', 'Seblak'
                ] as $item)
                <button type="button"
                    onclick="toggle(this)"
                    data-value="{{ $item }}"
                    class="kategori border border-gray-300 py-2 rounded-full text-xs text-gray-700 bg-white transition">
                    {{ $item }}
                </button>
                @endforeach
            </div>

            <div id="hiddenInputs"></div>

            <div class="flex gap-2">
                <button type="button" onclick="skipOnboarding()"
                    class="flex-1 border border-gray-300 py-2.5 rounded-full text-sm text-gray-500">
                    Lewati
                </button>
                <button id="submitBtn" disabled
                    class="flex-1 bg-gray-300 text-white py-2.5 rounded-full text-sm transition">
                    Lanjutkan
                </button>
            </div>
        </form>
    </div>
</div>
@endif

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<script>
let selected = [];

function toggle(el) {
    let value = el.getAttribute('data-value');
    if (selected.includes(value)) {
        selected = selected.filter(v => v !== value);
        el.classList.remove('bg-[#2D6A4F]', 'text-white', 'border-[#2D6A4F]');
    } else {
        if (selected.length >= 3) { alert('Maksimal 3 pilihan'); return; }
        selected.push(value);
        el.classList.add('bg-[#2D6A4F]', 'text-white', 'border-[#2D6A4F]');
    }
    document.getElementById('counter').innerText = selected.length + "/3 dipilih";
    let hidden = '';
    selected.forEach(val => {
        hidden += `<input type="hidden" name="kategori[]" value="${val}">`;
    });
    document.getElementById('hiddenInputs').innerHTML = hidden;
    let btn = document.getElementById('submitBtn');
    if (selected.length === 3) {
        btn.disabled = false;
        btn.classList.remove('bg-gray-300');
        btn.classList.add('bg-[#2D6A4F]');
    } else {
        btn.disabled = true;
        btn.classList.add('bg-gray-300');
        btn.classList.remove('bg-[#2D6A4F]');
    }
}

function skipOnboarding() {
    document.getElementById('onboardingPopup').style.display = 'none';
}
</script>

@endsection