@extends('layouts.app')

@section('title', 'Pilih Preferensi - Petha')
@section('content')

<div class="min-h-screen bg-[#2D6A4F] flex items-center justify-center p-4">
    <div class="bg-gray-100 p-6 rounded-3xl w-full max-w-md">

        <span class="text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full">
            Preferensi Awal
        </span>

        <h2 class="text-xl font-bold text-green-700 mt-2">
            Pilih 3 makanan favoritmu
        </h2>

        <p class="text-sm text-gray-500 mb-3">
            Rekomendasi akan menyesuaikan pilihanmu. Langkah ini wajib diisi sebelum
            melanjutkan ke dashboard.
        </p>

        <p id="counter" class="text-sm text-green-600 mb-4">
            0/3 dipilih
        </p>

        <div class="grid grid-cols-2 gap-3 mb-6">
            @php
            $kategori = ['Ayam Geprek','Nasi Padang','Mie Ayam','Bakso','Sate','Seafood','Martabak','Soto','Burger Halal','Seblak'];
            @endphp

            @foreach($kategori as $item)
            <button type="button"
                onclick="toggle(this)"
                class="kategori px-3 py-2 border rounded-full text-sm text-gray-700 transition">
                {{ $item }}
            </button>
            @endforeach
        </div>

        <div id="hidden-inputs"></div>

        <button id="submitBtn" type="button" onclick="submitOnboarding()"
            class="w-full bg-gray-300 text-white py-3 rounded-full cursor-not-allowed text-sm font-semibold transition" disabled>
            Lanjutkan ke Dashboard
        </button>

        <p id="errorMsg" class="text-red-500 text-xs mt-2 hidden">
            Gagal menyimpan preferensi, coba lagi.
        </p>
    </div>
</div>

<script>
let selected = [];

function toggle(el){
    let value = el.innerText;

    if(selected.includes(value)){
        selected = selected.filter(v => v !== value);
        el.classList.remove('bg-green-600','text-white');
    } else {
        if(selected.length >= 3){
            alert("Maksimal hanya 3 pilihan!");
            return;
        }
        selected.push(value);
        el.classList.add('bg-green-600','text-white');
    }

    document.getElementById('counter').innerText = selected.length + "/3 dipilih";

    let hiddenContainer = document.getElementById('hidden-inputs');
    hiddenContainer.innerHTML = '';
    selected.forEach(item => {
        hiddenContainer.innerHTML += `<input type="hidden" name="kategori[]" value="${item}">`;
    });

    let btn = document.getElementById('submitBtn');
    if(selected.length === 3){
        btn.disabled = false;
        btn.classList.remove('bg-gray-300','cursor-not-allowed');
        btn.classList.add('bg-green-600');
    } else {
        btn.disabled = true;
        btn.classList.add('bg-gray-300','cursor-not-allowed');
        btn.classList.remove('bg-green-600');
    }
}

function submitOnboarding(){
    if(selected.length !== 3) return;

    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerText = 'Menyimpan...';
    document.getElementById('errorMsg').classList.add('hidden');

    fetch('/onboarding', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ kategori: selected })
    })
    .then(r => {
        if (!r.ok) throw new Error('Gagal');
        return r.json();
    })
    .then(() => {
        window.location.href = '/dashboard';
    })
    .catch(() => {
        document.getElementById('errorMsg').classList.remove('hidden');
        btn.disabled = false;
        btn.innerText = 'Lanjutkan ke Dashboard';
    });
}
</script>
@endsection