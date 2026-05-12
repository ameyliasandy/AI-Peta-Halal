<!-- STATS -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <!-- PERLU VERIFIKASI -->
    <div class="bg-white rounded-2xl shadow-sm p-6 flex items-center gap-4">
        <div class="w-14 h-14 rounded-full bg-green-100 flex items-center justify-center">
            <i class="fa-solid fa-file-circle-check text-2xl text-green-500"></i>
        </div>

        <div>
            <h3 class="text-3xl font-bold text-gray-800">
                {{ $stats['perlu_verif'] }}
            </h3>
            <p class="text-gray-500">Perlu Diverifikasi</p>
        </div>
    </div>

    <!-- USAHA -->
    <div class="bg-white rounded-2xl shadow-sm p-6 flex items-center gap-4">
        <div class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center">
            <i class="fa-solid fa-store text-2xl text-blue-500"></i>
        </div>

        <div>
            <h3 class="text-3xl font-bold text-gray-800">
                {{ $stats['total_usaha'] }}
            </h3>
            <p class="text-gray-500">Usaha Halal</p>
        </div>
    </div>

    <!-- USER -->
    <div class="bg-white rounded-2xl shadow-sm p-6 flex items-center gap-4">
        <div class="w-14 h-14 rounded-full bg-purple-100 flex items-center justify-center">
            <i class="fa-solid fa-users text-2xl text-purple-500"></i>
        </div>

        <div>
            <h3 class="text-3xl font-bold text-gray-800">
                {{ $stats['total_user'] }}
            </h3>
            <p class="text-gray-500">Pengguna</p>
        </div>
    </div>

</div>