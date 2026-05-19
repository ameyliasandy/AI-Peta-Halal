<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Onboarding</title>
</head>

<body class="bg-green-700 flex justify-center items-center min-h-screen">
    <div class="bg-gray-100 p-6 rounded-3xl w-full max-w-md">
        <!-- Badge -->
         <span class="text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full">
    Preferensi Awal</span>
    
    <h2 class="text-xl font-bold text-green-700 mt-2">
    Pilih 3 makanan favoritmu </h2>
    
    <p class="text-sm text-gray-500 mb-3">
        Rekomendasi akan menyesuaikan pilihanmu
    </p>

    <!-- Counter -->
    <p id="counter" class="text-sm text-green-600 mb-4">
        0/3 dipilih
    </p>

    <form method="POST" action="/onboarding">
    @csrf

    <div class="grid grid-cols-2 gap-3 mb-6">

        @php
        $kategori = ['Ayam Geprek','Nasi Padang','Mie Ayam','Bakso','Sate','Seafood','Martabak','Soto','Burger Halal','Seblak'];
        @endphp

    @foreach($kategori as $item)
        <button type="button"
            onclick="toggle(this)"
            class="kategori px-3 py-2 border rounded-full text-sm text-gray-700">
            {{ $item }}
        </button>
    @endforeach

    </div>

<!-- hidden input -->
<div id="hidden-inputs"></div>

    <button id="submitBtn"
        class="w-full bg-gray-300 text-white py-2 rounded-full cursor-not-allowed" disabled>
        Lanjutkan
    </button>

    </form>
    </div>

<script>
let selected = [];

function toggle(el){

    let value = el.innerText;

    // 🔥 remove pilihan
    if(selected.includes(value)){

        selected = selected.filter(v => v !== value);

        el.classList.remove('bg-green-600','text-white');

    } else {

        // 🔥 limit 3
        if(selected.length >= 3){
            alert("Maksimal hanya 3 pilihan!");
            return;
        }

        selected.push(value);

        el.classList.add('bg-green-600','text-white');
    }

    // 🔥 update counter
    document.getElementById('counter').innerText =
        selected.length + "/3 dipilih";

    // 🔥 hidden inputs
    let hiddenContainer =
        document.getElementById('hidden-inputs');

    hiddenContainer.innerHTML = '';

    selected.forEach(item => {

        hiddenContainer.innerHTML += `
            <input type="hidden"
                   name="kategori[]"
                   value="${item}">
        `;
    });

    // 🔥 tombol
    let btn = document.getElementById('submitBtn');

    if(selected.length === 3){

        btn.disabled = false;

        btn.classList.remove(
            'bg-gray-300',
            'cursor-not-allowed'
        );

        btn.classList.add('bg-green-600');

    } else {

        btn.disabled = true;

        btn.classList.add(
            'bg-gray-300',
            'cursor-not-allowed'
        );
    }
}
</script>

</body>
</html>