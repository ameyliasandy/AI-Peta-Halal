{{-- resources/views/pemilik/dashboard.blade.php --}}
@extends('layouts.pemilik')

@section('content')
<div class="pb-10">

  {{-- HERO --}}
  <div class="px-5 pt-5">
    <p class="text-xs text-gray-400 mb-1">Selamat datang kembali ✦</p>
    <h1 class="text-lg sm:text-xl font-bold text-gray-900 mb-0.5 break-words">Halo, {{ Auth::user()->name }} 👋</h1>

    @php
      $restoran    = $restoran ?? null;
      $verifikasi  = $verifikasi ?? null;
      $statusHalal = $verifikasi?->status ?? 'pending';
    @endphp

    {{-- =========================================
         BANNER: kondisi berdasarkan status halal
    ========================================== --}}
    @if($statusHalal === 'approved')
      <p class="text-sm text-gray-500 mb-4">Usahamu sudah terverifikasi halal!</p>

      {{-- BANNER HIJAU - VERIFIED --}}
      <div class="rounded-2xl p-4 flex items-center gap-3 mb-5"
           style="background: linear-gradient(135deg, #085041, #1D9E75);">
        <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" viewBox="0 0 24 24"
               fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M5 7.2a2.2 2.2 0 0 1 2.2 -2.2h1a2.2 2.2 0 0 0 1.55 -.64l.7 -.7a2.2 2.2 0 0 1 3.12 0l.7 .7c.412 .41 .97 .64 1.55 .64h1a2.2 2.2 0 0 1 2.2 2.2v1c0 .58 .23 1.138 .64 1.55l.7 .7a2.2 2.2 0 0 1 0 3.12l-.7 .7a2.2 2.2 0 0 0 -.64 1.55v1a2.2 2.2 0 0 1 -2.2 2.2h-1a2.2 2.2 0 0 0 -1.55 .64l-.7 .7a2.2 2.2 0 0 1 -3.12 0l-.7 -.7a2.2 2.2 0 0 0 -1.55 -.64h-1a2.2 2.2 0 0 1 -2.2 -2.2v-1a2.2 2.2 0 0 0 -.64 -1.55l-.7 -.7a2.2 2.2 0 0 1 0 -3.12l.7 -.7a2.2 2.2 0 0 0 .64 -1.55v-1"/>
            <path d="M9 12l2 2l4 -4"/>
          </svg>
        </div>
        <div>
          <p class="text-white text-sm font-semibold">Sertifikasi Halal Disetujui ✓</p>
          <p class="text-white/70 text-xs mt-0.5">
            Usahamu kini tampil di peta Petha
            @if($verifikasi?->updated_at)
              · Disetujui {{ $verifikasi->updated_at->translatedFormat('d M Y') }}
            @endif
          </p>
        </div>
      </div>

    @elseif($statusHalal === 'proses' || $statusHalal === 'pending')
      <p class="text-sm text-gray-500 mb-4">Kelola usaha halalmu di sini.</p>

      {{-- BANNER KUNING - PROSES --}}
      <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 flex items-center gap-3 mb-5">
        <div class="w-9 h-9 flex items-center justify-center flex-shrink-0 text-amber-500">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"
               fill="none" stroke="currentColor" stroke-width="2">
            <path d="M6 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h14a2 2 0 0 0 2 -2v-9a2 2 0 0 0 -2 -2h-1"/>
            <path d="M6 7v-2a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v2"/>
            <path d="M9 14h6"/><path d="M9 17h3"/>
          </svg>
        </div>
        <div>
          <p class="text-amber-800 text-sm font-semibold">Verifikasi Halal Sedang Diproses</p>
          <p class="text-amber-600 text-xs mt-0.5">Tim kami sedang meninjau sertifikat halalmu. Estimasi 2–3 hari kerja.</p>
        </div>
      </div>

    @else
      <p class="text-sm text-gray-500 mb-4">Kelola usaha halalmu di sini.</p>
    @endif
  </div>

  {{-- STAT CARDS --}}
  <div class="grid grid-cols-2 gap-3 px-4 mb-5">
    <div class="bg-white border border-gray-100 rounded-2xl p-4">
      <div class="text-[#1D9E75] mb-2">
        @if($statusHalal === 'approved')
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 7.2a2.2 2.2 0 0 1 2.2 -2.2h1a2.2 2.2 0 0 0 1.55 -.64l.7 -.7a2.2 2.2 0 0 1 3.12 0l.7 .7c.412 .41 .97 .64 1.55 .64h1a2.2 2.2 0 0 1 2.2 2.2v1c0 .58 .23 1.138 .64 1.55l.7 .7a2.2 2.2 0 0 1 0 3.12l-.7 .7a2.2 2.2 0 0 0 -.64 1.55v1a2.2 2.2 0 0 1 -2.2 2.2h-1a2.2 2.2 0 0 0 -1.55 .64l-.7 .7a2.2 2.2 0 0 1 -3.12 0l-.7 -.7a2.2 2.2 0 0 0 -1.55 -.64h-1a2.2 2.2 0 0 1 -2.2 -2.2v-1a2.2 2.2 0 0 0 -.64 -1.55l-.7 -.7a2.2 2.2 0 0 1 0 -3.12l.7 -.7a2.2 2.2 0 0 0 .64 -1.55v-1"/><path d="M9 12l2 2l4 -4"/></svg>
          <p class="text-lg font-bold text-gray-900 mt-1">Terverifikasi</p>
        @else
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 3h6l3 9l-6 -3l-6 3z"/><path d="M6 12l-3 9h18l-3 -9"/></svg>
          <p class="text-lg font-bold text-gray-900 mt-1">Proses</p>
        @endif
      </div>
      <p class="text-xs text-gray-400">Status Akun</p>
    </div>

    <div class="bg-white border border-gray-100 rounded-2xl p-4">
      <div class="text-amber-500 mb-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 3h18v4h-18z"/><path d="M3 7v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2 -2v-10"/><path d="M12 12l0 .01"/><path d="M8 12l0 .01"/><path d="M16 12l0 .01"/></svg>
      </div>
      <p class="text-lg font-bold text-gray-900">{{ $jumlahMenu ?? 0 }}</p>
      <p class="text-xs text-gray-400">Jumlah Menu</p>
    </div>

    @if($statusHalal === 'approved')
    <div class="bg-white border border-gray-100 rounded-2xl p-4">
      <div class="text-blue-500 mb-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2"/><path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7"/></svg>
      </div>
      <p class="text-lg font-bold text-gray-900">{{ $dilihat ?? 0 }}</p>
      <p class="text-xs text-gray-400">Dilihat Minggu Ini</p>
    </div>

    <div class="bg-white border border-gray-100 rounded-2xl p-4">
      <div class="text-pink-500 mb-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572"/></svg>
      </div>
      <p class="text-lg font-bold text-gray-900">{{ $disimpan ?? 0 }}</p>
      <p class="text-xs text-gray-400">Disimpan Pengguna</p>
    </div>
    @endif
  </div>

  {{-- ACTION BUTTONS --}}
  <div class="px-5 mb-5 space-y-3">
    @if($statusHalal === 'approved' && $restoran)
      <a href="{{ route('pemilik.toko.index') }}"
         class="flex items-center justify-center gap-2 w-full py-3.5 rounded-2xl bg-[#1D9E75] text-white font-semibold text-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z"/><path d="M8 7v13"/><path d="M16 7v13"/><path d="M3 12h18"/></svg>
        Lihat Detail Toko
      </a>
    @endif

    <a href="{{ route('pemilik.toko.create') }}"
      class="flex items-center justify-center gap-2 w-full py-3.5 rounded-2xl border-2 border-gray-200 bg-white text-gray-800 font-semibold text-sm">
      + Daftarkan Usaha
    </a>

    <a href=""
       class="flex items-center justify-center gap-2 w-full py-3.5 rounded-2xl border border-gray-200 bg-white text-gray-700 font-semibold text-sm">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 3h6l3 9l-6 -3l-6 3z"/><path d="M6 12l-3 9h18l-3 -9"/></svg>
      Cek Status Verifikasi
    </a>
  </div>

  {{-- LANGKAH SELANJUTNYA --}}
  <div class="px-5">
    <p class="text-sm font-bold mb-3">📌 Langkah Selanjutnya</p>
    <div class="space-y-2">

      {{-- Step 1 --}}
      <div class="bg-white border border-gray-100 rounded-2xl px-4 py-3 flex items-center gap-3">
        <div class="w-9 h-9 rounded-xl flex-shrink-0 flex items-center justify-center bg-[#E1F5EE] text-[#1D9E75]">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10"/></svg>
        </div>
        <div class="flex-1">
          <p class="text-sm font-semibold text-gray-800">Isi data usaha</p>
          <p class="text-xs text-gray-400">Data lengkap & tersimpan</p>
        </div>
        <span class="text-xs font-semibold text-[#1D9E75]">Selesai</span>
      </div>

      {{-- Step 2 --}}
      <div class="bg-white border border-gray-100 rounded-2xl px-4 py-3 flex items-center gap-3">
        <div class="w-9 h-9 rounded-xl flex-shrink-0 flex items-center justify-center
             {{ $statusHalal === 'approved' ? 'bg-[#E1F5EE] text-[#1D9E75]' : 'bg-amber-50 text-amber-500' }}">
          @if($statusHalal === 'approved')
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10"/></svg>
          @else
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 3h6l3 9l-6 -3l-6 3z"/><path d="M6 12l-3 9h18l-3 -9"/></svg>
          @endif
        </div>
        <div class="flex-1">
          <p class="text-sm font-semibold text-gray-800">Upload sertifikat halal</p>
          <p class="text-xs text-gray-400">
            {{ $statusHalal === 'approved' ? 'Sertifikat diterima' : 'Menunggu verifikasi admin' }}
          </p>
        </div>
        <span class="text-xs font-semibold {{ $statusHalal === 'approved' ? 'text-[#1D9E75]' : 'text-amber-500' }}">
          {{ $statusHalal === 'approved' ? 'Selesai' : 'Proses' }}
        </span>
      </div>

      {{-- Step 3 --}}
      <div class="bg-white border border-gray-100 rounded-2xl px-4 py-3 flex items-center gap-3">
        <div class="w-9 h-9 rounded-xl flex-shrink-0 flex items-center justify-center
             {{ $statusHalal === 'approved' ? 'bg-[#E1F5EE] text-[#1D9E75]' : 'bg-gray-100 text-gray-400' }}">
          @if($statusHalal === 'approved')
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10"/></svg>
          @else
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M12 2a10 10 0 1 0 0 20A10 10 0 0 0 12 2"/></svg>
          @endif
        </div>
        <div class="flex-1">
          <p class="text-sm font-semibold text-gray-800">Usaha tampil di peta</p>
          <p class="text-xs text-gray-400">
            {{ $statusHalal === 'approved' ? 'Sudah bisa ditemukan pengguna' : 'Setelah di setujui' }}
          </p>
        </div>
        <span class="text-xs font-semibold {{ $statusHalal === 'approved' ? 'text-[#1D9E75]' : 'text-gray-400' }}">
          {{ $statusHalal === 'approved' ? 'Aktif' : 'Menunggu' }}
        </span>
      </div>

    </div>
  </div>

</div>

<style>
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection