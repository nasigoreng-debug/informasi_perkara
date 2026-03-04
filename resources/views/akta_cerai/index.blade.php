@extends('layouts.app')

@section('title', 'Monitoring Akta Cerai')

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
    }

    /* Notifikasi Periode Biru Lembut */
    .alert-periode {
        background-color: #eff6ff;
        border: 1px solid #dbeafe;
        color: #1e40af;
        border-radius: 12px;
        padding: 12px 20px;
        font-size: 0.9rem;
        font-weight: 500;
        display: flex;
        align-items: center;
    }

    .filter-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
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
    }

    .table-grid tbody td {
        border: 1px solid var(--border-color);
        padding: 16px 12px;
        font-size: 0.85rem;
        vertical-align: middle;
    }

    /* Badge Angka/Link Rincian */
    .status-badge {
        display: block;
        padding: 5px;
        border-radius: 6px;
        font-weight: 700;
        text-decoration: none !important;
        font-size: 0.8rem;
    }

    /* Grand Total Kuning Pekat */
    .bg-grand-total {
        background-color: #ffff00 !important;
        font-weight: 800 !important;
    }

    .bg-grand-total td {
        color: #000 !important;
        border-top: 2px solid #334155 !important;
    }

    .text-satker {
        text-align: left !important;
        padding-left: 20px !important;
        font-weight: 700;
    }

    @media print {
        .no-print {
            display: none !important;
        }
    }
</style>
@endpush

@section('content')
<div class="container py-5">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <a href="{{ route('monitoring') }}" class="btn-back me-3 shadow-sm no-print">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h2 class="fw-800 mb-0" style="color: var(--dark-navy);">Monitoring Akta Cerai</h2>
                <p class="text-muted small mb-0 font-italic">Urutan Berdasarkan Kinerja Penerbitan Tertinggi</p>
            </div>
        </div>
        <div class="no-print">
            <button onclick="window.print()" class="btn btn-white border shadow-sm fw-bold px-3 btn-sm">
                <i class="bi bi-printer me-2"></i> Cetak
            </button>
        </div>
    </div>

    {{-- FILTER CARD --}}
    <div class="filter-card shadow-sm no-print">
        <form action="" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="small fw-bold text-muted text-uppercase mb-1">Mulai Tanggal</label>
                <input type="date" name="tgl_awal" class="form-control" value="{{ $tglAwal }}">
            </div>
            <div class="col-md-3">
                <label class="small fw-bold text-muted text-uppercase mb-1">Sampai Tanggal</label>
                <input type="date" name="tgl_akhir" class="form-control" value="{{ $tglAkhir }}">
            </div>
            <div class="col-md-6 text-end">
                <div class="btn-group shadow-sm">
                    <button type="submit" class="btn btn-primary fw-bold px-4">FILTER</button>
                    <a href="{{ route('akta-cerai.index') }}" class="btn btn-light border px-3"><i class="bi bi-arrow-clockwise text-primary"></i></a>
                    <a href="{{ route('akta-cerai.export', ['tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhir]) }}" target="_blank" class="btn btn-success fw-bold px-4">EXPORT EXCEL</a>
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
                        <th width="70">No</th>
                        <th class="text-start px-4">Satuan Kerja</th>
                        <th width="120">Total AC</th>
                        <th width="120">Tepat</th>
                        <th width="120">Terlambat</th>
                        <th class="text-danger" width="150">AC < BHT</th>
                        <th width="200">Kinerja (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $index => $row)
                    <tr>
                        <td class="fw-bold text-muted">
                            @if($index === 0 && $row->persen_tepat_waktu > 0)
                            <i class="bi bi-patch-check-fill text-primary fs-5"></i>
                            @else
                            {{ $index + 1 }}
                            @endif
                        </td>
                        <td class="text-satker text-primary text-uppercase">{{ $row->satker }}</td>
                        <td class="px-2">
                            <a href="{{ route('akta-cerai.detail', [$row->satker, 'kategori' => 'all', 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhir]) }}"
                                target="_blank" class="status-badge bg-light text-dark border shadow-sm">
                                {{ number_format($row->total) }}
                            </a>
                        </td>
                        <td class="px-2">
                            <a href="{{ route('akta-cerai.detail', [$row->satker, 'kategori' => 'tepat', 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhir]) }}"
                                target="_blank" class="status-badge text-success shadow-sm" style="background: #ecfdf4; border: 1px solid #d1fae5;">
                                {{ number_format($row->tepat_waktu) }}
                            </a>
                        </td>
                        <td class="px-2">
                            <a href="{{ route('akta-cerai.detail', [$row->satker, 'kategori' => 'terlambat', 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhir]) }}"
                                target="_blank" class="status-badge text-warning shadow-sm" style="background: #fffbeb; border: 1px solid #fef3c7;">
                                {{ number_format($row->terlambat) }}
                            </a>
                        </td>
                        <td class="px-2">
                            <a href="{{ route('akta-cerai.detail', [$row->satker, 'kategori' => 'anomali', 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhir]) }}"
                                target="_blank" class="status-badge {{ $row->anomali > 0 ? 'bg-danger text-white' : 'bg-light text-danger border' }} shadow-sm">
                                {{ number_format($row->anomali) }}
                            </a>
                        </td>
                        <td class="px-3">
                            <div class="d-flex align-items-center justify-content-center gap-3">
                                <div class="progress shadow-sm flex-grow-1" style="height: 8px; border-radius: 10px; background: #f1f5f9;">
                                    <div class="progress-bar {{ $row->persen_tepat_waktu >= 85 ? 'bg-success' : ($row->persen_tepat_waktu >= 50 ? 'bg-warning' : 'bg-danger') }}"
                                        style="width: {{ $row->persen_tepat_waktu }}%"></div>
                                </div>
                                <span class="fw-800 small" style="min-width: 45px;">{{ $row->persen_tepat_waktu }}%</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-grand-total text-center">
                    <tr>
                        <td></td>
                        <td class="text-satker">TOTAL WILAYAH HUKUM PTA BANDUNG</td>
                        <td>{{ number_format($totals['total']) }}</td>
                        <td>{{ number_format($totals['tepat']) }}</td>
                        <td>{{ number_format($totals['lambat']) }}</td>
                        <td>{{ number_format($totals['anomali']) }}</td>
                        <td>{{ $totals['kinerja'] }}%</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection