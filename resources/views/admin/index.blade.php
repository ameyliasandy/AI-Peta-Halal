@extends('admin.layout')

@section('title', 'Dashboard')

@push('styles')
<style>
.page-header{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    margin-bottom:28px;
}

.page-title{
    font-size:28px;
    font-weight:800;
    color:var(--s9);
}

.page-sub{
    font-size:14px;
    color:var(--s4);
    margin-top:4px;
}

.filter-pill{
    background:#fff;
    border:1px solid var(--s2);
    border-radius:12px;
    padding:10px 16px;
    font-size:13px;
    box-shadow:0 2px 10px rgba(0,0,0,.04);
}

.stats-row{
    display:grid;
    grid-template-columns:repeat(3,minmax(0,240px));
    gap:16px;
    margin-bottom:24px;
}

.stat-card{
    background:#fff;
    border-radius:16px;
    padding:18px 20px;
    border:1px solid var(--s2);
    min-height:120px;
}

@media(max-width:900px){
    .stats-row{
        grid-template-columns:1fr;
    }

}
.stat-num{
    font-size:28px;
    font-weight:800;
    color:var(--s9);
    margin-top:10px;
}

.stat-label{
    font-size:14px;
    color:var(--s4);
}

.chart-card,
.pending-wrap{
    background:#fff;
    border-radius:18px;
    padding:24px;
    border:1px solid var(--s2);
    margin-bottom:24px;
}

.pending-grid{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:16px;
    margin-top:18px;
}

.pending-card{
    border:1px solid var(--s2);
    border-radius:14px;
    padding:18px;
}

.pending-name{
    font-size:15px;
    font-weight:700;
    margin-top:12px;
}

.pending-desc{
    font-size:13px;
    color:var(--s4);
    line-height:1.6;
    margin:10px 0 14px;
}

.lihat-btn{
    display:inline-block;
    background:var(--g);
    color:#fff;
    text-decoration:none;
    padding:8px 14px;
    border-radius:10px;
    font-size:13px;
    font-weight:600;
}

.save-btn{
    background:var(--g);
    color:#fff;
    text-decoration:none;
    border:none;
    padding:10px 16px;
    border-radius:12px;
    font-size:13px;
    font-weight:600;
    transition:.2s;
    white-space:nowrap;
}

.save-btn:hover{
    background:var(--gd);
}
</style>
@endpush

@section('content')

<div class="page-header">
    <div>
        <div class="page-title">Dashboard</div>
        <div class="page-sub">
            Hi, {{ session('admin')?->nama ?? 'Admin' }}.
            Welcome back to Petha Admin!
        </div>
    </div>

    <div class="filter-pill">
        {{ $periodeLabel }}
    </div>
</div>

<div class="stats-row">

    <div class="stat-card">
        📋
        <div class="stat-num">{{ $perluVerif }}</div>
        <div class="stat-label">Perlu di verif</div>
    </div>

    <div class="stat-card">
        🏪
        <div class="stat-num">{{ $totalUsahaHalal }}</div>
        <div class="stat-label">Usaha Halal</div>
    </div>

    <div class="stat-card">
        👤
        <div class="stat-num">{{ $totalPengguna }}</div>
        <div class="stat-label">Pengguna</div>
    </div>

</div>

<div class="chart-card">

    <div style="
        display:flex;
        justify-content:space-between;
        align-items:flex-start;
        margin-bottom:20px;
        gap:20px;
    ">

        <div>
            <h3 style="font-size:18px;font-weight:700;margin-bottom:6px;">
                Chart Aktivitas
            </h3>

            <p style="font-size:13px;color:var(--s4);">
                Restoran baru per hari minggu ini
            </p>
        </div>

        <a href="{{ route('admin.restoran.export') }}"
           class="save-btn">
            Save Report
        </a>

    </div>

    <canvas id="visitorChart" height="90"></canvas>
</div>

<div class="pending-wrap">

    <h3 style="font-size:18px;font-weight:700;">
        Usaha Halal Yang memerlukan Verifikasi
    </h3>

    <div class="pending-grid">

        @foreach($pendingRestoran as $r)
        <div class="pending-card">

            <div style="font-size:34px;">🍽️</div>

            <div class="pending-name">
                {{ $r->nama_restoran }}
            </div>

            <div class="pending-desc">
                {{ Str::limit($r->deskripsi,100) }}
            </div>

            <a href="{{ route('admin.restoran.show', $r->id_restoran) }}"
               class="lihat-btn">
                Lihat
            </a>

        </div>
        @endforeach

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('visitorChart');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($chartLabels),
        datasets: [{
            label: 'Restoran',
            data: @json($chartData),
            borderColor: '#1a9e5c',
            backgroundColor: 'rgba(26,158,92,.1)',
            tension: .4,
            fill:true
        }]
    },
    options: {
        responsive:true,

        plugins:{
            legend:{
                display:false
            }
        },

        scales:{
            y:{
                beginAtZero:true,
                min:0,
                ticks:{
                    stepSize:5,
                    precision:0
                },
                grid:{
                    color:'rgba(0,0,0,.05)'
                }
            },

            x:{
                grid:{
                    display:false
                }
            }
        }
    }
});
</script>

@endsection