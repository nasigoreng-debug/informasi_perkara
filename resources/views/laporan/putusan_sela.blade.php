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

    /* Card Mewah */
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

    /* Tombol Kembali Melingkar */
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

    /* Input & Badge */
    .form-control-custom {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding: 0.6rem 1rem;
        background: #fbfcfe;
    }

    .badge-soft-success {
        background: #f0fdf4;
        color: #16a34a;
        border: 1px solid #dcfce7;
        font-weight: 700;
        padding: 0.5rem 1rem;
        border-radius: 10px;
    }

    /* Efek Hover Baris */
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
                <h1 class="h3 fw-800 mb-1" style="color: #1e293b;">Data Putusan Sela</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                        <li class="breadcrumb-item"><a href="{{ route('welcome') }}" class="text-decoration-none text-muted">Panel Laporan</a></li>
                        <li class="breadcrumb-item active fw-bold" style="color: #4f46e5;">Putusan Sela</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-white border shadow-sm fw-bold px-4 py-2 rounded-pill">
                <i class="fas fa-print me-2 text-muted"></i> Cetak
            </button>
            <a href="{{ route('laporan-putus.putusan.sela.export', ['tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]) }}" class="btn btn-success shadow-sm fw-bold px-4 py-2 rounded-pill" target="_blank">
                <i class="fas fa-file-excel me-2"></i> Export Excel
            </a>
        </div>
    </div>

    <div class="card card-luxury mb-4 border-0">
        <div class="card-body p-4">
            <form action="{{ route('laporan-putus.putusan.sela') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-lg-3 col-md-4">
                    <label class="form-label fw-bold small text-muted text-uppercase">Mulai Tanggal</label>
                    <input type="date" name="tgl_awal" class="form-control form-control-custom" value="{{ $tgl_awal }}">
                </div>
                <div class="col-lg-3 col-md-4">
                    <label class="form-label fw-bold small text-muted text-uppercase">Sampai Tanggal</label>
                    <input type="date" name="tgl_akhir" class="form-control form-control-custom" value="{{ $tgl_akhir }}">
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary fw-800 flex-grow-1 py-2 shadow-sm rounded-pill" style="background: #4f46e5; border: none; height: 45px;">
                            <i class="fas fa-filter me-2"></i> TAMPILKAN
                        </button>
                        <a href="{{ route('laporan-putus.putusan.sela') }}" class="btn btn-light border d-flex align-items-center justify-content-center shadow-sm rounded-circle" style="width: 45px; height: 45px;" title="Reset Filter">
                            <i class="fas fa-undo-alt text-muted"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-2 d-none d-lg-block text-end">
                    <div class="small fw-bold text-muted text-uppercase">Total Data</div>
                    <div class="h3 fw-800 mb-0" style="color: #4f46e5;">{{ $data->count() }}</div>
                </div>
            </form>
        </div>
    </div>

    <div class="card card-luxury border-0">
        <div class="table-responsive">
            <table class="table table-luxury align-middle">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Satuan Kerja</th>
                        <th>Identitas Perkara</th>
                        <th class="text-center">Tgl. Register</th>
                        <th class="text-center">Tgl. Putusan Sela</th>
                        <th>Ketua Majelis</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $row)
                    <tr>
                        <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                        <td>
                            <div class="fw-800 text-dark">{{ strtoupper($row->nama_satker) }}</div>
                            <span class="text-muted small">Tingkat Pertama</span>
                        </td>
                        <td>
                            <div class="fw-800" style="color: #4f46e5;">{{ $row->nomor_perkara_banding }}</div>
                            <div class="text-muted small">Asal: {{ $row->nomor_perkara_pa }}</div>
                        </td>
                        <td class="text-center">
                            <span class="fw-medium text-muted">{{ date('d-m-Y', strtotime($row->tgl_register)) }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge-soft-success">
                                {{ date('d-m-Y', strtotime($row->tgl_putusan_sela)) }}
                            </span>
                        </td>
                        <td>
                            <div class="fw-bold small">{{ $row->ketua_majelis }}</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="py-4 opacity-25">
                                <i class="fas fa-folder-open fa-4x mb-3"></i>
                                <h5 class="fw-bold">Data tidak ditemukan</h5>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white border-0 py-3 text-center text-muted small">
            Dokumen ini digenerate otomatis oleh Sistem SIAPPTA - {{ date('d/m/Y H:i') }}
        </div>
    </div>
</div>
@endsection