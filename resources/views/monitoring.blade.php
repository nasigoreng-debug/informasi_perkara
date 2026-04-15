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
        --mint-custom: #00d2d3;
        --saldo-custom: #e74c3c;
        --navy-custom: #1e3799;
        --brown-custom: #8B4513;
        --gold-custom: #F39C12;
        --gray-custom: #7F8C8D;
        --olive-custom: #556B2F;
        --maroon-custom: #800000;
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

    .btn-custom {
        border: none;
        color: white !important;
        transition: all 0.3s ease;
    }

    .btn-custom:hover {
        filter: brightness(0.9);
        transform: scale(1.02);
    }

    /* Warna UNIK untuk setiap menu - TIDAK ADA YANG SAMA */
    .bg-primary-light {
        background-color: rgba(13, 110, 253, 0.1);
        color: #0d6efd;
    }

    .btn-primary-custom {
        background-color: #0d6efd;
    }

    .bg-info-light {
        background-color: rgba(13, 202, 240, 0.1);
        color: #0dcaf0;
    }

    .btn-info-custom {
        background-color: #0dcaf0;
    }

    .bg-success-light {
        background-color: rgba(25, 135, 84, 0.1);
        color: #198754;
    }

    .btn-success-custom {
        background-color: #198754;
    }

    .bg-mint-light {
        background-color: rgba(0, 210, 211, 0.1);
        color: #00d2d3;
    }

    .btn-mint-custom {
        background-color: #00d2d3;
    }

    .bg-saldo-light {
        background-color: rgba(231, 76, 60, 0.1);
        color: #e74c3c;
    }

    .btn-saldo-custom {
        background-color: #e74c3c;
    }

    .bg-danger-light {
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
    }

    .btn-danger-custom {
        background-color: #dc3545;
    }

    .bg-warning-light {
        background-color: rgba(255, 193, 7, 0.1);
        color: #ffc107;
    }

    .btn-warning-custom {
        background-color: #ffc107;
        color: #000 !important;
    }

    .bg-orange-light {
        background-color: rgba(253, 126, 20, 0.1);
        color: #fd7e14;
    }

    .btn-orange-custom {
        background-color: #fd7e14;
    }

    .bg-indigo-light {
        background-color: rgba(102, 16, 242, 0.1);
        color: #6610f2;
    }

    .btn-indigo-custom {
        background-color: #6610f2;
    }

    .bg-dark-light {
        background-color: rgba(33, 37, 41, 0.1);
        color: #212529;
    }

    .btn-dark-custom {
        background-color: #212529;
    }

    .bg-teal-light {
        background-color: rgba(0, 184, 148, 0.1);
        color: #00b894;
    }

    .btn-teal-custom {
        background-color: #00b894;
    }

    .bg-pink-light {
        background-color: rgba(232, 62, 140, 0.1);
        color: #e83e8c;
    }

    .btn-pink-custom {
        background-color: #e83e8c;
    }

    .bg-purple-light {
        background-color: rgba(111, 66, 193, 0.1);
        color: #6f42c1;
    }

    .btn-purple-custom {
        background-color: #6f42c1;
    }

    .bg-coral-light {
        background-color: rgba(255, 107, 107, 0.1);
        color: #ff6b6b;
    }

    .btn-coral-custom {
        background-color: #ff6b6b;
    }
</style>
@endpush

@section('content')
{{-- HERO HEADER --}}
<header class="hero-header text-center">
    <div class="container px-4">
        <div class="animate__animated animate__fadeInDown">
            <h1 class="fw-bold mb-3">Monitoring</h1>
            <p class="opacity-75 mx-auto mb-0" style="max-width: 650px; font-size: 1rem;">
                Pantau data operasional dan administrasi perkara secara real-time wilayah hukum PTA Bandung.
            </p>
        </div>
    </div>
</header>

{{-- MAIN CONTENT --}}
<main class="main-container container px-4">
    <div class="row g-4 justify-content-center">

        {{-- 1. MONITORING KASASI (BIRU) --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp">
            <a href="{{ route('kasasi.index') }}" class="menu-card">
                <div class="icon-box bg-primary-light">
                    <i class="fas fa-file-signature"></i>
                </div>
                <h5 class="card-title text-uppercase">Monitoring Kasasi</h5>
                <p class="card-desc">Informasi permohonan perkara banding yang dimohonkan kasasi se-Jawa Barat.</p>
                <div class="btn btn-primary-custom btn-sm rounded-pill w-100 mt-auto fw-bold btn-custom">Lihat Detail</div>
            </a>
        </div>

        @if(Auth::user()->canSeeAllData())

        {{-- 2. MONITORING EKSEKUSI (CYAN) --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
            <a href="{{ route('laporan.eksekusi.index') }}" class="menu-card">
                <div class="icon-box bg-info-light">
                    <i class="fas fa-gavel"></i>
                </div>
                <h5 class="card-title text-uppercase">Monitoring Eksekusi</h5>
                <p class="card-desc">Rekapitulasi data penyelesaian perkara eksekusi se-Jawa Barat.</p>
                <div class="btn btn-info-custom btn-sm rounded-pill w-100 mt-auto fw-bold btn-custom">Lihat Detail</div>
            </a>
        </div>

        {{-- 3. JADWAL SIDANG (HIJAU) --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.15s">
            <a href="{{ route('sidang.index') }}" class="menu-card">
                <div class="icon-box bg-success-light">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h5 class="card-title text-uppercase">Monitoring Jadwal Sidang</h5>
                <p class="card-desc">Informasi jadwal persidangan di PTA Bandung.</p>
                <div class="btn btn-success-custom btn-sm rounded-pill w-100 mt-auto fw-bold btn-custom">Lihat Detail</div>
            </a>
        </div>

        {{-- 4. MONITORING MEDIASI (MINT) --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
            <a href="{{ route('mediasi.index') }}" class="menu-card">
                <div class="icon-box bg-mint-light">
                    <i class="fas fa-hands-helping"></i>
                </div>
                <h5 class="card-title text-uppercase">Monitoring Mediasi</h5>
                <p class="card-desc">Rekapitulasi keberhasilan mediasi se-Jawa Barat.</p>
                <div class="btn btn-mint-custom btn-sm rounded-pill w-100 mt-auto fw-bold btn-custom">Lihat Detail</div>
            </a>
        </div>

        {{-- 5. MONITORING SALDO MINUS (MERAH SALDO) --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.25s">
            <a href="{{ route('saldo.minus') }}" class="menu-card">
                <div class="icon-box bg-saldo-light">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h5 class="card-title text-uppercase">Monitoring Saldo Minus</h5>
                <p class="card-desc">Pantau perkara dengan saldo minus.</p>
                <div class="btn btn-saldo-custom btn-sm rounded-pill w-100 mt-auto fw-bold btn-custom">Lihat Detail</div>
            </a>
        </div>

        {{-- 6. SISA PANJAR (MERAH DANGER) --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.3s">
            <a href="{{ route('sisa.panjar.menu') }}" class="menu-card">
                <div class="icon-box bg-danger-light">
                    <i class="fas fa-wallet"></i>
                </div>
                <h5 class="card-title text-uppercase">Sisa Panjar</h5>
                <p class="card-desc">Transparansi pengelolaan sisa panjar biaya perkara.</p>
                <div class="btn btn-danger-custom btn-sm rounded-pill w-100 mt-auto fw-bold btn-custom">Lihat Detail</div>
            </a>
        </div>

        {{-- 7. COURT CALENDAR (KUNING) --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.35s">
            <a href="{{ route('court-calendar.') }}" class="menu-card">
                <div class="icon-box bg-warning-light">
                    <i class="fas fa-tasks"></i>
                </div>
                <h5 class="card-title text-uppercase">Court Calendar</h5>
                <p class="card-desc">Pantau kepatuhan pengisian Court Calendar pada aplikasi SIPP Satker.</p>
                <div class="btn btn-warning-custom btn-sm rounded-pill w-100 mt-auto fw-bold btn-custom">Lihat Detail</div>
            </a>
        </div>

        {{-- 8. AKTA CERAI (ORANGE) --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.4s">
            <a href="{{ route('akta-cerai.index') }}" class="menu-card">
                <div class="icon-box bg-orange-light">
                    <i class="fas fa-certificate"></i>
                </div>
                <h5 class="card-title text-uppercase">Akta Cerai</h5>
                <p class="card-desc">Pantau kepatuhan penerbitan akta cerai di wilayah PTA Bandung.</p>
                <div class="btn btn-orange-custom btn-sm rounded-pill w-100 mt-auto fw-bold btn-custom">Lihat Detail</div>
            </a>
        </div>

        {{-- 9. MONITORING E-LAPORAN (UNGU/INDIGO) --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.45s">
            <a href="{{ route('monitoring.index') }}" class="menu-card">
                <div class="icon-box bg-indigo-light">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h5 class="card-title text-uppercase">Monitoring E-Laporan</h5>
                <p class="card-desc">Pantau kepatuhan konfirmasi e-laporan se-Jawa Barat.</p>
                <div class="btn btn-indigo-custom btn-sm rounded-pill w-100 mt-auto fw-bold btn-custom">Lihat Detail</div>
            </a>
        </div>

        {{-- 10. KEDISIPLINAN USER (HITAM/GELAP) --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.5s">
            <a href="{{ route('input.index') }}" class="menu-card">
                <div class="icon-box bg-dark-light">
                    <i class="fas fa-user-shield"></i>
                </div>
                <h5 class="card-title text-uppercase">Kedisiplinan User</h5>
                <p class="card-desc">Monitoring penggunaan akun Admin dalam penginputan data SIPP.</p>
                <div class="btn btn-dark-custom btn-sm rounded-pill w-100 mt-auto fw-bold btn-custom">Lihat Detail</div>
            </a>
        </div>

        {{-- 11. PERKARA TEPAT WAKTU (TEAL) --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.55s">
            <a href="{{ route('perkara.tepat_waktu') }}" class="menu-card">
                <div class="icon-box bg-teal-light">
                    <i class="fas fa-stopwatch"></i>
                </div>
                <h5 class="card-title text-uppercase">Perkara Tepat Waktu</h5>
                <p class="card-desc">Monitoring penyelesaian perkara tepat waktu.</p>
                <div class="btn btn-teal-custom btn-sm rounded-pill w-100 mt-auto fw-bold btn-custom">Lihat Detail</div>
            </a>
        </div>

        {{-- 12. KONTROL ALAMAT PIHAK (PINK) --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.6s">
            <a href="https://kabayan.pta-bandung.go.id/e_laporan/control_alamat" class="menu-card" target="_blank">
                <div class="icon-box bg-pink-light">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h5 class="card-title text-uppercase">Kontrol Alamat Pihak</h5>
                <p class="card-desc">Monitoring kelengkapan data alamat pihak pada aplikasi SIPP.</p>
                <div class="btn btn-pink-custom btn-sm rounded-pill w-100 mt-auto fw-bold btn-custom">Lihat Detail</div>
            </a>
        </div>

        {{-- 13. KONTROL DATA DISPENSASI KAWIN (PURPLE) --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.65s">
            <a href="https://kabayan.pta-bandung.go.id/e_laporan/control_dk" class="menu-card" target="_blank">
                <div class="icon-box bg-purple-light">
                    <i class="fas fa-child"></i>
                </div>
                <h5 class="card-title text-uppercase">Kontrol Data Dispensasi Kawin</h5>
                <p class="card-desc">Monitoring kelengkapan data perkara dispensasi kawin.</p>
                <div class="btn btn-purple-custom btn-sm rounded-pill w-100 mt-auto fw-bold btn-custom">Lihat Detail</div>
            </a>
        </div>

        {{-- 14. MONITORING AMAR PUTUSAN TIDAK LENGKAP (CORAL) --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.7s">
            <a href="{{ route('monitoring.amar') }}" class="menu-card">
                <div class="icon-box bg-coral-light">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h5 class="card-title text-uppercase">Amar Putusan Tidak Lengkap</h5>
                <p class="card-desc">Pantau amar putusan yang belum lengkap.</p>
                <div class="btn btn-coral-custom btn-sm rounded-pill w-100 mt-auto fw-bold btn-custom">Lihat Detail</div>
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