{{--
  STEP 1 — Info Dasar
  Dipakai oleh: admin (modal list) & pemilik (halaman daftar usaha)
  Variabel yang dibutuhkan dari controller:
    $kategori   → Collection of Kategori (with subKategori)
    $pemilik    → Collection of User (role pemilik_usaha) — hanya dirender jika $isAdmin
  Variabel blade:
    $isAdmin    → bool, true jika yang mengisi adalah admin
--}}

<div class="fsec">
  <div class="ft">Informasi Dasar Usaha</div>
  <div class="g2">

    {{-- Nama Usaha --}}
    <div class="fg" style="grid-column:1/-1">
      <label for="f-nama">Nama Usaha <span class="req">*</span></label>
      <input id="f-nama" type="text" name="nama_restoran" required
             placeholder="Sesuai sertifikat halal">
    </div>

    {{-- Kategori --}}
    <div class="fg">
      <label for="f-kat">Jenis Usaha <span class="req">*</span></label>
      <select id="f-kat" name="id_kategori" onchange="loadSub()" required>
        <option value="">Pilih kategori</option>
        @foreach($kategori as $k)
          <option value="{{ $k->id_kategori }}"
                  data-subs='{{ $k->subKategori->toJson() }}'>
            {{ $k->nama_kategori }}
          </option>
        @endforeach
      </select>
    </div>

    {{-- Sub Kategori --}}
    <div class="fg">
      <label for="f-sub">Sub Kategori</label>
      <select id="f-sub" name="id_sub_kategori">
        <option value="">Pilih jenis usaha dulu</option>
      </select>
    </div>

    {{-- Jam Operasional --}}
    <div class="fg">
      <label for="f-jam">Jam Operasional <span class="req">*</span></label>
      <input id="f-jam" type="text" name="jam_operasional" required
             placeholder="08:00 - 21:00">
    </div>

    {{-- Kapasitas --}}
    <div class="fg">
      <label for="f-kap">Kapasitas Tempat Duduk</label>
      <input id="f-kap" type="number" name="kapasitas_tempat"
             placeholder="50" min="0">
    </div>

    {{-- Pemilik — hanya tampil untuk Admin --}}
    @if($isAdmin ?? false)
    <div class="fg" style="grid-column:1/-1">
      <label for="f-pemilik">Pemilik Usaha <span class="req">*</span></label>
      <select id="f-pemilik" name="id_pemilik" required>
        <option value="">Pilih pemilik</option>
        @foreach($pemilik as $pm)
          <option value="{{ $pm->id }}">
            {{ $pm->name }}{{ $pm->no_hp ? ' ('.$pm->no_hp.')' : '' }}
          </option>
        @endforeach
      </select>
    </div>
    @else
    {{-- Pemilik = user yang sedang login --}}
    <input type="hidden" name="id_pemilik" value="{{ auth()->id() }}">
    @endif

    {{-- Deskripsi --}}
    <div class="fg" style="grid-column:1/-1">
      <label for="f-desc">Deskripsi Singkat</label>
      <textarea id="f-desc" name="deskripsi"
                placeholder="Ceritakan singkat tentang usaha Anda (maks. 200 karakter)"
                maxlength="200"></textarea>
    </div>

    {{-- Harga --}}
    <div class="fg">
      <label for="f-hmin">Harga Mulai Dari (Rp)</label>
      <input id="f-hmin" type="number" name="harga_rata_rata_min"
             placeholder="15000" min="0">
    </div>
    <div class="fg">
      <label for="f-hmax">Harga Tertinggi (Rp)</label>
      <input id="f-hmax" type="number" name="harga_rata_rata_max"
             placeholder="50000" min="0">
    </div>

    {{-- Foto Utama --}}
    <div class="fg" style="grid-column:1/-1">
      <label for="f-foto">Foto Utama Toko</label>
      <input id="f-foto" type="file" name="foto_utama" accept="image/*">
      <span class="hint">Format JPG/PNG, maks 2MB. Tampil sebagai cover halaman toko.</span>
    </div>

  </div>
</div>
