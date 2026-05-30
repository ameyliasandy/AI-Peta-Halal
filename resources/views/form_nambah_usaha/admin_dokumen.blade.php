{{--
  STEP 3 (ADMIN) — Dokumen & Verifikasi
  Admin bisa mengisi:
    - Semua yang bisa diisi pemilik (include partial di atas)
    - TAMBAHAN: Keputusan verifikasi & tipe halal
--}}

{{-- Include bagian yang sama dengan pemilik --}}
@include('form_nambah_usaha.pemilik_dokumen')

{{-- TAMBAHAN KHUSUS ADMIN --}}
<div class="fsec" style="margin-top:20px;padding-top:20px;border-top:2px dashed var(--s2)">
  <div class="ft" style="display:flex;align-items:center;gap:8px">
    <svg width="16" height="16" fill="none" stroke="var(--g)" stroke-width="2" viewBox="0 0 24 24">
      <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
    </svg>
    Keputusan Verifikasi <span style="font-size:11px;font-weight:500;color:var(--s4);margin-left:4px">(Hanya Admin)</span>
  </div>

  <div class="g2">

    <div class="fg">
      <label for="f-statusverif">Status Verifikasi</label>
      <select id="f-statusverif" name="status_verifikasi">
        <option value="pending">Pending — menunggu review</option>
        <option value="terverifikasi">Terverifikasi</option>
        <option value="ditolak">Ditolak</option>
      </select>
    </div>

    <div class="fg">
      <label for="f-tipehalal">Tipe Halal</label>
      <select id="f-tipehalal" name="tipe_halal">
        <option value="none">— Belum Terverifikasi —</option>
        <option value="certified">Certified Halal (MUI/BPJPH)</option>
        <option value="self_claimed">Self-Claimed Halal</option>
      </select>
    </div>

    <div class="fg" style="grid-column:1/-1">
      <label for="f-catatan">Catatan untuk Pemilik</label>
      <textarea id="f-catatan" name="catatan"
                placeholder="Tulis catatan jika ada kekurangan dokumen, penolakan, atau instruksi tambahan..."></textarea>
    </div>

  </div>
</div>
