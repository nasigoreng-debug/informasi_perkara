@extends('layouts.app')

@section('content')
<div class="container py-4 fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="font-weight-bold text-dark m-0">Dashboard Arsip Surat Masuk</h4>
            <p class="text-muted small mb-0 font-italic text-uppercase tracking-wider">Ringkasan Data & Statistik Tahunan</p>
        </div>
        <span class="badge bg-white shadow-sm px-3 py-2 rounded-pill text-primary border">
            <i class="far fa-calendar-alt me-1"></i> {{ date('d F Y') }}
        </span>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm rounded-lg overflow-hidden hover-elevate bg-gradient-primary text-white h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-uppercase small mb-1 opacity-75 fw-bold">Total Seluruh Arsip</p>
                            <h2 class="font-weight-bold mb-0">{{ $totalSurat }}</h2>
                        </div>
                        <i class="fas fa-database fa-3x opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm rounded-lg overflow-hidden hover-elevate bg-gradient-info text-white h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-uppercase small mb-1 opacity-75 fw-bold">Surat Masuk Tahun Ini</p>
                            <h2 class="font-weight-bold mb-0">{{ $suratTahunIni }}</h2>
                        </div>
                        <i class="fas fa-calendar-alt fa-3x opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-12">
            <div class="card border-0 shadow-sm rounded-lg overflow-hidden hover-elevate bg-gradient-success text-white h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-uppercase small mb-1 opacity-75 fw-bold">Input Bulan Ini</p>
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
            <a href="{{ route('surat.masuk.index') }}" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm">Lihat Semua<i class="fas fa-arrow-right ms-1 small"></i></a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0 custom-table">
                    <thead class="bg-light text-xs text-uppercase fw-bold text-muted">
                        <tr>
                            <th class="ps-4 py-3">No. Surat / Tanggal</th>
                            <th>Asal Surat</th>
                            <th>Perihal</th>
                            <th class="text-center pe-4">Status File</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentSurat as $s)
                        <tr>
                            <td class="ps-4">
                                <div class="font-weight-bold text-dark small">{{ $s->no_surat }}</div>
                                <div class="text-xs text-muted">{{ \Carbon\Carbon::parse($s->tgl_surat)->format('d/m/Y') }}</div>
                            </td>
                            <td class="small fw-bold text-primary">{{ Str::limit($s->asal_surat, 30) }}</td>
                            <td class="small text-muted text-truncate" style="max-width: 250px;">
                                {{ Str::limit($s->perihal, 50) }}
                            </td>
                            <td class="text-center pe-4">
                                @if($s->lampiran)
                                <span class="badge bg-soft-success text-success border border-success px-2 py-1 small">
                                    <i class="fas fa-check-circle me-1"></i> Tersedia
                                </span>
                                @else
                                <span class="badge bg-soft-secondary text-muted border px-2 py-1 small">Kosong</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted small italic">Belum ada data terbaru.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    }

    .bg-gradient-info {
        background: linear-gradient(135deg, #36b9cc 0%, #258391 100%);
    }

    .bg-gradient-success {
        background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
    }

    .bg-soft-success {
        background-color: rgba(40, 167, 69, 0.1);
    }

    .bg-soft-secondary {
        background-color: #f8f9fc;
    }

    .hover-elevate {
        transition: all 0.3s ease-in-out;
    }

    .hover-elevate:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.1) !important;
    }

    .rounded-lg {
        border-radius: 1rem;
    }

    .custom-table th {
        font-size: 0.65rem;
        letter-spacing: 0.05em;
    }

    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }
</style>
@endsection