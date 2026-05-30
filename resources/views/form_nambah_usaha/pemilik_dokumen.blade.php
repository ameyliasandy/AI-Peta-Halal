{{--
  STEP 3 (PEMILIK) — Dokumen & Klaim Halal
  Pemilik hanya mengisi:
    - Apakah punya sertifikat? (ya/tidak)
    - Kalau ya → upload dokumen & isi nomor sertifikat
    - Kalau tidak → centang persyaratan self-claimed
  Tidak ada bagian "Keputusan Admin" di sini.
--}}

<div class="fsec">
  <div class="ft">Status Sertifikat Halal</div>
  <div class="fg" style="max-width:340px">
    <label for="f-hassert">Apakah usaha sudah memiliki sertifikat halal?</label>
    <select id="f-hassert" name="has_sertifikat" onchange="togSert()">
      <option value="">— Pilih —</option>
      <option value="ya">✅ Ya, sudah bersertifikat</option>
      <option value="tidak">🕐 Belum, tapi ingin mengklaim halal</option>
    </select>
    <span class="hint">
      Usaha tanpa sertifikat resmi akan ditampilkan sebagai "Self-Claimed Halal"
      dan akan diverifikasi oleh tim kami.
    </span>
  </div>
</div>

{{-- BAGIAN: Punya Sertifikat --}}
<div class="fsec" id="sertSec" style="display:none">
  <div class="ft">Data Sertifikat</div>
  <div class="g3">

    <div class="fg">
      <label for="f-nosert">No. Sertifikat</label>
      <input id="f-nosert" type="text" name="no_sertifikat"
             placeholder="MUI-XXXX-XXXXXX">
    </div>

    <div class="fg">
      <label for="f-lembaga">Lembaga Penerbit</label>
      <input id="f-lembaga" type="text" name="lembaga_penerbit"
             placeholder="MUI / BPJPH">
    </div>

    <div class="fg">
      <label for="f-berlaku">Masa Berlaku</label>
      <input id="f-berlaku" type="date" name="masa_berlaku">
    </div>

    <div class="fg" style="grid-column:1/-1">
      <label for="f-docsert">Unggah Dokumen Sertifikat</label>
      <input id="f-docsert" type="file" name="dokumen_sertifikat"
             accept=".pdf,image/*">
      <span class="hint">Format PDF atau gambar (JPG/PNG), maks 5MB.</span>
    </div>

  </div>
</div>

{{-- BAGIAN: Belum Punya Sertifikat → Self-Claimed --}}
<div class="fsec" id="alasanSec" style="display:none">
  <div class="ft">Pernyataan Mandiri (Self-Claimed Halal)</div>
  <div class="alert-w">
    Centang seluruh persyaratan yang telah dipenuhi.
    Item bertanda <strong>*</strong> wajib dicentang agar usaha dapat ditampilkan di peta.
  </div>

  <p style="font-size:13px;font-weight:700;margin-bottom:8px;color:var(--s7)">A. Bahan baku & produk</p>
  <div class="chk-wrap" style="margin-bottom:14px">
    <label class="ci">
      <input type="checkbox" name="bebas_babi" value="1">
      <div>
        <div class="ct">Seluruh bahan baku bebas dari babi & turunannya <strong>*</strong></div>
        <div class="cs">Termasuk gelatin, lard, bahan penolong dari babi</div>
      </div>
    </label>
    <label class="ci">
      <input type="checkbox" name="daging_halal" value="1">
      <div>
        <div class="ct">Daging/unggas dari pemotongan halal <strong>*</strong></div>
        <div class="cs">Ada bukti/nota pembelian dari RPH atau penjual bersertifikat</div>
      </div>
    </label>
    <label class="ci">
      <input type="checkbox" name="bumbu_bebas_alkohol" value="1">
      <div>
        <div class="ct">Bumbu & bahan tambahan bebas alkohol <strong>*</strong></div>
        <div class="cs">Termasuk vanila extract, wine untuk masak, sake</div>
      </div>
    </label>
    <label class="ci">
      <input type="checkbox" name="kemasan_halal" value="1">
      <div>
        <div class="ct">Produk kemasan yang digunakan berlabel halal</div>
        <div class="cs">Saus, kecap, margarin, dan bahan kemasan lainnya</div>
      </div>
    </label>
  </div>

  <p style="font-size:13px;font-weight:700;margin-bottom:8px;color:var(--s7)">B. Peralatan & fasilitas</p>
  <div class="chk-wrap" style="margin-bottom:14px">
    <label class="ci">
      <input type="checkbox" name="peralatan_tidak_najis" value="1">
      <div>
        <div class="ct">Peralatan masak tidak terkontaminasi najis <strong>*</strong></div>
        <div class="cs">Wajan, pisau, talenan, dan wadah khusus bahan halal</div>
      </div>
    </label>
    <label class="ci">
      <input type="checkbox" name="tidak_jual_alkohol" value="1">
      <div>
        <div class="ct">Tidak menjual minuman beralkohol <strong>*</strong></div>
        <div class="cs">Termasuk bir, wine, dan minuman berkadar alkohol di atas 0,5%</div>
      </div>
    </label>
  </div>

  <p style="font-size:13px;font-weight:700;margin-bottom:8px;color:var(--s7)">C. Kebersihan & higienitas</p>
  <div class="chk-wrap">
    <label class="ci">
      <input type="checkbox" name="dapur_bersih" value="1">
      <div>
        <div class="ct">Dapur bersih dan bebas hama <strong>*</strong></div>
        <div class="cs">Lantai, permukaan dapur, dan area pengolahan dalam kondisi higienis</div>
      </div>
    </label>
    <label class="ci">
      <input type="checkbox" name="karyawan_bersih" value="1">
      <div>
        <div class="ct">Karyawan menjaga kebersihan diri & pakaian <strong>*</strong></div>
        <div class="cs">Menggunakan celemek, penutup kepala di dapur</div>
      </div>
    </label>
    <label class="ci">
      <input type="checkbox" name="sop_kebersihan" value="1">
      <div>
        <div class="ct">Memiliki SOP kebersihan dapur tertulis <strong>*</strong></div>
        <div class="cs">Prosedur cuci tangan, sanitasi peralatan, jadwal kebersihan</div>
      </div>
    </label>
  </div>
</div>
