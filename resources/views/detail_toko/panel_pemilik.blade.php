{{--
  detail_toko/panel_pemilik.blade.php
  Kotak info pemilik usaha.
  Dipakai di: admin (show).

  Variabel: $restoran
--}}
<div class="vc">
  <div class="vc-t">Info Pemilik</div>

  <div class="vr">
    <span class="vl">Nama</span>
    <span class="vv">{{ $restoran->pemilik?->name ?? '—' }}</span>
  </div>
  <div class="vr">
    <span class="vl">No. HP</span>
    <span class="vv">{{ $restoran->pemilik?->no_hp ?? '—' }}</span>
  </div>
  <div class="vr">
    <span class="vl">Email</span>
    <span class="vv" style="font-size:11px;word-break:break-all">
      {{ $restoran->pemilik?->email ?? '—' }}
    </span>
  </div>
</div>