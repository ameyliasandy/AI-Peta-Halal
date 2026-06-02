@extends('layouts.admin')
@section('title','List Usaha Halal')

@push('styles')
<style>
/* ── PAGE HEADER ── */
.ph{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:24px}
.ph-left h1{font-size:22px;font-weight:800;color:var(--s9)}
.ph-left p{font-size:13px;color:var(--s4);margin-top:3px}
.ph-right{display:flex;gap:10px}

/* ── STATS ── */
.stats{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:28px}
.sc{background:#fff;border-radius:14px;padding:18px 20px;border:1px solid var(--s2);display:flex;align-items:center;gap:14px}
.sc-icon{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.sc-icon svg{width:22px;height:22px}
.sc-num{font-size:26px;font-weight:800;color:var(--s9);line-height:1}
.sc-lbl{font-size:12px;color:var(--s4);margin-top:3px;line-height:1.3}

/* ── FILTERS ── */
.filter-bar{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:12px}
.filter-l{display:flex;align-items:center;gap:10px;flex-wrap:wrap}
.filter-r{display:flex;gap:6px;flex-wrap:wrap}
.search-box{display:flex;align-items:center;gap:8px;background:#fff;border:1.5px solid var(--s2);border-radius:10px;padding:8px 14px;width:260px}
.search-box input{border:none;background:transparent;font-family:var(--font);font-size:13px;color:var(--s7);outline:none;width:100%}
.search-box input::placeholder{color:var(--s4)}
.sel{border:1.5px solid var(--s2);border-radius:10px;padding:7px 12px;font-family:var(--font);font-size:13px;color:var(--s7);outline:none;background:#fff;cursor:pointer}
.fpill{padding:6px 15px;border-radius:20px;border:1.5px solid var(--s2);background:#fff;font-size:13px;font-weight:600;color:var(--s6);cursor:pointer;transition:all .15s;white-space:nowrap}
.fpill.on{background:var(--g);color:#fff;border-color:var(--g)}
.fpill:hover:not(.on){border-color:var(--s4)}

/* ── TABLE ── */
.tw{background:#fff;border-radius:16px;border:1px solid var(--s2);overflow:hidden}
table{width:100%;border-collapse:collapse}
thead tr{background:var(--s1)}
th{padding:11px 16px;font-size:11px;font-weight:700;color:var(--s6);text-align:left;letter-spacing:.04em;text-transform:uppercase;white-space:nowrap}
td{padding:13px 16px;font-size:13px;color:var(--s7);border-top:1px solid var(--s1);vertical-align:middle}
tr:hover td{background:var(--s0)}
.t-name{font-weight:600;color:var(--s9);font-size:13px}
.t-sub{font-size:11px;color:var(--s4);margin-top:2px}
.h-link{color:#3182ce;font-size:12px;font-weight:500;text-decoration:none}
.h-link:hover{text-decoration:underline}
.h-link.pur{color:#7c3aed}

/* badges */
.badge{display:inline-flex;align-items:center;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:700;white-space:nowrap}
.bv{background:#d1fae5;color:#065f46}
.bp{background:#fef3c7;color:#92400e}
.br{background:#fee2e2;color:#991b1b}
.bn{background:var(--s1);color:var(--s4)}
.be{background:#fee2e2;color:#991b1b}

/* action buttons */
.act{display:flex;flex-direction:column;gap:5px;min-width:72px}
.btn-e{background:#22c55e;color:#fff}
.btn-e:hover{background:#16a34a}
.btn-d{background:var(--r);color:#fff}
.btn-d:hover{opacity:.85}
.btn-t{background:#f59e0b;color:#fff}
.btn-t:hover{background:#d97706}

/* pagination */
.pagi{display:flex;align-items:center;justify-content:space-between;padding:15px 20px;border-top:1px solid var(--s1)}
.pagi-info{font-size:13px;color:var(--s4)}
.pagi-btns{display:flex;gap:5px}
.pb{width:32px;height:32px;border-radius:8px;border:1.5px solid var(--s2);background:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:13px;font-weight:600;color:var(--s7);transition:all .15s}
.pb.on{background:var(--g);color:#fff;border-color:var(--g)}
.pb:hover:not(.on):not(:disabled){border-color:var(--s7)}
.pb:disabled{opacity:.35;cursor:default}

/* ─ MODAL FORM ─ */
.steps{display:flex;background:var(--s1);border-radius:11px;padding:4px;margin-bottom:22px;gap:2px}
.si{flex:1;display:flex;align-items:center;justify-content:center;gap:6px;padding:8px 4px;border-radius:8px;font-size:12px;font-weight:600;color:var(--s4);cursor:pointer;transition:all .15s;white-space:nowrap}
.si.on{background:#fff;color:var(--g);box-shadow:0 1px 4px rgba(0,0,0,.1)}
.si.done{color:var(--g)}
.sn{width:20px;height:20px;border-radius:50%;background:var(--s4);display:flex;align-items:center;justify-content:center;font-size:11px;color:#fff;flex-shrink:0}
.si.on .sn,.si.done .sn{background:var(--g)}
.sp{display:none}.sp.on{display:block}

.fsec{margin-bottom:20px}
.ft{font-size:14px;font-weight:700;color:var(--s9);border-bottom:2px solid var(--gl);padding-bottom:7px;margin-bottom:14px}
.fg{display:flex;flex-direction:column;gap:5px}
.fg.full{grid-column:1/-1}
label{font-size:12px;font-weight:600;color:var(--s7)}
.req{color:var(--r);margin-left:2px}
.hint{font-size:11px;color:var(--s4)}
.g2{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.g3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px}
input[type=text],input[type=number],input[type=email],input[type=tel],
input[type=date],input[type=file],select,textarea{
  border:1.5px solid var(--s2);border-radius:9px;padding:8px 11px;
  font-family:var(--font);font-size:13px;color:var(--s7);
  outline:none;transition:border-color .15s;width:100%}
input:focus,select:focus,textarea:focus{border-color:var(--g);box-shadow:0 0 0 3px rgba(26,158,92,.1)}
textarea{resize:vertical;min-height:68px}
.chk-wrap{display:flex;flex-direction:column;gap:7px}
.ci{display:flex;align-items:flex-start;gap:10px;padding:9px 12px;border:1.5px solid var(--s2);border-radius:9px;cursor:pointer;transition:all .15s}
.ci:has(input:checked){border-color:var(--g);background:var(--gl)}
.ci input[type=checkbox]{width:15px;height:15px;margin-top:2px;flex-shrink:0;accent-color:var(--g);cursor:pointer}
.ct{font-size:13px;line-height:1.4}
.cs{font-size:11px;color:var(--s4);margin-top:1px}
.alert-w{background:#fffbeb;border:1px solid #f59e0b;border-radius:9px;padding:10px 14px;font-size:12px;color:#92400e;margin-bottom:12px}

/* delete confirm */
.del-icon{width:54px;height:54px;border-radius:16px;background:#fee2e2;display:flex;align-items:center;justify-content:center;margin:0 auto 16px}
.del-icon svg{width:26px;height:26px;color:var(--r)}
</style>
@endpush

@section('content')

{{-- PAGE HEADER --}}
<div class="ph">
  <div class="ph-left">
    <h1>List Usaha Halal</h1>
    <p>{{ $stats['terverifikasi'] + $stats['pending'] + $stats['ditolak'] }} Usaha Terdaftar &middot; Terakhir diperbarui {{ now()->translatedFormat('d F Y') }}</p>
  </div>
  <div class="ph-right">
    <a href="{{ route('admin.restoran.export') }}" class="btn btn-outline">
      <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
      Ekspor CSV
    </a>
    <button class="btn btn-primary" onclick="openTambah()">
      <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Tambah Usaha
    </button>
  </div>
</div>

{{-- STATS --}}
<div class="stats">
  <div class="sc">
    <div class="sc-icon" style="background:#d1fae5"><svg fill="none" stroke="#065f46" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
    <div><div class="sc-num">{{ $stats['terverifikasi'] }}</div><div class="sc-lbl">Terverifikasi</div></div>
  </div>
  <div class="sc">
    <div class="sc-icon" style="background:#fef3c7"><svg fill="none" stroke="#92400e" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></div>
    <div><div class="sc-num">{{ $stats['pending'] }}</div><div class="sc-lbl">Pending verifikasi</div></div>
  </div>
  <div class="sc">
    <div class="sc-icon" style="background:#fee2e2"><svg fill="none" stroke="#991b1b" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg></div>
    <div><div class="sc-num">{{ $stats['ditolak'] }}</div><div class="sc-lbl">Ditolak</div></div>
  </div>
  <div class="sc">
    <div class="sc-icon" style="background:#ede9fe"><svg fill="none" stroke="#5b21b6" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div>
    <div><div class="sc-num">{{ $stats['expire'] }}</div><div class="sc-lbl">Sertifikasi hampir expire</div></div>
  </div>
</div>

{{-- FILTERS --}}
<div class="filter-bar">
  <div class="filter-l">
    <div class="search-box">
      <svg width="14" height="14" fill="none" stroke="#94a3b8" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" id="searchInput" placeholder="Cari nama usaha" value="{{ request('search') }}">
    </div>
    <select class="sel" id="sortSel" onchange="applyQ()">
      <option value="terbaru" {{ request('sort','terbaru')==='terbaru'?'selected':'' }}>Urutkan: Terbaru</option>
      <option value="nama_az" {{ request('sort')==='nama_az'?'selected':'' }}>Nama A-Z</option>
      <option value="rating"  {{ request('sort')==='rating'?'selected':'' }}>Rating Tertinggi</option>
    </select>
    <select class="sel" id="wilayahSel">
      <option>Wilayah: Semua</option>
    </select>
  </div>
  <div class="filter-r">
    @php $ak = request('kategori',''); @endphp
    <button class="fpill {{ $ak==='' ?'on':'' }}" onclick="filterKat('')">Semua</button>
    @foreach($kategori as $k)
      <button class="fpill {{ $ak==(string)$k->id_kategori?'on':'' }}"
              onclick="filterKat('{{ $k->id_kategori }}')">{{ $k->nama_kategori }}</button>
    @endforeach
  </div>
</div>

{{-- TABLE --}}
<div class="tw">
  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>Nama Usaha</th>
        <th>Kategori</th>
        <th>Halal</th>
        <th>Alamat</th>
        <th>Status</th>
        <th>Sertifikat Exp</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($restoran as $i => $r)
      @php $v = $r->verifikasiHalal; @endphp
      <tr data-id="{{ $r->id_restoran }}">
        <td>{{ $restoran->firstItem() + $i }}</td>
        <td>
          <div class="t-name">{{ $r->nama_restoran }}</div>
          {{-- ✅ FIX: User model pakai kolom 'name' bukan 'nama' --}}
          <div class="t-sub">{{ $r->pemilik?->name }}</div>
        </td>
        <td>{{ $r->kategori?->nama_kategori ?? '-' }}</td>
        <td>
          @if($r->status_halal==='certified')
            <a class="h-link">Certified Halal</a>
          @elseif($r->status_halal==='self_claimed')
            <a class="h-link pur">Self-Claimed Halal</a>
          @else
            <span style="color:var(--s4);font-size:12px">—</span>
          @endif
        </td>
        <td style="max-width:160px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
          {{ $r->kota ? $r->kota.', '.Str::limit($r->alamat,25) : Str::limit($r->alamat,30) }}
        </td>
        <td>
          @if($v?->status==='terverifikasi') <span class="badge bv">Terverifikasi</span>
          @elseif($v?->status==='pending')   <span class="badge bp">Pending</span>
          @elseif($v?->status==='ditolak')   <span class="badge br">Ditolak</span>
          @else                              <span class="badge bn">Belum Diajukan</span>
          @endif
        </td>
        <td>
          @if($v?->masa_berlaku)
            <span class="badge {{ $v->isSertifikatHampirExpire() ? 'be':'' }}" style="{{ !$v->isSertifikatHampirExpire()?'background:none;padding:0;color:var(--s7)':'' }}">
              {{ $v->masa_berlaku->format('d/m/Y') }}
            </span>
          @else
            <span style="color:var(--s4);font-size:12px">—</span>
          @endif
        </td>
        <td>
          <div class="act">
            <button class="btn btn-sm btn-e" onclick="openEdit({{ $r->id_restoran }})">Edit</button>
            <button class="btn btn-sm btn-d" onclick="openDel({{ $r->id_restoran }},'{{ addslashes($r->nama_restoran) }}')">Hapus</button>
            <a href="{{ route('admin.restoran.show', $r->id_restoran) }}" class="btn btn-sm btn-t">Lihat Toko</a>
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="8" style="text-align:center;padding:48px;color:var(--s4)">Tidak ada data usaha</td></tr>
      @endforelse
    </tbody>
  </table>

  <div class="pagi">
    <div class="pagi-info">Menampilkan {{ $restoran->firstItem() }}–{{ $restoran->lastItem() }} dari {{ $restoran->total() }} usaha</div>
    <div class="pagi-btns">
      <button class="pb" {{ $restoran->onFirstPage()?'disabled':'' }} onclick="goPage({{ $restoran->currentPage()-1 }})">←</button>
      @for($p=1;$p<=$restoran->lastPage();$p++)
        <button class="pb {{ $restoran->currentPage()===$p?'on':'' }}" onclick="goPage({{ $p }})">{{ $p }}</button>
      @endfor
      <button class="pb" {{ !$restoran->hasMorePages()?'disabled':'' }} onclick="goPage({{ $restoran->currentPage()+1 }})">→</button>
    </div>
  </div>
</div>

{{-- ═══════════════════════════ MODAL TAMBAH/EDIT ═══════════════════════════ --}}
<div class="modal-overlay" id="restoranModal">
  <div class="modal">
    <div class="modal-head">
      <div>
        <div class="modal-title" id="modalTitle">Tambah Data Usaha Halal</div>
        <div class="modal-sub">Lengkapi semua informasi yang diperlukan dengan benar</div>
      </div>
      <button class="modal-x" onclick="closeM('restoranModal')">✕</button>
    </div>

    <div class="modal-body">
      {{-- STEP INDICATOR --}}
      <div class="steps">
        <div class="si on" id="s1" onclick="gS(1)"><div class="sn">1</div>Info Dasar</div>
        <div class="si"    id="s2" onclick="gS(2)"><div class="sn">2</div>Lokasi & Kontak</div>
        <div class="si"    id="s3" onclick="gS(3)"><div class="sn">3</div>Dokumen & Verifikasi</div>
        <div class="si"    id="s4" onclick="gS(4)"><div class="sn">4</div>Simpan & Kirim</div>
      </div>

      <form id="restoranForm" enctype="multipart/form-data">
        @csrf

        {{-- STEP 1 --}}
        <div class="sp on" id="p1">
          @include('form_nambah_usaha.info_dasar', ['isAdmin' => true])
        </div>

        {{-- STEP 2 --}}
        <div class="sp" id="p2">
          @include('form_nambah_usaha.lokasi', ['isAdmin' => true])
        </div>

        {{-- STEP 3 — versi admin (termasuk keputusan verifikasi) --}}
        <div class="sp" id="p3">
          @include('form_nambah_usaha.admin_dokumen', ['isAdmin' => true])
        </div>

        {{-- STEP 4 --}}
        <div class="sp" id="p4">
          @include('form_nambah_usaha.review', ['isAdmin' => true])
        </div>

      </form>
    </div>

    <div class="modal-foot">
      <button class="btn btn-outline" id="btnPrev" onclick="prevS()" style="display:none">← Kembali</button>
      <button class="btn btn-primary"  id="btnNext" onclick="nextS()">Selanjutnya →</button>
      <button class="btn btn-primary"  id="btnSave" onclick="saveRestoran()" style="display:none">
        <span id="saveL">Simpan & Kirim</span>
        <span id="saveS" class="spinner" style="display:none"></span>
      </button>
    </div>
  </div>
</div>

{{-- ═══════════════════════════ MODAL HAPUS ═══════════════════════════ --}}
<div class="modal-overlay" id="delModal">
  <div class="modal" style="max-width:420px">
    <div class="modal-body" style="padding:32px 28px;text-align:center">
      <div class="del-icon">
        <svg fill="none" stroke="#e53e3e" stroke-width="2" viewBox="0 0 24 24">
          <polyline points="3 6 5 6 21 6"/>
          <path d="M19 6l-1 14H6L5 6"/>
          <path d="M10 11v6M14 11v6"/>
          <path d="M9 6V4h6v2"/>
        </svg>
      </div>
      <div style="font-size:18px;font-weight:700;color:var(--s9)">Hapus Usaha?</div>
      <div style="font-size:13px;color:var(--s6);margin-top:8px;line-height:1.7">
        Yakin ingin menghapus <strong id="delNama"></strong>?<br>
        Semua data termasuk menu akan terhapus permanen.
      </div>
    </div>
    <div class="modal-foot" style="justify-content:center;gap:12px">
      <button class="btn btn-outline" onclick="closeM('delModal')">Batal</button>
      <button class="btn btn-danger"  onclick="doDelete()">
        <span id="delL">Ya, Hapus</span>
        <span id="delS" class="spinner" style="display:none"></span>
      </button>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
// Injeksi config ke JS sebelum load scripts
window.FORM_IS_ADMIN = true;
window.FORM_EDIT_ID  = null; // diset ulang oleh openEdit()
</script>
@include('form_nambah_usaha._form_scripts')
<script>
// ── FUNGSI KHUSUS ADMIN ──────────────────────────────────────────
let deleteId = null;

function openTambah() {
  window.FORM_EDIT_ID = null;
  EDIT_ID = null; 
  document.getElementById('modalTitle').textContent = 'Tambah Data Usaha Halal';
  resetRestoranForm();
  document.getElementById('restoranModal').classList.add('open');
}

async function openEdit(id) {
  window.FORM_EDIT_ID = id;
  EDIT_ID = id;  
  document.getElementById('modalTitle').textContent = 'Edit Data Usaha';
  resetRestoranForm();
  await populateEdit(id);
  document.getElementById('restoranModal').classList.add('open');
}

function openDel(id, nama) {
  deleteId = id;
  document.getElementById('delNama').textContent = nama;
  document.getElementById('delModal').classList.add('open');
}

async function doDelete() {
  document.getElementById('delL').style.display = 'none';
  document.getElementById('delS').style.display = '';
  const data = await apiFetch(`/admin/restoran/${deleteId}`, { method: 'DELETE' });
  document.getElementById('delL').style.display = '';
  document.getElementById('delS').style.display = 'none';
  if (data.success) {
    showToast('Usaha berhasil dihapus', 'success');
    closeM('delModal');
    document.querySelector(`tr[data-id="${deleteId}"]`)?.remove();
  } else {
    showToast('Gagal menghapus', 'error');
  }
}

function closeM(id) { document.getElementById(id).classList.remove('open'); }
document.querySelectorAll('.modal-overlay').forEach(o =>
  o.addEventListener('click', e => { if (e.target === o) o.classList.remove('open'); })
);
function applyQ(page = 1) {
    const params = new URLSearchParams(window.location.search);

    // search
    const search = document.getElementById('searchInput')?.value;
    if (search) {
        params.set('search', search);
    } else {
        params.delete('search');
    }

    // sort
    const sort = document.getElementById('sortSel')?.value;
    if (sort) {
        params.set('sort', sort);
    }

    // wilayah
    const wilayah = document.getElementById('wilayahSel')?.value;
    if (wilayah && wilayah !== 'Wilayah: Semua') {
        params.set('wilayah', wilayah);
    } else {
        params.delete('wilayah');
    }

    params.set('page', page);

    window.location.href = `{{ route('admin.restoran.list') }}?${params.toString()}`;
}

function filterKat(id) {
    const params = new URLSearchParams(window.location.search);

    if (id) {
        params.set('kategori', id);
    } else {
        params.delete('kategori');
    }

    params.set('page', 1);

    window.location.href = `{{ route('admin.restoran.list') }}?${params.toString()}`;
}

function goPage(page) {
    applyQ(page);
}

// search enter
document.getElementById('searchInput')?.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        applyQ();
    }
});

// realtime search delay
let searchTimer;
document.getElementById('searchInput')?.addEventListener('input', function() {
    clearTimeout(searchTimer);

    searchTimer = setTimeout(() => {
        applyQ();
    }, 500);
});
</script>
@endpush