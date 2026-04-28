@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h3 class="fw-bold mb-0 text-dark">
                <i class="fas fa-layer-group text-primary me-2"></i>Monitoring E-Filing
            </h3>
            <p class="text-muted small mb-0">Antrean perkara e-court yang sudah bayar SKUM namun belum
                register nomor perkara.</p>
        </div>
        <div class="col-md-6">
            <form action="{{ route('efiling.index') }}" method="GET" class="d-flex justify-content-md-end gap-2">
                <div class="input-group input-group-sm w-auto shadow-sm">
                    <span class="input-group-text bg-white text-muted small fw-bold">PERIODE</span>
                    <input type="date" name="tgl_awal" class="form-control" value="{{ $tgl_awal }}">
                    <span class="input-group-text bg-white border-start-0 border-end-0 text-muted">s.d</span>
                    <input type="date" name="tgl_akhir" class="form-control" value="{{ $tgl_akhir }}">

                    <button type="submit" class="btn btn-primary px-3" title="Filter Data">
                        <i class="fas fa-search"></i>
                    </button>

                    <a href="{{ route('efiling.index') }}" class="btn btn-light border" title="Reset Filter">
                        <i class="fas fa-sync-alt text-secondary"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm bg-primary text-white card-stat">
                <div class="card-body d-flex justify-content-between align-items-center p-4">
                    <div>
                        <h6 class="text-uppercase small opacity-75 mb-1 fw-bold">Total Antrean Perkara</h6>
                        <h2 class="fw-bold mb-0">{{ number_format($grandTotalAntrean) }}</h2>
                    </div>
                    <i class="fas fa-copy fa-3x opacity-25"></i>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm bg-success text-white card-stat">
                <div class="card-body d-flex justify-content-between align-items-center p-4">
                    <div>
                        <h6 class="text-uppercase small opacity-75 mb-1 fw-bold">Total Nominal</h6>
                        <h2 class="fw-bold mb-0">Rp {{ number_format($grandTotalNominal, 0, ',', '.') }}</h2>
                    </div>
                    <i class="fas fa-wallet fa-3x opacity-25"></i>
                </div>
            </div>
        </div>
    </div>

    @if (count($satkerOffline) > 0)
    <div class="alert alert-danger shadow-sm border-0 mb-4 d-flex align-items-center">
        <i class="fas fa-exclamation-triangle fs-4 me-3"></i>
        <div>
            <strong class="d-block">Satker Offline/Gagal Koneksi:</strong>
            <span class="small">{{ implode(', ', $satkerOffline) }}</span>
        </div>
    </div>
    @endif

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white py-3 border-0">
            <h6 class="fw-bold text-dark mb-0">
                <i class="fas fa-table me-2 text-primary"></i>Ringkasan Pendaftaran Per Satker
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary small text-uppercase">
                        <tr>
                            <th class="ps-4 py-3">Satuan Kerja</th>
                            <th class="text-center">Jumlah Antrean</th>
                            <th class="text-end">Total Nominal</th>
                            <th class="text-center">Menunggu Terlama</th>
                            <th class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $row)
                        <tr>
                            <td class="ps-4 fw-bold text-dark">{{ $row->SATKER }}</td>
                            <td class="text-center">
                                <span
                                    class="badge rounded-pill bg-soft-primary text-primary px-3 py-2 border border-primary-subtle">
                                    {{ number_format($row->total_antrean) }} Perkara
                                </span>
                            </td>
                            <td class="text-end fw-bold text-success">
                                Rp {{ number_format($row->total_nominal, 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                @if ($row->durasi_terlama > 5)
                                <span class="badge bg-soft-danger text-danger px-3 py-2">
                                    <i class="fas fa-clock me-1"></i>{{ $row->durasi_terlama }} Hari
                                </span>
                                @else
                                <span class="badge bg-light text-muted px-3 py-2 border">
                                    {{ $row->durasi_terlama }} Hari
                                </span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <a href="{{ route('efiling.detail', ['satker' => strtolower($row->SATKER), 'tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]) }}"
                                    class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm">
                                    <i class="fas fa-search-plus me-1"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted italic">
                                <i class="fas fa-check-circle fa-3x mb-3 text-light"></i><br>
                                Tidak ada pendaftaran yang menggantung untuk periode ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                    @if ($grandTotalAntrean > 0)
                    <tfoot class="table-dark">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase font-monospace">GRAND TOTAL ({{ count($reports) }}
                                SATKER)</th>
                            <th class="text-center">{{ number_format($grandTotalAntrean) }} PERKARA</th>
                            <th class="text-end text-warning">Rp
                                {{ number_format($grandTotalNominal, 0, ',', '.') }}
                            </th>
                            <th class="text-center">-</th>
                            <th class="text-center pe-4">-</th>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    /* Card Stat Effect */
    .card-stat {
        transition: all 0.3s cubic-bezier(.25, .8, .25, 1);
        border-radius: 12px;
    }

    .card-stat:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.12) !important;
    }

    /* Table Custom Badge */
    .bg-soft-primary {
        background-color: #e7f1ff;
        color: #0d6efd;
    }

    .bg-soft-danger {
        background-color: #fceaea;
        color: #dc3545;
    }

    /* Typography */
    .table tfoot th {
        font-size: 0.9rem;
        letter-spacing: 1px;
        border-top: 2px solid #444 !important;
    }

    .table thead th {
        font-weight: 700;
        background-color: #f8f9fa;
    }

    /* Border Radius Fix */
    .input-group-text,
    .form-control {
        border-color: #dee2e6;
    }
</style>
@endsection