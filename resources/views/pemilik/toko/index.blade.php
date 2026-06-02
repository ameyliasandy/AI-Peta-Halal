@extends('layouts.pemilik')
@section('title', 'Toko Saya')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
  <h2 style="font-size:18px;font-weight:700">Toko Saya</h2>
  <a href="{{ route('pemilik.toko.create') }}" class="btn btn-primary">+ Tambah Usaha</a>
</div>

@forelse($restoran as $toko)

<div style="
    background:#fff;
    border:1px solid #e2e8f0;
    border-radius:18px;
    padding:16px;
    margin-bottom:16px;
    display:flex;
    align-items:center;
    gap:16px;
    box-shadow:0 2px 8px rgba(0,0,0,.04);
">

    {{-- FOTO --}}
    @if($toko->foto_utama)
        <img
            src="{{ asset('storage/'.$toko->foto_utama) }}"
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
            📍 {{ $toko->kota }}
            •
            {{ $toko->kategori?->nama_kategori ?? '-' }}
        </div>

        <div>
            @if($toko->verifikasiHalal?->status === 'terverifikasi')
                <span style="
                    background:#dcfce7;
                    color:#15803d;
                    padding:4px 10px;
                    border-radius:999px;
                    font-size:12px;
                    font-weight:600;
                ">
                    ✓ Terverifikasi
                </span>

            @elseif($toko->verifikasiHalal?->status === 'pending')
                <span style="
                    background:#fef3c7;
                    color:#b45309;
                    padding:4px 10px;
                    border-radius:999px;
                    font-size:12px;
                    font-weight:600;
                ">
                    ⏳ Pending
                </span>

            @elseif($toko->verifikasiHalal?->status === 'ditolak')
                <span style="
                    background:#fee2e2;
                    color:#b91c1c;
                    padding:4px 10px;
                    border-radius:999px;
                    font-size:12px;
                    font-weight:600;
                ">
                    ✕ Ditolak
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
           class="btn btn-outline">
            Kelola →
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
       class="btn btn-primary">
        + Daftarkan Usaha
    </a>

</div>

@endforelse

@endsection