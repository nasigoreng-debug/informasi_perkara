@extends('layouts.app')

@section('title', 'Rekap Jenis Perkara')

@push('styles')
<style>
    /* Container utama */
    .jenis-container {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: #f4f7fa;
    }

    /* Header judul (senada dengan RK.1 & RK.2) */
    .jenis-container .page-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .jenis-container .page-header h3 {
        font-weight: 800;
        text-transform: uppercase;
        color: #2c3e50;
        margin-bottom: 0.5rem;
        font-size: 1.75rem;
    }

    .jenis-container .page-header h5 {
        color: #6c757d;
        font-weight: 400;
    }

    .jenis-container .page-header .badge {
        background-color: #6c757d !important;
        font-weight: 500;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }

    .jenis-container .page-header .header-line {
        width: 60px;
        height: 4px;
        background: #3498db;
        border-radius: 10px;
        margin: 10px auto 0;
    }

    /* Filter card (senada dengan RK.1 & RK.2) */
    .jenis-container .filter-card {
        background: #ffffff;
        border: none;
        border-radius: 8px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        margin-bottom: 1.5rem;
        border: 1px solid #dee2e6;
    }

    .jenis-container .filter-card .card-body {
        padding: 1.5rem;
        background-color: #f8f9fa;
    }

    .jenis-container .filter-card .form-label {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #6c757d;
        margin-bottom: 0.5rem;
    }

    .jenis-container .filter-card .form-control {
        border-radius: 0.25rem;
        border: 1px solid #ced4da;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }

    .jenis-container .filter-card .form-control:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    /* Tombol-tombol */
    .jenis-container .btn-filter {
        background-color: #2c3e50 !important;
        color: white !important;
        font-weight: 500;
        border: none;
        border-radius: 0.25rem;
        padding: 0.375rem 1rem;
        font-size: 0.875rem;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .jenis-container .btn-filter:hover {
        background-color: #1e2b37 !important;
    }

    .jenis-container .btn-reset {
        background-color: #dc3545 !important;
        color: white !important;
        border: none;
        border-radius: 0.25rem;
        padding: 0.375rem 1rem;
        font-size: 0.875rem;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
    }

    .jenis-container .btn-reset:hover {
        background-color: #bb2d3b !important;
        color: white;
    }

    .jenis-container .btn-excel {
        background-color: #198754 !important;
        color: white !important;
        border: none;
        border-radius: 0.25rem;
        padding: 0.375rem 1rem;
        font-size: 0.875rem;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
    }

    .jenis-container .btn-excel:hover {
        background-color: #146c43 !important;
        color: white;
    }

    .jenis-container .btn-print {
        background-color: #6c757d !important;
        color: white !important;
        border: none;
        border-radius: 0.25rem;
        padding: 0.375rem 1rem;
        font-size: 0.875rem;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
    }

    .jenis-container .btn-print:hover {
        background-color: #5c636a !important;
        color: white;
    }

    /* Periode badge */
    .jenis-container .periode-badge {
        background: #f8f9fa;
        color: #2c3e50;
        border-radius: 0.25rem;
        padding: 0.375rem 0.75rem;
        font-weight: 500;
        font-size: 0.75rem;
        border: 1px solid #dee2e6;
    }

    /* Table card (senada dengan RK.1 & RK.2) */
    .jenis-container .table-card {
        background: #ffffff;
        border: none;
        border-radius: 8px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        overflow: hidden;
        border: 1px solid #dee2e6;
    }

    .jenis-container .table-card .card-body {
        padding: 0;
    }

    /* Tabel presisi seperti RK.1 & RK.2 */
    .jenis-container .table-rk {
        width: 100%;
        border-collapse: collapse;
        font-size: 10px;
    }

    .jenis-container .table-rk th,
    .jenis-container .table-rk td {
        padding: 8px 6px !important;
        border: 1px solid #dee2e6 !important;
        vertical-align: middle;
    }

    .jenis-container .table-rk thead th {
        background-color: #2c3e50 !important;
        color: #ffffff !important;
        font-weight: 700;
        text-transform: uppercase;
        text-align: center;
        position: sticky;
        top: 0;
        z-index: 10;
        font-size: 10px;
    }

    .jenis-container .table-rk thead th:first-child {
        width: 50px;
    }

    .jenis-container .table-rk thead th:nth-child(2) {
        text-align: left;
    }

    .jenis-container .table-rk tbody td:first-child {
        text-align: center;
        font-weight: 700;
        background-color: white;
    }

    .jenis-container .table-rk tbody td:nth-child(2) {
        font-weight: 600;
        text-transform: uppercase;
        text-align: left;
        background-color: white;
    }

    .jenis-container .table-rk tbody td:nth-child(3),
    .jenis-container .table-rk tbody td:nth-child(4),
    .jenis-container .table-rk tbody td:nth-child(5) {
        text-align: center;
    }

    /* Sticky kolom pertama */
    .jenis-container .sticky-col {
        position: sticky !important;
        left: 0;
        z-index: 5;
        background-color: white;
    }

    .jenis-container .fz-1 {
        left: 0;
        width: 50px;
    }

    .jenis-container .fz-2 {
        left: 50px;
    }

    /* Badge angka */
    .jenis-container .badge-total {
        background-color: #eef2ff;
        color: #4f46e5;
        border: 1px solid #e0e7ff;
        font-weight: 700;
        padding: 0.375rem 0.75rem;
        border-radius: 0.25rem;
        display: inline-block;
        min-width: 60px;
        font-size: 10px;
    }

    .jenis-container .badge-ecourt {
        background-color: #f0fdf4;
        color: #16a34a;
        border: 1px solid #dcfce7;
        font-weight: 700;
        padding: 0.375rem 0.75rem;
        border-radius: 0.25rem;
        display: inline-block;
        min-width: 60px;
        font-size: 10px;
    }

    .jenis-container .badge-manual {
        background-color: #fff1f2;
        color: #e11d48;
        border: 1px solid #ffe4e6;
        font-weight: 700;
        padding: 0.375rem 0.75rem;
        border-radius: 0.25rem;
        display: inline-block;
        min-width: 60px;
        font-size: 10px;
    }

    .jenis-container .text-zero {
        color: #cbd5e1;
        font-weight: 700;
        opacity: 0.5;
        font-size: 10px;
    }

    /* Footer total */
    .jenis-container .tfoot-luxury {
        background-color: #2c3e50 !important;
        color: #ffffff !important;
        font-weight: 700;
    }

    .jenis-container .tfoot-luxury td {
        padding: 8px 6px !important;
        border: 1px solid #dee2e6 !important;
        background-color: #2c3e50 !important;
        color: white !important;
        font-size: 10px;
    }

    .jenis-container .tfoot-luxury td:first-child {
        text-align: center;
    }

    .jenis-container .tfoot-luxury td:nth-child(2) {
        text-align: center;
    }

    .jenis-container .tfoot-luxury td:nth-child(3),
    .jenis-container .tfoot-luxury td:nth-child(4),
    .jenis-container .tfoot-luxury td:nth-child(5) {
        text-align: center;
    }

    /* Hover effect */
    .jenis-container .table-rk tbody tr:hover td {
        background-color: #f1f5f9 !important;
        transition: 0.2s;
    }

    /* Responsive */
    .jenis-container .table-responsive {
        position: relative;
        border-radius: 8px;
        overflow: auto;
        max-height: 70vh;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4 jenis-container">
    {{-- HEADER JUDUL (SENADA DENGAN RK.1 & RK.2) --}}
    <div class="page-header">
        <h3 class="fw-bold text-uppercase">REKAP JENIS PERKARA</h3>
        <h5 class="text-muted fw-normal">
            PENGADILAN AGAMA SE-JAWA BARAT | 
            <span class="badge bg-secondary">Periode: {{ $tgl_awal }} s/d {{ $tgl_akhir }}</span>
        </h5>
        <div class="header-line"></div>
    </div>

    {{-- FILTER CARD (SENADA DENGAN RK.1 & RK.2) --}}
    <div class="filter-card">
        <div class="card-body">
            <form action="{{ url()->current() }}" method="GET" class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted">Tgl Awal</label>
                    <input type="date" name="tgl_awal" class="form-control form-control-sm" value="{{ $tgl_awal }}" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted">Tgl Akhir</label>
                    <input type="date" name="tgl_akhir" class="form-control form-control-sm" value="{{ $tgl_akhir }}" required>
                </div>
                <div class="col-md-8 d-flex gap-2">
                    <button type="submit" class="btn-filter px-3 shadow-sm">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <a href="{{ url()->current() }}" class="btn-reset px-3 shadow-sm">
                        <i class="fas fa-undo me-1"></i> Reset
                    </a>
                    <a href="{{ route('laporan.banding.jenis.export', ['tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]) }}" class="btn-excel px-3 shadow-sm" target="_blank">
                        <i class="fas fa-file-excel me-1"></i> Excel
                    </a>
                    <button onclick="window.print()" class="btn-print px-3 shadow-sm">
                        <i class="fas fa-print me-1"></i> Cetak
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- TABLE CARD --}}
    <div class="table-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table-rk mb-0">
                    <thead>
                        <tr>
                            <th class="fz-1 sticky-col">NO</th>
                            <th class="fz-2 sticky-col" style="text-align: left">PENGADILAN AGAMA</th>
                            <th>TOTAL</th>
                            <th>E-COURT</th>
                            <th>MANUAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @forelse($results as $row)
                            @if($row->kategori != 'TOTAL SELURUH JENIS PERKARA')
                                <tr>
                                    <td class="fz-1 sticky-col">{{ $no++ }}</td>
                                    <td class="fz-2 sticky-col text-start fw-bold text-uppercase px-3">{{ $row->kategori }}</td>
                                    <td>
                                        <span class="badge-total">{{ number_format($row->total) }}</span>
                                    </td>
                                    <td>
                                        @if($row->ecourt > 0)
                                            <span class="badge-ecourt">{{ number_format($row->ecourt) }}</span>
                                        @else
                                            <span class="text-zero">0</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($row->manual > 0)
                                            <span class="badge-manual">{{ number_format($row->manual) }}</span>
                                        @else
                                            <span class="text-zero">0</span>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    Tidak ada data untuk periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @foreach($results as $row)
                        @if($row->kategori == 'TOTAL SELURUH JENIS PERKARA')
                            <tfoot class="tfoot-luxury">
                                <tr>
                                    <td class="fz-1 sticky-col" colspan="2">TOTAL KESELURUHAN</td>
                                    <td>{{ number_format($row->total) }}</td>
                                    <td>{{ number_format($row->ecourt) }}</td>
                                    <td>{{ number_format($row->manual) }}</td>
                                </tr>
                            </tfoot>
                        @endif
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
@endsection