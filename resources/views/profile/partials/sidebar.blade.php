<aside class="w-64 shrink-0">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- Tombol Kembali --}}
        <div class="p-3 border-b border-gray-100">
            <button
                onclick="history.back()"
                class="w-full flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-100 transition">
                ← Kembali
            </button>
        </div>

        <div class="bg-[#2d6a4f] px-6 py-5 text-white text-center">
            <div class="w-16 h-16 rounded-full mx-auto mb-3 overflow-hidden bg-[#1b4332] flex items-center justify-center">
                @if(Auth::user()->foto_profil)
                    <img src="{{ Storage::url(Auth::user()->foto_profil) }}" class="w-full h-full object-cover">
                @else
                    <span class="text-2xl font-semibold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </span>
                @endif
            </div>

            <p class="font-semibold text-sm">{{ Auth::user()->name }}</p>
            <p class="text-xs text-green-200 truncate">{{ Auth::user()->email }}</p>
        </div>

        <nav class="p-3 space-y-1">
            @php
                $menus = [
                    ['route' => 'profile.index',      'label' => 'Profil Saya'],
                    ['route' => 'profile.favorit',    'label' => 'Favorit'],
                    ['route' => 'profile.preferensi', 'label' => 'Preferensi Makanan'],
                    ['route' => 'profile.pengaturan', 'label' => 'Pengaturan'],
                ];
            @endphp

            @foreach($menus as $menu)
            <a href="{{ route($menu['route']) }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm transition
                      {{ request()->routeIs($menu['route'])
                         ? 'bg-[#2d6a4f] text-white font-medium'
                         : 'text-gray-600 hover:bg-gray-50' }}">
                {{ $menu['label'] }}
            </a>
            @endforeach

            <hr class="my-2 border-gray-100">

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm text-red-500 hover:bg-red-50 transition">
                    Keluar
                </button>
            </form>
        </nav>

    </div>
</aside>