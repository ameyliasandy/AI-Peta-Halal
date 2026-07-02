{{--
  pemilik/toko/index.blade.php
  Detail toko versi PEMILIK.
  Bisa: lihat info, edit profil, toggle buka/tutup, CRUD menu.
  Tidak bisa: ubah verifikasi, lihat info pemilik lain.
--}}
@extends('layouts.pemilik')
@section('title', 'Toko Saya — ' . $restoran->nama_restoran)

@push('styles')
<style>
/* ── Cover ── */
.cover-wrap{border-radius:16px;overflow:hidden;margin-bottom:22px;height:220px;background:var(--s2)}
.cover-wrap img{width:100%;height:220px;object-fit:cover;display:block}
.cover-ph{
  width:100%;height:220px;
  background:linear-gradient(135deg,var(--gl),var(--gm));
  display:flex;align-items:center;justify-content:center
}

/* ── Layout dua kolom ── */
.layout{display:grid;grid-template-columns:1fr 280px;gap:20px;align-items:start}
@media(max-width:768px){.layout{grid-template-columns:1fr}}

/* ── Card utama ── */
.card{background:#fff;border-radius:16px;border:1px solid var(--s2);padding:22px}

/* ── Nama & badge ── */
.tname{font-size:21px;font-weight:800;color:var(--s9);display:flex;align-items:center;gap:10px;flex-wrap:wrap}
.hb{background:var(--g);color:#fff;font-size:11px;font-weight:700;padding:4px 12px;border-radius:20px}
.sb{background:#7c3aed;color:#fff;font-size:11px;font-weight:700;padding:4px 12px;border-radius:20px}

/* ── Rating row ── */
.rrow{display:flex;align-items:center;gap:12px;margin-top:8px;font-size:13px;font-weight:600;color:var(--s7);flex-wrap:wrap}
.dot{width:7px;height:7px;border-radius:50%;flex-shrink:0}

/* ── Deskripsi ── */
.tdesc{font-size:14px;color:var(--s6);line-height:1.7;margin-top:14px;padding-top:14px;border-top:1px solid var(--s1)}

/* ── Info grid ── */
.igrid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:16px}
@media(max-width:480px){.igrid{grid-template-columns:1fr}}
.iitem{background:var(--s1);border-radius:11px;padding:11px 13px;display:flex;align-items:flex-start;gap:10px}
.iitem svg{width:16px;height:16px;color:var(--s4);flex-shrink:0;margin-top:2px}
.ilbl{font-size:11px;color:var(--s4);font-weight:600;text-transform:uppercase;letter-spacing:.04em}
.ival{font-size:13px;font-weight:600;color:var(--s7);margin-top:2px}
.ival.g{color:var(--g)}

/* ── Aksi bawah card ── */
.tacts{display:flex;gap:8px;margin-top:16px;flex-wrap:wrap}

/* ── Panel sidebar (vc) ── */
.vc{background:#fff;border-radius:14px;border:1px solid var(--s2);padding:18px;margin-bottom:14px}
.vc-t{font-size:14px;font-weight:700;color:var(--s9);margin-bottom:12px}
.vr{display:flex;justify-content:space-between;align-items:center;font-size:13px;padding:7px 0;border-bottom:1px solid var(--s1)}
.vr:last-of-type{border:none}
.vl{color:var(--s6);flex-shrink:0}
.vv{font-weight:600;color:var(--s9);font-size:12px;text-align:right}
.cn{background:var(--s1);border-radius:8px;padding:9px 12px;font-size:12px;color:var(--s6);margin-top:10px;line-height:1.6}

/* ── Toggle buka/tutup ── */
.toggle-wrap{
  display:flex;align-items:center;justify-content:space-between;
  padding:4px 0 8px
}
.toggle-lbl{font-size:13px;font-weight:600;color:var(--s7)}
.toggle-btn{
  position:relative;width:44px;height:24px;
  border-radius:99px;border:none;cursor:pointer;
  transition:background .2s;flex-shrink:0
}
.toggle-btn.on{background:var(--g)}
.toggle-btn.off{background:var(--s4)}
.toggle-btn::after{
  content:'';position:absolute;top:3px;
  width:18px;height:18px;border-radius:50%;
  background:#fff;transition:left .2s
}
.toggle-btn.on::after{left:23px}
.toggle-btn.off::after{left:3px}

/* ── Form ── */
.g2{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.fg{display:flex;flex-direction:column;gap:5px}
label{font-size:12px;font-weight:600;color:var(--s7)}
.req{color:var(--r);margin-left:2px}
input[type=text],input[type=number],input[type=email],input[type=tel],
input[type=date],input[type=file],select,textarea{
  border:1.5px solid var(--s2);border-radius:9px;padding:8px 11px;
  font-family:var(--font);font-size:13px;color:var(--s7);
  outline:none;transition:border-color .15s;width:100%;background:#fff
}
input:focus,select:focus,textarea:focus{
  border-color:var(--g);box-shadow:0 0 0 3px rgba(26,158,92,.08)
}
textarea{resize:vertical;min-height:66px}

.map-box{
    border-radius:12px;
    overflow:hidden;
    height:210px;
    background:var(--s1);
}

.map-box iframe{
    width:100%;
    height:100%;
    border:none;
    display:block;
}
</style>
@endpush

@section('content')
@php $v = $restoran->verifikasiHalal; @endphp

@include('detail_toko.cover')

<div class="layout">
  {{-- KIRI --}}
  <div>
    <div class="card">
      @include('detail_toko.info_toko')
      <div class="tacts">
        <button class="btn btn-primary" onclick="document.getElementById('editTokoM').classList.add('open')">
          <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
            <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4z"/>
          </svg>
          Edit Profil
        </button>
        @php
            $lokasiMaps = trim(
                ($restoran->nama_restoran ?? '') . ', ' .
                ($restoran->alamat ?? '') . ', Batam'
            );

            $queryMaps = urlencode($lokasiMaps);
        @endphp


        <a href="https://www.google.com/maps/search/?api=1&query={{ $queryMaps }}"
          target="_blank"
          class="btn btn-outline">

          <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/>
            <circle cx="12" cy="10" r="3"/>
          </svg>

          Lihat di Peta
        </a>
      </div>
    </div>
  </div>

  {{-- KANAN --}}
  <div>

    {{-- Status verifikasi — read only --}}
    <div class="vc">
      <div class="vc-t">Status Verifikasi Halal</div>
      <div class="vr">
        <span class="vl">Status</span>
        @if($v?->status === 'terverifikasi')
          <span class="badge bv">✓ Terverifikasi</span>
        @elseif($v?->status === 'pending')
          <span class="badge bp"> Menunggu Review</span>
        @elseif($v?->status === 'ditolak')
          <span class="badge br">✕ Ditolak</span>
        @else
          <span class="badge bn">Belum Diajukan</span>
        @endif
      </div>
      <div class="vr">
        <span class="vl">No. Sertifikat</span>
        <span class="vv">{{ $v?->no_sertifikat ?? '—' }}</span>
      </div>
      <div class="vr">
        <span class="vl">Masa Berlaku</span>
        <span class="vv" style="{{ $v?->isSertifikatHampirExpire() ? 'color:var(--r)' : '' }}">
          {{ $v?->masa_berlaku?->format('d/m/Y') ?? '—' }}
        </span>
      </div>
      @if($v?->catatan)
        <div class="cn"><strong>Catatan Admin:</strong> {{ $v->catatan }}</div>
      @endif
    </div>

    {{-- Dokumen sertifikat --}}
    @if($v?->dokumen_sertifikat)
    <div class="vc">
      <div class="vc-t">Dokumen Sertifikat</div>
      <a href="{{ asset('storage/'.$v->dokumen_sertifikat) }}" target="_blank"
         class="btn btn-outline" style="width:100%;justify-content:center">
        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
          <polyline points="14 2 14 8 20 8"/>
        </svg>
        Lihat Dokumen
      </a>
    </div>
    @endif
  </div>
</div>

@include('detail_toko.grid_menu_aksi', [
  'urlStoreMenu'  => '/pemilik/toko/menu',
  'urlUpdateMenu' => '/pemilik/toko/menu',
  'urlDeleteMenu' => '/pemilik/toko/menu',
])

@include('detail_toko.modal_edit_profil', [
  'isAdmin'   => false,
  'urlUpdate' => '/pemilik/toko/update',
])

<script>

function closeM(id) { document.getElementById(id).classList.remove('open'); }
document.querySelectorAll('.modal-overlay').forEach(o =>
  o.addEventListener('click', e => { if (e.target === o) o.classList.remove('open'); })
);
</script>
@endsection