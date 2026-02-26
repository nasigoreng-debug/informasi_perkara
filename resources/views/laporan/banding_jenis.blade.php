@extends('layouts.app')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
    :root {
        --pta-bg: #fdfdfd;
        --pta-card-bg: #ffffff;
        --text-main: #334155;
        --text-light: #64748b;
        --accent-blue: #3b82f6;
        --accent-green: #10b981;
        --accent-red: #ef4444;
        --border-color: #f1f5f9;
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: var(--pta-bg);
        color: var(--text-main);
        -webkit-font-smoothing: antialiased;
    }

    /* ANTI UNDERLINE */
    a,
    .table a {
        text-decoration: none !important;
        box-shadow: none !important;
    }

    h2 {
        font-weight: 600;
        letter-spacing: -0.02em;
        color: #1e293b;
    }

    .small-light {
        font-weight: 300;
        color: var(--text-light);
        font-size: 0.85rem;
    }

    .card-aesthetic {
        background: var(--pta-card-bg);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
        overflow: hidden;
    }

    .table thead th {
        font-weight: 500;
        font-size: 0.8rem;
        color: var(--text-light);
        background: #fafafa;
        border-bottom: 1px solid var(--border-color);
        padding: 16px;
    }

    .table tbody td {
        font-weight: 300;
        font-size: 0.9rem;
        padding: 16px;
        border-bottom: 1px solid #f8fafc;
    }

    .satker-name {
        font-weight: 400;
        color: #1e293b;
    }

    .link-aesthetic {
        font-weight: 400;
        padding: 6px 14px;
        border-radius: 8px;
        display: inline-block;
    }

    .num-total {
        color: var(--accent-blue);
        background: #eff6ff;
        border: 1px solid #dbeafe;
    }

    .num-ecourt {
        color: var(--accent-green);
        background: #ecfdf5;
        border: 1px solid #d1fae5;
    }

    .num-manual {
        color: var(--accent-red);
        background: #fef2f2;
        border: 1px solid #fee2e2;
    }

    .text-zero {
        color: #cbd5e1;
        font-weight: 300;
    }

    .tfoot-aesthetic {
        background: #f8fafc;
        font-weight: 500;
        border-top: 2px solid var(--border-color);
    }

    .form-control-clean {
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        font-size: 0.85rem;
        border-radius: 8px;
        padding: 8px 12px;
        font-weight: 300;
    }

    .btn-clean {
        border-radius: 8px;
        font-weight: 400;
        font-size: 0.85rem;
        padding: 8px 16px;
        transition: 0.3s;
    }
</style>
@endsection

@section('content')
<div class="container py-4 px-4">
    {{-- Header --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="fw-bold text-dark mb-1">Rekap Jenis Perkara</h2>
            <p class="small-light m-0 text-uppercase">PTA BANDUNG • DATA PER JENIS PERKARA</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('laporan-utama') }}" class="btn btn-white shadow-sm border px-4 btn-clean bg-white text-muted">
                <i class="fas fa-arrow-left me-2"></i> KEMBALI
            </a>
        </div>
    </div>

    {{-- Filter Card (SAMA PERSIS DENGAN RK1 & RK2) --}}
    <div class="card filter-section shadow-sm mb-4 border-0">
        <div class="card-body p-4">
            <form action="{{ url()->current() }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="small fw-bold text-muted mb-2">TANGGAL AWAL</label>
                    <input type="date" name="tgl_awal" class="form-control border-0 bg-light py-2" value="{{ $tgl_awal }}">
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold text-muted mb-2">TANGGAL AKHIR</label>
                    <input type="date" name="tgl_akhir" class="form-control border-0 bg-light py-2" value="{{ $tgl_akhir }}">
                </div>
                <div class="col-md-4">
                    <div class="btn-group w-100 shadow-sm">
                        <button type="submit" class="btn btn-primary fw-bold">FILTER</button>
                        <a href="{{ url()->current() }}" class="btn btn-outline-danger fw-bold"><i class="fas fa-undo"></i></a>
                    </div>
                </div>
                <div class="col-md-2 text-end">
                    <div class="d-flex gap-2">
                        <button type="button" onclick="window.print()" class="btn btn-clean btn-light border w-100">
                            <i class="fas fa-print"></i>
                        </button>
                        <a href="{{ route('laporan.banding.diterima.export', ['tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]) }}" class="btn btn-clean btn-success w-100">
                            <i class="fas fa-file-excel me-1"></i> Excel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="card-aesthetic">
        <div class="table-responsive">
            <table class="table table-hover align-middle text-nowrap">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 5%">No</th>
                        <th>Kategori / Jenis Perkara</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">E-Court</th>
                        <th class="text-center">Manual</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no=1; @endphp
                    @foreach($results as $row)
                    @if($row->kategori != 'TOTAL SELURUH JENIS PERKARA')
                    <tr>
                        <td class="text-center small-light">{{ $no++ }}</td>
                        <td class="satker-name">{{ $row->kategori }}</td>
                        <td class="text-center">
                            <span class="link-aesthetic num-total">{{ number_format($row->total) }}</span>
                        </td>
                        <td class="text-center">
                            @if($row->ecourt > 0)
                            <span class="link-aesthetic num-ecourt">{{ number_format($row->ecourt) }}</span>
                            @else
                            <span class="text-zero">0</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($row->manual > 0)
                            <span class="link-aesthetic num-manual">{{ number_format($row->manual) }}</span>
                            @else
                            <span class="text-zero">0</span>
                            @endif
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
                <tfoot class="tfoot-aesthetic">
                    @foreach($results as $row)
                    @if($row->kategori == 'TOTAL SELURUH JENIS PERKARA')
                    <tr>
                        <td colspan="2" class="text-center py-4">TOTAL KESELURUHAN</td>
                        <td class="text-center">{{ number_format($row->total) }}</td>
                        <td class="text-center">{{ number_format($row->ecourt) }}</td>
                        <td class="text-center">{{ number_format($row->manual) }}</td>
                    </tr>
                    @endif
                    @endforeach
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection