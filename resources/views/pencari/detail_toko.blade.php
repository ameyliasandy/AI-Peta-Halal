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

/* ── Tombol Kembali ── */
.btn-back{
  display:inline-flex;
  align-items:center;
  gap:8px;
  padding:8px 16px;
  background:white;
  color:var(--s7);
  font-weight:600;
  font-size:13px;
  border-radius:10px;
  border:1px solid var(--s2);
  transition:all .2s ease;
  text-decoration:none;
  margin-bottom:20px;
}
.btn-back:hover{
  background:var(--s1);
  border-color:var(--s4);
  transform:translateX(-2px);
}

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

/* ── Tombol aksi di dalam card ── */
.tacts{display:flex;gap:8px;margin-top:18px;flex-wrap:wrap}

/* ===============================
   ULASAN
================================ */
.review-card{padding:24px}
.review-card .vc-t{font-size:16px;font-weight:800;margin-bottom:18px}
.review-card textarea{
  width:100%;
  min-height:95px;
  border-radius:12px;
  padding:12px 14px;
  border:1.5px solid var(--s2);
  font-family:var(--font);
  font-size:13px;
  color:var(--s7);
  outline:none;
  transition:border-color .15s;
  background:#fff;
  resize:vertical;
}
.review-card textarea:focus{
  border-color:var(--g);
  box-shadow:0 0 0 3px rgba(26,158,92,.12);
}
.fg{display:flex;flex-direction:column;gap:5px}
.fg label{font-size:12px;font-weight:700;color:var(--s7)}
#starRating{display:flex;gap:5px;background:var(--s1);padding:8px 12px;border-radius:12px;width:max-content}
#starRating .star{font-size:30px;cursor:pointer;transition:.15s ease}
#starRating .star:hover{transform:scale(1.15)}
.success-msg{
  background:var(--gl);
  color:var(--gd);
  padding:10px 14px;
  border-radius:10px;
  font-size:13px;
  margin-bottom:14px;
  border:1px solid rgba(26,158,92,.15);
}

/* ── Daftar Ulasan ── */
.ulasan-list{margin-top:16px}
.ulasan-item{
  padding:14px 0;
  border-bottom:1px solid var(--s1);
}
.ulasan-item:last-child{border-bottom:none}
.ulasan-header{
  display:flex;
  align-items:center;
  gap:10px;
  margin-bottom:4px;
  flex-wrap:wrap;
}
.ulasan-user{
  font-weight:700;
  font-size:13px;
  color:var(--s9);
}
.ulasan-date{
  font-size:11px;
  color:var(--s4);
}
.ulasan-rating{
  color:#f59e0b;
  font-size:13px;
}
.ulasan-text{
  font-size:13px;
  color:var(--s6);
  line-height:1.6;
  margin-top:2px;
}
.ulasan-empty{
  text-align:center;
  padding:30px 0;
  color:var(--s4);
  font-size:14px;
}

/* ── Responsive ── */
@media(max-width:700px){
  .tacts{width:100%}
  .tacts .btn{flex:1}
  #starRating{width:100%;justify-content:center}
}

