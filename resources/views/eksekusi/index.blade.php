@extends('layouts.app')

@section('title', 'Rekapitulasi Kinerja Eksekusi')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    :root {
        --dark-navy: #1e293b;
        --border-color: #e2e8f0;
        --bg-body: #f8fafc;
    }

    body {
        background-color: var(--bg-body);
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: #334155;
    }

    /* Header & Action */
    .btn-back {
        width: 40px; height: 40px; border-radius: 8px;
        display: inline-flex; align-items: center; justify-content: center;
        background: white; border: 1px solid var(--border-color);
        color: var(--dark-navy); text-decoration: none !important;
    }

    /* Global Overview Box - Classic Dark Mode */
    .global-card {
        background: var(--dark-navy);
        color: white;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 1.5rem;
    }
    .global-label { font-size: 0.7rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; }
    .global-value { font-size: 1.75rem; font-weight: 800; text-decoration: none !important; color: white; transition: 0.2s; }
    .global-value:hover { color: #38bdf8; }

    /* KPI Cards - Putih Kotak */
    .kpi-container { display: grid; grid-template-columns: repeat(5, 1fr); gap: 15px; margin-bottom: 1.5rem; }
    .kpi-box {
        background: white; border: 1px solid var(--border-color);
        padding: 1.25rem; border-radius: 10px; text-align: center;
    }
    .kpi-label { font-size: 0.65rem; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 5px; display: block; }
    .kpi-num { font-size: 1.25rem; font-weight: 800; margin: 0; color: var(--dark-navy); }

    /* Filter Strip */
    .filter-strip {
        background: white; border: 1px solid var(--border-color);
        border-radius: 10px; padding: 1rem 1.5rem; margin-bottom: 1.5rem;
    }
    input[type="date"] {
        border: 1px solid var(--border-color); border-radius: 6px;
        padding: 5px 10px; font-size: 0.85rem; outline: none;
    }

    /* Table Grid - Garis Tipis Sempurna */
    .table-container {
        background: white; border: 1px solid var(--border-color);
        border-radius: 12px; overflow: hidden;
    }
    .table-grid { width: 100%; border-collapse: collapse; margin-bottom: 0; }
    .table-grid thead th {
        background: #f8fafc; border: 1px solid var(--border-color);
        padding: 12px; font-size: 0.7rem; font-weight: 800; color: #475569; text-transform: uppercase;
    }
    .table-grid tbody td { border: 1px solid var(--border-color); padding: 10px 12px; font-size: 0.85rem; vertical-align: middle; }
    .table-grid tr:hover td { background-color: #f1f5f9; }

    /* Custom Link Styles */
    .link-detail { text-decoration: none !important; font-weight: 700; display: block; color: inherit; padding: 2px; border-radius: 4px; }
    .link-detail:hover { background: #e0f2fe; color: #0369a1; }
    .text-success-link { color: #16a34a; }
    .text-danger-link { color: #dc2626; }

    .tfoot-dark { background: var(--dark-navy); color: white; font-weight: 700; }
    .tfoot-dark td { border: 1px solid #334155 !important; }

    @media (max-width: 992px) { .kpi-container { grid-template-columns: repeat(3, 1fr); } }
</style>
@endpush

@section('content')
<div class="container py-4 px-xl-5">
    @php
        $urutanManual = ['BANDUNG', 'INDRAMAYU', 'MAJALENGKA', 'SUMBER', 'CIAMIS', 'TASIKMALAYA', 'KARAWANG', 'CIMAHI', 'SUBANG', 'SUMEDANG', 'PURWAKARTA', 'SUKABUMI', 'CIANJUR', 'KUNINGAN', 'CIBADAK', 'CIREBON', 'GARUT', 'BOGOR', 'BEKASI', 'CIBINONG', 'CIKARANG', 'DEPOK', 'TASIKKOTA', 'BANJAR', 'SOREANG', 'NGAMPRAH'];
        $sortedData = collect($data)->sortBy(function($item) use ($urutanManual) {
            $satkerName = is_object($item) ? $item->satker : $item['satker'];
            $namaClean = strtoupper(trim(str_replace(['PA ', 'PENGADILAN AGAMA '], '', $satkerName)));
            $index = array_search($namaClean, $urutanManual);
            return ($index === false) ? 999 : $index;
        });
        $hariIni = date('Y-m-d');
        $tglAkhirFix = ($tglAkhir >= date('Y-12-31') || $tglAkhir > $hariIni) ? $hariIni : $tglAkhir;
    @endphp

    {{-- 1. TOP HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <a href="{{ url('monitoring') }}" class="btn-back me-3 shadow-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h3 class="fw-800 mb-0">Kinerja Eksekusi</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="{{ url('monitoring') }}" class="text-decoration-none">Panel</a></li>
                        <li class="breadcrumb-item active fw-bold">Monitoring</li>
                    </ol>
                </nav>
            </div>
        </div>
        {{-- Cetak Target Blank via JavaScript Window.open --}}
        <button onclick="window.open(window.location.href, '_blank').print()" class="btn btn-white border shadow-sm btn-sm fw-bold px-3">
            <i class="bi bi-printer me-2"></i> Cetak Halaman
        </button>
    </div>

    {{-- 2. GLOBAL OVERVIEW --}}
    <div class="global-card shadow-sm border-0">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="row text-center text-md-start">
                    <div class="col-md-4 border-end border-secondary border-opacity-25">
                        <p class="global-label mb-1">Total Permohonan</p>
                        <a href="{{ route('laporan.eksekusi.detail', ['satker' => 'ALL', 'jenis' => 'TOTAL_DITERIMA', 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhirFix]) }}" target="_blank" class="global-value">
                            {{ number_format($allTime['diterima'], 0, ',', '.') }}
                        </a>
                    </div>
                    <div class="col-md-4 border-end border-secondary border-opacity-25">
                        <p class="global-label mb-1 text-success">Total Selesai</p>
                        <a href="{{ route('laporan.eksekusi.detail', ['satker' => 'ALL', 'jenis' => 'TOTAL_SELESAI', 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhirFix]) }}" target="_blank" class="global-value text-success">
                            {{ number_format($allTime['selesai'], 0, ',', '.') }}
                        </a>
                    </div>
                    <div class="col-md-4">
                        <p class="global-label mb-1 text-warning">Total Sisa</p>
                        <a href="{{ route('laporan.eksekusi.detail', ['satker' => 'ALL', 'jenis' => 'TOTAL_SISA', 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhirFix]) }}" target="_blank" class="global-value text-warning">
                            {{ number_format($allTime['sisa'], 0, ',', '.') }}
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mt-4 mt-md-0 ps-md-5 border-start border-secondary border-opacity-25">
                <div class="d-flex justify-content-between mb-2 small fw-bold">
                    <span class="text-uppercase opacity-50">Rasio Penyelesaian</span>
                    <span>{{ $allTime['persentase'] }}%</span>
                </div>
                <div class="progress" style="height: 10px; background: rgba(255,255,255,0.1); border-radius: 20px;">
                    <div class="progress-bar bg-info" style="width: {{ $allTime['persentase'] }}%"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. FILTER STRIP --}}
    <div class="filter-strip shadow-sm">
        <form action="{{ route('laporan.eksekusi.index') }}" method="GET" class="row g-3 align-items-center">
            <div class="col-md-auto small fw-800 text-muted">FILTER PERIODE:</div>
            <div class="col-md-auto d-flex align-items-center gap-2">
                <input type="date" name="tgl_awal" value="{{ $tglAwal }}" max="{{ $hariIni }}">
                <span class="text-muted small fw-bold">S/D</span>
                <input type="date" name="tgl_akhir" value="{{ $tglAkhirFix }}" max="{{ $hariIni }}">
            </div>
            <div class="col-md-auto">
                <button type="submit" class="btn btn-dark btn-sm px-4 fw-bold rounded-2">Proses Data</button>
                <a href="{{ route('laporan.eksekusi.index') }}" class="btn btn-light btn-sm border ms-1"><i class="bi bi-arrow-clockwise"></i></a>
            </div>
        </form>
    </div>

    {{-- 4. KPI CARDS --}}
    <div class="kpi-container">
        <div class="kpi-box shadow-sm">
            <span class="kpi-label">Sisa Lalu</span>
            <p class="kpi-num">{{ number_format($summary['SISA'], 0, ',', '.') }}</p>
        </div>
        <div class="kpi-box shadow-sm">
            <span class="kpi-label">Diterima</span>
            <p class="kpi-num text-primary">{{ number_format($summary['DITERIMA'], 0, ',', '.') }}</p>
        </div>
        <div class="kpi-box shadow-sm bg-light">
            <span class="kpi-label text-dark">Beban</span>
            <p class="kpi-num">{{ number_format($summary['BEBAN'], 0, ',', '.') }}</p>
        </div>
        <div class="kpi-box shadow-sm">
            <span class="kpi-label text-success">Selesai</span>
            <p class="kpi-num text-success">{{ number_format($summary['SELESAI'], 0, ',', '.') }}</p>
        </div>
        <div class="kpi-box shadow-sm">
            <span class="kpi-label text-danger">Sisa Periode</span>
            <p class="kpi-num text-danger">{{ number_format($summary['SISA_TAHUN_INI'], 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- 5. TABLE SECTION --}}
    <div class="table-container shadow-sm border-0">
        <div class="p-3 bg-white border-bottom d-flex justify-content-between align-items-center">
            <h6 class="fw-800 mb-0">Rincian Per Satuan Kerja</h6>
            <a href="{{ route('laporan.eksekusi.export', ['tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhirFix]) }}" target="_blank" class="btn btn-success btn-sm fw-bold px-3">
                <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
            </a>
        </div>
        <div class="table-responsive">
            <table class="table-grid text-center">
                <thead>
                    <tr>
                        <th width="50">NO</th>
                        <th class="text-start">SATUAN KERJA</th>
                        <th>SISA LALU</th>
                        <th>DITERIMA</th>
                        <th>BEBAN</th>
                        <th>SELESAI</th>
                        <th>RASIO (%)</th>
                        <th>SISA</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach($sortedData as $row)
                    @php
                        $r = (object) $row;
                        $persen = $r->BEBAN > 0 ? ($r->SELESAI / $r->BEBAN) * 100 : 0;
                    @endphp
                    <tr>
                        <td class="text-muted small">{{ $no++ }}</td>
                        <td class="text-start fw-700 text-uppercase" style="font-size: 0.75rem;">{{ $r->satker }}</td>
                        <td>
                            <a href="{{ route('laporan.eksekusi.detail', ['satker' => $r->satker, 'jenis' => 'SISA', 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhirFix]) }}" target="_blank" class="link-detail">
                                {{ number_format($r->SISA, 0, ',', '.') }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('laporan.eksekusi.detail', ['satker' => $r->satker, 'jenis' => 'DITERIMA', 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhirFix]) }}" target="_blank" class="link-detail">
                                {{ number_format($r->DITERIMA, 0, ',', '.') }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('laporan.eksekusi.detail', ['satker' => $r->satker, 'jenis' => 'BEBAN', 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhirFix]) }}" target="_blank" class="link-detail">
                                {{ number_format($r->BEBAN, 0, ',', '.') }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('laporan.eksekusi.detail', ['satker' => $r->satker, 'jenis' => 'SELESAI', 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhirFix]) }}" target="_blank" class="link-detail text-success-link">
                                {{ number_format($r->SELESAI, 0, ',', '.') }}
                            </a>
                        </td>
                        <td class="fw-800">{{ number_format($persen, 1, ',', '.') }}%</td>
                        <td>
                            @if($r->SISA_TAHUN_INI > 0)
                            <a href="{{ route('laporan.eksekusi.detail', ['satker' => $r->satker, 'jenis' => 'SISA_TAHUN_INI', 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhirFix]) }}" target="_blank" class="link-detail text-danger-link">
                                {{ number_format($r->SISA_TAHUN_INI, 0, ',', '.') }}
                            </a>
                            @else
                            <span class="opacity-25 fw-bold">0</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="tfoot-dark text-center">
                    <tr>
                        <td colspan="2" class="text-start ps-4 py-3">TOTAL WILAYAH HUKUM PTA BANDUNG</td>
                        <td>{{ number_format($summary['SISA'], 0, ',', '.') }}</td>
                        <td>{{ number_format($summary['DITERIMA'], 0, ',', '.') }}</td>
                        <td>{{ number_format($summary['BEBAN'], 0, ',', '.') }}</td>
                        <td>{{ number_format($summary['SELESAI'], 0, ',', '.') }}</td>
                        <td>{{ number_format(($summary['BEBAN'] > 0 ? ($summary['SELESAI'] / $summary['BEBAN']) * 100 : 0), 1, ',', '.') }}%</td>
                        <td>{{ number_format($summary['SISA_TAHUN_INI'], 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection