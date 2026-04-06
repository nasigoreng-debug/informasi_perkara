@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f0f2f5; }
    :root {
        --pta-blue: #4e73df; --pta-indigo: #6610f2; --pta-success: #00b894;
        --pta-warning: #f1c40f; --pta-danger: #ff7675; --glass: rgba(255, 255, 255, 0.9);
    }
    .header-glass {
        background: var(--glass); backdrop-filter: blur(10px); border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.3); box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05);
    }
    .stat-card-premium {
        border: none; border-radius: 20px; color: white; position: relative; overflow: hidden;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1); height: 100%;
    }
    .stat-card-premium:hover { transform: translateY(-8px); box-shadow: 0 12px 24px rgba(0,0,0,0.15); }
    .card-icon-bg { position: absolute; right: -10px; top: -10px; font-size: 4rem; opacity: 0.15; transform: rotate(15deg); }
    .putus-box {
        background: #ffffff; border-radius: 20px; padding: 1.2rem; border: 1px solid #edf2f7;
        transition: all 0.3s ease; text-align: center;
    }
    .putus-box:hover { background: #f8faff; border-color: var(--pta-blue); transform: translateY(-3px); }
    .putus-indicator { width: 35px; height: 4px; border-radius: 10px; margin: 8px auto; }
</style>

<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="header-glass p-4 mb-4 d-flex flex-wrap align-items-center justify-content-between">
        <div>
            <h2 class="fw-800 text-dark mb-1" style="letter-spacing: -1px;">Sistem Monitoring Perkara</h2>
            <p class="text-muted small mb-0"><i class="fas fa-university me-1"></i> PTA Bandung — <span class="text-primary fw-600">Periode {{ $tahun }}</span></p>
        </div>
        <form action="{{ route('dashboard') }}" method="GET" id="filterForm" class="d-flex gap-2">
            <input type="hidden" name="tgl_awal" id="tgl_awal" value="{{ $tgl_awal }}">
            <input type="hidden" name="tgl_akhir" id="tgl_akhir" value="{{ $tgl_akhir }}">
            <div class="bg-light p-1 rounded-3 d-flex">
                <button type="button" class="btn btn-sm btn-light quick-period border-0 me-1" data-period="month">Bulan</button>
                <button type="button" class="btn btn-sm btn-light quick-period border-0" data-period="year">Tahun</button>
            </div>
            <div class="d-flex align-items-center bg-light rounded-3 px-2 border">
                <input type="date" class="form-control form-control-sm bg-transparent border-0" id="display_tgl_awal" value="{{ $tgl_awal }}">
                <span class="mx-1 text-muted">-</span>
                <input type="date" class="form-control form-control-sm bg-transparent border-0" id="display_tgl_akhir" value="{{ $tgl_akhir }}">
            </div>
            <button type="submit" class="btn btn-primary rounded-3 px-4 fw-600">Terapkan</button>
        </form>
    </div>

    {{-- ALUR PERKARA IDEAL (6 CARDS) --}}
    <div class="row g-3 mb-4 text-white">
        @php
            $mainStats = [
                ['l' => 'Sisa Lalu', 'v' => $cardData->sisa_lalu, 't' => 'sisa_lalu', 'c' => '#636e72', 'i' => 'fa-history'],
                ['l' => 'Diterima', 'v' => $cardData->diterima, 't' => 'diterima', 'c' => '#0984e3', 'i' => 'fa-file-download'],
                ['l' => 'Beban Kerja', 'v' => $beban, 't' => 'beban_kerja', 'c' => '#6c5ce7', 'i' => 'fa-briefcase'],
                ['l' => 'Putusan Sela', 'v' => $putusanSela, 't' => 'putusan_sela', 'c' => '#d63031', 'i' => 'fa-gavel'],
                ['l' => 'Selesai', 'v' => $cardData->selesai, 't' => 'selesai', 'c' => '#00b894', 'i' => 'fa-check-double'],
                ['l' => 'Sisa Akhir', 'v' => $cardData->sisa, 't' => 'sisa', 'c' => '#fdb827', 'i' => 'fa-hourglass-end'],
            ];
        @endphp
        @foreach($mainStats as $stat)
        <div class="col-xl-2 col-md-4 col-6">
            <a href="{{ route('dashboard.detail', ['type' => $stat['t'], 'tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]) }}" class="text-decoration-none">
                <div class="card stat-card-premium p-3" style="background: {{ $stat['c'] }}">
                    <div class="position-relative z-index-1">
                        <div class="small text-uppercase fw-700 opacity-75" style="font-size: 9px;">{{ $stat['l'] }}</div>
                        <h3 class="fw-800 mb-0 mt-1">{{ number_format($stat['v'] ?? 0) }}</h3>
                    </div>
                    <i class="fas {{ $stat['i'] }} card-icon-bg"></i>
                </div>
            </a>
        </div>
        @endforeach
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4 text-center">
                    <h5 class="fw-800 text-dark mb-4 text-start">Statistik Jenis Putusan</h5>
                    <div class="row g-3">
                        @php
                            $jp = [
                                ['l' => 'Dikuatkan', 'v' => $jenisPutus->dikuatkan, 't' => 'dikuatkan', 'c' => '#4e73df'],
                                ['l' => 'Dibatalkan', 'v' => $jenisPutus->dibatalkan, 't' => 'dibatalkan', 'c' => '#ff7675'],
                                ['l' => 'N.O', 'v' => $jenisPutus->n_o, 't' => 'n_o', 'c' => '#00cec9'],
                                ['l' => 'Dicabut', 'v' => $jenisPutus->dicabut, 't' => 'dicabut', 'c' => '#f1c40f'],
                            ];
                        @endphp
                        @foreach($jp as $putus)
                        <div class="col-3">
                            <a href="{{ route('dashboard.detail', ['type' => $putus['t'], 'tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]) }}" class="text-decoration-none text-dark">
                                <div class="putus-box">
                                    <div class="small fw-700 text-muted" style="font-size: 9px;">{{ $putus['l'] }}</div>
                                    <h4 class="fw-800 mb-0 mt-1">{{ number_format($putus['v'] ?? 0) }}</h4>
                                    <div class="putus-indicator" style="background: {{ $putus['c'] }}"></div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-4" style="height: 250px;"><canvas id="chartPremium"></canvas></div>
                </div>
            </div>
            
            <div class="row g-3 text-white text-center">
                <div class="col-md-4"><a href="{{ route('dashboard.detail', ['type' => '0_30', 'tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]) }}" class="text-decoration-none text-white"><div class="p-3 rounded-4 shadow-sm" style="background: #00b894;"><span class="small fw-bold d-block">0-30 Hari</span><span class="h4 fw-800">{{ $zonaWarna->hijau_tua }}</span></div></a></div>
                <div class="col-md-4"><a href="{{ route('dashboard.detail', ['type' => '31_60', 'tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]) }}" class="text-decoration-none text-white"><div class="p-3 rounded-4 shadow-sm" style="background: #f1c40f;"><span class="small fw-bold d-block">31-60 Hari</span><span class="h4 fw-800">{{ $zonaWarna->hijau_muda }}</span></div></a></div>
                <div class="col-md-4"><a href="{{ route('dashboard.detail', ['type' => '90_up', 'tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]) }}" class="text-decoration-none text-white"><div class="p-3 rounded-4 shadow-sm" style="background: #ff7675;"><span class="small fw-bold d-block">> 90 Hari</span><span class="h4 fw-800">{{ $zonaWarna->merah }}</span></div></a></div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3 border-0"><h6 class="m-0 fw-800 text-dark">Rekap Jenis Perkara</h6></div>
                <div class="card-body p-0 overflow-auto" style="max-height: 500px;">
                    <table class="table table-hover align-middle mb-0">
                        <tbody>
                            @foreach($rekapJenis as $item)
                            <tr>
                                <td class="px-4 py-3"><div class="fw-700 text-dark" style="font-size: 11px;">{{ $item->jenis }}</div></td>
                                <td class="text-center px-4"><a href="{{ route('dashboard.detail', ['type' => 'per_jenis', 'jenis' => $item->jenis, 'tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]) }}" class="btn btn-sm btn-light fw-800 rounded-pill px-3" style="font-size: 10px;">{{ $item->total }}</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const formatYMD = (d) => d.toISOString().split('T')[0];
        const form = document.getElementById('filterForm'), inAwal = document.getElementById('tgl_awal'), inAkhir = document.getElementById('tgl_akhir');
        
        document.querySelectorAll('.quick-period').forEach(btn => {
            btn.addEventListener('click', function() {
                const p = this.dataset.period, today = new Date(); let start = new Date();
                if(p === 'month') start = new Date(today.getFullYear(), today.getMonth(), 1);
                else if(p === 'year') start = new Date(today.getFullYear(), 0, 1);
                inAwal.value = formatYMD(start); inAkhir.value = formatYMD(today); form.submit();
            });
        });

        form.addEventListener('submit', () => { 
            inAwal.value = document.getElementById('display_tgl_awal').value; 
            inAkhir.value = document.getElementById('display_tgl_akhir').value; 
        });

        new Chart(document.getElementById('chartPremium').getContext('2d'), {
            type: 'line',
            data: {
                labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'],
                datasets: [{ label: 'Putus', data: [15, 28, 22, 10, 0, 0, 0, 0, 0, 0, 0, 0], borderColor: '#4e73df', borderWidth: 3, tension: 0.4, fill: true, backgroundColor: 'rgba(78, 115, 223, 0.1)' }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
        });
    });
</script>
@endpush
@endsection