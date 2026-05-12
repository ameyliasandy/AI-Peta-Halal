@extends('admin.layout')
@section('title', $restoran->nama_restoran)
@push('styles')
<style>
.back{display:inline-flex;align-items:center;gap:7px;font-size:13px;font-weight:600;color:var(--s6);text-decoration:none;margin-bottom:20px}
.back:hover{color:var(--g)}
.cover-wrap{border-radius:16px;overflow:hidden;margin-bottom:22px;height:220px;background:var(--s2)}
.cover-wrap img{width:100%;height:220px;object-fit:cover;display:block}
.cover-ph{width:100%;height:220px;background:linear-gradient(135deg,var(--gl),var(--gm));display:flex;align-items:center;justify-content:center}
.layout{display:grid;grid-template-columns:1fr 290px;gap:20px;align-items:start}

/* LEFT */
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

/* RIGHT */
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

/* MENU */
.msec{background:#fff;border-radius:16px;border:1px solid var(--s2);margin-top:20px;overflow:hidden}
.mhead{padding:16px 22px;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid var(--s1)}
.mhead-t{font-size:15px;font-weight:700;color:var(--s9)}
.mgrid{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:12px;padding:16px 22px}
.mi{border-radius:12px;border:1.5px solid var(--s2);overflow:hidden;position:relative;transition:all .15s}
.mi:hover{border-color:var(--g);box-shadow:0 3px 12px rgba(26,158,92,.1)}
.mi-img{width:100%;height:110px;object-fit:cover;background:var(--s2);display:block}
.mi-ph{width:100%;height:110px;background:linear-gradient(135deg,var(--gl),var(--gm));display:flex;align-items:center;justify-content:center;font-size:26px}
.mi-info{padding:9px 11px}
.mi-n{font-size:13px;font-weight:700;color:var(--s9)}
.mi-p{font-size:12px;font-weight:600;color:var(--g);margin-top:2px}
.mi-acts{display:flex;gap:4px;padding:0 11px 9px}
.mi-habis{position:absolute;top:7px;right:7px;background:var(--r);color:#fff;font-size:10px;font-weight:700;padding:2px 7px;border-radius:9px}

/* MODAL EDIT TOKO */
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
<a href="{{ route('admin.restoran.list') }}" class="back">
  <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
  Kembali ke List Usaha
</a>

@php $v = $restoran->verifikasiHalal; @endphp

<div class="cover-wrap">
  @if($restoran->foto_utama)
    <img src="{{ asset('storage/'.$restoran->foto_utama) }}" alt="{{ $restoran->nama_restoran }}">
  @else
    <div class="cover-ph">
      <svg width="52" height="52" fill="none" stroke="var(--g)" stroke-width="1.5" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
    </div>
  @endif
</div>

<div class="layout">
  {{-- KIRI --}}
  <div>
    <div class="card">
      <div class="tname">
        {{ $restoran->nama_restoran }}
        @if($restoran->status_halal==='certified')<span class="hb">✓ HALAL MUI</span>
        @elseif($restoran->status_halal==='self_claimed')<span class="sb">Self-Claimed</span>
        @endif
      </div>
      <div class="rrow">
        <span style="color:#f59e0b">⭐</span>
        <span>{{ $restoran->rating }}({{ $restoran->jumlah_ulasan }} ulasan)</span>
        <span style="display:flex;align-items:center;gap:5px">
          <span class="dot" style="background:{{ $restoran->status_buka?'#22c55e':'var(--r)' }}"></span>
          <span style="color:{{ $restoran->status_buka?'#22c55e':'var(--r)' }}">{{ $restoran->status_buka?'Buka sekarang':'Tutup' }}</span>
        </span>
      </div>
      @if($restoran->deskripsi)<div class="tdesc">{{ $restoran->deskripsi }}</div>@endif

      <div class="igrid">
        <div class="iitem">
          <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          <div><div class="ilbl">Jam Operasional</div><div class="ival">{{ $restoran->jam_operasional??'-' }}</div></div>
        </div>
        <div class="iitem">
          <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
          <div><div class="ilbl">Harga Rata-rata</div><div class="ival g">{{ $restoran->harga_rata_rata }}</div></div>
        </div>
        <div class="iitem">
          <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81 2 2 0 012 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L6.09 8.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>
          <div><div class="ilbl">Telepon</div><div class="ival g">{{ $restoran->no_telepon??'-' }}</div></div>
        </div>
        <div class="iitem">
          <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
          <div><div class="ilbl">Kategori</div><div class="ival g">{{ $restoran->subKategori?->nama_sub_kategori??$restoran->kategori?->nama_kategori??'-' }}</div></div>
        </div>
        <div class="iitem" style="grid-column:1/-1">
          <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
          <div><div class="ilbl">Alamat</div><div class="ival">{{ $restoran->alamat }}, {{ $restoran->kota }}</div></div>
        </div>
      </div>

      <div class="tacts">
        <button class="btn btn-primary" onclick="document.getElementById('editTokoM').classList.add('open')">
          <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4z"/></svg>
          Edit Profil Toko
        </button>
        @if($restoran->latitude)
        <a href="https://maps.google.com/?q={{ $restoran->latitude }},{{ $restoran->longitude }}" target="_blank" class="btn btn-outline">
          <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
          Lihat di Peta
        </a>
        @endif
      </div>
    </div>
  </div>

  {{-- KANAN --}}
  <div>
    <div class="vc">
      <div class="vc-t">Verifikasi Halal</div>
      <div class="vr"><span class="vl">Status</span>
        @if($v?->status==='terverifikasi') <span class="badge bv">Terverifikasi</span>
        @elseif($v?->status==='pending')   <span class="badge bp">Pending</span>
        @elseif($v?->status==='ditolak')   <span class="badge br">Ditolak</span>
        @else <span class="badge bn">Belum Diajukan</span>
        @endif
      </div>
      <div class="vr"><span class="vl">No. Sertifikat</span><span class="vv">{{ $v?->no_sertifikat??'—' }}</span></div>
      <div class="vr"><span class="vl">Lembaga</span><span class="vv">{{ $v?->lembaga_penerbit??'—' }}</span></div>
      <div class="vr"><span class="vl">Masa Berlaku</span>
        <span class="vv" style="{{ $v?->isSertifikatHampirExpire()?'color:var(--r)':'' }}">{{ $v?->masa_berlaku?->format('d/m/Y')??'—' }}</span>
      </div>
      <div class="vr"><span class="vl">Tgl Verifikasi</span><span class="vv">{{ $v?->tanggal_verifikasi?->format('d/m/Y')??'—' }}</span></div>
      @if($v?->catatan)<div class="cn"><strong>Catatan:</strong> {{ $v->catatan }}</div>@endif

      <div class="va-wrap">
        <select id="vStatus">
          <option value="">Ubah Status Verifikasi...</option>
          <option value="terverifikasi">✓ Terverifikasi</option>
          <option value="pending">⏳ Pending</option>
          <option value="ditolak">✕ Ditolak</option>
        </select>
        <textarea id="vCatatan" placeholder="Catatan admin (opsional)..."></textarea>
        <button class="btn btn-primary" style="width:100%;justify-content:center" onclick="updateVerif({{ $restoran->id_restoran }})">
          <span id="vL">Simpan Status</span><span id="vS" class="spinner" style="display:none"></span>
        </button>
      </div>
    </div>

    @if($v?->dokumen_sertifikat)
    <div class="vc">
      <div class="vc-t">Dokumen Sertifikat</div>
      <a href="{{ asset('storage/'.$v->dokumen_sertifikat) }}" target="_blank" class="btn btn-outline" style="width:100%;justify-content:center">
        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        Lihat Dokumen
      </a>
    </div>
    @endif

    <div class="vc">
      <div class="vc-t">Info Pemilik</div>
      <div class="vr"><span class="vl">Nama</span><span class="vv">{{ $restoran->pemilik?->nama??'-' }}</span></div>
      <div class="vr"><span class="vl">No. HP</span><span class="vv">{{ $restoran->pemilik?->no_hp??'-' }}</span></div>
      <div class="vr"><span class="vl">Email</span><span class="vv" style="font-size:11px">{{ $restoran->pemilik?->email??'-' }}</span></div>
    </div>
  </div>
</div>

{{-- MENU --}}
<div class="msec">
  <div class="mhead">
    <div class="mhead-t">Menu Tersedia ({{ $restoran->menu->count() }})</div>
    <button class="btn btn-primary btn-sm" onclick="openAddMenu()">
      <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Tambah Menu
    </button>
  </div>
  <div class="mgrid" id="mgrid">
    @forelse($restoran->menu as $m)
    <div class="mi" id="mi-{{ $m->id_menu }}">
      @if(!$m->tersedia)<div class="mi-habis">Habis</div>@endif
      @if($m->foto_menu)
        <img class="mi-img" src="{{ asset('storage/'.$m->foto_menu) }}" alt="">
      @else
        <div class="mi-ph">🍽</div>
      @endif
      <div class="mi-info">
        <div class="mi-n">{{ $m->nama_menu }}</div>
        <div class="mi-p">{{ $m->harga_format }}</div>
      </div>
      <div class="mi-acts">
        <button class="btn btn-sm btn-e" style="flex:1;justify-content:center"
                onclick="openEditMenu({{ $m->id_menu }},'{{ addslashes($m->nama_menu) }}',{{ $m->harga }},'{{ addslashes($m->deskripsi??'') }}',{{ $m->tersedia?1:0 }})">Edit</button>
        <button class="btn btn-sm btn-d" onclick="delMenu({{ $m->id_menu }})">✕</button>
      </div>
    </div>
    @empty
    <div id="emptyMenu" style="grid-column:1/-1;text-align:center;padding:40px;color:var(--s4)">Belum ada menu</div>
    @endforelse
  </div>
</div>

{{-- MODAL EDIT PROFIL TOKO --}}
<div class="modal-overlay" id="editTokoM">
  <div class="modal">
    <div class="modal-head">
      <div><div class="modal-title">Edit Profil Toko</div><div class="modal-sub">Ubah informasi usaha</div></div>
      <button class="modal-x" onclick="closeM('editTokoM')">✕</button>
    </div>
    <div class="modal-body">
      <form id="editTokoF" enctype="multipart/form-data">
        @csrf
        <div class="g2">
          <div class="fg" style="grid-column:1/-1"><label>Nama Restoran <span class="req">*</span></label><input type="text" name="nama_restoran" value="{{ $restoran->nama_restoran }}" required></div>
          <div class="fg"><label>Jam Operasional</label><input type="text" name="jam_operasional" value="{{ $restoran->jam_operasional }}"></div>
          <div class="fg"><label>No. Telepon</label><input type="tel" name="no_telepon" value="{{ $restoran->no_telepon }}"></div>
          <div class="fg"><label>Email Usaha</label><input type="email" name="email_usaha" value="{{ $restoran->email_usaha }}"></div>
          <div class="fg"><label>Website/Sosmed</label><input type="text" name="website_sosmed" value="{{ $restoran->website_sosmed }}"></div>
          <div class="fg"><label>Harga Min (Rp)</label><input type="number" name="harga_rata_rata_min" value="{{ $restoran->harga_rata_rata_min }}"></div>
          <div class="fg"><label>Harga Max (Rp)</label><input type="number" name="harga_rata_rata_max" value="{{ $restoran->harga_rata_rata_max }}"></div>
          <div class="fg" style="grid-column:1/-1"><label>Alamat</label><input type="text" name="alamat" value="{{ $restoran->alamat }}"></div>
          <div class="fg"><label>Kota</label><input type="text" name="kota" value="{{ $restoran->kota }}"></div>
          <div class="fg"><label>Provinsi</label><input type="text" name="provinsi" value="{{ $restoran->provinsi }}"></div>
          <div class="fg" style="grid-column:1/-1"><label>Deskripsi</label><textarea name="deskripsi">{{ $restoran->deskripsi }}</textarea></div>
          <div class="fg" style="grid-column:1/-1"><label>Foto Utama</label><input type="file" name="foto_utama" accept="image/*"></div>
          <div class="fg"><label>Status Toko</label>
            <select name="status_buka">
              <option value="1" {{ $restoran->status_buka?'selected':'' }}>Buka</option>
              <option value="0" {{ !$restoran->status_buka?'selected':'' }}>Tutup</option>
            </select>
          </div>
        </div>
      </form>
    </div>
    <div class="modal-foot">
      <button class="btn btn-outline" onclick="closeM('editTokoM')">Batal</button>
      <button class="btn btn-primary" onclick="saveEditToko({{ $restoran->id_restoran }})">
        <span id="etL">Simpan</span><span id="etS" class="spinner" style="display:none"></span>
      </button>
    </div>
  </div>
</div>

{{-- MODAL MENU --}}
<div class="modal-overlay" id="menuM">
  <div class="modal" style="max-width:460px">
    <div class="modal-head">
      <div><div class="modal-title" id="menuMT">Tambah Menu</div></div>
      <button class="modal-x" onclick="closeM('menuM')">✕</button>
    </div>
    <div class="modal-body">
      <form id="menuF" enctype="multipart/form-data">
        @csrf
        <div style="display:flex;flex-direction:column;gap:12px">
          <div class="fg"><label>Nama Menu <span class="req">*</span></label><input type="text" name="nama_menu" required></div>
          <div class="fg"><label>Harga (Rp) <span class="req">*</span></label><input type="number" name="harga" min="0" required></div>
          <div class="fg"><label>Deskripsi</label><textarea name="deskripsi" style="min-height:56px"></textarea></div>
          <div class="fg"><label>Kategori Menu</label>
            <select name="id_kategori">
              <option value="">Tanpa Kategori</option>
              @foreach($kategoriMenu as $km)
                <option value="{{ $km->id_kategori_menu }}">{{ $km->nama_kategori }}</option>
              @endforeach
            </select>
          </div>
          <div class="fg"><label>Status</label>
            <select name="tersedia"><option value="1">Tersedia</option><option value="0">Habis</option></select>
          </div>
          <div class="fg"><label>Foto Menu</label><input type="file" name="foto_menu" accept="image/*"></div>
        </div>
      </form>
    </div>
    <div class="modal-foot">
      <button class="btn btn-outline" onclick="closeM('menuM')">Batal</button>
      <button class="btn btn-primary" onclick="saveMenu()">
        <span id="mL">Simpan</span><span id="mS" class="spinner" style="display:none"></span>
      </button>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
const RID = {{ $restoran->id_restoran }};
let editMenuId = null;

async function updateVerif(id){
  const st=document.getElementById('vStatus').value;
  const ct=document.getElementById('vCatatan').value;
  if(!st){showToast('Pilih status verifikasi','error');return;}
  document.getElementById('vL').style.display='none';document.getElementById('vS').style.display='';
  const fd=new FormData();
  fd.append('status_verifikasi',st);fd.append('catatan',ct);
  const d=await fetch(`/admin/restoran/${id}/update`,{method:'POST',headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json'},body:fd}).then(r=>r.json());
  document.getElementById('vL').style.display='';document.getElementById('vS').style.display='none';
  if(d.success){showToast('Status verifikasi diperbarui','success');setTimeout(()=>location.reload(),700);}
  else showToast('Gagal memperbarui','error');
}

async function saveEditToko(id){
  document.getElementById('etL').style.display='none';document.getElementById('etS').style.display='';
  const fd=new FormData(document.getElementById('editTokoF'));
  const d=await fetch(`/admin/restoran/${id}/update`,{method:'POST',headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json'},body:fd}).then(r=>r.json());
  document.getElementById('etL').style.display='';document.getElementById('etS').style.display='none';
  if(d.success){showToast('Profil toko diperbarui','success');setTimeout(()=>location.reload(),700);}
  else showToast('Gagal menyimpan','error');
}

function openAddMenu(){
  editMenuId=null;
  document.getElementById('menuMT').textContent='Tambah Menu';
  document.getElementById('menuF').reset();
  document.getElementById('menuM').classList.add('open');
}

function openEditMenu(id,nama,harga,deskripsi,tersedia){
  editMenuId=id;
  document.getElementById('menuMT').textContent='Edit Menu';
  const f=document.getElementById('menuF');
  f.querySelector('[name=nama_menu]').value=nama;
  f.querySelector('[name=harga]').value=harga;
  f.querySelector('[name=deskripsi]').value=deskripsi;
  f.querySelector('[name=tersedia]').value=tersedia;
  document.getElementById('menuM').classList.add('open');
}

async function saveMenu(){
  document.getElementById('mL').style.display='none';document.getElementById('mS').style.display='';
  const fd=new FormData(document.getElementById('menuF'));
  const url=editMenuId?`/admin/restoran/${RID}/menu/${editMenuId}`:`/admin/restoran/${RID}/menu`;
  const d=await fetch(url,{method:'POST',headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json'},body:fd}).then(r=>r.json());
  document.getElementById('mL').style.display='';document.getElementById('mS').style.display='none';
  if(d.success){
    showToast(editMenuId?'Menu diperbarui':'Menu ditambahkan','success');
    closeM('menuM');setTimeout(()=>location.reload(),600);
  }else showToast('Gagal menyimpan menu','error');
}

async function delMenu(id){
  if(!confirm('Hapus menu ini?'))return;
  const d=await apiFetch(`/admin/restoran/${RID}/menu/${id}`,{method:'DELETE'});
  if(d.success){document.getElementById(`mi-${id}`)?.remove();showToast('Menu dihapus','success');}
}

function closeM(id){document.getElementById(id).classList.remove('open');}
document.querySelectorAll('.modal-overlay').forEach(o=>o.addEventListener('click',e=>{if(e.target===o)o.classList.remove('open');}));
</script>
@endpush