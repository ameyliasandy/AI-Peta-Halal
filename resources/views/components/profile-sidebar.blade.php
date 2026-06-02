<aside class="w-64 shrink-0">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- Header avatar --}}
        <div class="bg-[#2d6a4f] px-6 py-5 text-white text-center">
            <div class="w-16 h-16 rounded-full mx-auto mb-3 overflow-hidden bg-[#1b4332] flex items-center justify-center">
                @if(Auth::user()->foto_profil)
                    <img src="{{ Storage::url(Auth::user()->foto_profil) }}"
                         class="w-full h-full object-cover">
                @else
                    <span class="text-2xl font-semibold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </span>
                @endif
            </div>
            <p class="font-semibold text-sm">{{ Auth::user()->name }}</p>
            <p class="text-xs text-green-200 truncate">{{ Auth::user()->email }}</p>
        </div>

        {{-- Menu navigasi --}}
        <nav class="p-3 space-y-1">
            @php
                $menus = [
                    ['route' => 'profile.index',      'label' => 'Profil Saya',         'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                    ['route' => 'profile.favorit',    'label' => 'Favorit',             'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'],
                    ['route' => 'profile.preferensi', 'label' => 'Preferensi Makanan', 'icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z'],
                    ['route' => 'profile.pengaturan', 'label' => 'Pengaturan',          'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
                ];
            @endphp

            @foreach($menus as $menu)
            <a href="{{ route($menu['route']) }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm transition
                      {{ request()->routeIs($menu['route'])
                         ? 'bg-[#2d6a4f] text-white font-medium'
                         : 'text-gray-600 hover:bg-gray-50' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $menu['icon'] }}"/>
                </svg>
                {{ $menu['label'] }}
            </a>
            @endforeach

            <hr class="my-2 border-gray-100">

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm text-red-500 hover:bg-red-50 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Keluar
                </button>
            </form>
        </nav>
    </div>
</aside>