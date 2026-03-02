@extends('layouts.app')

@section('content')
<div class="container py-4 fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="font-weight-bold text-dark m-0">Dashboard Arsip Surat Keluar</h4>
            <p class="text-muted small mb-0 font-italic">Statistik dan aktivitas arsip digital terbaru</p>
        </div>
        <span class="badge bg-white shadow-sm px-3 py-2 rounded-pill text-primary border fw-bold">
            <i class="far fa-calendar-alt me-1"></i> {{ date('d F Y') }}
        </span>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm rounded-lg bg-gradient-primary text-white h-100 hover-elevate">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-uppercase text-xs mb-1 opacity-75 fw-bold">Total Arsip Keluar</p>
                            <h2 class="font-weight-bold mb-0">{{ $totalKeluar }}</h2>
                        </div>
                        <i class="fas fa-envelope-open-text fa-3x opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm rounded-lg bg-gradient-info text-white h-100 hover-elevate">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-uppercase text-xs mb-1 opacity-75 fw-bold">Keluar Tahun Ini</p>
                            <h2 class="font-weight-bold mb-0">{{ $keluarTahunIni }}</h2>
                        </div>
                        <i class="fas fa-calendar-alt fa-3x opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-12">
            <div class="card border-0 shadow-sm rounded-lg bg-gradient-success text-white h-100 hover-elevate">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-uppercase text-xs mb-1 opacity-75 fw-bold">Input Bulan Ini</p>
                            <h2 class="font-weight-bold mb-0">{{ $inputBulanIni }}</h2>
                        </div>
                        <i class="fas fa-calendar-check fa-3x opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-history me-2"></i> 5 Arsip Terbaru</h6>
            <a href="{{ route('surat.keluar.index') }}" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm hover-elevate">
                Lihat Semua<i class="fas fa-arrow-right ms-1 small"></i>
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0 custom-dashboard-table">
                    <thead class="bg-light text-xs text-uppercase fw-bold text-muted">
                        <tr>
                            <th class="ps-4 py-3">No. Surat & Tanggal</th>
                            <th>Tujuan</th>
                            <th>Perihal</th>
                            <th class="text-center pe-4">Status File</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentSurat as $s)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark small">{{ $s->no_surat }}</div>
                                <div class="text-xs text-muted">{{ \Carbon\Carbon::parse($s->tgl_surat)->format('d/m/Y') }}</div>
                            </td>
                            <td>
                                <div class="small fw-bold text-primary text-uppercase" style="font-size: 0.7rem;">{{ Str::limit($s->tujuan_surat, 30) }}</div>
                            </td>
                            <td>
                                <div class="small text-muted text-truncate" style="max-width: 250px;" title="{{ $s->perihal }}">
                                    {{ $s->perihal }}
                                </div>
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center gap-1">
                                    @if($s->surat_pta)
                                    <span class="badge bg-soft-danger text-danger border border-danger shadow-sm">
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </span>
                                    @endif
                                    @if($s->konsep_surat)
                                    <span class="badge bg-soft-primary text-primary border border-primary shadow-sm">
                                        <i class="fas fa-file-word"></i> WORD
                                    </span>
                                    @endif
                                    @if(!$s->surat_pta && !$s->konsep_surat)
                                    <span class="badge bg-light text-muted border small italic">Tidak Tersedia</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted small italic">
                                <i class="fas fa-folder-open fa-3x mb-3 opacity-25 text-primary"></i><br>
                                Belum ada data surat keluar yang diinput.
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
    /* Gradient Warna Sultan */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    }

    .bg-gradient-info {
        background: linear-gradient(135deg, #36b9cc 0%, #258391 100%);
    }

    .bg-gradient-success {
        background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
    }

    /* Soft Colors Badges */
    .bg-soft-danger {
        background-color: rgba(220, 53, 69, 0.1);
    }

    .bg-soft-primary {
        background-color: rgba(78, 115, 223, 0.1);
    }

    .hover-elevate {
        transition: all 0.3s ease;
    }

    .hover-elevate:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.1) !important;
        cursor: pointer;
    }

    .rounded-lg {
        border-radius: 1rem;
    }

    .custom-dashboard-table th {
        font-size: 0.65rem;
        letter-spacing: 0.05em;
        border-bottom: 2px solid #f8f9fc;
    }

    .custom-dashboard-table td {
        padding-top: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #f8f9fc;
    }

    .text-xs {
        font-size: 0.75rem;
    }

    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endsection