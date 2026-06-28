{{--
  STEP 4 — Ringkasan & Konfirmasi
  Dipakai oleh: admin & pemilik
  JS buildSumm() akan mengisi #summBox
--}}

<div style="text-align:center;padding:24px 0 12px">
  <div style="width:64px;height:64px;background:var(--gl);border-radius:20px;
              display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
    <svg width="32" height="32" fill="none" stroke="var(--g)" stroke-width="2" viewBox="0 0 24 24">
      <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
  </div>

  @if($isAdmin ?? false)
    <div style="font-size:18px;font-weight:800;color:var(--s9)">Siap Disimpan!</div>
    <div style="font-size:13px;color:var(--s6);margin-top:8px;line-height:1.8">
      Periksa ringkasan data di bawah, lalu klik <strong>Simpan & Kirim</strong>.
    </div>
  @else
    <div style="font-size:18px;font-weight:800;color:var(--s9)">Siap Dikirim!</div>
    <div style="font-size:13px;color:var(--s6);margin-top:8px;line-height:1.8">
      Pastikan semua data sudah benar.<br>
      Usaha Anda akan ditinjau oleh tim Peta Halal sebelum ditampilkan.
    </div>
  @endif
</div>

{{-- Ringkasan otomatis diisi JS --}}
<div id="summBox"
     style="background:var(--s1);border-radius:12px;padding:16px 20px;
            font-size:13px;color:var(--s7);line-height:2.2;margin-top:8px">
  <span style="color:var(--s4)">Memuat ringkasan...</span>
</div>

@if(!($isAdmin ?? false))
<div class="alert-w" style="margin-top:16px;background:#f0fdf4;border-color:var(--g);color:#166534">
  Setelah dikirim, tim Peta Halal akan memverifikasi usaha Anda dalam 1–3 hari kerja.
  Anda akan mendapat notifikasi melalui email atau nomor HP yang terdaftar.
</div>
@endif
