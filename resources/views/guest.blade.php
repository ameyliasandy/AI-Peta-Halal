@extends('layouts.app')
@section('title', 'Petha - Temukan Makanan Halal')
@section('content')

    {{-- Navbar --}}
    <nav class="bg-white shadow-sm px-6 md:px-16 lg:px-32 py-4 flex items-center justify-between sticky top-0 z-40">
        <p class="text-xl font-bold text-[#2D6A4F]">Petha</p>
        <div class="flex items-center gap-3">
            <a href="/login"
                class="text-sm font-medium text-[#2D6A4F] border border-[#2D6A4F] px-4 py-2 rounded-full hover:bg-[#2D6A4F] hover:text-white transition">
                Masuk
            </a>
            <a href="/register"
                class="text-sm font-medium bg-[#2D6A4F] text-white px-4 py-2 rounded-full hover:bg-green-800 transition">
                Daftar
            </a>
        </div>
    </nav>

    {{-- Hero Section --}}
    <div class="bg-[#2D6A4F] text-white px-6 md:px-16 lg:px-32 py-12">
        <h1 class="text-3xl md:text-4xl font-bold leading-snug mb-2">
            Temukan Makanan<br>Halal Favoritmu 🍽️
        </h1>
        <p class="text-sm md:text-base opacity-80 mb-6">Jelajahi makanan halal populer di sekitarmu</p>

        {{-- Search Bar --}}
        <div class="bg-white rounded-full flex items-center px-5 py-3 shadow-md max-w-2xl">
            <span class="text-gray-400 mr-3">🔍</span>
            <input type="text" placeholder="Cari makanan halal..."
                class="flex-1 outline-none text-sm text-gray-500">
        </div>
    </div>

    {{-- Konten Utama --}}
    <div class="px-6 md:px-16 lg:px-32 py-8 pb-28">

        {{-- Kategori --}}
        <div class="mb-8">
            <h2 class="font-bold text-gray-800 text-lg mb-4">Kategori</h2>
            <div class="flex gap-6 overflow-x-auto pb-2">
                @foreach([
                    ['emoji' => '🍮', 'label' => 'Dessert'],
                    ['emoji' => '🍟', 'label' => 'Cemilan'],
                    ['emoji' => '🍛', 'label' => 'Makanan Berat'],
                    ['emoji' => '🧋', 'label' => 'Minuman'],
                ] as $kat)
                <div class="flex flex-col items-center gap-2 min-w-[70px] cursor-pointer hover:opacity-80 transition">
                    <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center text-2xl hover:bg-green-100 transition">
                        {{ $kat['emoji'] }}
                    </div>
                    <span class="text-xs text-center text-gray-600">{{ $kat['label'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Makanan Populer --}}
        <div>
            <h2 class="font-bold text-gray-800 text-lg mb-4">Makanan Populer</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                <x-food-card name="Nasi Padang Sederhana" price="Rp. 18.000" />
                <x-food-card name="Mie Ayam Jamur Spesial" price="Rp. 15.000" />
                <x-food-card name="Bakso Malang" price="Rp. 15.000" />
                <x-food-card name="Ayam Geprek" price="Rp. 12.000" />
                <x-food-card name="Sate Madura" price="Rp. 20.000" />
            </div>
        </div>

    </div>

    {{-- CTA Fixed Bottom --}}
    <div class="fixed bottom-0 left-0 right-0 bg-[#2D6A4F] px-6 md:px-16 lg:px-32 py-3 z-40">
        <div class="max-w-3xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="text-white text-center sm:text-left">
                <p class="font-bold text-sm">Dapatkan rekomendasi personal</p>
                <p class="text-xs opacity-80">Masuk untuk makanan halal sesuai seleramu</p>
            </div>
            <div class="flex gap-3 flex-shrink-0">
                <a href="/login"
                    class="text-sm font-bold border-2 border-white text-white py-2 px-5 rounded-full hover:bg-white hover:text-[#2D6A4F] transition whitespace-nowrap">
                    Masuk
                </a>
                <a href="/register"
                    class="text-sm font-bold border-2 border-white text-white py-2 px-5 rounded-full hover:bg-white hover:text-[#2D6A4F] transition whitespace-nowrap">
                    Daftarkan Usaha
                </a>
            </div>
        </div>
    </div>

    {{-- Onboarding Popup --}}
    @if(!session('guest_preferensi'))
    <div id="onboardingPopup" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 px-4">
        <div class="bg-gray-100 p-6 rounded-3xl w-full max-w-md">

            <span class="text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full">
                Preferensi Awal
            </span>

            <h2 class="text-xl font-bold text-green-700 mt-2">Pilih 3 makanan favoritmu</h2>
            <p class="text-sm text-gray-500 mb-3">Rekomendasi akan menyesuaikan pilihanmu</p>
            <p id="counter" class="text-sm text-green-600 mb-4">0/3 dipilih</p>

            <form method="POST" action="/onboarding">
                @csrf
                <div class="grid grid-cols-2 gap-3 mb-6">
                    @foreach(['Ayam Geprek','Nasi Padang','Mie Ayam','Bakso','Sate','Seafood','Martabak','Soto','Burger Halal','Seblak'] as $item)
                    <button type="button" onclick="toggle(this)"
                        data-value="{{ $item }}"
                        class="kategori px-3 py-2 border rounded-full text-sm text-gray-700 bg-white hover:bg-green-50 transition">
                        {{ $item }}
                    </button>
                    @endforeach
                </div>

                <div id="hiddenInputs"></div>

                <div class="flex gap-3">
                    <button type="button" onclick="skipOnboarding()"
                        class="flex-1 border border-gray-300 text-gray-500 py-2 rounded-full text-sm hover:bg-gray-200 transition">
                        Lewati
                    </button>
                    <button id="submitBtn"
                        class="flex-1 bg-gray-300 text-white py-2 rounded-full cursor-not-allowed text-sm"
                        disabled>
                        Lanjutkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    let selected = [];

    function toggle(el) {
        let value = el.getAttribute('data-value');

        if (selected.includes(value)) {
            selected = selected.filter(v => v !== value);
            el.classList.remove('bg-green-600', 'text-white', 'border-green-600');
            el.classList.add('text-gray-700');
        } else {
            if (selected.length >= 3) {
                alert("Maksimal hanya 3 pilihan!");
                return;
            }
            selected.push(value);
            el.classList.add('bg-green-600', 'text-white', 'border-green-600');
            el.classList.remove('text-gray-700');
        }

        document.getElementById('counter').innerText = selected.length + "/3 dipilih";

        let hiddenInputs = '';
        selected.forEach(function(val) {
            hiddenInputs += `<input type="hidden" name="kategori[]" value="${val}">`;
        });
        document.getElementById('hiddenInputs').innerHTML = hiddenInputs;

        let btn = document.getElementById('submitBtn');
        if (selected.length === 3) {
            btn.disabled = false;
            btn.classList.remove('bg-gray-300', 'cursor-not-allowed');
            btn.classList.add('bg-green-600', 'cursor-pointer');
        } else {
            btn.disabled = true;
            btn.classList.add('bg-gray-300', 'cursor-not-allowed');
            btn.classList.remove('bg-green-600', 'cursor-pointer');
        }
    }

    function skipOnboarding() {
        document.getElementById('onboardingPopup').style.display = 'none';
    }
    </script>
    @endif

@endsection