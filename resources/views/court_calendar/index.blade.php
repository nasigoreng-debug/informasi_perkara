@extends('layouts.app')

@section('title', 'Monitoring Court Calendar')

@section('content')
@php
// HITUNG DATA DI AWAL AGAR BISA DIPAKAI DI MANA SAJA
$grandTotal = collect($data)->sum('jumlah');
$tuntas = collect($data)->where('jumlah', 0)->count();
$tertinggi = collect($data)->sortByDesc('jumlah')->first();
@endphp

<div class="container py-4 px-4">
    <div class="row g-3 mb-4 align-items-center animate__animated animate__fadeIn">
        <div class="col-lg-6">
            <h3 class="fw-bold text-dark mb-1">MONITORING COURT CALENDAR</h3>
            <p class="text-muted small mb-0">
                Periode Putus:
                <span class="badge bg-secondary px-2">{{ date('d/m/Y', strtotime($tglAwal)) }}</span> s/d
                <span class="badge bg-secondary px-2">{{ date('d/m/Y', strtotime($tglAkhir)) }}</span>
            </p>
        </div>
        <div class="col-lg-6">
            <div class="d-flex justify-content-lg-end">
                <form action="{{ route('court-calendar') }}" method="GET" class="d-flex gap-2 bg-white p-2 rounded-4 shadow-sm border">
                    <input type="date" name="tgl_awal" class="form-control form-control-sm border-0 bg-light" value="{{ $tglAwal }}">
                    <input type="date" name="tgl_akhir" class="form-control form-control-sm border-0 bg-light" value="{{ $tglAkhir }}">
                    <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3" title="Filter">
                        <i class="fas fa-filter"></i>
                    </button>
                    <a href="{{ route('court-calendar.export', ['tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhir]) }}" class="btn btn-success btn-sm rounded-pill px-3 no-loader" title="Export Excel">
                        <i class="fas fa-file-excel"></i>
                    </a>
                </form>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4 animate__animated animate__fadeIn">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 bg-primary text-white h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-25 rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-file-invoice fa-2x"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 opacity-75 small fw-bold text-uppercase">TOTAL BELUM INPUT</h6>
                            <h2 class="fw-bold mb-0">{{ number_format($grandTotal, 0, ',', '.') }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 bg-danger text-white h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-25 rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                        <div class="overflow-hidden">
                            <h6 class="mb-1 opacity-75 small fw-bold text-uppercase text-truncate">TUNGGAKAN TERTINGGI</h6>
                            <h4 class="fw-bold mb-0 text-truncate">{{ $tertinggi->satker ?? '-' }}</h4>
                            <small class="fw-bold text-white-50">{{ number_format($tertinggi->jumlah ?? 0, 0, ',', '.') }} Perkara</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 bg-success text-white h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-25 rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-check-double fa-2x"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 opacity-75 small fw-bold text-uppercase">SATKER TUNTAS</h6>
                            <h2 class="fw-bold mb-0">{{ $tuntas }}</h2>
                            <small class="fw-bold text-white-50">Dari {{ count($data) }} Satuan Kerja</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden animate__animated animate__fadeInUp">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-dark text-white">
                    <tr class="small fw-bold text-uppercase">
                        <th class="ps-4 py-3 text-center" width="60">No</th>
                        <th class="py-3">Satuan Kerja</th>
                        <th class="py-3 text-center">Belum Input</th>
                        <th class="py-3 text-center" width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $row)
                    <tr>
                        <td class="ps-4 text-center text-muted small">{{ $index + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-light text-primary rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                    <i class="fas fa-balance-scale fa-xs"></i>
                                </div>
                                <span class="fw-bold text-dark text-uppercase">{{ $row->satker }}</span>
                            </div>
                        </td>
                        <td class="text-center">
                            @if($row->jumlah > 0)
                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2 rounded-pill fs-6 fw-bold">
                                {{ number_format($row->jumlah, 0, ',', '.') }}
                            </span>
                            @else
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill fs-6 fw-bold">
                                <i class="fas fa-check-circle"></i> TUNTAS
                            </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('court-calendar.detail', ['satker' => $row->satker, 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhir]) }}" class="btn btn-light btn-sm rounded-pill px-3 shadow-sm border fw-bold">
                                <i class="fas fa-search me-1"></i> DETAIL
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 italic text-muted">Data Kosong.</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-light fw-bold border-top">
                    <tr>
                        <td colspan="2" class="ps-4 py-3 text-dark">TOTAL SELURUH WILAYAH</td>
                        <td class="text-center py-3">
                            <span class="badge bg-dark rounded-pill px-4 py-2 fs-6 shadow-sm">
                                {{ number_format($grandTotal, 0, ',', '.') }} Perkara
                            </span>
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection