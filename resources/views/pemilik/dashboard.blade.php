{{-- resources/views/pemilik/dashboard.blade.php --}}
@extends('layouts.pemilik')

@section('content')
<div class="pb-10">

  {{-- HERO --}}
  <div class="px-5 pt-5">
    <p class="text-xs text-gray-400 mb-1">Selamat datang kembali</p>
    <h1 class="text-lg sm:text-xl font-bold text-gray-900 mb-0.5 break-words">
      Halo, {{ Auth::user()->name }}
    </h1>
    <p class="text-sm text-gray-500 mb-4">Kelola semua usaha halalmu di sini.</p>
  </div>

  {{-- STATISTIK TOTAL --}}
  <div class="grid grid-cols-2 md:grid-cols-4 gap-3 px-4 mb-5">

    {{-- Total Usaha --}}
    <div class="bg-white border border-gray-100 rounded-2xl p-4">
      <div class="text-[#1D9E75] mb-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
          <path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z"/>
          <path d="M8 7v13"/><path d="M16 7v13"/><path d="M3 12h18"/>
        </svg>
      </div>
      <p class="text-lg font-bold text-gray-900">{{ $totalUsaha }}</p>
      <p class="text-xs text-gray-400">Total Usaha</p>
    </div>

    {{-- Total Menu --}}
    <div class="bg-white border border-gray-100 rounded-2xl p-4">
      <div class="text-amber-500 mb-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
          <path d="M3 3h18v4h-18z"/>
          <path d="M3 7v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2 -2v-10"/>
          <path d="M12 12l0 .01"/><path d="M8 12l0 .01"/><path d="M16 12l0 .01"/>
        </svg>
      </div>
      <p class="text-lg font-bold text-gray-900">{{ $totalMenu }}</p>
      <p class="text-xs text-gray-400">Total Menu</p>
    </div>

    {{-- Terverifikasi --}}
    <div class="bg-white border border-gray-100 rounded-2xl p-4">
      <div class="text-green-500 mb-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
          <path d="M5 7.2a2.2 2.2 0 0 1 2.2 -2.2h1a2.2 2.2 0 0 0 1.55 -.64l.7 -.7a2.2 2.2 0 0 1 3.12 0l.7 .7c.412 .41 .97 .64 1.55 .64h1a2.2 2.2 0 0 1 2.2 2.2v1c0 .58 .23 1.138 .64 1.55l.7 .7a2.2 2.2 0 0 1 0 3.12l-.7 .7a2.2 2.2 0 0 0 -.64 1.55v1a2.2 2.2 0 0 1 -2.2 2.2h-1a2.2 2.2 0 0 0 -1.55 .64l-.7 .7a2.2 2.2 0 0 1 -3.12 0l-.7 -.7a2.2 2.2 0 0 0 -1.55 -.64h-1a2.2 2.2 0 0 1 -2.2 -2.2v-1a2.2 2.2 0 0 0 -.64 -1.55l-.7 -.7a2.2 2.2 0 0 1 0 -3.12l.7 -.7a2.2 2.2 0 0 0 .64 -1.55v-1"/>
          <path d="M9 12l2 2l4 -4"/>
        </svg>
      </div>
      <p class="text-lg font-bold text-gray-900">{{ $totalTerverifikasi }}</p>
      <p class="text-xs text-gray-400">Terverifikasi</p>
    </div>

    {{-- Diproses --}}
    <div class="bg-white border border-gray-100 rounded-2xl p-4">
      <div class="text-amber-400 mb-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="10"/>
          <polyline points="12 6 12 12 16 14"/>
        </svg>
      </div>
      <p class="text-lg font-bold text-gray-900">{{ $totalPending }}</p>
      <p class="text-xs text-gray-400">Diproses</p>
    </div>

  </div>

  {{-- Baris kedua stat: Ditolak --}}
  @if($totalDitolak > 0)
  <div class="px-4 mb-5">
    <div class="bg-red-50 border border-red-100 rounded-2xl px-4 py-3 flex items-center gap-3">
      <div class="text-red-500 flex-shrink-0">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="10"/>
          <line x1="15" y1="9" x2="9" y2="15"/>
          <line x1="9" y1="9" x2="15" y2="15"/>
        </svg>
      </div>
      <div class="flex-1 min-w-0">
        <p class="text-sm font-semibold text-red-700">
          {{ $totalDitolak }} usaha ditolak
        </p>
        <p class="text-xs text-red-400">Silakan cek detail usaha dan upload ulang sertifikat.</p>
      </div>
      <a href="{{ route('pemilik.toko.index') }}" class="text-xs text-red-500 font-semibold flex-shrink-0">
        Lihat
      </a>
    </div>
  </div>
  @endif

  {{-- ACTION BUTTONS --}}
  <div class="px-5 mb-5">
    <a href="{{ route('pemilik.toko.create') }}"
       class="flex items-center justify-center gap-2 w-full py-3.5 rounded-2xl bg-[#1D9E75] text-white font-semibold text-sm">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <line x1="12" y1="5" x2="12" y2="19"/>
        <line x1="5" y1="12" x2="19" y2="12"/>
      </svg>
      + Daftarkan Usaha Baru
    </a>
  </div>

  {{-- DAFTAR TOKO TERVERIFIKASI (tampil di peta) --}}
  @php
    $tokoVerified = $restorans->filter(function($r) {
      return in_array($r->verifikasiHalal?->status, ['approved', 'terverifikasi']);
    });
  @endphp

  @if($tokoVerified->count() > 0)
  <div class="px-5 mb-5">
    <div class="flex justify-between items-center mb-3">
      <p class="text-sm font-bold">Sudah Tampil di Peta</p>
      <span class="text-xs text-gray-400">{{ $tokoVerified->count() }} usaha</span>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
      @foreach($tokoVerified as $toko)
        @php $verifikasi = $toko->verifikasiHalal; @endphp
        <div class="bg-white border border-green-100 rounded-2xl p-4 hover:shadow-md transition-shadow">
          <div class="flex items-start justify-between">
            <div class="flex-1 min-w-0">
              <div class="flex items-center gap-2 mb-1">
                <h3 class="text-sm font-semibold text-gray-900 truncate">{{ $toko->nama_restoran }}</h3>
                <span class="text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-700 flex-shrink-0">
                  Terverifikasi
                </span>
              </div>
              @if($toko->alamat)
                <p class="text-xs text-gray-500 truncate mb-1">{{ $toko->alamat }}</p>
              @endif
              <div class="flex items-center gap-4 text-xs text-gray-400">
                <span>Menu: {{ $toko->menu->count() }}</span>
                @if($verifikasi?->no_sertifikat)
                  <span>No. Sertifikat: {{ $verifikasi->no_sertifikat }}</span>
                @endif
                @if($verifikasi?->masa_berlaku)
                  <span>Berlaku s/d: {{ \Carbon\Carbon::parse($verifikasi->masa_berlaku)->format('d/m/Y') }}</span>
                @endif
              </div>
            </div>
            <div class="flex flex-col items-end gap-1 ml-3 flex-shrink-0">
              <a href="{{ route('pemilik.toko.show', $toko->id_restoran) }}"
                 class="text-xs text-[#1D9E75] font-semibold hover:underline">Detail</a>
            </div>
          </div>
          {{-- Progress bar 100% --}}
          <div class="mt-3 pt-3 border-t border-gray-50">
            <div class="flex items-center gap-2">
              <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full rounded-full bg-[#1D9E75]" style="width: 100%"></div>
              </div>
              <span class="text-[10px] font-semibold text-gray-400 flex-shrink-0">100%</span>
            </div>
            <p class="text-[10px] text-gray-400 mt-1">Siap tampil di peta</p>
          </div>
        </div>
      @endforeach
    </div>
  </div>
  @endif

  {{-- DAFTAR SEMUA TOKO --}}
  <div class="px-5">
    <div class="flex justify-between items-center mb-3">
      <p class="text-sm font-bold">Daftar Usaha Saya</p>
      @if($restorans->count() > 0)
        <a href="{{ route('pemilik.toko.index') }}" class="text-xs text-[#1D9E75] font-semibold">Lihat Semua</a>
      @endif
    </div>

    @if($restorans->count() > 0)
      <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        @foreach($restorans as $toko)
          @php
            $verifikasi  = $toko->verifikasiHalal;
            $statusHalal = $verifikasi?->status ?? null;
            $isApproved  = in_array($statusHalal, ['approved', 'terverifikasi']);
            $isPending   = in_array($statusHalal, ['pending', 'proses']);
            $isDitolak   = $statusHalal === 'ditolak';

            $statusColor = $isApproved ? 'bg-green-100 text-green-700'
                         : ($isPending  ? 'bg-amber-100 text-amber-700'
                         : ($isDitolak  ? 'bg-red-100 text-red-700'
                         : 'bg-gray-100 text-gray-700'));

            $statusText  = $isApproved ? 'Terverifikasi'
                         : ($isPending  ? 'Diproses'
                         : ($isDitolak  ? 'Ditolak'
                         : 'Belum Daftar'));

            $progress = 20; // toko sudah ada
            if ($verifikasi)  $progress += 40;
            if ($isApproved)  $progress += 40;
          @endphp

          <div class="bg-white border border-gray-100 rounded-2xl p-4 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
              <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-1">
                  <h3 class="text-sm font-semibold text-gray-900 truncate">{{ $toko->nama_restoran }}</h3>
                  <span class="text-xs px-2 py-0.5 rounded-full {{ $statusColor }} flex-shrink-0">
                    {{ $statusText }}
                  </span>
                </div>

                @if($toko->alamat)
                  <p class="text-xs text-gray-500 truncate mb-1">{{ $toko->alamat }}</p>
                @endif

                <div class="flex items-center gap-4 text-xs text-gray-400">
                  <span>Menu: {{ $toko->menu->count() }}</span>
                  @if($isApproved && $verifikasi?->no_sertifikat)
                    <span>Sertifikat: {{ $verifikasi->no_sertifikat }}</span>
                  @endif
                </div>
              </div>

              <div class="flex flex-col items-end gap-1 ml-3 flex-shrink-0">
                <a href="{{ route('pemilik.toko.show', $toko->id_restoran) }}"
                   class="text-xs text-[#1D9E75] font-semibold hover:underline">Detail</a>
              </div>
            </div>

            {{-- Progress bar --}}
            <div class="mt-3 pt-3 border-t border-gray-50">
              <div class="flex items-center gap-2">
                <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                  <div class="h-full rounded-full bg-[#1D9E75] transition-all duration-500"
                       style="width: {{ $progress }}%"></div>
                </div>
                <span class="text-[10px] font-semibold text-gray-400 flex-shrink-0">{{ $progress }}%</span>
              </div>
              <p class="text-[10px] text-gray-400 mt-1">
                @if($isApproved)
                  Siap tampil di peta
                @elseif($isPending)
                  Menunggu verifikasi admin
                @elseif($isDitolak)
                  {{ $verifikasi?->catatan ?? 'Silakan upload ulang sertifikat' }}
                @else
                  Lengkapi data usaha
                @endif
              </p>
            </div>
          </div>
        @endforeach
      </div>
    @else
      {{-- Empty State --}}
      <div class="bg-white border border-gray-100 rounded-2xl p-8 text-center">
        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z"/>
            <path d="M8 7v13"/><path d="M16 7v13"/><path d="M3 12h18"/>
          </svg>
        </div>
        <p class="text-sm font-semibold text-gray-900 mb-1">Belum Ada Usaha</p>
        <p class="text-xs text-gray-400 mb-3">Daftarkan usaha halal pertamamu sekarang!</p>
        <a href="{{ route('pemilik.toko.create') }}"
           class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-[#1D9E75] text-white text-sm font-semibold">
          + Daftarkan Usaha
        </a>
      </div>
    @endif
  </div>

</div>

<style>
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection