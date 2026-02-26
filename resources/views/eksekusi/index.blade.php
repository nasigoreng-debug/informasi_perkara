@extends('layouts.app')

@section('title', 'Rekapitulasi Kinerja Eksekusi')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

<style>
    :root {
        --pta-navy: #1e293b;
        --pta-indigo: #4f46e5;
        --bg-light: #f8fafc;
    }

    body {
        background-color: var(--bg-light);
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: #334155;
    }

    /* DNA Card Luxury */
    .card-luxury {
        background: #ffffff;
        border: none;
        border-radius: 24px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
        overflow: hidden;
    }

    /* Header DNA */
    .dashboard-header {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        border-radius: 24px;
        padding: 2.5rem;
        color: white;
        box-shadow: 0 15px 35px rgba(30, 41, 59, 0.15);
    }

    /* DNA Tombol Kembali Melingkar */
    .btn-back {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        border: 1px solid #e2e8f0;
        color: var(--pta-indigo);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transition: all 0.3s;
        text-decoration: none !important;
    }

    .btn-back:hover {
        background: var(--pta-indigo);
        color: white;
        transform: translateX(-5px);
    }

    /* Overview Stats DNA */
    .global-overview {
        background: #1e293b;
        border-radius: 24px;
        color: white;
        padding: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }

    /* KPI Cards DNA */
    .kpi-card {
        border: none;
        border-radius: 20px;
        background: white;
        padding: 1.5rem;
        transition: transform 0.3s ease;
    }

    .kpi-card:hover {
        transform: translateY(-5px);
    }

    /* Table DNA */
    .table-luxury thead th {
        background: #f8fafc;
        color: #64748b;
        font-weight: 800;
        text-transform: uppercase;
        padding: 1.2rem;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        border-bottom: 2px solid #f1f5f9;
    }

    .table-luxury tbody td {
        padding: 1.2rem;
        vertical-align: middle;
        font-size: 0.875rem;
        border-bottom: 1px solid #f1f5f9;
    }

    /* DNA Link & Badge */
    .angka-link {
        font-weight: 800;
        padding: 0.5rem 1rem;
        border-radius: 12px;
        text-decoration: none !important;
        display: inline-block;
        transition: 0.2s;
    }

    .link-blue {
        background: #eef2ff;
        color: #4f46e5;
        border: 1px solid #e0e7ff;
    }

    .link-green {
        background: #f0fdf4;
        color: #16a34a;
        border: 1px solid #dcfce7;
    }

    .link-red {
        background: #fff1f2;
        color: #e11d48;
        border: 1px solid #ffe4e6;
    }

    .angka-link:hover {
        transform: translateY(-2px);
        filter: brightness(0.95);
    }

    .tfoot-total {
        background-color: #1e293b !important;
        color: white !important;
        font-weight: 800;
    }

    input[type="date"] {
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 8px 15px;
        background: rgba(255, 255, 255, 0.1);
        color: white;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="container py-4 px-xl-5">
    @php
    $urutanManual = ['BANDUNG', 'INDRAMAYU', 'MAJALENGKA', 'SUMBER', 'CIAMIS', 'TASIKMALAYA', 'KARAWANG', 'CIMAHI', 'SUBANG', 'SUMEDANG', 'PURWAKARTA', 'SUKABUMI', 'CIANJUR', 'KUNINGAN', 'CIBADAK', 'CIREBON', 'GARUT', 'BOGOR', 'BEKASI', 'CIBINONG', 'CIKARANG', 'DEPOK', 'TASIKKOTA', 'BANJAR', 'SOREANG', 'NGAMPRAH'];
    $sortedData = collect($data)->sortBy(function($item) use ($urutanManual) {
    $namaClean = strtoupper(trim(str_replace(['PA ', 'PENGADILAN AGAMA '], '', $item->satker)));
    $index = array_search($namaClean, $urutanManual);
    return ($index === false) ? 999 : $index;
    });
    $hariIni = date('Y-m-d');
    $tglAkhirFix = ($tglAkhir >= date('Y-12-31') || $tglAkhir > $hariIni) ? $hariIni : $tglAkhir;
    @endphp

    <div class="page-heading d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <a href="{{ url('laporan-utama') }}" class="btn-back me-3 shadow-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h1 class="h3 fw-800 mb-1">Kinerja Eksekusi</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                        <li class="breadcrumb-item"><a href="{{ url('laporan-utama') }}" class="text-decoration-none text-muted">Panel</a></li>
                        <li class="breadcrumb-item active fw-bold text-primary">Monitoring Eksekusi</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="text-end">
            <button onclick="window.print()" class="btn btn-white border shadow-sm fw-bold px-4 py-2 rounded-pill bg-white">
                <i class="bi bi-printer me-2"></i> Cetak
            </button>
        </div>
    </div>

    <div class="dashboard-header mb-4 animate__animated animate__fadeIn">
        <div class="row align-items-center">
            <div class="col-md-5">
                <h4 class="mb-1 text-uppercase fw-800">Filter Periode</h4>
                <p class="mb-0 opacity-75 small">Pantau progres penyelesaian eksekusi real-time</p>
            </div>
            <div class="col-md-7">
                <form action="{{ route('laporan.eksekusi.index') }}" method="GET" class="d-flex justify-content-md-end align-items-center gap-3">
                    <input type="date" name="tgl_awal" id="tgl_awal" value="{{ $tglAwal }}" max="{{ $hariIni }}">
                    <span class="small fw-bold opacity-50">S/D</span>
                    <input type="date" name="tgl_akhir" id="tgl_akhir" value="{{ $tglAkhirFix }}" max="{{ $hariIni }}">

                    <button type="submit" class="btn btn-primary px-4 fw-800 rounded-pill shadow-sm" style="background: #4f46e5; border: none; height: 42px;">
                        CARI DATA
                    </button>
                    <a href="{{ route('laporan.eksekusi.index') }}" class="btn btn-reset p-0 d-flex align-items-center justify-content-center rounded-circle" style="width: 42px; height: 42px;" title="Reset">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </form>
            </div>
        </div>
    </div>

    <div class="global-overview mb-4 animate__animated animate__fadeInUp">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="row text-center text-md-start">
                    <div class="col-md-4 border-end border-white border-opacity-10">
                        <p class="text-white-50 mb-1 small fw-bold">TOTAL PERMOHONAN</p>
                        <h2 class="fw-800 mb-0">
                            <a href="{{ route('laporan.eksekusi.detail', ['satker' => 'ALL', 'jenis' => 'TOTAL_DITERIMA', 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhirFix]) }}" class="text-info text-decoration-none">
                                {{ number_format($allTime['diterima'], 0, ',', '.') }}
                            </a>
                        </h2>
                    </div>
                    <div class="col-md-4 border-end border-white border-opacity-10">
                        <p class="text-white-50 mb-1 small fw-bold">TOTAL SELESAI</p>
                        <h2>
                            <a href="{{ route('laporan.eksekusi.detail', ['satker' => 'ALL', 'jenis' => 'TOTAL_SELESAI', 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhirFix]) }}" class="text-success text-decoration-none">
                                {{ number_format($allTime['selesai'], 0, ',', '.') }}
                            </a>
                        </h2>

                    </div>
                    <div class="col-md-4">
                        <p class="text-white-50 mb-1 small fw-bold">SISA</p>
                        <h2>
                            <a href="{{ route('laporan.eksekusi.detail', ['satker' => 'ALL', 'jenis' => 'TOTAL_SISA', 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhirFix]) }}" class="text-warning text-decoration-none">
                                {{ number_format($allTime['sisa'], 0, ',', '.') }}
                            </a>
                        </h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4 ps-md-5 mt-4 mt-md-0">
                <div class="d-flex justify-content-between mb-2 small fw-800">
                    <span class="text-white-50 uppercase">Rasio Penyelesaian</span>
                    <span class="text-white">{{ $allTime['persentase'] }}%</span>
                </div>
                <div class="progress" style="height: 12px; background: rgba(255,255,255,0.15); border-radius: 20px;">
                    <div class="progress-bar bg-success" style="width: {{ $allTime['persentase'] }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        @foreach([['Sisa Lalu', $summary['SISA'], 'bi-journal-text'], ['Diterima', $summary['DITERIMA'], 'bi-plus-circle'], ['Beban', $summary['BEBAN'], 'bi-collection'], ['Selesai', $summary['SELESAI'], 'bi-check-circle'], ['Sisa', $summary['SISA_TAHUN_INI'], 'bi-clock-history']] as $index => $c)
        <div class="col-md col-6 animate__animated animate__fadeInUp" style="animation-delay: {{ $index * 0.1 }}s">
            <div class="card kpi-card shadow-sm text-center">
                <div class="text-muted mb-2"><i class="{{ $c[2] }} h4"></i></div>
                <h6 class="small fw-bold text-uppercase text-muted mb-1">{{ $c[0] }}</h6>
                <h3 class="mb-0 fw-800 text-dark">{{ number_format($c[1] ?? 0, 0, ',', '.') }}</h3>
            </div>
        </div>
        @endforeach
    </div>

    <div class="card card-luxury border-0 animate__animated animate__fadeInUp">
        <div class="p-4 d-flex justify-content-between align-items-center border-bottom bg-white">
            <h5 class="fw-800 mb-0">Rincian Per Satuan Kerja</h5>
            <button onclick="exportToExcel()" class="btn btn-success fw-bold px-4 rounded-pill shadow-sm">
                <i class="bi bi-file-earmark-excel me-2"></i> Ekspor Excel
            </button>
        </div>
        <div class="table-responsive">
            <table class="table table-luxury text-center align-middle" id="rekapTable">
                <thead>
                    <tr>
                        <th width="60">NO</th>
                        <th class="text-start">SATUAN KERJA</th>
                        <th>SISA LALU</th>
                        <th>DITERIMA</th>
                        <th>BEBAN</th>
                        <th>SELESAI</th>
                        <th>RASIO</th>
                        <th>SISA</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach($sortedData as $row)
                    @php $persen = $row->BEBAN > 0 ? ($row->SELESAI / $row->BEBAN) * 100 : 0; @endphp
                    <tr>
                        <td class="small fw-bold text-muted">{{ $no++ }}</td>
                        <td class="text-start fw-800 text-dark text-uppercase" style="font-size: 0.75rem;">{{ $row->satker }}</td>
                        <td><a href="{{ route('laporan.eksekusi.detail', ['satker' => $row->satker, 'jenis' => 'SISA', 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhirFix]) }}" class="angka-link link-blue">{{ number_format($row->SISA, 0, ',', '.') }}</a></td>
                        <td><a href="{{ route('laporan.eksekusi.detail', ['satker' => $row->satker, 'jenis' => 'DITERIMA', 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhirFix]) }}" class="angka-link link-blue">{{ number_format($row->DITERIMA, 0, ',', '.') }}</a></td>
                        <td><a href="{{ route('laporan.eksekusi.detail', ['satker' => $row->satker, 'jenis' => 'BEBAN', 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhirFix]) }}" class="angka-link link-blue">{{ number_format($row->BEBAN, 0, ',', '.') }}</a></td>
                        <td><a href="{{ route('laporan.eksekusi.detail', ['satker' => $row->satker, 'jenis' => 'SELESAI', 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhirFix]) }}" class="angka-link link-green">{{ number_format($row->SELESAI, 0, ',', '.') }}</a></td>
                        <td class="fw-800">{{ number_format($persen, 1, ',', '.') }}%</td>
                        <td>
                            @if($row->SISA_TAHUN_INI > 0)
                            <a href="{{ route('laporan.eksekusi.detail', ['satker' => $row->satker, 'jenis' => 'SISA_TAHUN_INI', 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhirFix]) }}" class="angka-link link-red">
                                {{ number_format($row->SISA_TAHUN_INI, 0, ',', '.') }}
                            </a>
                            @else
                            <span class="fw-bold opacity-25">0</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="tfoot-total">
                    <tr>
                        <td colspan="2" class="py-4">TOTAL WILAYAH HUKUM PTA BANDUNG</td>
                        <td>{{ number_format($summary['SISA'], 0, ',', '.') }}</td>
                        <td>{{ number_format($summary['DITERIMA'], 0, ',', '.') }}</td>
                        <td>{{ number_format($summary['BEBAN'], 0, ',', '.') }}</td>
                        <td>{{ number_format($summary['SELESAI'], 0, ',', '.') }}</td>
                        <td>{{ number_format(($summary['BEBAN']>0?($summary['SELESAI']/$summary['BEBAN'])*100:0), 1, ',', '.') }}%</td>
                        <td>{{ number_format($summary['SISA_TAHUN_INI'], 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection