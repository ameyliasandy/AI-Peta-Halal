@if($showOnboarding ?? false)
<div id="onboardingModal" class="fixed inset-0 z-[100] flex justify-center items-center p-4">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

    <div class="relative bg-gray-100 p-6 rounded-3xl w-full max-w-md z-10 max-h-[90vh] overflow-y-auto">
        <!-- Badge -->
        <span class="text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full">
            Preferensi Awal
        </span>

        <h2 class="text-xl font-bold text-green-700 mt-2">
            Pilih 3 makanan favoritmu
        </h2>

        <p class="text-sm text-gray-500 mb-3">
            Rekomendasi akan menyesuaikan pilihanmu
        </p>

        <!-- Counter -->
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
                class="kategori px-3 py-2 border rounded-full text-sm text-gray-700">
                {{ $item }}
            </button>
            @endforeach
        </div>

        <div id="hidden-inputs"></div>

        <div class="flex gap-2">
            <button type="button" onclick="skipOnboarding()"
                class="flex-1 bg-white border border-gray-300 text-gray-500 py-2 rounded-full text-sm font-semibold hover:bg-gray-50 transition">
                Lewati
            </button>

            <button id="submitBtn" type="button" onclick="submitOnboarding()"
                class="flex-1 bg-gray-300 text-white py-2 rounded-full cursor-not-allowed text-sm font-semibold transition" disabled>
                Lanjutkan
            </button>
        </div>
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
    kirimOnboarding({ kategori: selected });
}

function skipOnboarding(){
    kirimOnboarding({ skip: 1 });
}

function kirimOnboarding(payload){
    fetch('/onboarding', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(payload)
    })
    .then(r => r.json())
    .then(() => {
        document.getElementById('onboardingModal').remove();
    })
    .catch(() => alert('Gagal menyimpan, coba lagi.'));
}
</script>
@endif