/* ── Popup Menu ── */
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.5);
  backdrop-filter: blur(4px);
  z-index: 999;
  display: none;
  align-items: center;
  justify-content: center;
  padding: 20px;
}
.modal-overlay.active {
  display: flex;
}
.modal-content {
  background: white;
  border-radius: 20px;
  max-width: 600px;
  width: 100%;
  max-height: 90vh;
  overflow-y: auto;
  padding: 24px;
  position: relative;
  animation: modalSlide .3s ease;
}
@keyframes modalSlide {
  from { transform: translateY(30px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}
.modal-close {
  position: sticky;
  top: 0;
  float: right;
  background: white;
  border: none;
  font-size: 24px;
  cursor: pointer;
  color: var(--s6);
  padding: 4px 8px;
  border-radius: 8px;
  transition: .2s;
}
.modal-close:hover {
  background: var(--s1);
  color: var(--s9);
}
.modal-title {
  font-size: 20px;
  font-weight: 800;
  color: var(--s9);
  margin-bottom: 16px;
}
.modal-menu-item {
  display: flex;
  gap: 16px;
  padding: 12px 0;
  border-bottom: 1px solid var(--s1);
  align-items: center;
}
.modal-menu-item:last-child {
  border-bottom: none;
}
.modal-menu-img {
  width: 60px;
  height: 60px;
  border-radius: 12px;
  object-fit: cover;
  background: var(--s1);
  flex-shrink: 0;
}
.modal-menu-info {
  flex: 1;
}
.modal-menu-name {
  font-weight: 600;
  font-size: 14px;
  color: var(--s9);
}
.modal-menu-price {
  font-size: 13px;
  color: var(--g);
  font-weight: 700;
}
.modal-menu-desc {
  font-size: 12px;
  color: var(--s6);
  margin-top: 2px;
}
.modal-empty {
  text-align: center;
  padding: 40px 0;
  color: var(--s4);
}
</style>
@endpush

@section('content')
@php $v = $restoran->verifikasiHalal; @endphp

<div class="dt-wrap">

  {{-- TOMBOL KEMBALI --}}
  <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('dashboard') }}" 
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
            <span class="sb" style="background:var(--g)">● Buka</span>
          @else
            <span class="sb" style="background:var(--s4)">● Tutup</span>
          @endif
        </div>

        {{-- Rating --}}
        <div class="rrow">
          <span style="color:#f59e0b;font-size:16px">★</span>
          <span>{{ number_format($restoran->ulasan->avg('rating') ?? 0, 1) }}</span>
          <span class="dot" style="background:var(--s3)"></span>
          <span>{{ $restoran->ulasan->count() }} ulasan</span>
          @if($restoran->kategori)
            <span class="dot" style="background:var(--s3)"></span>
            <span style="font-weight:400;color:var(--s6)">{{ $restoran->kategori->nama_kategori ?? '' }}</span>
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

        {{-- TOMBOL AKSI - MENGGUNAKAN TAILWIND --}}
        <div class="tacts">
        <button 
          id="btnFavoritRestoran"
          onclick="toggleFavoritRestoran()"
          class="inline-flex items-center justify-center gap-2 px-5 py-2.5 border border-pink-400 text-pink-500 hover:bg-pink-50 font-bold text-sm rounded-lg transition-all">
          ♡ Favorit
        </button>
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

        {{-- TOMBOL BUKA MAPS - TAILWIND --}}
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
        <div style="margin-top:14px;background:var(--s1);border-radius:12px;padding:12px">
          <div style="font-size:12px;color:var(--s6);margin-bottom:8px">Lokasi Dicari Berdasarkan</div>
          <div style="font-size:13px;font-weight:700;color:var(--s9)">{{ $restoran->nama_restoran }}</div>
          @if($restoran->alamat)
            <div style="margin-top:5px;font-size:12px;color:var(--s7)">{{ $restoran->alamat }}</div>
          @endif
        </div>

        @if($restoran->latitude && $restoran->longitude)
          <div style="margin-top:10px;font-size:12px;color:var(--s6)">
            Koordinat tersimpan:<br>
            Lat: <b>{{ $restoran->latitude }}</b> | Long: <b>{{ $restoran->longitude }}</b>
          </div>
        @endif
      </div>
    </div>
  </div>

  {{-- MENU --}}
  @if($restoran->menu->count() > 0)
  <div id="menu-section" style="margin-top:24px">
      @include('detail_toko.grid_menu')
  </div>
  @endif

  {{-- ULASAN --}}
  <div class="card review-card" style="margin-top:24px">
    <div class="vc-t">✍️ Beri Ulasan & Rating</div>

    @if(session('success'))
      <div class="success-msg">{{ session('success') }}</div>
    @endif

    @auth
    <form method="POST" action="{{ route('ulasan.store') }}">
      @csrf
      <input type="hidden" name="id_restoran" value="{{ $restoran->id_restoran }}">

      <div class="fg" style="margin-bottom:12px">
        <label>Rating Kamu</label>
        <div id="starRating" style="display:flex;gap:6px;margin-top:4px">
          @for($i = 1; $i <= 5; $i++)
          <span class="star" data-value="{{ $i }}" style="font-size:28px;cursor:pointer;color:#d1d5db;transition:color .15s">★</span>
          @endfor
        </div>
        <input type="hidden" name="rating" id="ratingInput" required>
      </div>

      <div class="fg">
        <label>Komentar (opsional)</label>
        <textarea name="komentar" placeholder="Bagaimana pengalaman makanmu di sini?" maxlength="500"></textarea>
      </div>

      {{-- TOMBOL KIRIM ULASAN - TAILWIND --}}
      <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 mt-4 bg-green-600 hover:bg-green-700 text-white font-bold text-sm rounded-lg transition-all hover:-translate-y-0.5 hover:shadow-md">
        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/>
        </svg>
        Kirim Ulasan
      </button>
    </form>

    <script>
    document.querySelectorAll('#starRating .star').forEach(star => {
      star.addEventListener('click', function() {
        const value = this.getAttribute('data-value');
        document.getElementById('ratingInput').value = value;
        document.querySelectorAll('#starRating .star').forEach(s => {
          s.style.color = s.getAttribute('data-value') <= value ? '#facc15' : '#d1d5db';
        });
      });
    });

    function toggleFavoritRestoran() {
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
      if (!csrfToken) {
        console.error('CSRF token not found');
        alert('Terjadi kesalahan. Silakan refresh halaman.');
        return;
      }

      fetch('/favorit/toggle', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
          id_restoran: "{{ $restoran->id_restoran }}"
        })
      })
      .then(r => r.json())
      .then(data => {
        let btn = document.getElementById('btnFavoritRestoran');
        if (data.status == "tambah") {
          btn.innerHTML = "♥ Favorit";
          btn.className = "px-5 py-2.5 bg-red-100 hover:bg-red-200 text-red-500 rounded-lg font-bold text-sm transition";
        } else {
          btn.innerHTML = "♡ Favorit";
          btn.className = "px-5 py-2.5 bg-pink-200 hover:bg-pink-300 text-pink-600 rounded-lg font-bold text-sm transition";
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan. Silakan coba lagi.');
      });
    }

    // ── Menu Modal ──
    function openMenuModal() {
      document.getElementById('menuModal').classList.add('active');
      document.body.style.overflow = 'hidden';
    }

    function closeMenuModal() {
      document.getElementById('menuModal').classList.remove('active');
      document.body.style.overflow = '';
    }

    // Close modal on overlay click
    document.addEventListener('DOMContentLoaded', function() {
      const modal = document.getElementById('menuModal');
      if (modal) {
        modal.addEventListener('click', function(e) {
          if (e.target === this) {
            closeMenuModal();
          }
        });
      }
    });

    // Close modal with ESC key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        closeMenuModal();
      }
    });
    </script>

    @else
    <p style="font-size:13px;color:var(--s6)">
      <a href="{{ route('login') }}" style="color:var(--g);font-weight:600">Login</a> untuk memberi ulasan dan rating.
    </p>
    @endauth
  </div>

  {{-- DAFTAR ULASAN DARI ORANG LAIN --}}
  <div class="card" style="margin-top:16px">
    <div class="vc-t" style="margin-bottom:4px">
      📝 Semua Ulasan 
      <span style="font-weight:400;font-size:13px;color:var(--s4)">({{ $restoran->ulasan->count() }})</span>
    </div>

    @if($restoran->ulasan->count() > 0)
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
        <p style="font-size:12px;color:var(--s4);margin-top:4px">Jadilah yang pertama memberi ulasan!</p>
      </div>
    @endif
  </div>

