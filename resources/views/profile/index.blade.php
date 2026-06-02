@extends('layouts.app')
@section('title', 'Profil Saya - Petha')
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

            {{-- Edit Profil --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-base font-semibold text-gray-800 mb-5">Edit Profil</h2>

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="flex items-center gap-5 mb-6">
                        <div class="w-20 h-20 rounded-full overflow-hidden bg-[#2d6a4f] flex items-center justify-center shrink-0" id="foto-wrapper">
                            @if($user->foto_profil)
                                <img src="{{ Storage::url($user->foto_profil) }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-2xl font-semibold text-white">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </span>
                            @endif
                        </div>
                        <div>
                            <label class="cursor-pointer inline-flex items-center gap-2 text-sm text-[#2d6a4f] font-medium border border-[#2d6a4f] px-4 py-2 rounded-lg hover:bg-green-50 transition">
                                Ganti Foto
                                <input type="file" name="foto_profil" id="input-foto" class="hidden" accept="image/*">
                            </label>
                            <p class="text-xs text-gray-400 mt-1">JPG, PNG maks. 2MB</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#2d6a4f] focus:ring-1 focus:ring-[#2d6a4f]">
                            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#2d6a4f] focus:ring-1 focus:ring-[#2d6a4f]">
                            @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">No. Telepon</label>
                            <input type="text" name="no_telepon" value="{{ old('no_telepon', $user->no_telepon) }}"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#2d6a4f] focus:ring-1 focus:ring-[#2d6a4f]">
                        </div>
                    </div>

                    <button type="submit"
                        class="mt-5 bg-[#2d6a4f] hover:bg-[#1b4332] text-white text-sm font-medium px-6 py-2.5 rounded-xl transition">
                        Simpan Perubahan
                    </button>
                </form>
            </div>

            {{-- Ganti Password --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-base font-semibold text-gray-800 mb-5">Ganti Password</h2>

                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Password Lama</label>
                            <input type="password" name="password_lama"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#2d6a4f] focus:ring-1 focus:ring-[#2d6a4f]">
                            @error('password_lama')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Password Baru</label>
                            <input type="password" name="password_baru"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#2d6a4f] focus:ring-1 focus:ring-[#2d6a4f]">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Konfirmasi Password Baru</label>
                            <input type="password" name="password_baru_confirmation"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#2d6a4f] focus:ring-1 focus:ring-[#2d6a4f]">
                        </div>
                    </div>
                    <button type="submit"
                        class="mt-5 bg-[#2d6a4f] hover:bg-[#1b4332] text-white text-sm font-medium px-6 py-2.5 rounded-xl transition">
                        Ubah Password
                    </button>
                </form>
            </div>

            {{-- Riwayat Pencarian --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-base font-semibold text-gray-800 mb-4">Riwayat Pencarian</h2>
                <p class="text-sm text-gray-400 text-center py-6">Belum ada riwayat pencarian.</p>
            </div>

        </div>
    </div>
</div>

<script>
    document.getElementById('input-foto').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(ev) {
            document.getElementById('foto-wrapper').innerHTML =
                `<img src="${ev.target.result}" class="w-full h-full object-cover">`;
        };
        reader.readAsDataURL(file);
    });
</script>

@endsection