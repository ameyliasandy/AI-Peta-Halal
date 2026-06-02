{{--
  STEP 2 — Lokasi & Kontak
  Dipakai oleh: admin & pemilik (sama persis)
  Data kecamatan/kelurahan di-handle oleh JS di _form_scripts.blade.php
--}}

<div class="fsec">
  <div class="ft">Lokasi Usaha</div>
  <div class="g2">

    {{-- Alamat --}}
    <div class="fg" style="grid-column:1/-1">
      <label for="f-alamat">Alamat Lengkap <span class="req">*</span></label>
      <input id="f-alamat" type="text" name="alamat" required
             placeholder="Nama jalan, nomor, RT/RW, kelurahan, dll">
    </div>

    {{-- Kecamatan --}}
    <div class="fg">
      <label for="f-kec">Kecamatan</label>
      <select id="f-kec" name="kecamatan_kelurahan" onchange="loadKelurahan()">
        <option value="">Pilih Kecamatan</option>
        <option value="Batam Kota">Batam Kota</option>
        <option value="Batu Aji">Batu Aji</option>
        <option value="Batu Ampar">Batu Ampar</option>
        <option value="Bengkong">Bengkong</option>
        <option value="Bulang">Bulang</option>
        <option value="Galang">Galang</option>
        <option value="Lubuk Baja">Lubuk Baja</option>
        <option value="Nongsa">Nongsa</option>
        <option value="Sagulung">Sagulung</option>
        <option value="Sei Beduk">Sei Beduk</option>
        <option value="Sekupang">Sekupang</option>
        <option value="Belakang Padang">Belakang Padang</option>
      </select>
    </div>

    {{-- Kelurahan — diisi dinamis oleh JS --}}
    <div class="fg">
      <label for="f-kel">Kelurahan</label>
      <select id="f-kel" name="kecamatan_kelurahan">
        <option value="">Pilih kecamatan dulu</option>
      </select>
    </div>

    {{-- Kota (readonly) --}}
    <div class="fg">
      <label for="f-kota">Kota</label>
      <input id="f-kota" type="text" name="kota" value="Batam" readonly
             style="background:var(--s1);color:var(--s6);cursor:default">
    </div>

    {{-- Provinsi (readonly) --}}
    <div class="fg">
      <label for="f-prov">Provinsi</label>
      <input id="f-prov" type="text" name="provinsi" value="Kepulauan Riau" readonly
             style="background:var(--s1);color:var(--s6);cursor:default">
    </div>

    {{-- Kode Pos — diisi otomatis oleh JS --}}
    <div class="fg">
      <label for="f-kpos">Kode Pos</label>
      <input id="f-kpos" type="text" name="kode_pos" placeholder="29xxx">
    </div>

    {{-- Koordinat — opsional, pemilik mungkin tidak tahu --}}
    <div class="fg">
      <label for="f-lat">Latitude
        @if(!($isAdmin ?? false))
          <span class="hint" style="font-weight:400">(opsional)</span>
        @endif
      </label>
      <input id="f-lat" type="number" name="latitude" step="any"
             placeholder="1.129000">
    </div>
    <div class="fg">
      <label for="f-lng">Longitude
        @if(!($isAdmin ?? false))
          <span class="hint" style="font-weight:400">(opsional)</span>
        @endif
      </label>
      <input id="f-lng" type="number" name="longitude" step="any"
             placeholder="104.029000">
    </div>
    

    @if(!($isAdmin ?? false))
    <div class="fg" style="grid-column:1/-1">
      <div class="alert-w" style="background:#eff6ff;border-color:#3b82f6;color:#1e40af">
        💡 Koordinat GPS membantu pelanggan menemukan lokasi usaha Anda di peta.
        Buka Google Maps, klik lokasi toko, lalu salin angka yang muncul.
      </div>
    </div>
    @endif

  </div>
</div>

<div class="fsec">
  <div class="ft">Kontak Usaha</div>
  <div class="g2">

    <div class="fg">
      <label for="f-telp">No. Telepon</label>
      <input id="f-telp" type="tel" name="no_telepon" placeholder="08xx-xxxx-xxxx">
    </div>

    <div class="fg">
      <label for="f-email">Email Usaha</label>
      <input id="f-email" type="email" name="email_usaha"
             placeholder="toko@email.com">
    </div>

    <div class="fg" style="grid-column:1/-1">
      <label for="f-sosmed">Website / Media Sosial</label>
      <input id="f-sosmed" type="text" name="website_sosmed"
             placeholder="instagram.com/nama_toko atau www.toko.com">
    </div>

  </div>
</div>
