@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4">
    <!-- Header -->
    <div class="row mb-4 align-items-center">
        <div class="col-sm-6">
            <h4 class="font-weight-bold text-navy m-0">
                <i class="fas fa-binoculars mr-2 text-primary"></i>Monitoring Akta Cerai Wilayah PTA Bandung
            </h4>
            <p class="text-muted small mb-0">Sinkronisasi Terakhir: {{ now()->format('d/m/Y H:i') }}</p>
        </div>
        <div class="col-sm-6">
            <form action="{{ route('bht.no.akta.index') }}" method="GET" class="d-flex justify-content-end align-items-end">
                <div class="mr-2 text-left">
                    <label class="small font-weight-bold mb-1">Periode:</label>
                    <div class="input-group input-group-sm">
                        <input type="date" name="tgl_awal" class="form-control" value="{{ $tgl_awal }}">
                        <div class="input-group-append">
                            <span class="input-group-text">s/d</span>
                        </div>
                        <input type="date" name="tgl_akhir" class="form-control" value="{{ $tgl_akhir }}">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-sm shadow-sm px-3">
                    <i class="fas fa-filter"></i>
                </button>
                <a href="{{ route('bht.no.akta.index') }}" class="btn btn-secondary btn-sm shadow-sm border ml-1">
                    <i class="fas fa-sync-alt"></i>
                </a>
            </form>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="row">
        <div class="col-lg-4 col-6 mb-3">
            <div class="card shadow-sm border-left-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="text-info mb-0">{{ number_format($reports->sum('jml_gugat')) }}</h3>
                            <p class="text-secondary font-weight-bold mb-0">Cerai Gugat</p>
                        </div>
                        <div class="bg-info rounded-circle p-3">
                            <i class="fas fa-user-friends text-white fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-6 mb-3">
            <div class="card shadow-sm border-left-purple">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="text-purple mb-0">{{ number_format($reports->sum('jml_talak')) }}</h3>
                            <p class="text-secondary font-weight-bold mb-0">Cerai Talak</p>
                        </div>
                        <div class="bg-purple rounded-circle p-3">
                            <i class="fas fa-user-check text-white fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-12 mb-3">
            <div class="card shadow-sm border-left-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="text-danger mb-0">{{ number_format($reports->sum('total')) }}</h3>
                            <p class="text-secondary font-weight-bold mb-0">Total AC Belum Terbit</p>
                        </div>
                        <div class="bg-danger rounded-circle p-3">
                            <i class="fas fa-exclamation-circle text-white fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Rekapitulasi -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="card-title font-weight-bold text-navy mb-0">
                <i class="fas fa-university mr-2 text-primary"></i> Rekapitulasi per Satuan Kerja
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-bordered mb-0">
                    <thead class="bg-secondary text-white">
                        <tr class="text-center">
                            <th width="60">No</th>
                            <th class="text-left">Satuan Kerja</th>
                            <th width="120">Cerai Gugat</th>
                            <th width="120">Cerai Talak</th>
                            <th width="180">Total Belum Terbit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $row)
                        <tr>
                            <td class="text-center text-secondary">{{ $loop->iteration }}</td>
                            <td class="font-weight-bold text-navy">PA {{ $row->satker }}</td>
                            <td class="text-center">
                                <a href="{{ route('bht.no.akta.detail', ['satker' => $row->satker, 'jenis' => 'Cerai Gugat', 'tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]) }}"
                                    class="btn btn-info btn-sm px-3">
                                    {{ number_format($row->jml_gugat) }}
                                </a>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('bht.no.akta.detail', ['satker' => $row->satker, 'jenis' => 'Cerai Talak', 'tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]) }}"
                                    class="btn btn-purple btn-sm px-3">
                                    {{ number_format($row->jml_talak) }}
                                </a>
                            </td>
                            <td class="text-center bg-light">
                                <a href="{{ route('bht.no.akta.detail', ['satker' => $row->satker, 'tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]) }}"
                                    class="btn btn-danger btn-sm font-weight-bold px-4 rounded-pill">
                                    {{ number_format($row->total) }} Perkara
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-5 text-center text-secondary">
                                <i class="fas fa-database mr-2"></i>Data tidak tersedia
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    /* Warna Teks */
    .text-navy {
        color: #001f3f;
    }

    .text-purple {
        color: #6f42c1;
    }

    .text-secondary {
        color: #6c757d;
    }

    /* Border Kiri Cards */
    .border-left-info {
        border-left: 4px solid #17a2b8 !important;
    }

    .border-left-purple {
        border-left: 4px solid #6f42c1 !important;
    }

    .border-left-danger {
        border-left: 4px solid #dc3545 !important;
    }

    /* Background Warna */
    .bg-purple {
        background-color: #6f42c1 !important;
    }

    .bg-secondary {
        background-color: #6c757d !important;
    }

    /* Tombol Warna */
    .btn-purple {
        background-color: #6f42c1;
        border-color: #6f42c1;
        color: white;
    }

    .btn-purple:hover {
        background-color: #5a32a3;
        border-color: #5a32a3;
        color: white;
    }

    /* Efek Tabel */
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }

    /* Tambahan */
    .fa-lg {
        font-size: 1.25rem;
    }

    .rounded-circle {
        border-radius: 50% !important;
    }
</style>
@endsection