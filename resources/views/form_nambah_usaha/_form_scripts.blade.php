{{--
  _form_scripts.blade.php
  Script JS untuk form tambah/edit usaha.
  Dipakai oleh admin (modal di list.blade.php) dan pemilik (halaman tersendiri).

  Variabel JS yang diinjeksi dari blade:
    window.FORM_IS_ADMIN  → bool
    window.FORM_EDIT_ID   → int|null (null = tambah baru)

  ✅ Auto-save ke sessionStorage:
    - Menyimpan semua field (kecuali file) setiap ada perubahan
    - Restore otomatis saat form dibuka kembali
    - Dibersihkan otomatis setelah submit berhasil
    - Key: 'petha_form_draft' (pemilik) atau 'petha_form_draft_admin' (admin)
--}}
<script>
// ── CONFIG ──────────────────────────────────────────────────────
const IS_ADMIN  = window.FORM_IS_ADMIN  ?? false;
let   EDIT_ID   = window.FORM_EDIT_ID   ?? null;
const TOTAL     = 4;
let   curStep   = 1;

// Key untuk sessionStorage — pisahkan admin vs pemilik
const DRAFT_KEY = IS_ADMIN ? 'petha_form_draft_admin' : 'petha_form_draft';

// ── DATA WILAYAH BATAM ───────────────────────────────────────────
const BATAM_AREA = {
  'Batam Kota'     : { kel: ['Belian','Baloi Permai','Sungai Panas','Sukajadi','Teluk Tering','Taman Baloi','Sei Ladi'], kpos: '29461' },
  'Batu Aji'       : { kel: ['Bukit Tempayan','Tembesi','Kibing','Buliang'], kpos: '29422' },
  'Batu Ampar'     : { kel: ['Batu Ampar','Kampung Seraya','Sungai Jodoh','Tanjung Sengkuang'], kpos: '29451' },
  'Bengkong'       : { kel: ['Bengkong Harapan','Bengkong Laut','Bengkong Indah','Sadai'], kpos: '29458' },
  'Bulang'         : { kel: ['Bulang Lintang','Batu Legong','Temoyong','Pantai Gelam','Pulau Buluh','Pulau Jaloh'], kpos: '29471' },
  'Galang'         : { kel: ['Galang Baru','Air Raja','Karas','Sembulang','Sijantung','Subang Mas','Rempang Cate'], kpos: '29472' },
  'Lubuk Baja'     : { kel: ['Batu Selicin','Baloi Indah','Kampung Pelita','Lubuk Baja Kota','Tanjung Uma'], kpos: '29441' },
  'Nongsa'         : { kel: ['Batu Besar','Kabil','Nongsa','Sambau'], kpos: '29466' },
  'Sagulung'       : { kel: ['Sei Langkai','Sei Lekop','Sei Pelunggut','Sei Binti','Sagulung Kota','Tembesi'], kpos: '29425' },
  'Sei Beduk'      : { kel: ['Duriangkang','Mangsang','Muka Kuning','Tanjung Piayu'], kpos: '29433' },
  'Sekupang'       : { kel: ['Sungai Harapan','Tanjung Pinggir','Tanjung Riau','Tiban Baru','Tiban Indah','Tiban Lama','Patam Lestari'], kpos: '29415' },
  'Belakang Padang': { kel: ['Belakang Padang','Kasu','Pecong','Pemping','Pulau Terong','Sekanak Raya'], kpos: '29411' },
};

// ── AUTO-SAVE: Kumpulkan semua nilai form → simpan ke sessionStorage ──────
function draftSave() {
  // Jika ini mode edit (ada EDIT_ID), skip draft karena data sudah di DB
  if (EDIT_ID) return;

  const f = document.getElementById('restoranForm');
  if (!f) return;

  const draft = {};

  // Semua input, select, textarea — kecuali file
  f.querySelectorAll('input, select, textarea').forEach(el => {
    if (!el.name || el.type === 'file' || el.type === 'hidden') return;

    if (el.type === 'checkbox') {
      draft[el.name] = el.checked;
    } else {
      draft[el.name] = el.value;
    }
  });

  // Simpan step terakhir supaya bisa lanjut dari sini
  draft['__step'] = curStep;

  try {
    sessionStorage.setItem(DRAFT_KEY, JSON.stringify(draft));
  } catch(e) {
    // sessionStorage penuh atau private mode — tidak apa-apa
  }
}

// ── AUTO-SAVE: Restore dari sessionStorage ke form ───────────────────────
function draftRestore() {
  // Hanya restore untuk form tambah baru (bukan edit)
  if (EDIT_ID) return;

  let draft;
  try {
    const raw = sessionStorage.getItem(DRAFT_KEY);
    if (!raw) return;
    draft = JSON.parse(raw);
  } catch(e) {
    return;
  }

  const f = document.getElementById('restoranForm');
  if (!f) return;

  // Restore kecamatan dulu (karena kelurahan tergantung kecamatan)
  const kecVal = draft['kecamatan_kelurahan'] ?? draft['__kecamatan'] ?? '';
  const kelVal = draft['__kelurahan'] ?? '';

  const kecSel = document.getElementById('f-kec');
  if (kecSel && kecVal) {
    kecSel.value = kecVal;
    loadKelurahan(kelVal || null);
  }

  // Restore field lainnya
  Object.entries(draft).forEach(([name, value]) => {
    if (name.startsWith('__')) return; // skip meta fields

    const el = f.querySelector(`[name="${name}"]`);
    if (!el || el.type === 'file') return;

    if (el.type === 'checkbox') {
      el.checked = !!value;
    } else if (el.tagName === 'SELECT') {
      el.value = value;
      // Trigger sub-kategori jika kategori di-restore
      if (el.id === 'f-kat') loadSub();
    } else {
      el.value = value ?? '';
    }
  });

  // Toggle tampilan sertifikat
  togSert();

  // Kembali ke step terakhir
  const lastStep = parseInt(draft['__step'] ?? 1);
  if (lastStep > 1) {
    // Tampilkan notifikasi draft
    showDraftBanner(lastStep);
  }
}

// ── DRAFT BANNER: Notifikasi bahwa ada draft tersimpan ─────────────────
function showDraftBanner(lastStep) {
  // Cegah duplikat
  if (document.getElementById('draftBanner')) return;

  const banner = document.createElement('div');
  banner.id = 'draftBanner';
  banner.style.cssText = `
    position:fixed;top:70px;left:50%;transform:translateX(-50%);
    background:#1D9E75;color:#fff;
    padding:10px 18px;border-radius:12px;
    font-size:13px;font-weight:600;z-index:9998;
    box-shadow:0 8px 20px rgba(0,0,0,.15);
    display:flex;align-items:center;gap:10px;
    max-width:90vw;
  `;
  banner.innerHTML = `
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
      <path d="M12 2v10m0 0l-3-3m3 3l3-3"/>
      <path d="M3 17v2a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-2"/>
    </svg>
    Draft tersimpan — lanjut dari step ${lastStep}
    <button onclick="dismissDraftAndContinue(${lastStep})"
            style="background:rgba(255,255,255,.25);border:none;color:#fff;
                   padding:3px 10px;border-radius:7px;font-size:12px;
                   font-weight:700;cursor:pointer;font-family:inherit">
      Lanjut
    </button>
    <button onclick="clearDraft()"
            style="background:none;border:none;color:rgba(255,255,255,.7);
                   font-size:12px;cursor:pointer;padding:3px 6px;font-family:inherit">
      Hapus
    </button>
  `;
  document.body.appendChild(banner);

  // Auto-hide setelah 8 detik
  setTimeout(() => banner.remove(), 8000);
}

function dismissDraftAndContinue(step) {
  document.getElementById('draftBanner')?.remove();
  gS(step);
}

function clearDraft() {
  try { sessionStorage.removeItem(DRAFT_KEY); } catch(e) {}
  document.getElementById('draftBanner')?.remove();
  resetRestoranForm();
  showToast('Draft dihapus', 'success');
}

// ── AUTO-SAVE: Pasang event listener di semua field form ────────────────
function initAutoSave() {
  if (EDIT_ID) return; // tidak perlu auto-save untuk mode edit

  const f = document.getElementById('restoranForm');
  if (!f) return;

  // Debounce supaya tidak terlalu sering nulis ke sessionStorage
  let saveTimer;
  const debouncedSave = () => {
    clearTimeout(saveTimer);
    saveTimer = setTimeout(() => {
      // Simpan nilai kecamatan & kelurahan secara terpisah (karena select kelurahan dinamis)
      const draft = JSON.parse(sessionStorage.getItem(DRAFT_KEY) ?? '{}');
      const kecEl = document.getElementById('f-kec');
      const kelEl = document.getElementById('f-kel');
      if (kecEl) draft['__kecamatan'] = kecEl.value;
      if (kelEl) draft['__kelurahan'] = kelEl.value;
      try { sessionStorage.setItem(DRAFT_KEY, JSON.stringify(draft)); } catch(e) {}

      draftSave();
    }, 400);
  };

  // Event listener untuk setiap perubahan field
  f.addEventListener('input',  debouncedSave);
  f.addEventListener('change', debouncedSave);
}

// ── KELURAHAN ────────────────────────────────────────────────────
function loadKelurahan(selectedKel = null) {
  const kec    = document.getElementById('f-kec')?.value;
  const kelSel = document.getElementById('f-kel');
  const kpoEl  = document.getElementById('f-kpos');

  if (!kelSel) return;
  kelSel.innerHTML = '<option value="">Pilih Kelurahan</option>';
  if (!kec || !BATAM_AREA[kec]) return;

  const area = BATAM_AREA[kec];
  area.kel.forEach(kel => {
    const o       = document.createElement('option');
    o.value       = kel;
    o.textContent = kel;
    if (selectedKel && kel === selectedKel) o.selected = true;
    kelSel.appendChild(o);
  });

  if (kpoEl) kpoEl.value = area.kpos;
}

// ── SUB KATEGORI ─────────────────────────────────────────────────
function loadSub(selId = null) {
  const katEl = document.getElementById('f-kat');
  const subEl = document.getElementById('f-sub');
  if (!katEl || !subEl) return;

  const opt = katEl.options[katEl.selectedIndex];
  subEl.innerHTML = '<option value="">Pilih sub kategori</option>';
  if (!opt?.dataset?.subs) return;

  JSON.parse(opt.dataset.subs).forEach(s => {
    const o       = document.createElement('option');
    o.value       = s.id_sub_kategori;
    o.textContent = s.nama_sub_kategori;
    if (selId && s.id_sub_kategori == selId) o.selected = true;
    subEl.appendChild(o);
  });
}

// ── TOGGLE SERTIFIKAT / SELF-CLAIMED ────────────────────────────
function togSert() {
  const v = document.getElementById('f-hassert')?.value;
  const sertEl   = document.getElementById('sertSec');
  const alasanEl = document.getElementById('alasanSec');
  if (sertEl)   sertEl.style.display   = v === 'ya'     ? '' : 'none';
  if (alasanEl) alasanEl.style.display = v === 'tidak'  ? '' : 'none';
}

// ── STEP NAVIGATION ──────────────────────────────────────────────
function gS(n) {
  for (let i = 1; i <= TOTAL; i++) {
    document.getElementById(`p${i}`)?.classList.toggle('on', i === n);
    const s = document.getElementById(`s${i}`);
    if (!s) continue;
    s.classList.remove('on', 'done');
    if (i === n)    s.classList.add('on');
    else if (i < n) s.classList.add('done');
  }
  curStep = n;

  // Simpan progress step ke draft
  draftSave();

  const btnPrev = document.getElementById('btnPrev');
  const btnNext = document.getElementById('btnNext');
  const btnSave = document.getElementById('btnSave');
  if (btnPrev) btnPrev.style.display = n > 1     ? '' : 'none';
  if (btnNext) btnNext.style.display = n < TOTAL ? '' : 'none';
  if (btnSave) btnSave.style.display = n === TOTAL ? '' : 'none';
  if (n === TOTAL) buildSumm();
}

function nextS() { if (curStep < TOTAL) gS(curStep + 1); }
function prevS() { if (curStep > 1)     gS(curStep - 1); }

// ── RINGKASAN (STEP 4) ───────────────────────────────────────────
function buildSumm() {
  const f   = document.getElementById('restoranForm');
  if (!f) return;
  const g   = n => f.querySelector(`[name="${n}"]`)?.value ?? '—';
  const kec = document.getElementById('f-kec')?.value  ?? '—';
  const kel = document.getElementById('f-kel')?.value  ?? '—';

  let rows = `
    <b>Nama Usaha:</b> ${g('nama_restoran')}<br>
    <b>Jam Operasional:</b> ${g('jam_operasional')}<br>
    <b>Alamat:</b> ${g('alamat')}<br>
    <b>Kecamatan:</b> ${kec} &nbsp;|&nbsp; <b>Kelurahan:</b> ${kel}<br>
    <b>Kota:</b> Batam, Kepulauan Riau<br>
    <b>No. Telepon:</b> ${g('no_telepon')}<br>
    <b>Sertifikat:</b> ${g('has_sertifikat') === 'ya' ? '✅ Bersertifikat' : '🕐 Self-Claimed'}
  `;

  if (IS_ADMIN) {
    rows += `<br><b>Status Verifikasi:</b> ${g('status_verifikasi')}`;
    rows += `<br><b>Tipe Halal:</b> ${g('tipe_halal')}`;
  }

  const box = document.getElementById('summBox');
  if (box) box.innerHTML = rows;
}

// ── SAVE FORM ────────────────────────────────────────────────────
async function saveRestoran() {
  const saveL = document.getElementById('saveL');
  const saveS = document.getElementById('saveS');
  if (saveL) saveL.style.display = 'none';
  if (saveS) saveS.style.display = '';

  const kecVal = document.getElementById('f-kec')?.value ?? '';
  const kelVal = document.getElementById('f-kel')?.value ?? '';
  setHidden('kecamatan_kelurahan', kecVal + (kelVal ? ', ' + kelVal : ''));

  const fd  = new FormData(document.getElementById('restoranForm'));
  const url = EDIT_ID
    ? `/admin/restoran/${EDIT_ID}/update`
    : (IS_ADMIN ? '/admin/restoran' : '/pemilik/toko');

  try {
    const res  = await fetch(url, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
      body: fd,
    });
    const data = await res.json();

    if (data.success) {
      //Hapus draft setelah submit berhasil
      try { sessionStorage.removeItem(DRAFT_KEY); } catch(e) {}

      showToast(data.message, 'success');
      if (IS_ADMIN) {
        closeM('restoranModal');
        setTimeout(() => location.reload(), 800);
      } else {
        setTimeout(() => window.location = data.redirect ?? '/pemilik/dashboard', 800);
      }
    } else {
      showToast(data.message ?? 'Terjadi kesalahan', 'error');
    }
  } catch (err) {
    console.error(err);
    showToast('Gagal terhubung ke server', 'error');
  } finally {
    if (saveL) saveL.style.display = '';
    if (saveS) saveS.style.display = 'none';
  }
}

// Helper: buat atau update input hidden
function setHidden(name, value) {
  const f  = document.getElementById('restoranForm');
  let el   = f.querySelector(`input[type=hidden][name="${name}"]`);
  if (!el) {
    el      = document.createElement('input');
    el.type = 'hidden';
    el.name = name;
    f.appendChild(el);
  }
  el.value = value;
}

// ── RESET FORM (untuk buka modal tambah) ─────────────────────────
function resetRestoranForm() {
  document.getElementById('restoranForm')?.reset();
  const kelEl  = document.getElementById('f-kel');
  const kposEl = document.getElementById('f-kpos');
  const subEl  = document.getElementById('f-sub');
  if (kelEl)  kelEl.innerHTML  = '<option value="">Pilih kecamatan dulu</option>';
  if (kposEl) kposEl.value     = '';
  if (subEl)  subEl.innerHTML  = '<option value="">Pilih jenis usaha dulu</option>';
  togSert();
  gS(1);
}

// ── POPULATE FORM UNTUK EDIT ─────────────────────────────────────
async function populateEdit(id) {
  const data = await apiFetch(`/admin/restoran/${id}/data`);
  const r    = data.restoran;
  const v    = data.verifikasi;
  const f    = document.getElementById('restoranForm');
  const set  = (n, val) => {
    const el = f.querySelector(`[name="${n}"]`);
    if (el && val != null) el.value = val;
  };

  set('nama_restoran',    r.nama_restoran);
  set('id_kategori',      r.id_kategori);
  loadSub(r.id_sub_kategori);
  set('kapasitas_tempat', r.kapasitas_tempat);
  set('jam_operasional',  r.jam_operasional);
  set('id_pemilik',       r.id_pemilik);
  set('deskripsi',        r.deskripsi);
  set('harga_rata_rata_min', r.harga_rata_rata_min);
  set('harga_rata_rata_max', r.harga_rata_rata_max);
  set('alamat',           r.alamat);

  const kecSel = document.getElementById('f-kec');
  if (kecSel && r.kecamatan_kelurahan) {
    const parts = r.kecamatan_kelurahan.split(', ');
    kecSel.value = parts[0] ?? '';
    loadKelurahan(parts[1] ?? null);
  }

  set('kota',         r.kota     ?? 'Batam');
  set('provinsi',     r.provinsi ?? 'Kepulauan Riau');
  set('kode_pos',     r.kode_pos);
  set('latitude',     r.latitude);
  set('longitude',    r.longitude);
  set('no_telepon',   r.no_telepon);
  set('email_usaha',  r.email_usaha);
  set('website_sosmed', r.website_sosmed);

  if (v) {
    set('no_sertifikat',    v.no_sertifikat);
    set('lembaga_penerbit', v.lembaga_penerbit);
    set('masa_berlaku',     v.masa_berlaku?.substring(0, 10));
    set('status_verifikasi', v.status);
    set('catatan',          v.catatan);
    ['bebas_babi','daging_halal','bumbu_bebas_alkohol','kemasan_halal',
     'peralatan_tidak_najis','tidak_jual_alkohol',
     'dapur_bersih','karyawan_bersih','sop_kebersihan'].forEach(k => {
      const cb = f.querySelector(`[name="${k}"]`);
      if (cb) cb.checked = !!v[k];
    });
    set('has_sertifikat', v.no_sertifikat ? 'ya' : 'tidak');
    togSert();
  }

  if (IS_ADMIN) {
    set('tipe_halal', r.status_halal);
  }
}

// ── INIT — jalankan saat DOM siap ────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
  // Pasang auto-save listener
  initAutoSave();

  // Restore draft (hanya untuk form tambah baru, bukan edit)
  if (!EDIT_ID) {
    draftRestore();
  }
});
</script>