</div>

{{-- MENU MODAL --}}
<div id="menuModal" class="modal-overlay">
  <div class="modal-content">
    <button class="modal-close" onclick="closeMenuModal()">✕</button>
    <div class="modal-title">Menu {{ $restoran->nama_restoran }}</div>
    
    @if($restoran->menu->count() > 0)
      @foreach($restoran->menu as $menu)
        <div class="modal-menu-item">
          @if($menu->foto)
            <img src="{{ asset('storage/' . $menu->foto) }}" alt="{{ $menu->nama_menu }}" class="modal-menu-img">
          @else
            <div class="modal-menu-img" style="display:flex;align-items:center;justify-content:center;font-size:24px;background:var(--s1)">
            </div>
          @endif
          <div class="modal-menu-info">
            <div class="modal-menu-name">{{ $menu->nama_menu }}</div>
            <div class="modal-menu-price">Rp {{ number_format($menu->harga, 0, ',', '.') }}</div>
            @if($menu->deskripsi)
              <div class="modal-menu-desc">{{ Str::limit($menu->deskripsi, 60) }}</div>
            @endif
          </div>
        </div>
      @endforeach
    @else
      <div class="modal-empty">
        <div style="font-size:48px;margin-bottom:8px">🍽️</div>
        <p>Belum ada menu yang ditambahkan</p>
      </div>
    @endif
  </div>
</div>

@endsection