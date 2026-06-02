{{--
  admin/restoran/show.blade.php
  Detail toko versi ADMIN.
  Bisa: lihat semua info, ubah verifikasi, edit profil, CRUD menu.
--}}
@extends('layouts.admin')
@section('title', $restoran->nama_restoran)

@push('styles')
<style>
.back{display:inline-flex;align-items:center;gap:7px;font-size:13px;font-weight:600;color:var(--s6);text-decoration:none;margin-bottom:20px}
.back:hover{color:var(--g)}
.cover-wrap{border-radius:16px;overflow:hidden;margin-bottom:22px;height:220px;background:var(--s2)}
.cover-wrap img{width:100%;height:220px;object-fit:cover;display:block}
.cover-ph{width:100%;height:220px;background:linear-gradient(135deg,var(--gl),var(--gm));display:flex;align-items:center;justify-content:center}
.layout{display:grid;grid-template-columns:1fr 290px;gap:20px;align-items:start}
.card{background:#fff;border-radius:16px;border:1px solid var(--s2);padding:22px}
.tname{font-size:21px;font-weight:800;color:var(--s9);display:flex;align-items:center;gap:10px;flex-wrap:wrap}
.hb{background:var(--g);color:#fff;font-size:11px;font-weight:700;padding:4px 12px;border-radius:20px}
.sb{background:#7c3aed;color:#fff;font-size:11px;font-weight:700;padding:4px 12px;border-radius:20px}
.rrow{display:flex;align-items:center;gap:12px;margin-top:8px;font-size:13px;font-weight:600;color:var(--s7)}
.dot{width:7px;height:7px;border-radius:50%;flex-shrink:0}
.tdesc{font-size:14px;color:var(--s6);line-height:1.7;margin-top:14px;padding-top:14px;border-top:1px solid var(--s1)}
.igrid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:16px}
.iitem{background:var(--s1);border-radius:11px;padding:11px 13px;display:flex;align-items:flex-start;gap:10px}
.iitem svg{width:16px;height:16px;color:var(--s4);flex-shrink:0;margin-top:2px}
.ilbl{font-size:11px;color:var(--s4);font-weight:600;text-transform:uppercase;letter-spacing:.04em}
.ival{font-size:13px;font-weight:600;color:var(--s7);margin-top:2px}
.ival.g{color:var(--g)}
.tacts{display:flex;gap:8px;margin-top:16px}
.vc{background:#fff;border-radius:14px;border:1px solid var(--s2);padding:18px;margin-bottom:14px}
.vc-t{font-size:14px;font-weight:700;color:var(--s9);margin-bottom:12px}
.vr{display:flex;justify-content:space-between;align-items:center;font-size:13px;padding:6px 0;border-bottom:1px solid var(--s1)}
.vr:last-of-type{border:none}
.vl{color:var(--s6)}.vv{font-weight:600;color:var(--s9);font-size:12px}
.cn{background:var(--s1);border-radius:8px;padding:9px 12px;font-size:12px;color:var(--s6);margin-top:10px;line-height:1.6}
.va-wrap{display:flex;flex-direction:column;gap:8px;margin-top:12px}
.va-wrap select,.va-wrap textarea{font-size:13px;border:1.5px solid var(--s2);border-radius:9px;padding:8px 11px;font-family:var(--font);outline:none;width:100%;color:var(--s7)}
.va-wrap textarea{min-height:50px;resize:none}
.va-wrap select:focus,.va-wrap textarea:focus{border-color:var(--g);box-shadow:0 0 0 3px rgba(26,158,92,.1)}
.msec{background:#fff;border-radius:16px;border:1px solid var(--s2);margin-top:20px;overflow:hidden}
.mhead{padding:16px 22px;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid var(--s1)}
.mhead-t{font-size:15px;font-weight:700;color:var(--s9)}
.mgrid{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:12px;padding:16px 22px}
.mi{border-radius:12px;border:1.5px solid var(--s2);overflow:hidden;position:relative;transition:all .15s}
.mi:hover{border-color:var(--g);box-shadow:0 3px 12px rgba(26,158,92,.1)}
.mi-img{width:100%;height:110px;object-fit:cover;display:block}
.mi-ph{width:100%;height:110px;background:linear-gradient(135deg,var(--gl),var(--gm));display:flex;align-items:center;justify-content:center;font-size:26px}
.mi-info{padding:9px 11px}
.mi-n{font-size:13px;font-weight:700;color:var(--s9)}
.mi-p{font-size:12px;font-weight:600;color:var(--g);margin-top:2px}
.mi-acts{display:flex;gap:4px;padding:0 11px 9px}
.mi-habis{position:absolute;top:7px;right:7px;background:var(--r);color:#fff;font-size:10px;font-weight:700;padding:2px 7px;border-radius:9px}
.g2{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.fg{display:flex;flex-direction:column;gap:5px}
label{font-size:12px;font-weight:600;color:var(--s7)}
.req{color:var(--r);margin-left:2px}
input[type=text],input[type=number],input[type=email],input[type=tel],
input[type=date],input[type=file],select,textarea{
  border:1.5px solid var(--s2);border-radius:9px;padding:8px 11px;
  font-family:var(--font);font-size:13px;color:var(--s7);outline:none;
  transition:border-color .15s;width:100%}
input:focus,select:focus,textarea:focus{border-color:var(--g);box-shadow:0 0 0 3px rgba(26,158,92,.1)}
textarea{resize:vertical;min-height:66px}
</style>
@endpush

@section('content')
@php $v = $restoran->verifikasiHalal; @endphp

<a href="{{ route('admin.restoran.list') }}" class="back">
  <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
  Kembali ke List Usaha
</a>

@include('detail_toko.cover')

<div class="layout">
  {{-- KIRI --}}
  <div>
    <div class="card">
      @include('detail_toko.info_toko')
      <div class="tacts">
        <button class="btn btn-primary" onclick="document.getElementById('editTokoM').classList.add('open')">
          <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4z"/></svg>
          Edit Profil Toko
        </button>
        @if($restoran->latitude)
        <a href="https://maps.google.com/?q={{ $restoran->latitude }},{{ $restoran->longitude }}"
           target="_blank" class="btn btn-outline">
          <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
          Lihat di Peta
        </a>
        @endif
      </div>
    </div>
  </div>

  {{-- KANAN --}}
  <div>
    @include('detail_toko.panel_verifikasi')
    @include('detail_toko.panel_pemilik')
  </div>
</div>

@include('detail_toko.grid_menu_aksi', [
  'urlStoreMenu'  => "/admin/restoran/{$restoran->id_restoran}/menu",
  'urlUpdateMenu' => "/admin/restoran/{$restoran->id_restoran}/menu",
  'urlDeleteMenu' => "/admin/restoran/{$restoran->id_restoran}/menu",
])

@include('detail_toko.modal_edit_profil', [
  'isAdmin'   => true,
  'urlUpdate' => "/admin/restoran/{$restoran->id_restoran}/update",
])

<script>
async function updateVerif(id) {
  const st = document.getElementById('vStatus').value;
  const ct = document.getElementById('vCatatan').value;
  if (!st) { showToast('Pilih status verifikasi', 'error'); return; }
  document.getElementById('vL').style.display = 'none';
  document.getElementById('vS').style.display = '';
  const fd = new FormData();
  fd.append('status_verifikasi', st);
  fd.append('catatan', ct);
  const d = await fetch(`/admin/restoran/${id}/update`, {
    method: 'POST',
    headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
    body: fd,
  }).then(r => r.json());
  document.getElementById('vL').style.display = '';
  document.getElementById('vS').style.display = 'none';
  if (d.success) { showToast('Status verifikasi diperbarui', 'success'); setTimeout(() => location.reload(), 700); }
  else showToast('Gagal memperbarui', 'error');
}

function closeM(id) { document.getElementById(id).classList.remove('open'); }
document.querySelectorAll('.modal-overlay').forEach(o =>
  o.addEventListener('click', e => { if (e.target === o) o.classList.remove('open'); })
);
</script>
@endsection