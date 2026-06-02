@extends('layouts.app')
@section('title', 'Preferensi Makanan - Petha')
@section('content')

<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="flex gap-6 items-start">
        @include('profile.partials.sidebar')

        <div class="flex-1">

            @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-xl mb-4">
                {{ session('success') }}
            </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-base font-semibold text-gray-800 mb-1">Preferensi Makanan</h2>
                <p class="text-sm text-gray-400 mb-6">Pilih jenis makanan yang kamu suka untuk rekomendasi yang lebih akurat.</p>

                @php
                    $opsi = [
                        'Masakan Padang', 'Masakan Jawa', 'Masakan Sunda',
                        'Seafood', 'Ayam & Bebek', 'Sate & Grill',
                        'Mi & Bakso', 'Nasi Goreng', 'Makanan Tradisional',
                        'Makanan Cepat Saji', 'Vegetarian', 'Minuman & Dessert',
                        'Jepang & Korea', 'Arab & Timur Tengah', 'Snack & Jajanan',
                    ];
                @endphp

                <form method="POST" action="{{ route('profile.preferensi.update') }}">
                    @csrf
                    <div class="flex flex-wrap gap-2">
                        @foreach($opsi as $item)
                        <label class="cursor-pointer">
                            <input type="checkbox" name="preferensi[]" value="{{ $item }}"
                                   class="hidden peer"
                                   {{ in_array($item, $dipilih) ? 'checked' : '' }}>
                            <span class="inline-block border border-gray-200 text-sm px-4 py-2 rounded-full
                                         peer-checked:bg-[#2d6a4f] peer-checked:text-white peer-checked:border-[#2d6a4f]
                                         hover:border-[#2d6a4f] hover:text-[#2d6a4f] transition select-none">
                                {{ $item }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                    <button type="submit"
                        class="mt-6 bg-[#2d6a4f] hover:bg-[#1b4332] text-white text-sm font-medium px-6 py-2.5 rounded-xl transition">
                        Simpan Preferensi
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection