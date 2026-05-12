<!-- VERIFIKASI -->
<div>

    <h2 class="text-2xl font-bold text-gray-800 mb-6">
        Usaha Halal yang Memerlukan Verifikasi
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        @foreach ($verifikasi as $item)
            <div class="bg-white rounded-2xl shadow-sm p-6">

                <!-- HEADER -->
                <div class="flex items-center gap-4 mb-4">

                    <img src="{{ asset('img/default-usaha.jpg') }}"
                         class="w-12 h-12 rounded-full object-cover">

                    <div>
                        <h3 class="font-bold text-lg text-gray-800">
                            {{ $item->nama }}
                        </h3>

                        <p class="text-xs text-gray-400">
                            {{ $item->created_at->diffForHumans() }}
                        </p>
                    </div>

                </div>

                <!-- DESKRIPSI -->
                <p class="text-sm text-gray-500 mb-5 leading-relaxed">
                    {{ $item->deskripsi ?? 'Belum ada deskripsi usaha.' }}
                </p>

                <!-- BUTTON -->
                <a href="#"
                   class="inline-block bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-full text-sm font-semibold transition">
                    Lihat Detail
                </a>

            </div>
        @endforeach

    </div>

</div>