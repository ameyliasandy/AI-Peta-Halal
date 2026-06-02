{{--
  pencari/detail_toko.blade.php
  Detail toko versi PENCARI / publik.
  Hanya bisa: lihat info, lihat menu, lihat peta.
  Tidak ada tombol edit, verifikasi, atau aksi apapun.
--}}
@extends('layouts.app')
@section('title', $restoran->nama_restoran . ' — Peta Halal Batam')

@push('styles')
<style>
/* ── Wrapper ── */
.dt-wrap{max-width:960px;margin:0 auto;padding:32px 20px}

/* ── Cover ── */
.cover-wrap{border-radius:20px;overflow:hidden;margin-bottom:28px;height:280px;background:var(--s2)}
.cover-wrap img{width:100%;height:280px;object-fit:cover;display:block}
.cover-ph{
  width:100%;height:280px;
  background:linear-gradient(135deg,var(--gl),var(--gm));
  display:flex;align-items:center;justify-content:center
}

/* ── Layout dua kolom ── */
.layout{display:grid;grid-template-columns:1fr 300px;gap:24px;align-items:start}
@media(max-width:700px){.layout{grid-template-columns:1fr}}

/* ── Card utama ── */
.card{
  background:#fff;border-radius:18px;
  border:1px solid var(--s2);padding:24px;
  box-shadow:0 2px 12px rgba(0,0,0,.04)
}

/* ── Nama & badge ── */
.tname{font-size:24px;font-weight:800;color:var(--s9);display:flex;align-items:center;gap:10px;flex-wrap:wrap}
.hb{background:var(--g);color:#fff;font-size:11px;font-weight:700;padding:4px 12px;border-radius:20px}
.sb{background:#7c3aed;color:#fff;font-size:11px;font-weight:700;padding:4px 12px;border-radius:20px}

/* ── Rating row ── */
.rrow{display:flex;align-items:center;gap:12px;margin-top:10px;font-size:13px;font-weight:600;color:var(--s7);flex-wrap:wrap}
.dot{width:7px;height:7px;border-radius:50%;flex-shrink:0}

/* ── Deskripsi ── */
.tdesc{font-size:14px;color:var(--s6);line-height:1.8;margin-top:16px;padding-top:16px;border-top:1px solid var(--s1)}

/* ── Info grid ── */
.igrid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:18px}
@media(max-width:480px){.igrid{grid-template-columns:1fr}}
.iitem{background:var(--s1);border-radius:12px;padding:12px 14px;display:flex;align-items:flex-start;gap:10px}
.iitem svg{width:16px;height:16px;color:var(--s4);flex-shrink:0;margin-top:2px}
.ilbl{font-size:11px;color:var(--s4);font-weight:600;text-transform:uppercase;letter-spacing:.04em}
.ival{font-size:13px;font-weight:600;color:var(--s7);margin-top:2px}
.ival.g{color:var(--g)}

/* ── Banner verifikasi halal ── */
.verif-banner{
  display:flex;align-items:center;gap:12px;
  background:var(--gl);border-radius:12px;
  padding:12px 16px;margin-top:18px;
  border:1px solid color-mix(in srgb, var(--g) 20%, transparent)
}
.verif-banner svg{color:var(--g);flex-shrink:0}
.verif-banner-t{font-size:13px;font-weight:700;color:var(--g)}
.verif-banner-s{font-size:11px;color:var(--gd);margin-top:2px}

/* ── Panel sidebar (vc) ── */
.vc{
  background:#fff;border-radius:16px;
  border:1px solid var(--s2);padding:18px;
  margin-bottom:16px;
  box-shadow:0 2px 12px rgba(0,0,0,.04)
}
.vc-t{font-size:14px;font-weight:700;color:var(--s9);margin-bottom:12px}
.vr{display:flex;justify-content:space-between;align-items:center;font-size:13px;padding:7px 0;border-bottom:1px solid var(--s1)}
.vr:last-of-type{border:none}
.vl{color:var(--s6);flex-shrink:0}
.vv{font-weight:600;color:var(--s9);font-size:12px;text-align:right}

/* ── Peta embed ── */
.map-box{border-radius:12px;overflow:hidden;height:210px;background:var(--s1);margin-top:4px}
.map-box iframe{width:100%;height:100%;border:none;display:block}
</style>
@endpush

@section('content')
@php $v = $restoran->verifikasiHalal; @endphp

<div class="dt-wrap">

  @include('detail_toko.cover')

  <div class="layout">
    {{-- KIRI: Info Toko --}}
    <div>
      <div class="card">
        @include('detail_toko.info_toko')

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

        {{-- Tombol aksi pencari --}}
        <div style="display:flex;gap:8px;margin-top:18px;flex-wrap:wrap">
          @if($restoran->no_telepon)
          <a href="tel:{{ $restoran->no_telepon }}" class="btn btn-primary">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81 2 2 0 012 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L6.09 8.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/>
            </svg>
            Hubungi
          </a>
          @endif
          @if($restoran->latitude)
          <a href="https://maps.google.com/?q={{ $restoran->latitude }},{{ $restoran->longitude }}"
             target="_blank" class="btn btn-outline">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/>
              <circle cx="12" cy="10" r="3"/>
            </svg>
            Buka di Maps
          </a>
          @endif
        </div>
      </div>
    </div>

    {{-- KANAN: Peta + Info Singkat --}}
    <div>
      {{-- Peta embed --}}
      @if($restoran->latitude && $restoran->longitude)
      <div class="vc">
        <div class="vc-t">Lokasi</div>
        <div class="map-box">
          <iframe
            src="https://maps.google.com/maps?q={{ $restoran->latitude }},{{ $restoran->longitude }}&z=16&output=embed"
            allowfullscreen loading="lazy">
          </iframe>
        </div>
        <a href="https://maps.google.com/?q={{ $restoran->latitude }},{{ $restoran->longitude }}"
           target="_blank"
           class="btn btn-outline" style="width:100%;justify-content:center;margin-top:10px">
          Buka Google Maps
        </a>
      </div>
      @endif

      {{-- Info singkat --}}
      <div class="vc">
        <div class="vc-t">Informasi</div>
        <div class="vr">
          <span class="vl">Jam Buka</span>
          <span class="vv">{{ $restoran->jam_operasional ?? '—' }}</span>
        </div>
        <div class="vr">
          <span class="vl">Kisaran Harga</span>
          <span class="vv">{{ $restoran->harga_rata_rata ?? '—' }}</span>
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
             class="vv" style="color:var(--g);word-break:break-all">
            {{ Str::limit($restoran->website_sosmed, 22) }}
          </a>
        </div>
        @endif
      </div>
    </div>
  </div>

  {{-- Menu — read only --}}
  @include('detail_toko.grid_menu')

</div>
@endsection