{{--
  detail_toko/info_toko.blade.php
  Nama, badge, rating, status buka, deskripsi, grid info.
  Dipakai di: admin, pemilik, pencari.

  Variabel: $restoran
--}}
<div class="tname">
  {{ $restoran->nama_restoran }}
  @if($restoran->status_halal === 'certified')
    <span class="hb">✓ HALAL MUI</span>
  @elseif($restoran->status_halal === 'self_claimed')
    <span class="sb">Self-Claimed</span>
  @endif
</div>

<div class="rrow">
  <span style="color:#f59e0b">⭐</span>
  <span>{{ number_format($restoran->rating, 1) }} ({{ $restoran->jumlah_ulasan }} ulasan)</span>
  <span style="display:flex;align-items:center;gap:5px">
    <span class="dot" style="background:{{ $restoran->status_buka ? '#22c55e' : 'var(--r)' }}"></span>
    <span style="color:{{ $restoran->status_buka ? '#22c55e' : 'var(--r)' }}">
      {{ $restoran->status_buka ? 'Buka sekarang' : 'Tutup' }}
    </span>
  </span>
</div>

@if($restoran->deskripsi)
  <div class="tdesc">{{ $restoran->deskripsi }}</div>
@endif

<div class="igrid">
  <div class="iitem">
    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
      <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
    </svg>
    <div>
      <div class="ilbl">Jam Operasional</div>
      <div class="ival">{{ $restoran->jam_operasional ?? '—' }}</div>
    </div>
  </div>

  <div class="iitem">
    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
      <line x1="12" y1="1" x2="12" y2="23"/>
      <path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>
    </svg>
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
    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
      <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81 2 2 0 012 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L6.09 8.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/>
    </svg>
    <div>
      <div class="ilbl">Telepon</div>
      <div class="ival g">{{ $restoran->no_telepon ?? '—' }}</div>
    </div>
  </div>

  <div class="iitem">
    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
      <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
    </svg>
    <div>
      <div class="ilbl">Kategori</div>
      <div class="ival g">
        {{ $restoran->subKategori?->nama_sub_kategori ?? $restoran->kategori?->nama_kategori ?? '—' }}
      </div>
    </div>
  </div>

  <div class="iitem" style="grid-column:1/-1">
    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
      <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/>
      <circle cx="12" cy="10" r="3"/>
    </svg>
    <div>
      <div class="ilbl">Alamat</div>
      <div class="ival">{{ $restoran->alamat }}, {{ $restoran->kota }}</div>
    </div>
  </div>
</div>