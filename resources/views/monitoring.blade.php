@extends('layouts.app')

@section('title', 'Panel Monitoring | PTA Bandung')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<style>
    :root {
        --primary-blue: #1a2a6c;
        --bg-light: #f4f7fa;
    }

    .hero-header {
        background: linear-gradient(135deg, #1a2a6c 0%, #2a4858 100%);
        padding: 50px 0 90px;
        color: white;
        border-bottom-left-radius: 50px;
        border-bottom-right-radius: 50px;
        margin-top: -20px;
        /* Menempel ke navbar */
    }

    .main-container {
        margin-top: -60px;
        padding-bottom: 80px;
    }

    .menu-card {
        border: none;
        border-radius: 25px;
        background: white;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        text-decoration: none !important;
        padding: 35px 25px;
        height: 100%;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .menu-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
    }

    .icon-box {
        width: 70px;
        height: 70px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        font-size: 2rem;
    }

    .card-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 12px;
        min-height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #2d3436;
    }

    .card-desc {
        font-size: 0.85rem;
        color: #636e72;
        margin-bottom: 20px;
    }
</style>
@endpush

@section('content')
{{-- HERO HEADER --}}
<header class="hero-header text-center">
    <div class="container px-4">
        <div class="animate__animated animate__fadeInDown">
            <h1 class="fw-bold mb-2">Panel Monitoring</h1>
            <p class="opacity-75 mx-auto mb-0" style="max-width: 600px; font-size: 0.95rem;">
                Pantau data operasional dan administrasi perkara secara real-time wilayah hukum PTA Bandung.
            </p>
        </div>
    </div>
</header>

{{-- MAIN CONTENT --}}
<main class="main-container container px-4">
    <div class="row g-4 justify-content-center">

        {{-- 1. MONITORING KASASI (SEMUA USER) --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp">
            <a href="{{ route('kasasi.index') }}" class="menu-card">
                <div class="icon-box bg-primary bg-opacity-10 text-primary">
                    <i class="fas fa-file-signature"></i>
                </div>
                <h5 class="card-title text-uppercase">Monitoring Kasasi</h5>
                <p class="card-desc">Informasi permohonan perkara kasasi Satker se-Jawa Barat.</p>
                <div class="btn btn-outline-primary btn-sm rounded-pill w-100 mt-auto fw-bold">Lihat Detail</div>
            </a>
        </div>

        {{-- MENU KHUSUS ADMIN & MANAGER PTA --}}
        @if(Auth::user()->canSeeAllData())

        {{-- 2. MONITORING EKSEKUSI --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
            <a href="{{ route('laporan.eksekusi.index') }}" class="menu-card">
                <div class="icon-box bg-info bg-opacity-10 text-info">
                    <i class="fas fa-gavel"></i>
                </div>
                <h5 class="card-title text-uppercase">Monitoring Eksekusi</h5>
                <p class="card-desc">Rekapitulasi data penyelesaian perkara eksekusi Satker se-Jawa Barat.</p>
                <div class="btn btn-outline-info btn-sm rounded-pill w-100 mt-auto fw-bold">Lihat Detail</div>
            </a>
        </div>

        {{-- 3. JADWAL SIDANG --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.15s">
            <a href="{{ route('sidang.index') }}" class="menu-card">
                <div class="icon-box bg-success bg-opacity-10 text-success">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h5 class="card-title text-uppercase">Jadwal Sidang</h5>
                <p class="card-desc">Informasi agenda persidangan harian di wilayah hukum PTA Bandung.</p>
                <div class="btn btn-outline-success btn-sm rounded-pill w-100 mt-auto fw-bold">Lihat Detail</div>
            </a>
        </div>

        {{-- 4. SISA PANJAR --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
            <a href="{{ route('sisa.panjar.menu') }}" class="menu-card">
                <div class="icon-box bg-danger bg-opacity-10 text-danger">
                    <i class="fas fa-wallet"></i>
                </div>
                <h5 class="card-title text-uppercase">Sisa Panjar</h5>
                <p class="card-desc">Transparansi pengelolaan sisa panjar biaya perkara wilayah PTA.</p>
                <div class="btn btn-outline-danger btn-sm rounded-pill w-100 mt-auto fw-bold">Lihat Detail</div>
            </a>
        </div>

        {{-- 5. COURT CALENDAR --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.25s">
            <a href="#" class="menu-card">
                <div class="icon-box bg-warning bg-opacity-10 text-warning">
                    <i class="fas fa-tasks"></i>
                </div>
                <h5 class="card-title text-uppercase">Court Calendar</h5>
                <p class="card-desc">Pantau kepatuhan pengisian Court Calendar pada aplikasi SIPP Satker.</p>
                <div class="btn btn-outline-warning btn-sm rounded-pill w-100 mt-auto fw-bold text-dark">Lihat Detail</div>
            </a>
        </div>

        {{-- 6. AKTA CERAI --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.3s">
            <a href="#" class="menu-card">
                <div class="icon-box bg-warning bg-opacity-10 text-warning">
                    <i class="fas fa-certificate"></i>
                </div>
                <h5 class="card-title text-uppercase">Akta Cerai</h5>
                <p class="card-desc">Pantau kepatuhan penerbitan akta cerai di wilayah PTA Bandung.</p>
                <div class="btn btn-outline-warning btn-sm rounded-pill w-100 mt-auto fw-bold text-dark">Lihat Detail</div>
            </a>
        </div>

        @else
        {{-- TAMPILAN JIKA LOGIN SEBAGAI USER BIASA (SATKER DAERAH) --}}
        <div class="col-12 animate__animated animate__fadeIn" style="animation-delay: 0.4s">
            <div class="alert alert-light border-0 shadow-sm p-4 rounded-4 text-center">
                <div class="d-flex flex-column align-items-center">
                    <i class="fas fa-lock text-muted fs-2 mb-3"></i>
                    <h6 class="fw-bold text-dark">Akses Terbatas</h6>
                    <p class="mb-0 text-muted small" style="max-width: 500px;">
                        Menu monitoring lanjutan (Eksekusi, Sidang, Sisa Panjar) hanya dapat diakses oleh Administrator PTA Bandung.
                    </p>
                </div>
            </div>
        </div>
        @endif

    </div>
</main>
@endsection