@extends('layouts.app')

@section('title', 'Monitoring Court Calendar')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    :root {
        --dark-navy: #1e293b;
        --border-color: #e2e8f0;
        --bg-body: #f8fafc;
        --accent-blue: #2563eb;
    }

    body {
        background-color: var(--bg-body);
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: #334155;
        letter-spacing: -0.01em;
    }

    .container-wide {
        max-width: 96%;
        margin: 0 auto;
    }

    /* Notifikasi Periode */
    .alert-periode {
        background-color: #eff6ff;
        border: 1px solid #dbeafe;
        color: #1e40af;
        border-radius: 12px;
        padding: 12px 20px;
        font-size: 0.85rem;
        font-weight: 500;
        display: flex;
        align-items: center;
    }

    /* Navigasi & Filter */
    .btn-back {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: white;
        border: 1px solid var(--border-color);
        color: var(--dark-navy);
        text-decoration: none !important;
        transition: 0.2s;
    }

    .btn-back:hover {
        background: var(--dark-navy);
        color: white;
    }

    .filter-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
    }

    input[type="date"] {
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 6px 12px;
        font-size: 0.9rem;
        outline: none;
    }

    /* Table DNA - Grid Kotak Tipis (Classic Clean) */
    .table-container {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .table-grid {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 0;
    }

    .table-grid thead th {
        background: #f8fafc;
        border: 1px solid var(--border-color);
        padding: 16px 12px;
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        color: #64748b;
        letter-spacing: 0.05em;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .table-grid tbody td {
        border: 1px solid var(--border-color);
        padding: 16px 12px;
        font-size: 0.85rem;
        vertical-align: middle;
        line-height: 1.5;
    }

    .table-grid tr:hover td {
        background-color: #f1f5f9;
    }

    /* Visual Elements */
    .progress-sm {
        height: 8px;
        border-radius: 10px;
        background: #e2e8f0;
        overflow: hidden;
        width: 80px;
    }

    .status-pill {
        padding: 5px 12px;
        border-radius: 6px;
        font-weight: 800;
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }

    .bg-tuntas { background: #f0fdf4; color: #16a34a; border: 1px solid #dcfce7; }
    .bg-baik { background: #f0f9ff; color: #0ea5e9; border: 1px solid #e0f2fe; }
    .bg-belum { background: #fff1f2; color: #e11d48; border: 1px solid #ffe4e6; }

    .tfoot-dark {
        background: var(--dark-navy);
        color: white;
        font-weight: 700;
    }

    .tfoot-dark td {
        border: 1px solid #334155 !important;
        padding: 18px !important;
    }

    @media print {
        .no-print, .btn-back, .filter-card, .alert-periode {
            display: none !important;
        }
        .table-container {
            border: none;
            box-shadow: none;
        }
        body { background: white; }
    }
</style>
@endpush

@section('content')
<div class="container py-5">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <a href="{{ url('monitoring') }}" class="btn-back me-3 shadow-sm no-print">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h2 class="fw-800 mb-0" style="color: var(--dark-navy); letter-spacing: -0.02em;">Peringkat Kepatuhan Court Calendar</h2>
                <p class="text-muted small mb-0 font-italic">Urutan: Persentase Tertinggi & Volume Terima Terbanyak</p>
            </div>
        </div>
        <div class="no-print d-flex gap-2">
            <button onclick="window.print()" class="btn btn-white border shadow-sm fw-bold px-3 btn-sm">
                <i class="bi bi-printer me-2"></i> Cetak
            </button>
        </div>
    </div>

    {{-- FILTER FORM --}}
    <div class="filter-card shadow-sm no-print">
        <form action="{{ route('court-calendar') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="small fw-bold text-muted text-uppercase mb-1">Dari Tgl Daftar</label>
                <input type="date" name="tgl_awal" class="form-control" value="{{ $tglAwal }}">
            </div>
            <div class="col-md-3">
                <label class="small fw-bold text-muted text-uppercase mb-1">Sampai Tgl Daftar</label>
                <input type="date" name="tgl_akhir" class="form-control" value="{{ $tglAkhir }}">
            </div>
            <div class="col-md-6 text-end">
                <div class="btn-group shadow-sm">
                    <button type="submit" class="btn btn-primary fw-bold px-4">
                        <i class="bi bi-filter me-1"></i> FILTER
                    </button>
                    <a href="{{ route('court-calendar') }}" class="btn btn-light border px-3" title="Reset Filter">
                        <i class="bi bi-arrow-clockwise text-primary"></i>
                    </a>
                    <a href="{{ route('court-calendar.export', ['tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhir]) }}" target="_blank" class="btn btn-success fw-bold px-4">
                        <i class="bi bi-file-earmark-excel me-1"></i> EXPORT
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- NOTIFIKASI PERIODE --}}
    <div class="alert-periode shadow-sm mb-4">
        <i class="bi bi-info-circle-fill me-3 fs-5"></i>
        <div>
            Menampilkan hasil monitoring periode: 
            <span class="fw-bold text-dark">{{ \Carbon\Carbon::parse($tglAwal)->translatedFormat('d F Y') }}</span> 
            s.d 
            <span class="fw-bold text-dark">{{ \Carbon\Carbon::parse($tglAkhir)->translatedFormat('d F Y') }}</span>
        </div>
    </div>

    {{-- DATA TABLE --}}
    <div class="table-container shadow-sm">
        <div class="table-responsive">
            <table class="table-grid text-center">
                <thead>
                    <tr>
                        <th width="70">Rank</th>
                        <th class="text-start px-4">Satuan Kerja</th>
                        <th width="150">Total Terima</th>
                        <th width="150">Sudah Input</th>
                        <th width="150">Belum Input</th>
                        <th width="180">Status</th>
                        <th width="220">Kepatuhan (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @php $gTotal = 0; $gSudah = 0; $gBelum = 0; @endphp
                    @foreach($results as $index => $row)
                    @php
                        $gTotal += $row->total;
                        $gSudah += $row->sudah;
                        $gBelum += $row->belum;
                    @endphp
                    <tr>
                        <td class="fw-bold text-muted">
                            @if($index === 0 && $row->persentase == 100)
                            <i class="bi bi-patch-check-fill text-primary fs-5" title="Terbaik"></i>
                            @else
                            {{ $index + 1 }}
                            @endif
                        </td>
                        <td class="text-start px-4 fw-700 text-uppercase text-primary">{{ $row->satker }}</td>
                        <td class="fw-bold">{{ number_format($row->total) }}</td>
                        <td class="text-success fw-bold">{{ number_format($row->sudah) }}</td>
                        <td>
                            @if($row->belum > 0)
                            <a href="{{ route('court-calendar.detail', ['satker' => $row->db, 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhir]) }}"
                                target="_blank" class="text-danger fw-bold text-decoration-none">
                                {{ number_format($row->belum) }}
                            </a>
                            @else
                            <span class="text-muted opacity-25">0</span>
                            @endif
                        </td>
                        <td>
                            @if($row->persentase >= 100)
                            <span class="status-pill bg-tuntas">Sempurna</span>
                            @elseif($row->persentase >= 85)
                            <span class="status-pill bg-baik">Baik</span>
                            @else
                            <span class="status-pill bg-belum">Kurang</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center justify-content-center gap-3">
                                <div class="progress-sm">
                                    <div class="progress-bar {{ $row->persentase >= 85 ? 'bg-success' : 'bg-danger' }}" style="width: {{ $row->persentase }}%"></div>
                                </div>
                                <span class="fw-800 small" style="width: 45px;">{{ $row->persentase }}%</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="tfoot-dark text-center">
                    <tr>
                        <td></td>
                        <td class="text-start px-4">TOTAL WILAYAH HUKUM PTA BANDUNG</td>
                        <td>{{ number_format($gTotal) }}</td>
                        <td>{{ number_format($gSudah) }}</td>
                        <td>{{ number_format($gBelum) }}</td>
                        <td></td>
                        <td>{{ $gTotal > 0 ? round(($gSudah / $gTotal) * 100, 2) : 0 }}%</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection