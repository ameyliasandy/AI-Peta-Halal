@extends('layouts.app')
@section('title', $restoran->nama_restoran . ' — Peta Halal Batam')

@section('content')
<style>
.dt-wrap{max-width:960px;margin:0 auto;padding:32px 20px}
.btn-back{
  display:inline-flex;
  align-items:center;
  gap:8px;
  padding:8px 16px;
  background:white;
  color:#374151;
  font-weight:600;
  font-size:13px;
  border-radius:10px;
  border:1px solid #e5e7eb;
  transition:all .2s ease;
  text-decoration:none;
  margin-bottom:20px;
}
.btn-back:hover{
  background:#f9fafb;
  border-color:#9ca3af;
  transform:translateX(-2px);
}
.cover-wrap{border-radius:20px;overflow:hidden;margin-bottom:28px;height:280px;background:#e5e7eb}
.cover-wrap img{width:100%;height:280px;object-fit:cover;display:block}
.cover-ph{width:100%;height:280px;background:linear-gradient(135deg,#d1d5db,#9ca3af);display:flex;align-items:center;justify-content:center}
.layout{display:grid;grid-template-columns:1fr 300px;gap:24px;align-items:start}
@media(max-width:700px){.layout{grid-template-columns:1fr}}
.card{background:#fff;border-radius:18px;border:1px solid #e5e7eb;padding:24px;box-shadow:0 2px 12px rgba(0,0,0,.04)}
.tname{font-size:24px;font-weight:800;color:#111827;display:flex;align-items:center;gap:10px;flex-wrap:wrap}
.hb{background:#2D6A4F;color:#fff;font-size:11px;font-weight:700;padding:4px 12px;border-radius:20px}
.sb{background:#7c3aed;color:#fff;font-size:11px;font-weight:700;padding:4px 12px;border-radius:20px}
.sb-open{background:#22c55e;color:#fff;font-size:11px;font-weight:700;padding:4px 12px;border-radius:20px}
.sb-close{background:#6b7280;color:#fff;font-size:11px;font-weight:700;padding:4px 12px;border-radius:20px}
.rrow{display:flex;align-items:center;gap:12px;margin-top:10px;font-size:13px;font-weight:600;color:#374151;flex-wrap:wrap}
.dot{width:7px;height:7px;border-radius:50%;flex-shrink:0}
.tdesc{font-size:14px;color:#4b5563;line-height:1.8;margin-top:16px;padding-top:16px;border-top:1px solid #f3f4f6}
.igrid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:18px}
@media(max-width:480px){.igrid{grid-template-columns:1fr}}
.iitem{background:#f9fafb;border-radius:12px;padding:12px 14px;display:flex;align-items:flex-start;gap:10px}
.iitem svg{width:16px;height:16px;color:#6b7280;flex-shrink:0;margin-top:2px}
.ilbl{font-size:11px;color:#6b7280;font-weight:600;text-transform:uppercase;letter-spacing:.04em}
.ival{font-size:13px;font-weight:600;color:#111827;margin-top:2px}
.ival.g{color:#2D6A4F}
.verif-banner{display:flex;align-items:center;gap:12px;background:#dcfce7;border-radius:12px;padding:12px 16px;margin-top:18px;border:1px solid #86efac}
.verif-banner svg{color:#2D6A4F;flex-shrink:0}
.verif-banner-t{font-size:13px;font-weight:700;color:#2D6A4F}
.verif-banner-s{font-size:11px;color:#166534;margin-top:2px}
.vc{background:#fff;border-radius:16px;border:1px solid #e5e7eb;padding:18px;margin-bottom:16px;box-shadow:0 2px 12px rgba(0,0,0,.04)}
.vc-t{font-size:14px;font-weight:700;color:#111827;margin-bottom:12px}
.vr{display:flex;justify-content:space-between;align-items:center;font-size:13px;padding:7px 0;border-bottom:1px solid #f3f4f6}
.vr:last-of-type{border:none}
.vl{color:#6b7280;flex-shrink:0}
.vv{font-weight:600;color:#111827;font-size:12px;text-align:right}
.map-box{border-radius:12px;overflow:hidden;height:210px;background:#f3f4f6;margin-top:4px}
.map-box iframe{width:100%;height:100%;border:none;display:block}
.tacts{display:flex;gap:8px;margin-top:18px;flex-wrap:wrap}

/* Ulasan */
.review-card{padding:24px}
.review-card .vc-t{font-size:16px;font-weight:800;margin-bottom:18px}
.ulasan-list{margin-top:16px}
.ulasan-item{padding:14px 0;border-bottom:1px solid #f3f4f6}
.ulasan-item:last-child{border-bottom:none}
.ulasan-header{display:flex;align-items:center;gap:10px;margin-bottom:4px;flex-wrap:wrap}
.ulasan-user{font-weight:700;font-size:13px;color:#111827}
.ulasan-date{font-size:11px;color:#6b7280}
.ulasan-rating{color:#f59e0b;font-size:13px}
.ulasan-text{font-size:13px;color:#4b5563;line-height:1.6;margin-top:2px}
.ulasan-empty{text-align:center;padding:30px 0;color:#6b7280;font-size:14px}
.text-green{color:#2D6A4F}
</style>

@php $v = $restoran->verifikasiHalal; @endphp

<div class="dt-wrap">

  {{-- TOMBOL KEMBALI --}}
  <a href="{{ url()->previous() !== url()->current() ? url()->previous() : '/' }}" 
     class="btn-back">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
      <polyline points="15 18 9 12 15 6"/>
    </svg>
    Kembali
  </a>

  {{-- COVER --}}
  <div class="cover-wrap">
    @if($restoran->foto_utama)
      <img src="{{ asset('storage/'.$restoran->foto_utama) }}" alt="{{ $restoran->nama_restoran }}">
    @else
      <div class="cover-ph">
        <span style="color:#fff;font-size:18px;font-weight:600;opacity:.7">📸 Foto Cover</span>
      </div>
    @endif
  </div>

  <div class="layout">
    {{-- KIRI: Info Toko --}}
    <div>
      <div class="card">
        {{-- Nama & Badge --}}
        <div class="tname">
          {{ $restoran->nama_restoran }}
          @if($v?->status === 'terverifikasi')
            <span class="hb">✓ Terverifikasi</span>
          @endif
          @if($restoran->status_buka === 'buka')
            <span class="sb-open">● Buka</span>
          @else
            <span class="sb-close">● Tutup</span>
          @endif
        </div>

        {{-- Rating --}}
        <div class="rrow">
          <span style="color:#f59e0b;font-size:16px">★</span>
          <span>{{ number_format($restoran->ulasan_avg_rating ?? 0, 1) }}</span>
          <span class="dot" style="background:#d1d5db"></span>
          <span>{{ $restoran->ulasan_count ?? 0 }} ulasan</span>
          @if($restoran->kategori)
            <span class="dot" style="background:#d1d5db"></span>
            <span style="font-weight:400;color:#6b7280">{{ $restoran->kategori->nama_kategori ?? '' }}</span>
          @endif
        </div>

        {{-- Deskripsi --}}
        @if($restoran->deskripsi)
          <div class="tdesc">{{ $restoran->deskripsi }}</div>
        @endif

        {{-- Info Grid --}}
        <div class="igrid">
          <div class="iitem">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/>
              <circle cx="12" cy="10" r="3"/>
            </svg>
            <div>
              <div class="ilbl">Alamat</div>
              <div class="ival">{{ $restoran->alamat ?? '—' }}</div>
            </div>
          </div>
          <div class="iitem">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10"/>
              <polyline points="12 6 12 12 16 14"/>
            </svg>
            <div>
              <div class="ilbl">Jam Operasional</div>
              <div class="ival">{{ $restoran->jam_operasional ?? '—' }}</div>
            </div>
          </div>
          <div class="iitem">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81 2 2 0 012 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L6.09 8.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/>
            </svg>
            <div>
              <div class="ilbl">Telepon</div>
              <div class="ival">{{ $restoran->no_telepon ?? '—' }}</div>
            </div>
          </div>
          <div class="iitem">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10"/>
              <line x1="2" y1="12" x2="22" y2="12"/>
              <path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/>
            </svg>
            <div>
              <div class="ilbl">Harga Rata-rata</div>
              <div class="ival">{{ $restoran->harga_rata_rata ?? '—' }}</div>
            </div>
          </div>
        </div>

        {{-- Banner verifikasi halal --}}
        @if($v?->status === 'terverifikasi')
        <div class="verif-banner">
          <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            <polyline points="9 12 11 14 15 10"/>
          </svg>
          <div>
            <div class="verif-banner-t">Terverifikasi Halal</div>
            <div class="verif-banner-s">
              {{ $v->lembaga_penerbit ?? 'Peta Halal Batam' }}
              @if($v->no_sertifikat) · No. {{ $v->no_sertifikat }} @endif
            </div>
          </div>
        </div>
        @endif

        {{-- TOMBOL AKSI GUEST (tanpa favorit) --}}
        <div class="tacts">
          {{-- Tombol Hubungi --}}
          @if($restoran->no_telepon)
          <a href="tel:{{ $restoran->no_telepon }}" 
             class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white font-bold text-sm rounded-lg transition-all hover:-translate-y-0.5 hover:shadow-md">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81 2 2 0 012 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L6.09 8.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/>
            </svg>
            Hubungi
          </a>
          @endif

          {{-- Tombol Lihat Menu --}}
          @if($restoran->menu->count() > 0)
          <button onclick="openMenuModal()" 
             class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white hover:bg-green-50 text-green-600 font-bold text-sm rounded-lg border border-green-600 transition-all hover:-translate-y-0.5">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M4 12h16M4 18h16M4 6h16"/>
            </svg>
            Lihat Menu
          </button>
          @endif
        </div>
      </div>
    </div>

    {{-- KANAN: Peta + Info Singkat --}}
    <div>
      @php
        $lokasiMaps = trim(
          ($restoran->nama_restoran ?? '') . ', ' .
          ($restoran->alamat ?? '') . ', Batam'
        );
        $queryMaps = urlencode($lokasiMaps);
      @endphp

      {{-- Peta --}}
      <div class="vc">
        <div class="vc-t">Lokasi Toko</div>
        <div class="map-box">
          <iframe
            src="https://maps.google.com/maps?q={{ $queryMaps }}&hl=id&z=16&output=embed"
            loading="lazy"
            allowfullscreen>
          </iframe>
        </div>

        {{-- TOMBOL BUKA MAPS --}}
        <a href="https://www.google.com/maps/search/?api=1&query={{ $queryMaps }}"
          target="_blank"
          class="inline-flex items-center justify-center gap-2 w-full px-5 py-2.5 mt-3 bg-green-600 hover:bg-green-700 text-white font-bold text-sm rounded-lg transition-all hover:-translate-y-0.5 hover:shadow-md">
          <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/>
            <circle cx="12" cy="10" r="3"/>
          </svg>
          Buka Google Maps
        </a>

        {{-- Lokasi dicari berdasarkan --}}
        <div style="margin-top:14px;background:#f9fafb;border-radius:12px;padding:12px">
          <div style="font-size:12px;color:#6b7280;margin-bottom:8px">Lokasi Dicari Berdasarkan</div>
          <div style="font-size:13px;font-weight:700;color:#111827">{{ $restoran->nama_restoran }}</div>
          @if($restoran->alamat)
            <div style="margin-top:5px;font-size:12px;color:#374151">{{ $restoran->alamat }}</div>
          @endif
        </div>

        @if($restoran->latitude && $restoran->longitude)
          <div style="margin-top:10px;font-size:12px;color:#6b7280">
            Koordinat tersimpan:<br>
            Lat: <b>{{ $restoran->latitude }}</b> | Long: <b>{{ $restoran->longitude }}</b>
          </div>
        @endif
      </div>

      {{-- Info singkat --}}
      <div class="vc">
        <div class="vc-t">Informasi</div>
        <div class="vr">
          <span class="vl">Jam Buka</span>
          <span class="vv">{{ $restoran->jam_operasional ?? '—' }}</span>
        </div>
        @if($restoran->no_telepon)
        <div class="vr">
          <span class="vl">Telepon</span>
          <span class="vv">{{ $restoran->no_telepon }}</span>
        </div>
        @endif
        @if($restoran->website_sosmed)
        <div class="vr">
          <span class="vl">Media Sosial</span>
          <a href="{{ $restoran->website_sosmed }}" target="_blank"
             class="vv" style="color:#2D6A4F;word-break:break-all;text-decoration:none">
            {{ Str::limit($restoran->website_sosmed, 22) }}
          </a>
        </div>
        @endif
      </div>
    </div>
  </div>

  {{-- MENU — read only --}}
  @if($restoran->menu->count() > 0)
  <div id="menu-section" style="margin-top:24px">
    @include('detail_toko.grid_menu')
  </div>
  @endif

  {{-- ULASAN (Guest hanya bisa lihat) --}}
  <div class="card review-card" style="margin-top:24px">
    <div class="vc-t">📝 Ulasan 
      <span style="font-weight:400;font-size:13px;color:#6b7280">({{ $restoran->ulasan_count ?? 0 }})</span>
    </div>

    <p style="font-size:13px;color:#6b7280;margin-bottom:16px">
      <a href="{{ route('login') }}" style="color:#2D6A4F;font-weight:600">Login</a> untuk memberi ulasan dan rating.
    </p>

    @if(($restoran->ulasan ?? collect())->count() > 0)
      <div class="ulasan-list">
        @foreach($restoran->ulasan->sortByDesc('created_at') as $ulasan)
          <div class="ulasan-item">
            <div class="ulasan-header">
              <span class="ulasan-user">{{ $ulasan->user->name ?? 'Anonymous' }}</span>
              <span class="ulasan-rating">
                @for($i = 1; $i <= 5; $i++)
                  @if($i <= $ulasan->rating)
                    ★
                  @else
                    ☆
                  @endif
                @endfor
              </span>
              <span class="ulasan-date">{{ $ulasan->created_at->diffForHumans() }}</span>
            </div>
            @if($ulasan->komentar)
              <div class="ulasan-text">{{ $ulasan->komentar }}</div>
            @endif
          </div>
        @endforeach
      </div>
    @else
      <div class="ulasan-empty">
        <div style="font-size:40px;margin-bottom:8px">💬</div>
        <p>Belum ada ulasan untuk restoran ini.</p>
        <p style="font-size:12px;color:#6b7280;margin-top:4px">Jadilah yang pertama memberi ulasan!</p>
      </div>
    @endif
  </div>

</div>

{{-- MENU MODAL --}}
<div id="menuModal" class="fixed inset-0 z-50 hidden items-center justify-center">
  <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeMenuModal()"></div>
  <div class="relative bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto p-6 m-4">
    <button onclick="closeMenuModal()" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 transition flex items-center justify-center">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
      </svg>
    </button>
    <h2 class="text-xl font-bold text-gray-800 mb-4 pr-8">Menu {{ $restoran->nama_restoran }}</h2>
    
    @if($restoran->menu->count() > 0)
      <div class="space-y-3">
        @foreach($restoran->menu as $menu)
          <div class="flex gap-4 p-3 bg-gray-50 rounded-xl">
            @if($menu->foto)
              <img src="{{ asset('storage/' . $menu->foto) }}" alt="{{ $menu->nama_menu }}" class="w-16 h-16 rounded-xl object-cover">
            @else
              <div class="w-16 h-16 rounded-xl bg-gray-200 flex items-center justify-center text-2xl">🍽️</div>
            @endif
            <div class="flex-1">
              <p class="font-semibold text-sm text-gray-800">{{ $menu->nama_menu }}</p>
              <p class="text-green-700 font-bold text-sm">Rp {{ number_format($menu->harga, 0, ',', '.') }}</p>
              @if($menu->deskripsi)
                <p class="text-xs text-gray-500 mt-1">{{ Str::limit($menu->deskripsi, 60) }}</p>
              @endif
            </div>
          </div>
        @endforeach
      </div>
    @else
      <div class="text-center py-8">
        <div style="font-size:48px;margin-bottom:8px">🍽️</div>
        <p class="text-gray-500">Belum ada menu yang ditambahkan</p>
      </div>
    @endif
  </div>
</div>

<script>
function openMenuModal() {
  document.getElementById('menuModal').classList.remove('hidden');
  document.getElementById('menuModal').classList.add('flex');
  document.body.style.overflow = 'hidden';
}

function closeMenuModal() {
  document.getElementById('menuModal').classList.add('hidden');
  document.getElementById('menuModal').classList.remove('flex');
  document.body.style.overflow = '';
}

document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') closeMenuModal();
});

// Close modal on overlay click
document.getElementById('menuModal')?.addEventListener('click', function(e) {
  if (e.target === this) closeMenuModal();
});
</script>

@endsection