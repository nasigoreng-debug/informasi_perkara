@extends('layouts.app')

@section('title', 'Panel Monitoring | PTA Bandung')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<style>
    :root {
        --primary-blue: #1a2a6c;
        --bg-light: #f4f7fa;
        --indigo-custom: #6610f2;
        --teal-custom: #00b894;
        --orange-custom: #fd7e14;
        --pink-custom: #e83e8c;
        --purple-custom: #6f42c1;
        --cyan-custom: #0dcaf0;
        --coral-custom: #ff6b6b;
    }

    .hero-header {
        background: linear-gradient(135deg, var(--primary-blue) 0%, #2a4858 100%);
        padding: 60px 0 100px;
        color: white;
        border-bottom-left-radius: 50px;
        border-bottom-right-radius: 50px;
        margin-top: -20px;
    }

    .main-container {
        margin-top: -70px;
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
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .menu-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
    }

    .icon-box {
        width: 70px;
        height: 70px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 22px;
        font-size: 1.8rem;
    }

    .card-title {
        font-size: 1.05rem;
        font-weight: 700;
        margin-bottom: 12px;
        min-height: 40px;
        color: #2d3436;
        line-height: 1.3;
    }

    .card-desc {
        font-size: 0.88rem;
        color: #636e72;
        margin-bottom: 25px;
        line-height: 1.5;
    }

    /* Custom Color Classes for Icon Boxes */
    .bg-indigo-light {
        background-color: rgba(102, 16, 242, 0.1);
        color: var(--indigo-custom);
    }

    .bg-teal-light {
        background-color: rgba(0, 184, 148, 0.1);
        color: var(--teal-custom);
    }

    .bg-orange-light {
        background-color: rgba(253, 126, 20, 0.1);
        color: var(--orange-custom);
    }

    .bg-pink-light {
        background-color: rgba(232, 62, 140, 0.1);
        color: var(--pink-custom);
    }

    .bg-purple-light {
        background-color: rgba(111, 66, 193, 0.1);
        color: var(--purple-custom);
    }

    .bg-coral-light {
        background-color: rgba(255, 107, 107, 0.1);
        color: var(--coral-custom);
    }

    /* UNIFORM BUTTON STYLES */
    .btn-custom {
        border: none;
        color: white !important;
        transition: all 0.3s ease;
    }

    .btn-custom:hover {
        filter: brightness(0.9);
        transform: scale(1.02);
    }

    /* Specific Button Colors */
    .btn-indigo {
        background-color: var(--indigo-custom);
    }

    .btn-teal {
        background-color: var(--teal-custom);
    }

    .btn-orange {
        background-color: var(--orange-custom);
    }

    .btn-pink {
        background-color: var(--pink-custom);
    }

    .btn-purple {
        background-color: var(--purple-custom);
    }

    .btn-coral {
        background-color: var(--coral-custom);
    }
</style>
@endpush

@section('content')
{{-- HERO HEADER --}}
<header class="hero-header text-center">
    <div class="container px-4">
        <div class="animate__animated animate__fadeInDown">
            <h1 class="fw-bold mb-3">Panel Monitoring</h1>
            <p class="opacity-75 mx-auto mb-0" style="max-width: 650px; font-size: 1rem;">
                Pantau data operasional dan administrasi perkara secara real-time wilayah hukum PTA Bandung.
            </p>
        </div>
    </div>
</header>

{{-- MAIN CONTENT --}}
<main class="main-container container px-4">
    <div class="row g-4 justify-content-center">

        {{-- 1. MONITORING KASASI --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp">
            <a href="{{ route('kasasi.index') }}" class="menu-card">
                <div class="icon-box bg-primary bg-opacity-10 text-primary">
                    <i class="fas fa-file-signature"></i>
                </div>
                <h5 class="card-title text-uppercase">Monitoring Kasasi</h5>
                <p class="card-desc">Informasi permohonan perkara kasasi Satker se-Jawa Barat.</p>
                <div class="btn btn-primary btn-sm rounded-pill w-100 mt-auto fw-bold btn-custom">Lihat Detail</div>
            </a>
        </div>

        @if(Auth::user()->canSeeAllData())
        {{-- 2. MONITORING EKSEKUSI --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
            <a href="{{ route('laporan.eksekusi.index') }}" class="menu-card">
                <div class="icon-box bg-info bg-opacity-10 text-info">
                    <i class="fas fa-gavel"></i>
                </div>
                <h5 class="card-title text-uppercase">Monitoring Eksekusi</h5>
                <p class="card-desc">Rekapitulasi data penyelesaian perkara eksekusi Satker se-Jawa Barat.</p>
                <div class="btn btn-info btn-sm rounded-pill w-100 mt-auto fw-bold btn-custom text-white">Lihat Detail</div>
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
                <div class="btn btn-success btn-sm rounded-pill w-100 mt-auto fw-bold btn-custom">Lihat Detail</div>
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
                <div class="btn btn-danger btn-sm rounded-pill w-100 mt-auto fw-bold btn-custom">Lihat Detail</div>
            </a>
        </div>

        {{-- 5. COURT CALENDAR --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.25s">
            <a href="{{ route('court-calendar.') }}" class="menu-card">
                <div class="icon-box bg-warning bg-opacity-10 text-warning">
                    <i class="fas fa-tasks"></i>
                </div>
                <h5 class="card-title text-uppercase">Court Calendar</h5>
                <p class="card-desc">Pantau kepatuhan pengisian Court Calendar pada aplikasi SIPP Satker.</p>
                <div class="btn btn-warning btn-sm rounded-pill w-100 mt-auto fw-bold btn-custom text-dark">Lihat Detail</div>
            </a>
        </div>

        {{-- 6. AKTA CERAI --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.3s">
            <a href="{{ route('akta-cerai.index') }}" class="menu-card">
                <div class="icon-box bg-orange-light">
                    <i class="fas fa-certificate"></i>
                </div>
                <h5 class="card-title text-uppercase">Akta Cerai</h5>
                <p class="card-desc">Pantau kepatuhan penerbitan akta cerai di wilayah PTA Bandung.</p>
                <div class="btn btn-orange btn-sm rounded-pill w-100 mt-auto fw-bold btn-custom">Lihat Detail</div>
            </a>
        </div>

        {{-- 7. MONITORING E-LAPORAN --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.35s">
            <a href="{{ route('monitoring.index') }}" class="menu-card">
                <div class="icon-box bg-indigo-light">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h5 class="card-title text-uppercase">Monitoring E-Laporan</h5>
                <p class="card-desc">Pantau kedisiplinan dan rangking kepatuhan 27 LIPA Satker se-Jawa Barat.</p>
                <div class="btn btn-indigo btn-sm rounded-pill w-100 mt-auto fw-bold btn-custom">Lihat Detail</div>
            </a>
        </div>

        {{-- 8. KEDISIPLINAN USER --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.4s">
            <a href="{{ route('input.index') }}" class="menu-card">
                <div class="icon-box bg-dark bg-opacity-10 text-dark">
                    <i class="fas fa-user-shield"></i>
                </div>
                <h5 class="card-title text-uppercase">Kedisiplinan User</h5>
                <p class="card-desc">Monitoring penggunaan akun Admin vs Personal dalam penginputan data SIPP.</p>
                <div class="btn btn-dark btn-sm rounded-pill w-100 mt-auto fw-bold btn-custom">Lihat Detail</div>
            </a>
        </div>

        {{-- 9. PERKARA TEPAT WAKTU --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.45s">
            <a href="{{ route('perkara.tepat_waktu') }}" class="menu-card">
                <div class="icon-box bg-teal-light">
                    <i class="fas fa-stopwatch"></i>
                </div>
                <h5 class="card-title text-uppercase">Perkara Tepat Waktu</h5>
                <p class="card-desc">Monitoring penyelesaian perkara tepat waktu sesuai target yang ditentukan.</p>
                <div class="btn btn-teal btn-sm rounded-pill w-100 mt-auto fw-bold btn-custom">Lihat Detail</div>
            </a>
        </div>

        {{-- 10. KONTROL ALAMAT PIHAK --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.5s">
            <a href="https://kabayan.pta-bandung.go.id/e_laporan/control_alamat" class="menu-card" target="_blank">
                <div class="icon-box bg-pink-light">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h5 class="card-title text-uppercase">Kontrol Alamat Pihak</h5>
                <p class="card-desc">Monitoring kelengkapan data alamat pihak.</p>
                <div class="btn btn-pink btn-sm rounded-pill w-100 mt-auto fw-bold btn-custom">Lihat Detail</div>
            </a>
        </div>

        {{-- 11. KONTROL DATA DISPENSASI KAWIN --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.55s">
            <a href="https://kabayan.pta-bandung.go.id/e_laporan/control_dk" class="menu-card" target="_blank">
                <div class="icon-box bg-purple-light">
                    <i class="fas fa-child"></i>
                </div>
                <h5 class="card-title text-uppercase">Kontrol Data Dispensasi Kawin</h5>
                <p class="card-desc">Monitoring kelengkapan data perkara dispensasi kawin.</p>
                <div class="btn btn-purple btn-sm rounded-pill w-100 mt-auto fw-bold btn-custom">Lihat Detail</div>
            </a>
        </div>

        {{-- 12. MONITORING AMAR PUTUSAN TIDAK LENGKAP --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.6s">
            <a href="{{ route('monitoring.amar') }}" class="menu-card">
                <div class="icon-box bg-coral-light">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h5 class="card-title text-uppercase">Amar Putusan Tidak Lengkap</h5>
                <p class="card-desc">Pantau residu template atau amar putusan yang belum dilengkapi Satker.</p>
                <div class="btn btn-coral btn-sm rounded-pill w-100 mt-auto fw-bold btn-custom">Lihat Detail</div>
            </a>
        </div>

        @else
        {{-- TAMPILAN JIKA LOGIN SEBAGAI USER BIASA --}}
        <div class="col-12 animate__animated animate__fadeIn" style="animation-delay: 0.4s">
            <div class="alert alert-light border-0 shadow-sm p-5 rounded-4 text-center">
                <div class="d-flex flex-column align-items-center">
                    <i class="fas fa-lock text-muted fs-1 mb-3 opacity-50"></i>
                    <h5 class="fw-bold text-dark">Akses Terbatas</h5>
                    <p class="mb-0 text-muted" style="max-width: 500px;">
                        Menu monitoring lanjutan hanya dapat diakses oleh Administrator PTA Bandung untuk keperluan pengawasan daerah.
                    </p>
                </div>
            </div>
        </div>
        @endif

    </div>
</main>
@endsection