@extends('layouts.app')
@section('title', 'Petha - Temukan Makanan Halal')

@section('content')

{{-- FONT AWESOME --}}
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

{{-- NAVBAR --}}
<nav class="bg-white shadow-sm px-4 sm:px-6 md:px-12 lg:px-24 py-4 flex items-center justify-between sticky top-0 z-40">

    <h1 class="text-xl sm:text-2xl font-bold text-[#2D6A4F]">
        Petha
    </h1>

    <a href="/login"
        class="text-sm font-medium text-[#2D6A4F] border border-[#2D6A4F] px-4 py-2 rounded-full hover:bg-[#2D6A4F] hover:text-white transition">

        Masuk

    </a>

</nav>

{{-- HERO --}}
<section class="bg-[#2D6A4F] text-white px-4 sm:px-6 md:px-12 lg:px-24 pt-10 pb-12">

    <div class="max-w-3xl">

        <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold leading-tight mb-3">

            Temukan Makanan
            <br>
            Halal Favoritmu

        </h1>

        <p class="text-sm sm:text-base opacity-90 mb-6">

            Jelajahi makanan halal populer di sekitarmu

        </p>

        {{-- SEARCH --}}
        <div class="bg-white rounded-full flex items-center px-4 py-3 shadow-md w-full max-w-xl">

            <i class="fa-solid fa-magnifying-glass text-gray-400 mr-3"></i>

            <input type="text"
                placeholder="Cari makanan halal..."
                class="flex-1 outline-none text-sm text-gray-700 bg-transparent">

        </div>

    </div>

</section>

{{-- CONTENT --}}
<section class="px-4 sm:px-6 md:px-12 lg:px-24 py-8 pb-36">

    {{-- KATEGORI --}}
    <div class="mb-10">

        <h2 class="font-bold text-gray-800 text-lg mb-5">
            Kategori
        </h2>

        <div class="grid grid-cols-4 gap-4 sm:flex sm:gap-5 sm:overflow-x-auto">

            @foreach([
                ['icon' => 'fa-ice-cream', 'label' => 'Dessert'],
                ['icon' => 'fa-cookie-bite', 'label' => 'Cemilan'],
                ['icon' => 'fa-bowl-rice', 'label' => 'Makanan Berat'],
                ['icon' => 'fa-mug-hot', 'label' => 'Minuman'],
            ] as $kat)

            <div class="flex flex-col items-center min-w-[70px]">

                <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-[#F5F5F5] flex items-center justify-center shadow-sm">

                    <i class="fa-solid {{ $kat['icon'] }} text-[#2D6A4F] text-lg sm:text-xl"></i>

                </div>

                <p class="text-[11px] sm:text-xs text-gray-600 mt-2 text-center leading-tight">
                    {{ $kat['label'] }}
                </p>

            </div>

            @endforeach

        </div>

    </div>

    {{-- MAKANAN POPULER --}}
    <div>

        <h2 class="font-bold text-gray-800 text-lg mb-5">
            Makanan Populer
        </h2>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">

            <x-food-card name="Nasi Padang Sederhana" price="Rp. 18.000" />
            <x-food-card name="Mie Ayam Jamur Spesial" price="Rp. 15.000" />
            <x-food-card name="Bakso Malang" price="Rp. 15.000" />
            <x-food-card name="Ayam Geprek" price="Rp. 12.000" />
            <x-food-card name="Sate Madura" price="Rp. 20.000" />

        </div>

    </div>

</section>

{{-- CTA BOTTOM --}}
<div class="fixed bottom-0 left-0 right-0 bg-[#2D6A4F] px-4 sm:px-6 py-4 z-40 shadow-lg">

    <div class="max-w-4xl mx-auto flex flex-col sm:flex-row gap-3">

        {{-- PENCARI --}}
        <a href="/register?role=pencari"
            class="flex-1 text-center bg-white text-[#2D6A4F] py-3 rounded-full text-sm font-semibold hover:opacity-90 transition">

            Dapatkan Rekomendasi

        </a>

        {{-- PEMILIK USAHA --}}
        <a href="/register?role=pemilik_usaha"
            class="flex-1 text-center border border-white text-white py-3 rounded-full text-sm font-semibold hover:bg-white hover:text-[#2D6A4F] transition">

            Daftarkan Usaha

        </a>

    </div>

</div>

{{-- ONBOARDING --}}
@if(!session('guest_preferensi'))

<div id="onboardingPopup"
    class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 px-4">

    <div class="bg-[#F5F5F5] w-full max-w-[340px] rounded-[28px] p-5">

        <span class="text-[11px] bg-green-100 text-[#2D6A4F] px-3 py-1 rounded-full">

            Preferensi Awal

        </span>

        <h2 class="text-xl font-bold text-[#2D6A4F] mt-3">

            Pilih 3 makanan favoritmu

        </h2>

        <p class="text-sm text-gray-500 mt-1 mb-3">

            Rekomendasi akan menyesuaikan pilihanmu

        </p>

        <p id="counter" class="text-sm text-[#2D6A4F] mb-4">

            0/3 dipilih

        </p>

        <form method="POST" action="/onboarding">

            @csrf

            <div class="grid grid-cols-2 gap-2 mb-5">

                @foreach([
                    'Ayam Geprek',
                    'Nasi Padang',
                    'Mie Ayam',
                    'Bakso',
                    'Sate',
                    'Seafood',
                    'Martabak',
                    'Soto',
                    'Burger Halal',
                    'Seblak'
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

                <button type="button"
                    onclick="skipOnboarding()"
                    class="flex-1 border border-gray-300 py-2 rounded-full text-sm text-gray-500">

                    Lewati

                </button>

                <button id="submitBtn"
                    disabled
                    class="flex-1 bg-gray-300 text-white py-2 rounded-full text-sm">

                    Lanjutkan

                </button>

            </div>

        </form>

    </div>

</div>

<script>
let selected = [];

function toggle(el){

    let value = el.getAttribute('data-value');

    if(selected.includes(value)){

        selected = selected.filter(v => v !== value);

        el.classList.remove('bg-[#2D6A4F]','text-white','border-[#2D6A4F]');

    } else {

        if(selected.length >= 3){
            alert('Maksimal 3 pilihan');
            return;
        }

        selected.push(value);

        el.classList.add('bg-[#2D6A4F]','text-white','border-[#2D6A4F]');
    }

    document.getElementById('counter').innerText =
        selected.length + "/3 dipilih";

    let hidden = '';

    selected.forEach(function(val){
        hidden += `<input type="hidden" name="kategori[]" value="${val}">`;
    });

    document.getElementById('hiddenInputs').innerHTML = hidden;

    let btn = document.getElementById('submitBtn');

    if(selected.length === 3){

        btn.disabled = false;
        btn.classList.remove('bg-gray-300');
        btn.classList.add('bg-[#2D6A4F]');

    } else {

        btn.disabled = true;
        btn.classList.add('bg-gray-300');
        btn.classList.remove('bg-[#2D6A4F]');
    }
}

function skipOnboarding(){
    document.getElementById('onboardingPopup').style.display = 'none';
}
</script>

@endif

@endsection