{{--
  detail_toko/modal_edit_profil.blade.php
  Modal edit profil toko.
  Admin   → bisa ubah status_buka + semua field.
  Pemilik → semua field KECUALI status_buka (toggle terpisah).

  Variabel:
    $restoran
    $isAdmin   → bool
    $urlUpdate → string URL POST update
--}}
<div class="modal-overlay" id="editTokoM">
  <div class="modal" style="max-width:520px">
    <div class="modal-head">
      <div>
        <div class="modal-title">Edit Profil Toko</div>
        <div class="modal-sub">Ubah informasi usaha</div>
      </div>
      <button class="modal-x" onclick="closeM('editTokoM')">✕</button>
    </div>

    <div class="modal-body">
      <form id="editTokoF" enctype="multipart/form-data">
        @csrf
        <div style="display:flex;flex-direction:column;gap:14px">

          {{-- Nama --}}
          <div class="fg">
            <label>Nama Restoran <span class="req">*</span></label>
            <input type="text" name="nama_restoran" value="{{ $restoran->nama_restoran }}" required>
          </div>

          {{-- Jam + Telepon --}}
          <div class="g2">
            <div class="fg">
              <label>Jam Operasional</label>
              <input type="text" name="jam_operasional" value="{{ $restoran->jam_operasional }}" placeholder="cth: 08.00–22.00">
            </div>
            <div class="fg">
              <label>No. Telepon</label>
              <input type="tel" name="no_telepon" value="{{ $restoran->no_telepon }}">
            </div>
          </div>

          {{-- Email + Sosmed --}}
          <div class="g2">
            <div class="fg">
              <label>Email Usaha</label>
              <input type="email" name="email_usaha" value="{{ $restoran->email_usaha }}">
            </div>
            <div class="fg">
              <label>Website / Sosmed</label>
              <input type="text" name="website_sosmed" value="{{ $restoran->website_sosmed }}">
            </div>
          </div>

          {{-- Harga min/max --}}
          <div class="g2">
            <div class="fg">
              <label>Harga Min (Rp)</label>
              <input type="number" name="harga_rata_rata_min" value="{{ $restoran->harga_rata_rata_min }}" placeholder="10000">
            </div>
            <div class="fg">
              <label>Harga Max (Rp)</label>
              <input type="number" name="harga_rata_rata_max" value="{{ $restoran->harga_rata_rata_max }}" placeholder="50000">
            </div>
          </div>

          {{-- Alamat --}}
          <div class="fg">
            <label>Alamat</label>
            <input type="text" name="alamat" value="{{ $restoran->alamat }}">
          </div>

          {{-- Kota + Provinsi --}}
          <div class="g2">
            <div class="fg">
              <label>Kota</label>
              <input type="text" name="kota" value="{{ $restoran->kota }}">
            </div>
            <div class="fg">
              <label>Provinsi</label>
              <input type="text" name="provinsi" value="{{ $restoran->provinsi }}">
            </div>
          </div>

          {{-- Deskripsi --}}
          <div class="fg">
            <label>Deskripsi</label>
            <textarea name="deskripsi">{{ $restoran->deskripsi }}</textarea>
          </div>

        </div>
      </form>
    </div>

    <div class="modal-foot">
      <button class="btn btn-outline" onclick="closeM('editTokoM')">Batal</button>
      <button class="btn btn-primary" onclick="saveEditToko()">
        <span id="etL">Simpan Perubahan</span>
        <span id="etS" class="spinner" style="display:none"></span>
      </button>
    </div>
  </div>
</div>

<script>
const URL_UPDATE_TOKO = '{{ $urlUpdate }}';

async function saveEditToko() {
  document.getElementById('etL').style.display = 'none';
  document.getElementById('etS').style.display = '';

  const fd = new FormData(document.getElementById('editTokoF'));

  try {
    const res = await fetch(URL_UPDATE_TOKO, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
      body: fd,
    });
    const d = await res.json();
    if (d.success) {
      showToast('Profil toko diperbarui', 'success');
      closeM('editTokoM');
      setTimeout(() => location.reload(), 700);
    } else {
      showToast(d.message || 'Gagal menyimpan', 'error');
    }
  } catch (err) {
    console.error(err);
    showToast('Terjadi kesalahan', 'error');
  } finally {
    document.getElementById('etL').style.display = '';
    document.getElementById('etS').style.display = 'none';
  }
}
</script>