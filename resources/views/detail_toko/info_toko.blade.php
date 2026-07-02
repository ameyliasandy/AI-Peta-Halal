{{--
  detail_toko/info_toko.blade.php
  Nama, badge, rating, status buka, deskripsi, grid info.
  Variabel: $restoran
--}}

@php
  $statusVerif = $restoran->verifikasiHalal?->status;
  $tipeHalal   = $restoran->status_halal; // certified / self_claimed / none
  $isVerified  = in_array($statusVerif, ['terverifikasi', 'approved']);
@endphp

<div class="tname">
  {{ $restoran->nama_restoran }}
  
  @if($isVerified && $tipeHalal === 'certified')
    <span class="hb">HALAL MUI</span>
  @elseif($isVerified && $tipeHalal === 'self_claimed')
    <span class="sb">Self-Claimed ✓</span>
  @elseif($tipeHalal === 'self_claimed')
    <span class="sb">Self-Claimed</span>
  @elseif($statusVerif === 'pending')
    <span class="sb" style="background:#fef3c7;color:#92400e">Menunggu Verifikasi</span>
  @elseif($statusVerif === 'ditolak')
    <span class="sb" style="background:#fee2e2;color:#991b1b">Ditolak</span>
  @else
    <span class="sb">Belum Diverifikasi</span>
  @endif
</div>

<div class="rrow">
  <div style="display:flex;align-items:center;gap:4px">
    <div class="star-icon"></div>
    <span>{{ number_format($restoran->rating ?? 0, 1) }} ({{ $restoran->jumlah_ulasan ?? 0 }} ulasan)</span>
  </div>
  <div style="display:flex;align-items:center;gap:5px">
    <span class="dot" style="background:{{ $restoran->status_buka ? '#22c55e' : 'var(--r)' }}"></span>
    <span style="color:{{ $restoran->status_buka ? '#22c55e' : 'var(--r)' }}">
      {{ $restoran->status_buka ? 'Buka sekarang' : 'Tutup' }}
    </span>
  </div>
</div>

@if($restoran->deskripsi)
  <div class="tdesc">{{ $restoran->deskripsi }}</div>
@endif

<div class="igrid">
  <div class="iitem">
    <div class="icon-clock"></div>
    <div>
      <div class="ilbl">Jam Operasional</div>
      <div class="ival">{{ $restoran->jam_operasional ?? '—' }}</div>
    </div>
  </div>

  <div class="iitem">
    <div class="icon-money"></div>
    <div>
      <div class="ilbl">Kisaran Harga</div>
      <div class="ival g">
      @if($restoran->harga_rata_rata_min && $restoran->harga_rata_rata_max)
          Rp {{ number_format($restoran->harga_rata_rata_min,0,',','.') }}
          -
          Rp {{ number_format($restoran->harga_rata_rata_max,0,',','.') }}
      @elseif($restoran->harga_rata_rata_min)
          Mulai Rp {{ number_format($restoran->harga_rata_rata_min,0,',','.') }}
      @else
          —
      @endif
      </div>
    </div>
  </div>

  <div class="iitem">
    <div class="icon-phone"></div>
    <div>
      <div class="ilbl">Telepon</div>
      <div class="ival g">{{ $restoran->no_telepon ?? '—' }}</div>
    </div>
  </div>

  <div class="iitem">
    <div class="icon-tag"></div>
    <div>
      <div class="ilbl">Kategori</div>
      <div class="ival g">
        {{ $restoran->subKategori?->nama_sub_kategori ?? $restoran->kategori?->nama_kategori ?? '—' }}
      </div>
    </div>
  </div>

  <div class="iitem" style="grid-column:1/-1">
    <div class="icon-location"></div>
    <div>
      <div class="ilbl">Alamat</div>
      <div class="ival">{{ $restoran->alamat }}, {{ $restoran->kota }}</div>
      @if(isset($restoran->jarak_km))
      <div class="flex items-center gap-1 mt-1">
        <div class="w-1.5 h-1.5 rounded-full bg-[#2D6A4F]"></div>
        <span class="text-xs text-gray-500">{{ $restoran->jarak_km }} km</span>
      </div>
      @endif
    </div>
  </div>
</div>

<style>
/* CSS Icons */
.star-icon {
  width: 16px;
  height: 16px;
  background: #f59e0b;
  clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);
  flex-shrink: 0;
}

.icon-clock {
  width: 20px;
  height: 20px;
  flex-shrink: 0;
  background: currentColor;
  mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='currentColor'%3E%3Ccircle cx='12' cy='12' r='10'/%3E%3Cpolyline points='12 6 12 12 16 14'/%3E%3C/svg%3E") no-repeat center;
  mask-size: contain;
  -webkit-mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='currentColor'%3E%3Ccircle cx='12' cy='12' r='10'/%3E%3Cpolyline points='12 6 12 12 16 14'/%3E%3C/svg%3E") no-repeat center;
  -webkit-mask-size: contain;
}

.icon-money {
  width: 20px;
  height: 20px;
  flex-shrink: 0;
  background: currentColor;
  mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='currentColor'%3E%3Cline x1='12' y1='1' x2='12' y2='23'/%3E%3Cpath d='M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6'/%3E%3C/svg%3E") no-repeat center;
  mask-size: contain;
  -webkit-mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='currentColor'%3E%3Cline x1='12' y1='1' x2='12' y2='23'/%3E%3Cpath d='M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6'/%3E%3C/svg%3E") no-repeat center;
  -webkit-mask-size: contain;
}

.icon-phone {
  width: 20px;
  height: 20px;
  flex-shrink: 0;
  background: currentColor;
  mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='currentColor'%3E%3Cpath d='M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81 2 2 0 012 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L6.09 8.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z'/%3E%3C/svg%3E") no-repeat center;
  mask-size: contain;
  -webkit-mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='currentColor'%3E%3Cpath d='M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81 2 2 0 012 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L6.09 8.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z'/%3E%3C/svg%3E") no-repeat center;
  -webkit-mask-size: contain;
}

.icon-tag {
  width: 20px;
  height: 20px;
  flex-shrink: 0;
  background: currentColor;
  mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='currentColor'%3E%3Cpath d='M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z'/%3E%3C/svg%3E") no-repeat center;
  mask-size: contain;
  -webkit-mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='currentColor'%3E%3Cpath d='M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z'/%3E%3C/svg%3E") no-repeat center;
  -webkit-mask-size: contain;
}

.icon-location {
  width: 20px;
  height: 20px;
  flex-shrink: 0;
  background: currentColor;
  mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='currentColor'%3E%3Cpath d='M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z'/%3E%3Ccircle cx='12' cy='10' r='3'/%3E%3C/svg%3E") no-repeat center;
  mask-size: contain;
  -webkit-mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='currentColor'%3E%3Cpath d='M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z'/%3E%3Ccircle cx='12' cy='10' r='3'/%3E%3C/svg%3E") no-repeat center;
  -webkit-mask-size: contain;
}
</style>