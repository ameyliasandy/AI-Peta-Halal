@extends('layouts.pemilik')
@section('title', 'Toko Saya')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
  <h2 style="font-size:18px;font-weight:700">Toko Saya</h2>
  <a href="{{ route('pemilik.toko.create') }}" class="btn btn-primary">+ Tambah Usaha</a>
</div>

{{-- FILTER BUTTONS --}}
<div style="display:flex;gap:8px;margin-bottom:20px;flex-wrap:wrap">
  <button onclick="filterToko('semua')" 
          class="filter-btn active" 
          data-filter="semua"
          style="padding:6px 16px;border-radius:999px;border:1px solid #e2e8f0;background:#1D9E75;color:#fff;font-size:13px;font-weight:600;cursor:pointer;transition:all 0.2s;">
    Semua
  </button>
  <button onclick="filterToko('terverifikasi')" 
          class="filter-btn" 
          data-filter="terverifikasi"
          style="padding:6px 16px;border-radius:999px;border:1px solid #e2e8f0;background:#fff;color:#64748b;font-size:13px;font-weight:600;cursor:pointer;transition:all 0.2s;">
    Terverifikasi
  </button>
  <button onclick="filterToko('pending')" 
          class="filter-btn" 
          data-filter="pending"
          style="padding:6px 16px;border-radius:999px;border:1px solid #e2e8f0;background:#fff;color:#64748b;font-size:13px;font-weight:600;cursor:pointer;transition:all 0.2s;">
    Pending
  </button>
  <button onclick="filterToko('ditolak')" 
          class="filter-btn" 
          data-filter="ditolak"
          style="padding:6px 16px;border-radius:999px;border:1px solid #e2e8f0;background:#fff;color:#64748b;font-size:13px;font-weight:600;cursor:pointer;transition:all 0.2s;">
    Ditolak
  </button>
  <button onclick="filterToko('belum')" 
          class="filter-btn" 
          data-filter="belum"
          style="padding:6px 16px;border-radius:999px;border:1px solid #e2e8f0;background:#fff;color:#64748b;font-size:13px;font-weight:600;cursor:pointer;transition:all 0.2s;">
    Belum Diverifikasi
  </button>
</div>

{{-- LIST TOKO --}}
<div id="toko-list">
  @forelse($restoran as $toko)
    @php
      $status = $toko->verifikasiHalal?->status ?? 'belum';
      $statusClass = $status;
      if(in_array($status, ['approved', 'terverifikasi'])) {
        $statusClass = 'terverifikasi';
      } elseif(in_array($status, ['pending', 'proses'])) {
        $statusClass = 'pending';
      }
    @endphp

    <div class="toko-item" data-status="{{ $statusClass }}" style="
        background:#fff;
        border:1px solid #e2e8f0;
        border-radius:18px;
        padding:16px;
        margin-bottom:16px;
        display:flex;
        align-items:center;
        gap:16px;
        box-shadow:0 2px 8px rgba(0,0,0,.04);
        transition:all 0.3s;
    ">

        {{-- FOTO --}}
        @if($toko->foto_utama)
            <img src="{{ $toko->getFotoUtamaUrl() }}"
                style="
                    width:80px;
                    height:80px;
                    object-fit:cover;
                    border-radius:14px;
                    flex-shrink:0;
                ">
        @else
            <div style="
                width:80px;
                height:80px;
                border-radius:14px;
                background:#f1f5f9;
                display:flex;
                align-items:center;
                justify-content:center;
                font-size:30px;
                flex-shrink:0;
            ">
                🏪
            </div>
        @endif

        {{-- INFO --}}
        <div style="flex:1">

            <div style="
                font-size:16px;
                font-weight:700;
                color:#0f172a;
                margin-bottom:4px;
            ">
                {{ $toko->nama_restoran }}
            </div>

            <div style="
                font-size:13px;
                color:#64748b;
                margin-bottom:10px;
            ">
                {{ $toko->kota ?? '' }}
                {{ $toko->kategori?->nama_kategori ?? '-' }}
            </div>

            <div>
                @if(in_array($status, ['approved', 'terverifikasi']))
                    <span style="
                        background:#dcfce7;
                        color:#15803d;
                        padding:4px 10px;
                        border-radius:999px;
                        font-size:12px;
                        font-weight:600;
                    ">
                        Terverifikasi
                    </span>

                @elseif(in_array($status, ['pending', 'proses']))
                    <span style="
                        background:#fef3c7;
                        color:#b45309;
                        padding:4px 10px;
                        border-radius:999px;
                        font-size:12px;
                        font-weight:600;
                    ">
                        Pending
                    </span>

                @elseif($status === 'ditolak')
                    <span style="
                        background:#fee2e2;
                        color:#b91c1c;
                        padding:4px 10px;
                        border-radius:999px;
                        font-size:12px;
                        font-weight:600;
                    ">
                        Ditolak
                    </span>

                @else
                    <span style="
                        background:#f1f5f9;
                        color:#64748b;
                        padding:4px 10px;
                        border-radius:999px;
                        font-size:12px;
                        font-weight:600;
                    ">
                        Belum Diverifikasi
                    </span>
                @endif
            </div>

        </div>

        {{-- TOMBOL --}}
        <div>
            <a href="{{ route('pemilik.toko.show', $toko->id_restoran) }}"
               class="btn btn-outline"
               style="display:inline-block;padding:8px 16px;border:1px solid #1D9E75;border-radius:10px;color:#1D9E75;text-decoration:none;font-size:13px;font-weight:600;transition:all 0.2s;">
                Kelola
            </a>
        </div>

    </div>

  @empty

    <div style="
        background:#fff;
        border:1px dashed #cbd5e1;
        border-radius:18px;
        padding:60px 20px;
        text-align:center;
    ">

        <div style="font-size:48px;margin-bottom:12px">
            🏪
        </div>

        <div style="
            font-size:16px;
            font-weight:700;
            color:#334155;
            margin-bottom:8px;
        ">
            Belum ada usaha terdaftar
        </div>

        <div style="
            font-size:13px;
            color:#94a3b8;
            margin-bottom:18px;
        ">
            Daftarkan usaha pertamamu sekarang.
        </div>

        <a href="{{ route('pemilik.toko.create') }}"
           class="btn btn-primary"
           style="display:inline-block;padding:10px 24px;background:#1D9E75;color:#fff;border-radius:10px;text-decoration:none;font-weight:600;">
            + Daftarkan Usaha
        </a>

    </div>

  @endforelse
</div>

{{-- JAVASCRIPT FILTER --}}
<script>
function filterToko(filter) {
    // Update tombol aktif
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.style.background = '#fff';
        btn.style.color = '#64748b';
        btn.classList.remove('active');
    });
    
    const activeBtn = document.querySelector(`.filter-btn[data-filter="${filter}"]`);
    if (activeBtn) {
        activeBtn.style.background = '#1D9E75';
        activeBtn.style.color = '#fff';
        activeBtn.classList.add('active');
    }
    
    // Filter toko
    const items = document.querySelectorAll('.toko-item');
    let visibleCount = 0;
    
    items.forEach(item => {
        const status = item.dataset.status;
        
        if (filter === 'semua') {
            item.style.display = 'flex';
            visibleCount++;
        } else if (filter === 'terverifikasi' && status === 'terverifikasi') {
            item.style.display = 'flex';
            visibleCount++;
        } else if (filter === 'pending' && status === 'pending') {
            item.style.display = 'flex';
            visibleCount++;
        } else if (filter === 'ditolak' && status === 'ditolak') {
            item.style.display = 'flex';
            visibleCount++;
        } else if (filter === 'belum' && status === 'belum') {
            item.style.display = 'flex';
            visibleCount++;
        } else {
            item.style.display = 'none';
        }
    });
    
    // Tampilkan pesan jika tidak ada hasil
    let emptyMessage = document.getElementById('empty-filter-message');
    
    if (visibleCount === 0) {
        if (!emptyMessage) {
            emptyMessage = document.createElement('div');
            emptyMessage.id = 'empty-filter-message';
            emptyMessage.style.cssText = `
                background:#fff;
                border:1px dashed #cbd5e1;
                border-radius:18px;
                padding:40px 20px;
                text-align:center;
                margin-top:10px;
            `;
            emptyMessage.innerHTML = `
                <div style="font-size:40px;margin-bottom:10px"></div>
                <div style="font-size:15px;font-weight:600;color:#334155;margin-bottom:4px;">
                    Tidak ada data
                </div>
                <div style="font-size:13px;color:#94a3b8;">
                    Tidak ada toko dengan filter ini.
                </div>
            `;
            document.getElementById('toko-list').appendChild(emptyMessage);
        }
        emptyMessage.style.display = 'block';
    } else {
        if (emptyMessage) {
            emptyMessage.style.display = 'none';
        }
    }
}
</script>

<style>
.filter-btn:hover {
    background: #f1f5f9 !important;
    color: #0f172a !important;
}

.filter-btn.active:hover {
    background: #167a5b !important;
    color: #fff !important;
}

.toko-item {
    transition: all 0.3s ease;
}

.toko-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.08) !important;
}
</style>

@endsection