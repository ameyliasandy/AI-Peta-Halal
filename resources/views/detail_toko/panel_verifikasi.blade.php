{{--
  detail_toko/panel_verifikasi.blade.php
  Panel sidebar kanan — ADMIN only.
  Tampil status verifikasi + form ubah status.

  Variabel: $restoran, $v (= $restoran->verifikasiHalal)
--}}
<div class="vc">
  <div class="vc-t">Verifikasi Halal</div>

  <div class="vr">
    <span class="vl">Status</span>
    @if($v?->status === 'terverifikasi')
      <span class="badge bv">✓ Terverifikasi</span>
    @elseif($v?->status === 'pending')
      <span class="badge bp">⏳ Pending</span>
    @elseif($v?->status === 'ditolak')
      <span class="badge br">✕ Ditolak</span>
    @else
      <span class="badge bn">Belum Diajukan</span>
    @endif
  </div>

  <div class="vr">
    <span class="vl">No. Sertifikat</span>
    <span class="vv">{{ $v?->no_sertifikat ?? '—' }}</span>
  </div>

  <div class="vr">
    <span class="vl">Lembaga</span>
    <span class="vv">{{ $v?->lembaga_penerbit ?? '—' }}</span>
  </div>

  <div class="vr">
    <span class="vl">Masa Berlaku</span>
    <span class="vv" style="{{ $v?->isSertifikatHampirExpire() ? 'color:var(--r);font-weight:700' : '' }}">
      {{ $v?->masa_berlaku?->format('d/m/Y') ?? '—' }}
      @if($v?->isSertifikatHampirExpire()) ⚠️ @endif
    </span>
  </div>

  <div class="vr">
    <span class="vl">Tgl Verifikasi</span>
    <span class="vv">{{ $v?->tanggal_verifikasi?->format('d/m/Y') ?? '—' }}</span>
  </div>

  @if($v?->catatan)
    <div class="cn"><strong>Catatan:</strong> {{ $v->catatan }}</div>
  @endif

  {{-- Form ubah status --}}
  <div style="margin-top:14px;padding-top:14px;border-top:1px solid var(--s1)">
    <div style="font-size:11px;font-weight:700;color:var(--s5);text-transform:uppercase;letter-spacing:.05em;margin-bottom:8px">
      Ubah Status
    </div>
    <div class="va-wrap">
      <select id="vStatus">
        <option value="">Pilih status baru...</option>
        <option value="terverifikasi">✓ Terverifikasi</option>
        <option value="pending">⏳ Pending</option>
        <option value="ditolak">✕ Ditolak</option>
      </select>
      <textarea id="vCatatan" placeholder="Catatan admin (opsional)..." style="min-height:60px;resize:none"></textarea>
      <button class="btn btn-primary" style="width:100%;justify-content:center"
              onclick="updateVerif({{ $restoran->id_restoran }})">
        <span id="vL">Simpan Status</span>
        <span id="vS" class="spinner" style="display:none"></span>
      </button>
    </div>
  </div>
</div>

@if($v?->dokumen_sertifikat)
<div class="vc">
  <div class="vc-t">Dokumen Sertifikat</div>
  <a href="{{ asset('storage/'.$v->dokumen_sertifikat) }}" target="_blank"
     class="btn btn-outline" style="width:100%;justify-content:center">
    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
      <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
      <polyline points="14 2 14 8 20 8"/>
    </svg>
    Lihat Dokumen
  </a>
</div>
@endif

<style>
.va-wrap{display:flex;flex-direction:column;gap:8px}
.va-wrap select,
.va-wrap textarea{
  font-size:13px;
  border:1.5px solid var(--s2);
  border-radius:9px;
  padding:8px 11px;
  font-family:var(--font);
  outline:none;
  width:100%;
  color:var(--s7);
  background:#fff
}
.va-wrap select:focus,
.va-wrap textarea:focus{
  border-color:var(--g);
  box-shadow:0 0 0 3px rgba(26,158,92,.08)
}
</style>