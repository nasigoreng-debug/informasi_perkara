@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-0 text-uppercase">Monitoring Sisa Panjar</h3>
            <p class="text-muted small mb-0 font-italic">Pelacakan Biaya Perkara yang Belum Dikembalikan > 6 Bulan</p>
        </div>
        <a href="{{ url('/monitoring') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 shadow-sm bg-white fw-bold">
            <i class="fas fa-th-large me-1"></i> DASHBOARD MONITORING
        </a>
    </div>

    <div class="row g-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 animate__animated animate__fadeInUp">
                <div class="card-body p-4 text-center">
                    <div class="bg-indigo bg-opacity-10 text-indigo rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 70px; height: 70px; background-color: #6610f215; color: #6610f2;">
                        <i class="fas fa-balance-scale fa-2x"></i>
                    </div>
                    <h6 class="fw-bold text-muted text-uppercase small">Tingkat Pertama</h6>
                    <h4 class="fw-bold text-dark mb-3">Rp {{ number_format($totalPertama ?? 0, 0, ',', '.') }}</h4>
                    <a href="{{ route('sisa.panjar.pertama') }}" class="btn btn-primary w-100 rounded-pill fw-bold">
                        LIHAT REKAP <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
                <div class="card-body p-4 text-center">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                        <i class="fas fa-university fa-2x"></i>
                    </div>
                    <h6 class="fw-bold text-muted text-uppercase small">Tingkat Banding</h6>
                    <h4 class="fw-bold text-dark mb-3">Rp {{ number_format($totalBanding ?? 0, 0, ',', '.') }}</h4>
                    <a href="{{ route('sisa.panjar.banding') }}" class="btn btn-primary w-100 rounded-pill fw-bold">
                        LIHAT REKAP <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
                <div class="card-body p-4 text-center">
                    <div class="bg-success bg-opacity-10 text-success rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                        <i class="fas fa-gavel fa-2x"></i>
                    </div>
                    <h6 class="fw-bold text-muted text-uppercase small">Tingkat Kasasi</h6>
                    <h4 class="fw-bold text-dark mb-3">Rp {{ number_format($totalKasasi ?? 0, 0, ',', '.') }}</h4>
                    <a href="{{ route('sisa.panjar.kasasi') }}" class="btn btn-success w-100 rounded-pill fw-bold">
                        LIHAT REKAP <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 animate__animated animate__fadeInUp" style="animation-delay: 0.3s">
                <div class="card-body p-4 text-center">
                    <div class="bg-danger bg-opacity-10 text-danger rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                        <i class="fas fa-chart-pie fa-2x"></i>
                    </div>
                    <h6 class="fw-bold text-muted text-uppercase small">Tingkat PK</h6>
                    <h4 class="fw-bold text-dark mb-3">Rp {{ number_format($totalPK ?? 0, 0, ',', '.') }}</h4>
                    <a href="{{ route('sisa.panjar.pk') }}" class="btn btn-danger w-100 rounded-pill fw-bold">
                        LIHAT REKAP <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection