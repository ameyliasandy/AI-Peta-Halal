{{--
  _form_scripts.blade.php
  Script JS untuk form tambah/edit usaha.
  Dipakai oleh admin (modal di list.blade.php) dan pemilik (halaman tersendiri).

  Variabel JS yang diinjeksi dari blade:
    window.FORM_IS_ADMIN  → bool
    window.FORM_EDIT_ID   → int|null (null = tambah baru)
--}}
<script>
// ── CONFIG ──────────────────────────────────────────────────────
const IS_ADMIN  = window.FORM_IS_ADMIN  ?? false;
let   EDIT_ID   = window.FORM_EDIT_ID   ?? null;
const TOTAL     = IS_ADMIN ? 4 : 4; // sama, tapi step 3 isi-nya beda
let   curStep   = 1;

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
    if (i === n)   s.classList.add('on');
    else if (i < n) s.classList.add('done');
  }
  curStep = n;
  const btnPrev = document.getElementById('btnPrev');
  const btnNext = document.getElementById('btnNext');
  const btnSave = document.getElementById('btnSave');
  if (btnPrev) btnPrev.style.display = n > 1    ? '' : 'none';
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

  // Baris tambahan hanya untuk admin
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
      showToast(data.message, 'success');
      // Admin: tutup modal lalu reload. Pemilik: redirect ke dashboard.
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
  document.getElementById('f-kel').innerHTML = '<option value="">Pilih kecamatan dulu</option>';
  document.getElementById('f-kpos').value    = '';
  document.getElementById('f-sub').innerHTML = '<option value="">Pilih jenis usaha dulu</option>';
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

  // Kecamatan & kelurahan
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
    set('no_sertifikat',   v.no_sertifikat);
    set('lembaga_penerbit',v.lembaga_penerbit);
    set('masa_berlaku',    v.masa_berlaku?.substring(0, 10));
    set('status_verifikasi', v.status);
    set('catatan',         v.catatan);
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
</script>
