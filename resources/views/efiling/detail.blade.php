@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('efiling.index') }}"
                            class="text-decoration-none">Rekap</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Detail Satker</li>
                </ol>
            </nav>
            <h3 class="fw-bold text-dark mb-0">
                <i class="fas fa-university text-primary me-2"></i>{{ strtoupper($satker) }}
            </h3>
            <p class="text-muted small mb-0">Daftar rincian perkara menunggu pendaftaran</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('efiling.index', ['tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]) }}"
                class="btn btn-outline-secondary btn-sm shadow-sm px-3 rounded-pill">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Rekap
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm border-start border-primary border-4">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Jumlah Item</div>
                    <div class="h4 mb-0 fw-bold text-dark">{{ count($details) }} <small
                            class="text-muted fw-normal h6">Perkara</small></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm border-start border-success border-4">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total SKUM Satker</div>
                    <div class="h4 mb-0 fw-bold text-dark">Rp
                        {{ number_format(collect($details)->sum('jumlah_skum'), 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm border-start border-warning border-4">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Periode</div>
                    <div class="h6 mb-0 fw-bold text-dark text-truncate">
                        {{ \Carbon\Carbon::parse($tgl_awal)->format('d/m/Y') }} -
                        {{ \Carbon\Carbon::parse($tgl_akhir)->format('d/m/Y') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary small text-uppercase">
                        <tr>
                            <th class="ps-4 text-center" style="width: 50px;">No</th>
                            <th class="ps-4 py-3">No. Register</th>
                            <th>Virtual Account</th>
                            <th class="text-end">Nominal</th>
                            <th>Tanggal Bayar</th>
                            <th class="text-center">Status Tunggu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($details as $d)
                        <tr>
                            <td class="ps-4 text-center text-muted small">{{ $loop->iteration }}</td>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $d->nomor_register }}</div>
                                <div class="text-xs text-muted">ID: {{ substr($d->nomor_va, -5) }}</div>
                            </td>
                            <td>
                                <span
                                    class="font-monospace text-primary bg-light px-2 py-1 rounded border">{{ $d->nomor_va }}</span>
                            </td>
                            <td class="text-end fw-bold text-dark">
                                Rp {{ number_format($d->jumlah_skum, 0, ',', '.') }}
                            </td>
                            <td>
                                <div class="small fw-bold text-secondary">
                                    {{ \Carbon\Carbon::parse($d->tanggal_bayar)->format('d M Y') }}
                                </div>
                                <div class="text-xs text-muted">
                                    {{ \Carbon\Carbon::parse($d->tanggal_bayar)->format('H:i') }} WIB
                                </div>
                            </td>
                            <td class="text-center">
                                @if ($d->lama_tunggu > 5)
                                <div
                                    class="badge bg-soft-danger text-danger px-3 py-2 rounded-pill border border-danger-subtle">
                                    <i class="fas fa-exclamation-circle me-1"></i> {{ $d->lama_tunggu }} Hari
                                </div>
                                @else
                                <div
                                    class="badge bg-soft-info text-info px-3 py-2 rounded-pill border border-info-subtle">
                                    <i class="fas fa-clock me-1"></i> {{ $d->lama_tunggu }} Hari
                                </div>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="text-muted">Data tidak ditemukan.</div>
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
    .text-xs {
        font-size: 0.75rem;
    }

    .bg-soft-danger {
        background-color: #fff5f5;
    }

    .bg-soft-info {
        background-color: #f0faff;
    }

    .breadcrumb-item+.breadcrumb-item::before {
        content: ">";
    }

    .breadcrumb {
        font-size: 0.8rem;
    }

    .table thead th {
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .font-monospace {
        font-family: 'Courier New', Courier, monospace;
        font-size: 0.9rem;
    }
</style>
@endsection