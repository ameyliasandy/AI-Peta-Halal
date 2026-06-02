{{--
  detail_toko/grid_menu.blade.php
  Grid kartu menu — READ ONLY (pencari / publik).
  Hanya tampilkan menu yang tersedia = true.

  Variabel: $restoran (with menu)
--}}

<div class="msec">
  <div class="mhead">
    <div>
      <div class="mhead-t">Menu Tersedia</div>
      <div class="mhead-sub">{{ $restoran->menu->where('tersedia', true)->count() }} item siap dipesan</div>
    </div>
  </div>

  <div class="mgrid">
    @forelse($restoran->menu->where('tersedia', true) as $m)
    <div class="mi">
      @if($m->foto_menu)
        <img class="mi-img" src="{{ asset('storage/'.$m->foto_menu) }}" alt="{{ $m->nama_menu }}">
      @else
        <div class="mi-ph">🍽</div>
      @endif
      <div class="mi-info">
        <div class="mi-n">{{ $m->nama_menu }}</div>
        @if($m->deskripsi)
          <div class="mi-desc">{{ Str::limit($m->deskripsi, 55) }}</div>
        @endif
        <div class="mi-p">{{ $m->harga_format }}</div>
      </div>
    </div>
    @empty
    <div class="mgrid-empty">
      <div class="mgrid-empty-icon">🍽</div>
      <div class="mgrid-empty-t">Belum ada menu yang tersedia</div>
      <div class="mgrid-empty-s">Cek kembali nanti</div>
    </div>
    @endforelse
  </div>
</div>

{{-- Style grid menu read-only — konsisten dengan grid_menu_aksi --}}
<style>
.msec{background:#fff;border-radius:18px;border:1px solid var(--s2);margin-top:28px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,.04)}
.mhead{padding:18px 22px;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid var(--s1);gap:12px}
.mhead-t{font-size:15px;font-weight:700;color:var(--s9)}
.mhead-sub{font-size:11px;color:var(--s4);margin-top:2px}
.mgrid{
  display:grid;
  grid-template-columns:repeat(auto-fill,minmax(168px,1fr));
  gap:14px;
  padding:18px 22px
}

/* Kartu */
.mi{
  border-radius:14px;
  border:1.5px solid var(--s2);
  overflow:hidden;
  display:flex;
  flex-direction:column;
  transition:border-color .15s,box-shadow .15s,transform .15s
}
.mi:hover{
  border-color:var(--g);
  box-shadow:0 4px 16px rgba(26,158,92,.1);
  transform:translateY(-2px)
}
.mi-img{width:100%;height:130px;object-fit:cover;display:block;flex-shrink:0}
.mi-ph{
  width:100%;height:130px;flex-shrink:0;
  background:linear-gradient(135deg,var(--gl),var(--gm));
  display:flex;align-items:center;justify-content:center;font-size:30px
}
.mi-info{padding:10px 12px 14px;flex:1}
.mi-n{font-size:13px;font-weight:700;color:var(--s9);line-height:1.3}
.mi-desc{font-size:11px;color:var(--s4);margin-top:3px;line-height:1.5}
.mi-p{font-size:13px;font-weight:700;color:var(--g);margin-top:6px}

/* Empty state */
.mgrid-empty{
  grid-column:1/-1;
  display:flex;flex-direction:column;align-items:center;
  padding:52px 20px;text-align:center
}
.mgrid-empty-icon{font-size:36px;margin-bottom:10px;opacity:.4}
.mgrid-empty-t{font-size:14px;font-weight:600;color:var(--s6)}
.mgrid-empty-s{font-size:12px;color:var(--s4);margin-top:4px}
</style>