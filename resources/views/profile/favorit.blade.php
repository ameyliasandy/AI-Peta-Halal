@extends('layouts.app')
@section('title', 'Favorit Saya - Petha')
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
                <h2 class="text-base font-semibold text-gray-800 mb-5">Favorit Saya</h2>

                @if($favorit->isEmpty())
                    <div class="text-center py-12">
                        <p class="text-4xl mb-3">⭐</p>
                        <p class="text-sm text-gray-400">Belum ada favorit.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($favorit as $item)
                            @if($item->restoran)
                            <!-- Tampilan Restoran Favorit -->
                            <div class="flex gap-4 border border-gray-100 rounded-xl p-4 hover:border-gray-200 transition">
                                <div class="w-20 h-20 rounded-xl overflow-hidden shrink-0 bg-gray-100">
                                    @if($item->restoran->foto_utama)
                                        <img src="{{ $item->restoran->getFotoUtamaUrl() }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-[#d8f3dc] flex items-center justify-center text-2xl">🍽️</div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-sm text-gray-800 truncate">{{ $item->restoran->nama_restoran }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $item->restoran->kota }}</p>
                                    <div class="flex items-center gap-1 mt-1">
                                        <span class="text-yellow-400 text-xs">★</span>
                                        <span class="text-xs text-gray-600">{{ $item->restoran->rating ?? '-' }}</span>
                                    </div>
                                    @php $status = $item->restoran->status_halal; @endphp
                                    <span class="inline-block mt-2 text-xs px-2 py-0.5 rounded-full
                                        {{ $status === 'certified' ? 'bg-green-100 text-green-700' :
                                          ($status === 'self_claimed' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-500') }}">
                                        {{ $status === 'certified' ? 'Bersertifikat MUI' :
                                          ($status === 'self_claimed' ? 'Klaim Halal' : 'Belum Terverifikasi') }}
                                    </span>
                                </div>
                                <form method="POST" action="{{ route('profile.favorit.hapus', $item->id_restoran) }}" class="shrink-0">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-gray-300 hover:text-red-400 transition mt-1" title="Hapus">✕</button>
                                </form>
                            </div>
                            @endif

                            @if($item->menu)
                            <!-- Tampilan Menu Favorit -->
                            <div class="flex gap-4 border border-gray-100 rounded-xl p-4 hover:border-gray-200 transition">
                                <div class="w-20 h-20 rounded-xl overflow-hidden shrink-0 bg-gray-100">
                                    @if($item->menu->foto)
                                        <img src="{{ asset('storage/' . $item->menu->foto) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-[#f8d7da] flex items-center justify-center text-2xl">🍜</div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-sm text-gray-800 truncate">{{ $item->menu->nama_menu }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $item->menu->restoran->nama_restoran ?? 'Restoran' }}</p>
                                    <p class="text-xs text-gray-600 mt-1">Rp {{ number_format($item->menu->harga, 0, ',', '.') }}</p>
                                </div>
                                <form method="POST" action="{{ route('profile.favorit.hapus', $item->id_menu) }}" class="shrink-0">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-gray-300 hover:text-red-400 transition mt-1" title="Hapus">✕</button>
                                </form>
                            </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="mt-6">{{ $favorit->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection