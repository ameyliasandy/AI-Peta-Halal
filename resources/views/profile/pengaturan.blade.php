@extends('layouts.app')
@section('title', 'Pengaturan - Petha')
@section('content')

<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="flex gap-6 items-start">
        @include('profile.partials.sidebar')

        <div class="flex-1 space-y-6">

            @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-xl">
                {{ session('success') }}
            </div>
            @endif

            {{-- Notifikasi --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-base font-semibold text-gray-800 mb-5">Notifikasi</h2>

                <form method="POST" action="{{ route('profile.pengaturan.update') }}">
                    @csrf
                    <div class="space-y-1">
                        @foreach([
                            ['key' => 'notif_promo',  'label' => 'Promo & Penawaran',  'desc' => 'Notifikasi promo dari restoran yang kamu ikuti'],
                            ['key' => 'notif_ulasan', 'label' => 'Balasan Ulasan',      'desc' => 'Notifikasi ketika ulasanmu dibalas pemilik restoran'],
                        ] as $notif)
                        <div class="flex items-center justify-between py-4 border-b border-gray-50 last:border-0">
                            <div>
                                <p class="text-sm font-medium text-gray-700">{{ $notif['label'] }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $notif['desc'] }}</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer ml-4">
                                <input type="checkbox" name="{{ $notif['key'] }}" value="1"
                                       class="sr-only peer"
                                       {{ $user->{$notif['key']} ? 'checked' : '' }}>
                                <div class="w-10 h-6 bg-gray-200 rounded-full peer peer-checked:bg-[#2d6a4f] transition
                                            after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                                            after:bg-white after:rounded-full after:h-5 after:w-5
                                            after:transition-all peer-checked:after:translate-x-4"></div>
                            </label>
                        </div>
                        @endforeach
                    </div>
                    <button type="submit"
                        class="mt-5 bg-[#2d6a4f] hover:bg-[#1b4332] text-white text-sm font-medium px-6 py-2.5 rounded-xl transition">
                        Simpan Pengaturan
                    </button>
                </form>
            </div>

            {{-- Zona Bahaya --}}
            <div class="bg-white rounded-2xl shadow-sm border border-red-100 p-6">
                <h2 class="text-base font-semibold text-red-600 mb-1">Zona Bahaya</h2>
                <p class="text-sm text-gray-400 mb-4">Tindakan ini tidak dapat dibatalkan.</p>
                <button type="button"
                    onclick="if(confirm('Yakin ingin menghapus akun? Semua data akan hilang permanen.'))
                                document.getElementById('form-hapus-akun').submit()"
                    class="text-sm text-red-500 border border-red-200 px-4 py-2 rounded-xl hover:bg-red-50 transition">
                    Hapus Akun Saya
                </button>
                <form id="form-hapus-akun" method="POST" action="{{ route('profile.hapus') }}" class="hidden">
                    @csrf @method('DELETE')
                </form>
            </div>

        </div>
    </div>
</div>

@endsection