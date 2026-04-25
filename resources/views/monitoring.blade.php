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
        --slate-custom: #2c3e50;
        --maroon-custom: #d63031;
        --salem-custom: #38ada9;
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
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 12px;
        min-height: 40px;
        color: #2d3436;
        line-height: 1.3;
    }

    .card-desc {
        font-size: 0.85rem;
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

    .bg-slate-light {
        background-color: rgba(44, 62, 80, 0.1);
        color: #2c3e50;
    }

    .btn-slate-custom {
        background-color: #2c3e50;
    }

    .bg-saldo-light {
        background-color: rgba(231, 76, 60, 0.1);
        color: #e74c3c;
    }

    .btn-saldo-custom {
        background-color: #e74c3c;
    }

    .bg-coral-light {
        background-color: rgba(255, 107, 107, 0.1);
        color: #ff6b6b;
    }

    .btn-coral-custom {
        background-color: #ff6b6b;
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

    .bg-navy-light {
        background-color: rgba(30, 57, 153, 0.1);
        color: #1e3799;
    }

    .btn-navy-custom {
        background-color: #1e3799;
    }

    .bg-salem-light {
        background-color: rgba(56, 173, 169, 0.1);
        color: #38ada9;
    }

    .btn-salem-custom {
        background-color: #38ada9;
    }

    .bg-maroon-light {
        background-color: rgba(214, 48, 49, 0.1);
        color: #d63031;
    }

    .btn-maroon-custom {
        background-color: #d63031;
    }

    /* Role Badge */
    .role-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 16px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: bold;
    }

    .role-super {
        background: #dc3545;
        color: white;
    }

    .role-admin {
        background: #ffc107;
        color: #000;
    }

    .role-user {
        background: #17a2b8;
        color: white;
    }

    .role-viewer {
        background: #6c757d;
        color: white;
    }

    /* Restricted Card */
    .restricted-card {
        opacity: 0.7;
        cursor: not-allowed;
        filter: grayscale(0.2);
    }

    .restricted-card:hover {
        transform: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero-header {
            padding: 40px 0 70px;
        }

        .hero-header h1 {
            font-size: 1.75rem;
        }

        .main-container {
            margin-top: -50px;
        }

        .menu-card {
            padding: 25px 20px;
        }

        .icon-box {
            width: 55px;
            height: 55px;
            font-size: 1.4rem;
        }
    }
</style>
@endpush

@section('content')
{{-- HERO HEADER --}}
<header class="hero-header text-center">
    <div class="container px-4">
        <div class="animate__animated animate__fadeInDown">
            <div class="role-badge mb-3 
                @if(Auth::user()->isSuperAdmin()) role-super
                @elseif(Auth::user()->isAdmin()) role-admin
                @elseif(Auth::user()->isUser()) role-user
                @else role-viewer @endif">
                <i class="fas 
                    @if(Auth::user()->isSuperAdmin()) fa-crown
                    @elseif(Auth::user()->isAdmin()) fa-user-shield
                    @elseif(Auth::user()->isUser()) fa-user
                    @else fa-eye @endif me-2"></i>
                {{ Auth::user()->getRoleLabel() }}
            </div>
            <h1 class="fw-bold mb-3">Dashboard Monitoring</h1>
            <p class="opacity-75 mx-auto mb-0" style="max-width: 650px; font-size: 1rem;">
                @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                Pantau seluruh data operasional dan administrasi perkara secara real-time.
                @elseif(Auth::user()->isUser())
                Anda hanya memiliki akses untuk melihat Monitoring Kasasi.
                @elseif(Auth::user()->isViewer())
                Anda memiliki akses untuk melihat seluruh menu monitoring (Read Only).
                @endif
            </p>
        </div>
    </div>
</header>

{{-- MAIN CONTENT --}}
<main class="main-container container px-4">
    <div class="row g-4 justify-content-center">

        {{-- ==================== MENU UNTUK SUPER ADMIN & ADMIN (SEMUA MENU) ==================== --}}
        @if(Auth::user()->canSeeAllMenus())

        {{-- 1. MONITORING KASASI --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp">
            <a href="{{ route('kasasi.index') }}" class="menu-card">
                <div class="icon-box bg-primary-light"><i class="fas fa-file-signature"></i></div>
                <h5 class="card-title text-uppercase">Monitoring Kasasi</h5>
                <p class="card-desc">Informasi permohonan perkara banding yang dimohonkan kasasi se-Jawa Barat.</p>
                <div class="btn btn-primary-custom btn-sm rounded-pill w-100 mt-auto fw-bold btn-custom">Lihat Detail</div>
            </a>
        </div>

        {{-- 2. MONITORING EKSEKUSI --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.05s">
            <a href="{{ route('laporan.eksekusi.index') }}" class="menu-card">
                <div class="icon-box bg-info-light"><i class="fas fa-gavel"></i></div>
                <h5 class="card-title text-uppercase">Monitoring Eksekusi</h5>
                <p class="card-desc">Rekapitulasi data penyelesaian perkara eksekusi se-Jawa Barat.</p>
                <div class="btn btn-info-custom btn-sm rounded-pill w-100 mt-auto fw-bold btn-custom">Lihat Detail</div>
            </a>
        </div>

        {{-- 3. JADWAL SIDANG --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
            <a href="{{ route('sidang.index') }}" class="menu-card">
                <div class="icon-box bg-success-light"><i class="fas fa-calendar-check"></i></div>
                <h5 class="card-title text-uppercase">Monitoring Jadwal Sidang</h5>
                <p class="card-desc">Informasi jadwal persidangan di PTA Bandung.</p>
                <div class="btn btn-success-custom btn-sm rounded-pill w-100 mt-auto fw-bold btn-custom">Lihat Detail</div>
            </a>
        </div>

        {{-- 4. MONITORING MEDIASI --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.15s">
            <a href="{{ route('mediasi.index') }}" class="menu-card">
                <div class="icon-box bg-mint-light"><i class="fas fa-hands-helping"></i></div>
                <h5 class="card-title text-uppercase">Monitoring Mediasi</h5>
                <p class="card-desc">Rekapitulasi keberhasilan mediasi se-Jawa Barat.</p>
                <div class="btn btn-mint-custom btn-sm rounded-pill w-100 mt-auto fw-bold btn-custom">Lihat Detail</div>
            </a>
        </div>

        {{-- 5. PERKARA GUGATAN TIDAK MEDIASI --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
            <a href="{{ route('non-mediasi.gugatan') }}" class="menu-card">
                <div class="icon-box bg-slate-light"><i class="fas fa-file-exclamation"></i></div>
                <h5 class="card-title text-uppercase">Gugatan Tidak Mediasi</h5>
                <p class="card-desc">Monitoring perkara gugatan yang tidak melaksanakan proses mediasi.</p>
                <div class="btn btn-slate-custom btn-sm rounded-pill w-100 mt-auto fw-bold btn-custom">Lihat Detail</div>
            </a>
        </div>

        {{-- 6. MONITORING SALDO MINUS --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.25s">
            <a href="{{ route('saldo.minus') }}" class="menu-card">
                <div class="icon-box bg-saldo-light"><i class="fas fa-chart-line"></i></div>
                <h5 class="card-title text-uppercase">Monitoring Saldo Minus</h5>
                <p class="card-desc">Pantau perkara dengan saldo minus.</p>
                <div class="btn btn-saldo-custom btn-sm rounded-pill w-100 mt-auto fw-bold btn-custom">Lihat Detail</div>
            </a>
        </div>

        {{-- 7. SISA PANJAR --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.3s">
            <a href="{{ route('sisa.panjar.menu') }}" class="menu-card">
                <div class="icon-box bg-coral-light"><i class="fas fa-wallet"></i></div>
                <h5 class="card-title text-uppercase">Sisa Panjar</h5>
                <p class="card-desc">Transparansi pengelolaan sisa panjar biaya perkara.</p>
                <div class="btn btn-coral-custom btn-sm rounded-pill w-100 mt-auto fw-bold btn-custom">Lihat Detail</div>
            </a>
        </div>

        {{-- 8. COURT CALENDAR --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.35s">
            <a href="{{ route('court-calendar.') }}" class="menu-card">
                <div class="icon-box bg-warning-light"><i class="fas fa-tasks"></i></div>
                <h5 class="card-title text-uppercase">Court Calendar</h5>
                <p class="card-desc">Pantau kepatuhan pengisian Court Calendar pada aplikasi SIPP Satker.</p>
                <div class="btn btn-warning-custom btn-sm rounded-pill w-100 mt-auto fw-bold btn-custom">Lihat Detail</div>
            </a>
        </div>

        {{-- 9. AKTA CERAI --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.4s">
            <a href="{{ route('akta-cerai.index') }}" class="menu-card">
                <div class="icon-box bg-orange-light"><i class="fas fa-certificate"></i></div>
                <h5 class="card-title text-uppercase">Akta Cerai</h5>
                <p class="card-desc">Pantau kepatuhan penerbitan akta cerai di wilayah PTA Bandung.</p>
                <div class="btn btn-orange-custom btn-sm rounded-pill w-100 mt-auto fw-bold btn-custom">Lihat Detail</div>
            </a>
        </div>

        {{-- 10. MONITORING E-LAPORAN --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.45s">
            <a href="{{ route('monitoring.index') }}" class="menu-card">
                <div class="icon-box bg-indigo-light"><i class="fas fa-chart-line"></i></div>
                <h5 class="card-title text-uppercase">Monitoring E-Laporan</h5>
                <p class="card-desc">Pantau kepatuhan konfirmasi e-laporan se-Jawa Barat.</p>
                <div class="btn btn-indigo-custom btn-sm rounded-pill w-100 mt-auto fw-bold btn-custom">Lihat Detail</div>
            </a>
        </div>

        {{-- 11. KEDISIPLINAN USER --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.5s">
            <a href="{{ route('input.index') }}" class="menu-card">
                <div class="icon-box bg-dark-light"><i class="fas fa-user-shield"></i></div>
                <h5 class="card-title text-uppercase">Kedisiplinan User</h5>
                <p class="card-desc">Monitoring penggunaan akun Admin dalam penginputan data SIPP.</p>
                <div class="btn btn-dark-custom btn-sm rounded-pill w-100 mt-auto fw-bold btn-custom">Lihat Detail</div>
            </a>
        </div>

        {{-- 12. PERKARA TEPAT WAKTU --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.55s">
            <a href="{{ route('perkara.tepat_waktu') }}" class="menu-card">
                <div class="icon-box bg-teal-light"><i class="fas fa-stopwatch"></i></div>
                <h5 class="card-title text-uppercase">Perkara Tepat Waktu</h5>
                <p class="card-desc">Monitoring penyelesaian perkara tepat waktu.</p>
                <div class="btn btn-teal-custom btn-sm rounded-pill w-100 mt-auto fw-bold btn-custom">Lihat Detail</div>
            </a>
        </div>

        {{-- 13. KONTROL ALAMAT PIHAK --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.6s">
            <a href="https://kabayan.pta-bandung.go.id/e_laporan/control_alamat" class="menu-card" target="_blank">
                <div class="icon-box bg-pink-light"><i class="fas fa-map-marker-alt"></i></div>
                <h5 class="card-title text-uppercase">Kontrol Alamat Pihak</h5>
                <p class="card-desc">Monitoring kelengkapan data alamat pihak pada aplikasi SIPP.</p>
                <div class="btn btn-pink-custom btn-sm rounded-pill w-100 mt-auto fw-bold btn-custom">Lihat Detail</div>
            </a>
        </div>

        {{-- 14. KONTROL DATA DISPENSASI KAWIN --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.65s">
            <a href="https://kabayan.pta-bandung.go.id/e_laporan/control_dk" class="menu-card" target="_blank">
                <div class="icon-box bg-purple-light"><i class="fas fa-child"></i></div>
                <h5 class="card-title text-uppercase">Kontrol Data Dispensasi Kawin</h5>
                <p class="card-desc">Monitoring kelengkapan data perkara dispensasi kawin.</p>
                <div class="btn btn-purple-custom btn-sm rounded-pill w-100 mt-auto fw-bold btn-custom">Lihat Detail</div>
            </a>
        </div>

        {{-- 15. MONITORING AMAR PUTUSAN TIDAK LENGKAP --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.7s">
            <a href="{{ route('monitoring.amar') }}" class="menu-card">
                <div class="icon-box bg-navy-light"><i class="fas fa-exclamation-triangle"></i></div>
                <h5 class="card-title text-uppercase">Amar Putusan Tidak Lengkap</h5>
                <p class="card-desc">Pantau amar putusan yang belum lengkap.</p>
                <div class="btn btn-navy-custom btn-sm rounded-pill w-100 mt-auto fw-bold btn-custom">Lihat Detail</div>
            </a>
        </div>

        {{-- 16. MONITORING PRODEO --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.75s">
            <a href="{{ route('prodeo.index') }}" class="menu-card">
                <div class="icon-box bg-salem-light"><i class="fas fa-balance-scale"></i></div>
                <h5 class="card-title text-uppercase">Monitoring Prodeo</h5>
                <p class="card-desc">Pantau permohonan dan penetapan perkara prodeo (bantuan hukum) se-Jawa Barat.</p>
                <div class="btn btn-salem-custom btn-sm rounded-pill w-100 mt-auto fw-bold btn-custom">Lihat Detail</div>
            </a>
        </div>

        {{-- 17. MONITORING PERKARA GUGUR & DIGUGURKAN --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.8s">
            <a href="{{ route('perkara_gugur.index') }}" class="menu-card">
                <div class="icon-box bg-maroon-light"><i class="fas fa-file-signature"></i></div>
                <h5 class="card-title text-uppercase">Perkara Gugur & Digugurkan</h5>
                <p class="card-desc">Pantau statistik perkara yang diputus gugur atau digugurkan oleh majelis hakim.</p>
                <div class="btn btn-maroon-custom btn-sm rounded-pill w-100 mt-auto fw-bold btn-custom">Lihat Detail</div>
            </a>
        </div>

        {{-- ==================== MENU UNTUK USER (HANYA MONITORING KASASI) ==================== --}}
        @elseif(Auth::user()->canSeeKasasiOnly())

        <div class="col-md-8 col-lg-6 animate__animated animate__fadeInUp">
            <a href="{{ route('kasasi.index') }}" class="menu-card">
                <div class="icon-box bg-primary-light"><i class="fas fa-file-signature"></i></div>
                <h5 class="card-title text-uppercase">Monitoring Kasasi</h5>
                <p class="card-desc">Informasi permohonan perkara banding yang dimohonkan kasasi se-Jawa Barat.</p>
                <div class="btn btn-primary-custom btn-sm rounded-pill w-100 mt-auto fw-bold btn-custom">Lihat Detail</div>
            </a>
        </div>
        <div class="col-12 mt-4">
            <div class="alert alert-info border-0 rounded-4 text-center">
                <i class="fas fa-info-circle me-2"></i>
                Anda login sebagai <strong>User</strong>. Akses Anda terbatas pada menu <strong>Monitoring Kasasi</strong>.
            </div>
        </div>

        {{-- ==================== MENU UNTUK VIEWER (SEMUA MONITORING - READ ONLY) ==================== --}}
        @elseif(Auth::user()->canSeeAllMonitoring())

        {{-- 1. MONITORING KASASI --}}
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('kasasi.index') }}" class="menu-card">
                <div class="icon-box bg-primary-light"><i class="fas fa-file-signature"></i></div>
                <h5 class="card-title text-uppercase">Monitoring Kasasi</h5>
                <p class="card-desc">Informasi permohonan perkara banding yang dimohonkan kasasi se-Jawa Barat.</p>
                <div class="btn btn-secondary btn-sm rounded-pill w-100 mt-auto fw-bold"><i class="fas fa-eye me-2"></i> LIHAT (READ ONLY)</div>
            </a>
        </div>

        {{-- 2. MONITORING EKSEKUSI --}}
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('laporan.eksekusi.index') }}" class="menu-card">
                <div class="icon-box bg-info-light"><i class="fas fa-gavel"></i></div>
                <h5 class="card-title text-uppercase">Monitoring Eksekusi</h5>
                <p class="card-desc">Rekapitulasi data penyelesaian perkara eksekusi se-Jawa Barat.</p>
                <div class="btn btn-secondary btn-sm rounded-pill w-100 mt-auto fw-bold"><i class="fas fa-eye me-2"></i> LIHAT (READ ONLY)</div>
            </a>
        </div>

        {{-- 3. JADWAL SIDANG --}}
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('sidang.index') }}" class="menu-card">
                <div class="icon-box bg-success-light"><i class="fas fa-calendar-check"></i></div>
                <h5 class="card-title text-uppercase">Monitoring Jadwal Sidang</h5>
                <p class="card-desc">Informasi jadwal persidangan di PTA Bandung.</p>
                <div class="btn btn-secondary btn-sm rounded-pill w-100 mt-auto fw-bold"><i class="fas fa-eye me-2"></i> LIHAT (READ ONLY)</div>
            </a>
        </div>

        {{-- 4. MONITORING MEDIASI --}}
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('mediasi.index') }}" class="menu-card">
                <div class="icon-box bg-mint-light"><i class="fas fa-hands-helping"></i></div>
                <h5 class="card-title text-uppercase">Monitoring Mediasi</h5>
                <p class="card-desc">Rekapitulasi keberhasilan mediasi se-Jawa Barat.</p>
                <div class="btn btn-secondary btn-sm rounded-pill w-100 mt-auto fw-bold"><i class="fas fa-eye me-2"></i> LIHAT (READ ONLY)</div>
            </a>
        </div>

        {{-- 5. PERKARA GUGATAN TIDAK MEDIASI --}}
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('non-mediasi.gugatan') }}" class="menu-card">
                <div class="icon-box bg-slate-light"><i class="fas fa-file-exclamation"></i></div>
                <h5 class="card-title text-uppercase">Gugatan Tidak Mediasi</h5>
                <p class="card-desc">Monitoring perkara gugatan yang tidak melaksanakan proses mediasi.</p>
                <div class="btn btn-secondary btn-sm rounded-pill w-100 mt-auto fw-bold"><i class="fas fa-eye me-2"></i> LIHAT (READ ONLY)</div>
            </a>
        </div>

        {{-- 6. MONITORING SALDO MINUS --}}
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('saldo.minus') }}" class="menu-card">
                <div class="icon-box bg-saldo-light"><i class="fas fa-chart-line"></i></div>
                <h5 class="card-title text-uppercase">Monitoring Saldo Minus</h5>
                <p class="card-desc">Pantau perkara dengan saldo minus.</p>
                <div class="btn btn-secondary btn-sm rounded-pill w-100 mt-auto fw-bold"><i class="fas fa-eye me-2"></i> LIHAT (READ ONLY)</div>
            </a>
        </div>

        {{-- 7. SISA PANJAR --}}
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('sisa.panjar.menu') }}" class="menu-card">
                <div class="icon-box bg-coral-light"><i class="fas fa-wallet"></i></div>
                <h5 class="card-title text-uppercase">Sisa Panjar</h5>
                <p class="card-desc">Transparansi pengelolaan sisa panjar biaya perkara.</p>
                <div class="btn btn-secondary btn-sm rounded-pill w-100 mt-auto fw-bold"><i class="fas fa-eye me-2"></i> LIHAT (READ ONLY)</div>
            </a>
        </div>

        {{-- 8. COURT CALENDAR --}}
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('court-calendar.') }}" class="menu-card">
                <div class="icon-box bg-warning-light"><i class="fas fa-tasks"></i></div>
                <h5 class="card-title text-uppercase">Court Calendar</h5>
                <p class="card-desc">Pantau kepatuhan pengisian Court Calendar pada aplikasi SIPP Satker.</p>
                <div class="btn btn-secondary btn-sm rounded-pill w-100 mt-auto fw-bold"><i class="fas fa-eye me-2"></i> LIHAT (READ ONLY)</div>
            </a>
        </div>

        {{-- 9. AKTA CERAI --}}
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('akta-cerai.index') }}" class="menu-card">
                <div class="icon-box bg-orange-light"><i class="fas fa-certificate"></i></div>
                <h5 class="card-title text-uppercase">Akta Cerai</h5>
                <p class="card-desc">Pantau kepatuhan penerbitan akta cerai di wilayah PTA Bandung.</p>
                <div class="btn btn-secondary btn-sm rounded-pill w-100 mt-auto fw-bold"><i class="fas fa-eye me-2"></i> LIHAT (READ ONLY)</div>
            </a>
        </div>

        {{-- 10. MONITORING E-LAPORAN --}}
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('monitoring.index') }}" class="menu-card">
                <div class="icon-box bg-indigo-light"><i class="fas fa-chart-line"></i></div>
                <h5 class="card-title text-uppercase">Monitoring E-Laporan</h5>
                <p class="card-desc">Pantau kepatuhan konfirmasi e-laporan se-Jawa Barat.</p>
                <div class="btn btn-secondary btn-sm rounded-pill w-100 mt-auto fw-bold"><i class="fas fa-eye me-2"></i> LIHAT (READ ONLY)</div>
            </a>
        </div>

        {{-- 11. KEDISIPLINAN USER --}}
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('input.index') }}" class="menu-card">
                <div class="icon-box bg-dark-light"><i class="fas fa-user-shield"></i></div>
                <h5 class="card-title text-uppercase">Kedisiplinan User</h5>
                <p class="card-desc">Monitoring penggunaan akun Admin dalam penginputan data SIPP.</p>
                <div class="btn btn-secondary btn-sm rounded-pill w-100 mt-auto fw-bold"><i class="fas fa-eye me-2"></i> LIHAT (READ ONLY)</div>
            </a>
        </div>

        {{-- 12. PERKARA TEPAT WAKTU --}}
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('perkara.tepat_waktu') }}" class="menu-card">
                <div class="icon-box bg-teal-light"><i class="fas fa-stopwatch"></i></div>
                <h5 class="card-title text-uppercase">Perkara Tepat Waktu</h5>
                <p class="card-desc">Monitoring penyelesaian perkara tepat waktu.</p>
                <div class="btn btn-secondary btn-sm rounded-pill w-100 mt-auto fw-bold"><i class="fas fa-eye me-2"></i> LIHAT (READ ONLY)</div>
            </a>
        </div>

        {{-- 13. KONTROL ALAMAT PIHAK --}}
        <div class="col-md-6 col-lg-4">
            <a href="https://kabayan.pta-bandung.go.id/e_laporan/control_alamat" class="menu-card" target="_blank">
                <div class="icon-box bg-pink-light"><i class="fas fa-map-marker-alt"></i></div>
                <h5 class="card-title text-uppercase">Kontrol Alamat Pihak</h5>
                <p class="card-desc">Monitoring kelengkapan data alamat pihak pada aplikasi SIPP.</p>
                <div class="btn btn-secondary btn-sm rounded-pill w-100 mt-auto fw-bold"><i class="fas fa-external-link-alt me-2"></i> BUKA (READ ONLY)</div>
            </a>
        </div>

        {{-- 14. KONTROL DATA DISPENSASI KAWIN --}}
        <div class="col-md-6 col-lg-4">
            <a href="https://kabayan.pta-bandung.go.id/e_laporan/control_dk" class="menu-card" target="_blank">
                <div class="icon-box bg-purple-light"><i class="fas fa-child"></i></div>
                <h5 class="card-title text-uppercase">Kontrol Data Dispensasi Kawin</h5>
                <p class="card-desc">Monitoring kelengkapan data perkara dispensasi kawin.</p>
                <div class="btn btn-secondary btn-sm rounded-pill w-100 mt-auto fw-bold"><i class="fas fa-external-link-alt me-2"></i> BUKA (READ ONLY)</div>
            </a>
        </div>

        {{-- 15. MONITORING AMAR PUTUSAN TIDAK LENGKAP --}}
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('monitoring.amar') }}" class="menu-card">
                <div class="icon-box bg-navy-light"><i class="fas fa-exclamation-triangle"></i></div>
                <h5 class="card-title text-uppercase">Amar Putusan Tidak Lengkap</h5>
                <p class="card-desc">Pantau amar putusan yang belum lengkap.</p>
                <div class="btn btn-secondary btn-sm rounded-pill w-100 mt-auto fw-bold"><i class="fas fa-eye me-2"></i> LIHAT (READ ONLY)</div>
            </a>
        </div>

        {{-- 16. MONITORING PRODEO --}}
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('prodeo.index') }}" class="menu-card">
                <div class="icon-box bg-salem-light"><i class="fas fa-balance-scale"></i></div>
                <h5 class="card-title text-uppercase">Monitoring Prodeo</h5>
                <p class="card-desc">Pantau permohonan dan penetapan perkara prodeo (bantuan hukum) se-Jawa Barat.</p>
                <div class="btn btn-secondary btn-sm rounded-pill w-100 mt-auto fw-bold"><i class="fas fa-eye me-2"></i> LIHAT (READ ONLY)</div>
            </a>
        </div>

        {{-- 17. MONITORING PERKARA GUGUR & DIGUGURKAN --}}
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('perkara_gugur.index') }}" class="menu-card">
                <div class="icon-box bg-maroon-light"><i class="fas fa-file-signature"></i></div>
                <h5 class="card-title text-uppercase">Perkara Gugur & Digugurkan</h5>
                <p class="card-desc">Pantau statistik perkara yang diputus gugur atau digugurkan oleh majelis hakim.</p>
                <div class="btn btn-secondary btn-sm rounded-pill w-100 mt-auto fw-bold"><i class="fas fa-eye me-2"></i> LIHAT (READ ONLY)</div>
            </a>
        </div>

        <div class="col-12 mt-4">
            <div class="alert alert-secondary border-0 rounded-4 text-center">
                <i class="fas fa-eye me-2"></i>
                Anda login sebagai <strong>Viewer</strong>. Semua data ditampilkan dalam mode <strong>Read Only</strong>.
            </div>
        </div>

        @endif

    </div>
</main>
@endsection