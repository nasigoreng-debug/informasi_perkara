@extends('layouts.app')

@section('title', 'Portal Utama | PTA Bandung')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<style>
    :root {
        --primary-dark: #1a2a6c;
        --secondary-dark: #2a4858;
        --card-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
        --card-hover-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
    }

    .hero-header {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--secondary-dark) 100%);
        padding: 80px 0 140px;
        color: white;
        border-bottom-left-radius: 60px;
        border-bottom-right-radius: 60px;
        position: relative;
        overflow: hidden;
        margin-top: -20px;
        /* Menempel ke navbar */
    }

    .main-container {
        margin-top: -100px;
        padding-bottom: 60px;
    }

    .welcome-card {
        border: none;
        border-radius: 40px;
        background: #ffffff;
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        text-decoration: none !important;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 40px 30px;
        height: 100%;
        box-shadow: var(--card-shadow);
    }

    .welcome-card:hover {
        transform: translateY(-15px);
        box-shadow: var(--card-hover-shadow);
    }

    .icon-box {
        width: 90px;
        height: 90px;
        border-radius: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        font-size: 2.5rem;
        transition: all 0.5s ease;
    }

    .welcome-card:hover .icon-box {
        transform: scale(1.1) rotate(5deg);
    }

    .card-title {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--primary-dark);
        margin-bottom: 12px;
    }

    .card-desc {
        font-size: 0.85rem;
        color: #636e72;
        line-height: 1.5;
        min-height: 60px;
    }

    .badge-status {
        font-size: 0.7rem;
        padding: 6px 15px;
        border-radius: 50px;
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(5px);
    }
</style>
@endpush

@section('content')
{{-- HERO SECTION --}}
<header class="hero-header text-center">
    <div class="container px-4">
        <div class="animate__animated animate__fadeInDown">
            <span class="badge-status mb-3 d-inline-block text-uppercase fw-bold text-white">
                <i class="fas fa-shield-alt me-2 text-warning"></i>
                {{ Auth::user()->satker ? Auth::user()->satker->nama : 'PTA BANDUNG (PUSAT)' }}
            </span>
            <h1 class="display-6 fw-bold mb-3">Selamat Datang, {{ Auth::user()->name }}</h1>
            <p class="lead opacity-75 mx-auto mb-0" style="max-width: 600px; font-size: 1rem;">
                Portal integrasi data informasi perkara wilayah hukum PTA Bandung.
            </p>
        </div>
    </div>
</header>

{{-- MAIN MENU SECTION --}}
<main class="main-container container px-4">
    <div class="row justify-content-center g-4">

        {{-- 1. MENU MONITORING (Semua Role Bisa) --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp">
            <a href="{{ route('monitoring') }}" class="welcome-card">
                <div class="icon-box bg-primary bg-opacity-10 text-primary">
                    <i class="fas fa-desktop"></i>
                </div>
                <h2 class="card-title text-uppercase">Monitoring</h2>
                <p class="card-desc">Monitoring kinerja satuan kerja secara real-time Se-Wilayah PTA Bandung.</p>
                <div class="btn btn-primary w-100 py-2 mt-auto fw-bold rounded-pill">MASUK MONITORING</div>
            </a>
        </div>

        {{-- 2. MENU ADMINISTRASI (Hanya Admin & Manager) --}}
        @if(Auth::user()->canSeeAllData())
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
            <a href="{{ route('errors.under_construction') }}" class="welcome-card">
                <div class="icon-box bg-success bg-opacity-10 text-success">
                    <i class="fas fa-user-shield"></i>
                </div>
                <h2 class="card-title text-uppercase">Administrasi</h2>
                <p class="card-desc">Pengelolaan data administrasi kepaniteraan muda hukum.</p>
                <div class="btn btn-success w-100 py-2 mt-auto fw-bold rounded-pill">MASUK ADMINISTRASI</div>
            </a>
        </div>

        {{-- 3. MENU LAPORAN (Hanya Admin & Manager) --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
            <a href="{{ route('laporan-utama') }}" class="welcome-card">
                <div class="icon-box bg-warning bg-opacity-10 text-warning">
                    <i class="fas fa-file-signature"></i>
                </div>
                <h2 class="card-title text-uppercase">Laporan Utama</h2>
                <p class="card-desc">Rekapitulasi laporan perkara diterima dan diputus per periode.</p>
                <div class="btn btn-warning w-100 py-2 mt-auto fw-bold rounded-pill text-dark">MASUK LAPORAN</div>
            </a>
        </div>
        @endif

        {{-- 4. MENU KHUSUS ADMINISTRATOR (Setting User) --}}
        @if(Auth::user()->isAdmin())
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.3s">
            <a href="{{ route('users.index') }}" class="welcome-card">
                <div class="icon-box bg-dark bg-opacity-10 text-dark">
                    <i class="fas fa-users-cog"></i>
                </div>
                <h2 class="card-title text-uppercase">Pengaturan User</h2>
                <p class="card-desc">Manajemen pengguna, hak akses role, dan konfigurasi sistem.</p>
                <div class="btn btn-dark w-100 py-2 mt-auto fw-bold rounded-pill">KELOLA USER</div>
            </a>
        </div>
        @endif

        {{-- INFO UNTUK USER BIASA (MEMBER) --}}
        @if(Auth::user()->role_id == 3)
        <div class="col-lg-8 animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
            <div class="alert alert-info border-0 shadow-sm p-4 rounded-5 bg-white border-start border-primary border-5">
                <div class="d-flex gap-3 align-items-center">
                    <i class="fas fa-info-circle fs-2 text-primary"></i>
                    <div>
                        <h6 class="fw-bold mb-1 text-dark">Akses Terbatas Satker Daerah</h6>
                        <p class="mb-0 text-muted small">
                            Anda masuk sebagai perwakilan Satker. Menu Administrasi & Laporan dikunci oleh Admin PTA. Silakan gunakan menu Monitoring untuk melihat data perkara Anda.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>
</main>
@endsection