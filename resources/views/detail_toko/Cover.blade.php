{{-- detail_toko/cover.blade.php --}}
<div class="cover-wrap">
  @if($restoran->foto_utama)
    <img src="{{ asset('storage/'.$restoran->foto_utama) }}" alt="{{ $restoran->nama_restoran }}">
  @else
    <div class="cover-ph">
      <svg width="52" height="52" fill="none" stroke="var(--g)" stroke-width="1.5" viewBox="0 0 24 24">
        <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
        <polyline points="9 22 9 12 15 12 15 22"/>
      </svg>
    </div>
  @endif
</div>