{{--
  detail_toko/grid_menu_aksi.blade.php
  Grid kartu menu WITH tombol Edit & Hapus + tombol Tambah Menu.
  Dipakai di: admin (show), pemilik (index).

  Variabel:
    $restoran         → with menu
    $kategoriMenu     → Collection KategoriMenu
    $urlStoreMenu     → string URL POST tambah menu
    $urlUpdateMenu    → string URL PUT/POST edit menu
    $urlDeleteMenu    → string URL DELETE
--}}

<div class="msec">
  <div class="mhead">
    <div>
      <div class="mhead-t">Menu Tersedia</div>
      <div class="mhead-sub">{{ $restoran->menu->count() }} item terdaftar</div>
    </div>
    <button class="btn btn-primary btn-sm" onclick="openAddMenu()">
      <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
      </svg>
      Tambah Menu
    </button>
  </div>

  <div class="mgrid" id="mgrid">
    @forelse($restoran->menu as $m)
    <div class="mi" id="mi-{{ $m->id_menu }}">
      {{-- Badge Habis --}}
      @if(!$m->tersedia)
        <div class="mi-habis">Habis</div>
      @endif

      {{-- Foto --}}
      @if($m->foto_menu)
        <img class="mi-img" src="{{ asset('storage/'.$m->foto_menu) }}" alt="{{ $m->nama_menu }}">
      @else
        <div class="mi-ph">🍽</div>
      @endif

      {{-- Info --}}
      <div class="mi-info">
        <div class="mi-n">{{ $m->nama_menu }}</div>
        @if($m->deskripsi)
          <div class="mi-desc">{{ Str::limit($m->deskripsi, 45) }}</div>
        @endif
        <div class="mi-p">{{ $m->harga_format }}</div>
      </div>

      {{-- Aksi — dua tombol penuh lebar dalam satu baris --}}
      <div class="mi-acts">
        <button class="mi-btn mi-btn-edit"
          onclick="openEditMenu(
            {{ $m->id_menu }},
            '{{ addslashes($m->nama_menu) }}',
            {{ $m->harga }},
            '{{ addslashes($m->deskripsi ?? '') }}',
            {{ $m->tersedia ? 1 : 0 }},
            {{ $m->id_kategori_menu ?? 'null' }}
          )">
          <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
            <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4z"/>
          </svg>
          Edit
        </button>
        <button class="mi-btn mi-btn-del" onclick="delMenu({{ $m->id_menu }})">
          <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <polyline points="3 6 5 6 21 6"/>
            <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
            <path d="M10 11v6M14 11v6"/>
          </svg>
          Hapus
        </button>
      </div>
    </div>
    @empty
    <div class="mgrid-empty">
      <div class="mgrid-empty-icon">🍽</div>
      <div class="mgrid-empty-t">Belum ada menu</div>
      <div class="mgrid-empty-s">Klik "Tambah Menu" untuk menambahkan item pertama</div>
    </div>
    @endforelse
  </div>
</div>

