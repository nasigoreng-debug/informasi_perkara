@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: #f4f7fa;
    }

    .page-heading {
        padding: 2rem 0;
    }

    /* Card Luxury DNA */
    .card-luxury {
        background: #ffffff;
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
        overflow: hidden;
    }

    /* Tabel Presisi */
    .table-luxury thead th {
        background: #f8fafc;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        font-weight: 800;
        color: #64748b;
        padding: 1.2rem 1.5rem;
        border-bottom: 2px solid #f1f5f9;
    }

    .table-luxury tbody td {
        padding: 1.2rem 1.5rem;
        vertical-align: middle;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.875rem;
    }

    /* Tombol Kembali Melingkar DNA */
    .btn-back {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        border: 1px solid #e2e8f0;
        color: #4f46e5;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transition: all 0.3s;
        text-decoration: none;
    }

    .btn-back:hover {
        background: #4f46e5;
        color: white;
        transform: translateX(-5px);
    }

    /* Soft Badge DNA */
    .link-aesthetic {
        font-weight: 800;
        padding: 0.6rem 1.2rem;
        border-radius: 12px;
        display: inline-block;
        transition: all 0.2s;
        min-width: 45px;
    }

    .num-total {
        background: #eef2ff;
        color: #4f46e5;
        border: 1px solid #e0e7ff;
    }

    .num-ecourt {
        background: #f0fdf4;
        color: #16a34a;
        border: 1px solid #dcfce7;
    }

    .num-manual {
        background: #fff1f2;
        color: #e11d48;
        border: 1px solid #ffe4e6;
    }

    .tfoot-luxury {
        background-color: #1e293b !important;
        color: #ffffff !important;
        font-weight: 800;
    }

    .tfoot-luxury td {
        padding: 1.5rem !important;
        border: none !important;
    }

    .text-zero {
        color: #cbd5e1;
        font-weight: 700;
        opacity: 0.5;
    }

    /* Hover effect */
    .table-luxury tbody tr:hover {
        background-color: #f8fafc;
        transition: 0.2s;
    }
</style>

<div class="container px-4">
    <div class="page-heading d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <a href="{{ route('laporan-utama') }}" class="btn-back me-3 shadow-sm" title="Kembali ke Panel Laporan">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="h3 fw-800 mb-1" style="color: #1e293b;">Rekap Jenis Perkara</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                        <li class="breadcrumb-item"><a href="{{ route('laporan-utama') }}" class="text-decoration-none text-muted">Panel</a></li>
                        <li class="breadcrumb-item active fw-bold" style="color: #4f46e5;">Statistik per Jenis</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-white border shadow-sm fw-bold px-4 py-2 rounded-pill">
                <i class="fas fa-print me-2 text-muted"></i> Cetak
            </button>
            <a href="{{ route('laporan.banding.jenis.export', ['tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]) }}" class="btn btn-success shadow-sm fw-bold px-4 py-2 rounded-pill" target="_blank">
                <i class="fas fa-file-excel me-2"></i> Export Excel
            </a>
        </div>
    </div>

    <div class="card card-luxury mb-4 border-0">
        <div class="card-body p-4">
            <form action="{{ url()->current() }}" method="GET" class="row g-3 align-items-end">
                <div class="col-lg-3">
                    <label class="small fw-bold text-muted text-uppercase mb-2 d-block">Tanggal Awal</label>
                    <input type="date" name="tgl_awal" class="form-control border-light py-2" style="border-radius: 10px;" value="{{ $tgl_awal }}">
                </div>
                <div class="col-lg-3">
                    <label class="small fw-bold text-muted text-uppercase mb-2 d-block">Tanggal Akhir</label>
                    <input type="date" name="tgl_akhir" class="form-control border-light py-2" style="border-radius: 10px;" value="{{ $tgl_akhir }}">
                </div>
                <div class="col-lg-4">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary fw-800 flex-grow-1 py-2 shadow-sm rounded-pill" style="background: #4f46e5; border: none; height: 45px;">
                            <i class="fas fa-filter me-2"></i> TAMPILKAN
                        </button>
                        <a href="{{ url()->current() }}" class="btn btn-light border d-flex align-items-center justify-content-center shadow-sm rounded-circle" style="width: 45px; height: 45px;">
                            <i class="fas fa-undo-alt text-muted"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-2 text-end d-none d-lg-block">
                    <div class="small fw-bold text-muted text-uppercase">Periode Data</div>
                    <span class="badge bg-light text-primary border rounded-pill px-3 py-2 fw-bold">
                        {{ date('d M y', strtotime($tgl_awal)) }} - {{ date('d M y', strtotime($tgl_akhir)) }}
                    </span>
                </div>
            </form>
        </div>
    </div>

    <div class="card card-luxury border-0">
        <div class="table-responsive">
            <table class="table table-luxury align-middle text-nowrap">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 60px">No</th>
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
                        <td class="text-center fw-bold text-muted">{{ $no++ }}</td>
                        <td class="fw-800 text-dark">{{ $row->kategori }}</td>
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
                <tfoot class="tfoot-luxury">
                    @foreach($results as $row)
                    @if($row->kategori == 'TOTAL SELURUH JENIS PERKARA')
                    <tr>
                        <td colspan="2" class="text-center">TOTAL KESELURUHAN JENIS PERKARA</td>
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