@extends('layouts.app')

@section('title', 'Monitoring Sisa Panjar | PTA Bandung')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<style>
    :root {
        --primary-red: #b22222;
        --secondary-red: #8b0000;
        --bg-light: #f4f7fa;
    }

    .hero-header {
        background: linear-gradient(135deg, var(--primary-red) 0%, var(--secondary-red) 100%);
        padding: 50px 0 90px;
        color: white;
        border-bottom-left-radius: 50px;
        border-bottom-right-radius: 50px;
        margin-top: -20px;
        /* Menempel ke navbar */
        box-shadow: 0 10px 30px rgba(139, 0, 0, 0.2);
    }

    .main-container {
        margin-top: -60px;
        padding-bottom: 80px;
    }

    .menu-card {
        border: none;
        border-radius: 30px;
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
        position: relative;
        overflow: hidden;
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
        transition: 0.3s;
    }

    .menu-card:hover .icon-box {
        transform: scale(1.1) rotate(5deg);
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
        margin-bottom: 25px;
        line-height: 1.5;
    }

    .border-indicator {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
    }
</style>
@endpush

@section('content')
{{-- HERO HEADER --}}
<header class="hero-header text-center">
    <div class="container px-4">
        <div class="animate__animated animate__fadeInDown">
            <h1 class="fw-bold mb-2">Sisa Panjar Perkara</h1>
            <p class="opacity-75 mx-auto mb-0" style="max-width: 700px; font-size: 0.9rem;">
                Monitoring sisa panjar biaya perkara tingkat Banding, Kasasi, dan PK yang sudah putus
                dan 6 bulan sejak pemberitahuan belum PSP/Setor ke kas negara.
            </p>
        </div>
    </div>
</header>

{{-- GRID MENU --}}
<main class="main-container container px-4">
    <div class="row g-4 justify-content-center">

        {{-- 1. TINGKAT PERTAMA --}}
        <div class="col-md-6 col-lg-3 animate__animated animate__fadeInUp">
            <a href="{{ route('sisa.pertama') }}" class="menu-card">
                <div class="border-indicator bg-info"></div>
                <div class="icon-box bg-info bg-opacity-10 text-info">
                    <i class="fas fa-balance-scale"></i>
                </div>
                <h5 class="card-title text-uppercase">Sisa Panjar TK.I</h5>
                <p class="card-desc">Monitoring sisa panjar perkara tingkat pertama wilayah PTA Bandung.</p>
                <div class="btn btn-outline-info btn-sm rounded-pill w-100 mt-auto fw-bold">Buka Data</div>
            </a>
        </div>

        {{-- 2. BANDING --}}
        <div class="col-md-6 col-lg-3 animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
            <a href="{{ route('sisa.banding') }}" class="menu-card">
                <div class="border-indicator bg-primary"></div>
                <div class="icon-box bg-primary bg-opacity-10 text-primary">
                    <i class="fas fa-layer-group"></i>
                </div>
                <h5 class="card-title text-uppercase">Tingkat Banding</h5>
                <p class="card-desc">Monitoring sisa panjar biaya perkara banding wilayah PTA Bandung.</p>
                <div class="btn btn-outline-primary btn-sm rounded-pill w-100 mt-auto fw-bold">Buka Data</div>
            </a>
        </div>

        {{-- 3. KASASI --}}
        <div class="col-md-6 col-lg-3 animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
            <a href="{{ route('sisa.kasasi') }}" class="menu-card">
                <div class="border-indicator bg-success"></div>
                <div class="icon-box bg-success bg-opacity-10 text-success">
                    <i class="fas fa-gavel"></i>
                </div>
                <h5 class="card-title text-uppercase">Tingkat Kasasi</h5>
                <p class="card-desc">Pantau sisa panjar biaya perkara tingkat Kasasi secara real-time.</p>
                <div class="btn btn-outline-success btn-sm rounded-pill w-100 mt-auto fw-bold">Buka Data</div>
            </a>
        </div>

        {{-- 4. PK --}}
        <div class="col-md-6 col-lg-3 animate__animated animate__fadeInUp" style="animation-delay: 0.3s">
            <a href="{{ route('sisa.pk') }}" class="menu-card">
                <div class="border-indicator bg-danger"></div>
                <div class="icon-box bg-danger bg-opacity-10 text-danger">
                    <i class="fas fa-wallet"></i>
                </div>
                <h5 class="card-title text-uppercase">Tingkat PK</h5>
                <p class="card-desc">Informasi sisa panjar Peninjauan Kembali (PK) se-Jawa Barat.</p>
                <div class="btn btn-outline-danger btn-sm rounded-pill w-100 mt-auto fw-bold">Buka Data</div>
            </a>
        </div>

    </div>
</main>
@endsection