{{-- ── STYLE khusus grid menu aksi ──────────────────────────────── --}}
<style>
.msec{background:#fff;border-radius:16px;border:1px solid var(--s2);margin-top:24px;overflow:hidden}
.mhead{padding:16px 20px;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid var(--s1);gap:12px}
.mhead-t{font-size:15px;font-weight:700;color:var(--s9)}
.mhead-sub{font-size:11px;color:var(--s4);margin-top:2px}
.mgrid{
  display:grid;
  grid-template-columns:repeat(auto-fill,minmax(168px,1fr));
  gap:14px;
  padding:18px 20px
}

/* Kartu menu */
.mi{
  border-radius:13px;
  border:1.5px solid var(--s2);
  overflow:hidden;
  position:relative;
  display:flex;
  flex-direction:column;
  transition:border-color .15s,box-shadow .15s
}
.mi:hover{border-color:var(--g);box-shadow:0 4px 14px rgba(26,158,92,.1)}
.mi-img{width:100%;height:120px;object-fit:cover;display:block;flex-shrink:0}
.mi-ph{
  width:100%;height:120px;flex-shrink:0;
  background:linear-gradient(135deg,var(--gl),var(--gm));
  display:flex;align-items:center;justify-content:center;font-size:28px
}
.mi-habis{
  position:absolute;top:8px;right:8px;
  background:var(--r);color:#fff;
  font-size:10px;font-weight:700;
  padding:3px 8px;border-radius:20px;
  letter-spacing:.02em
}
.mi-info{padding:10px 12px 8px;flex:1}
.mi-n{font-size:13px;font-weight:700;color:var(--s9);line-height:1.3}
.mi-desc{font-size:11px;color:var(--s4);margin-top:3px;line-height:1.4}
.mi-p{font-size:13px;font-weight:700;color:var(--g);margin-top:6px}

/* Baris aksi — 2 tombol sejajar, penuh lebar */
.mi-acts{
  display:grid;
  grid-template-columns:1fr 1fr;
  border-top:1px solid var(--s1);
  flex-shrink:0
}
.mi-btn{
  display:inline-flex;align-items:center;justify-content:center;gap:5px;
  padding:8px 4px;
  font-size:12px;font-weight:600;font-family:var(--font);
  border:none;cursor:pointer;
  transition:background .12s,color .12s
}
.mi-btn-edit{
  background:transparent;
  color:var(--s6);
  border-right:1px solid var(--s1)
}
.mi-btn-edit:hover{background:var(--gl);color:var(--g)}
.mi-btn-del{
  background:transparent;
  color:var(--s4)
}
.mi-btn-del:hover{background:#fff1f1;color:var(--r)}

/* Empty state */
.mgrid-empty{
  grid-column:1/-1;
  display:flex;flex-direction:column;align-items:center;
  padding:52px 20px;
  text-align:center
}
.mgrid-empty-icon{font-size:36px;margin-bottom:10px;opacity:.4}
.mgrid-empty-t{font-size:14px;font-weight:600;color:var(--s6)}
.mgrid-empty-s{font-size:12px;color:var(--s4);margin-top:4px}
</style>

{{-- ── MODAL TAMBAH/EDIT MENU ───────────────────────────────────── --}}
<div class="modal-overlay" id="menuM">
  <div class="modal" style="max-width:480px">
    <div class="modal-head">
      <div>
        <div class="modal-title" id="menuMT">Tambah Menu</div>
        <div class="modal-sub">Isi detail item menu</div>
      </div>
      <button class="modal-x" onclick="closeM('menuM')">✕</button>
    </div>

    <div class="modal-body">
      <form id="menuF" enctype="multipart/form-data">
        @csrf
        <div style="display:flex;flex-direction:column;gap:14px">

          <div class="fg">
            <label>Nama Menu <span class="req">*</span></label>
            <input type="text" name="nama_menu" placeholder="cth: Nasi Goreng Spesial" required>
          </div>

          <div class="g2">
            <div class="fg">
              <label>Harga (Rp) <span class="req">*</span></label>
              <input type="number" name="harga" min="0" placeholder="25000" required>
            </div>
            <div class="fg">
              <label>Status</label>
              <select name="tersedia">
                <option value="1">✅ Tersedia</option>
                <option value="0">❌ Habis</option>
              </select>
            </div>
          </div>

          <div class="fg">
            <label>Kategori Menu</label>
            <select name="id_kategori_menu">
              <option value="">Tanpa Kategori</option>
              @foreach($kategoriMenu as $km)
                <option value="{{ $km->id_kategori_menu }}">{{ $km->nama_kategori }}</option>
              @endforeach
            </select>
          </div>

          <div class="fg">
            <label>Deskripsi</label>
            <textarea name="deskripsi" placeholder="Deskripsi singkat menu (opsional)..."></textarea>
          </div>

          <div class="fg">
            <label>Foto Menu</label>
            <input type="file" name="foto_menu" accept="image/*">
          </div>

        </div>
      </form>
    </div>

    <div class="modal-foot">
      <button class="btn btn-outline" onclick="closeM('menuM')">Batal</button>
      <button class="btn btn-primary" onclick="saveMenu()">
        <span id="mL">Simpan</span>
        <span id="mS" class="spinner" style="display:none"></span>
      </button>
    </div>
  </div>
</div>

<script>
const URL_STORE_MENU  = '{{ $urlStoreMenu }}';
const URL_UPDATE_MENU = '{{ $urlUpdateMenu }}';
const URL_DELETE_MENU = '{{ $urlDeleteMenu }}';

let editMenuId = null;

function openAddMenu() {
  editMenuId = null;
  document.getElementById('menuMT').textContent = 'Tambah Menu';
  document.querySelector('.modal-sub') && (document.querySelector('#menuM .modal-sub').textContent = 'Isi detail item menu');
  document.getElementById('menuF').reset();
  document.getElementById('menuM').classList.add('open');
}

function openEditMenu(id, nama, harga, deskripsi, tersedia, idKategori) {
  editMenuId = id;
  document.getElementById('menuMT').textContent = 'Edit Menu';
  const f = document.getElementById('menuF');
  f.querySelector('[name=nama_menu]').value        = nama;
  f.querySelector('[name=harga]').value            = harga;
  f.querySelector('[name=deskripsi]').value        = deskripsi;
  f.querySelector('[name=tersedia]').value         = tersedia;
  f.querySelector('[name=id_kategori_menu]').value = idKategori ?? '';
  document.getElementById('menuM').classList.add('open');
}

async function saveMenu() {
  try {
    document.getElementById('mL').style.display = 'none';
    document.getElementById('mS').style.display = '';

    const fd  = new FormData(document.getElementById('menuF'));
    const url = editMenuId ? `${URL_UPDATE_MENU}/${editMenuId}` : URL_STORE_MENU;
    if (editMenuId) fd.append('_method', 'PUT');

    const res = await fetch(url, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
      body: fd,
    });
    const d = await res.json();

    if (d.success) {
      showToast(editMenuId ? 'Menu diperbarui' : 'Menu ditambahkan', 'success');
      closeM('menuM');
      setTimeout(() => location.reload(), 600);
    } else {
      showToast(d.message || 'Gagal menyimpan menu', 'error');
    }
  } catch (err) {
    console.error(err);
    showToast('Terjadi kesalahan', 'error');
  } finally {
    document.getElementById('mL').style.display = '';
    document.getElementById('mS').style.display = 'none';
  }
}

async function delMenu(id) {
  if (!confirm('Hapus menu ini? Tindakan tidak dapat dibatalkan.')) return;
  const d = await apiFetch(`${URL_DELETE_MENU}/${id}`, { method: 'DELETE' });
  if (d.success) {
    document.getElementById(`mi-${id}`)?.remove();
    showToast('Menu dihapus', 'success');
  } else {
    showToast('Gagal menghapus', 'error');
  }
}
</script>