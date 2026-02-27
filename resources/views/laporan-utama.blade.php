@extends('layouts.app')

@section('title', 'Panel Laporan Perkara | PTA Bandung')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<style>
    :root {
        --primary-dark: #1a2a6c;
        --bg-light: #f0f4f8;
        --indigo-mewah: #4f46e5;
    }

    .hero-header {
        background: linear-gradient(135deg, var(--primary-dark) 0%, #2a4858 100%);
        padding: 60px 0 100px;
        color: white;
        border-bottom-left-radius: 60px;
        border-bottom-right-radius: 60px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        margin-top: -20px;
        /* Menempel ke navbar */
    }

    .main-container {
        margin-top: -70px;
        padding-bottom: 80px;
    }

    /* Grid 3 Kolom */
    .luxury-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
    }

    @media (max-width: 992px) {
        .luxury-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 600px) {
        .luxury-grid {
            grid-template-columns: 1fr;
        }
    }

    .menu-card {
        border: none;
        border-radius: 30px;
        background: white;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        text-decoration: none !important;
        padding: 35px 25px;
        height: 100%;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.04);
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .menu-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: transparent;
        transition: 0.3s;
    }

    .menu-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.1);
    }

    .menu-card:hover::before {
        background: currentColor;
    }

    .icon-box {
        width: 80px;
        height: 80px;
        border-radius: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        font-size: 2.5rem;
        transition: 0.3s;
    }

    .menu-card:hover .icon-box {
        transform: scale(1.1) rotate(5deg);
    }

    .card-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 10px;
    }

    .card-desc {
        font-size: 0.85rem;
        color: #64748b;
        line-height: 1.5;
        margin-bottom: 20px;
    }

    .badge-custom {
        font-size: 0.75rem;
        padding: 5px 14px;
        border-radius: 50px;
        margin-bottom: 12px;
        font-weight: 800;
        letter-spacing: 0.5px;
    }

    .btn-action {
        font-weight: 700;
        padding: 8px 20px;
        border-radius: 12px;
        transition: 0.3s;
        width: 100%;
        font-size: 0.9rem;
    }
</style>
@endpush

@section('content')
{{-- HEADER LAPORAN --}}
<header class="hero-header text-center">
    <div class="container px-4">
        <div class="animate__animated animate__fadeInDown">
            <h1 class="display-6 fw-800 mb-2">Laporan Perkara</h1>
            <p class="opacity-75 mx-auto mb-0" style="max-width: 700px; font-size: 1rem;">
                Akses rekapitulasi data statistik perkara harian wilayah hukum PTA Bandung secara transparan.
            </p>
        </div>
    </div>
</header>

{{-- GRID LAPORAN --}}
<main class="main-container container px-4">
    <div class="luxury-grid">

        {{-- RK1 --}}
        <div class="animate__animated animate__fadeInUp">
            <a href="{{ route('laporan.banding.diterima') }}" class="menu-card text-primary border-bottom border-primary border-4">
                <div class="icon-box bg-primary bg-opacity-10">
                    <i class="fas fa-file-import"></i>
                </div>
                <span class="badge-custom bg-primary text-white">RK1</span>
                <h5 class="card-title">Diterima Banding</h5>
                <p class="card-desc">Monitoring harian perkara banding yang masuk ke sistem PTA.</p>
                <div class="btn-action btn btn-outline-primary mt-auto">Buka RK1</div>
            </a>
        </div>

        {{-- RK2 --}}
        <div class="animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
            <a href="{{ route('laporan.banding.putus') }}" class="menu-card text-info border-bottom border-info border-4">
                <div class="icon-box bg-info bg-opacity-10">
                    <i class="fas fa-gavel"></i>
                </div>
                <span class="badge-custom bg-info text-white">RK2</span>
                <h5 class="card-title">Diputus Banding</h5>
                <p class="card-desc">Data rekapitulasi perkara banding yang telah selesai diputus.</p>
                <div class="btn-action btn btn-outline-info mt-auto">Buka RK2</div>
            </a>
        </div>

        {{-- RK3 --}}
        <div class="animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
            <a href="{{ route('laporan.index') }}" class="menu-card text-warning border-bottom border-warning border-4">
                <div class="icon-box bg-warning bg-opacity-10">
                    <i class="fas fa-folder-plus"></i>
                </div>
                <span class="badge-custom bg-warning text-dark">RK3</span>
                <h5 class="card-title">Diterima Satker</h5>
                <p class="card-desc">Statistik penerimaan perkara pada Pengadilan Agama (Satker).</p>
                <div class="btn-action btn btn-outline-warning mt-auto text-dark">Buka RK3</div>
            </a>
        </div>

        {{-- RK4 --}}
        <div class="animate__animated animate__fadeInUp" style="animation-delay: 0.3s">
            <a href="{{ route('laporan-putus.index') }}" class="menu-card text-danger border-bottom border-danger border-4">
                <div class="icon-box bg-danger bg-opacity-10">
                    <i class="fas fa-balance-scale"></i>
                </div>
                <span class="badge-custom bg-danger text-white">RK4</span>
                <h5 class="card-title">Diputus Satker</h5>
                <p class="card-desc">Laporan perkara yang telah diputus oleh Pengadilan Agama.</p>
                <div class="btn-action btn btn-outline-danger mt-auto">Buka RK4</div>
            </a>
        </div>

        {{-- JENIS PERKARA --}}
        <div class="animate__animated animate__fadeInUp" style="animation-delay: 0.4s">
            <a href="{{ route('laporan.banding.jenis') }}" class="menu-card text-success border-bottom border-success border-4">
                <div class="icon-box bg-success bg-opacity-10">
                    <i class="fas fa-list-ul"></i>
                </div>
                <span class="badge-custom bg-success text-white">STATISTIK</span>
                <h5 class="card-title">Jenis Perkara</h5>
                <p class="card-desc">Klasifikasi statistik perkara berdasarkan jenis sengketa.</p>
                <div class="btn-action btn btn-outline-success mt-auto">Lihat Statistik</div>
            </a>
        </div>

        {{-- PUTUSAN SELA --}}
        <div class="animate__animated animate__fadeInUp" style="animation-delay: 0.5s">
            <a href="{{ route('laporan-putus.putusan.sela') }}" class="menu-card border-bottom border-4" style="color: var(--indigo-mewah); border-color: var(--indigo-mewah) !important;">
                <div class="icon-box" style="background: rgba(79, 70, 229, 0.1);">
                    <i class="fas fa-scroll"></i>
                </div>
                <span class="badge-custom" style="background: var(--indigo-mewah); color: white;">KHUSUS</span>
                <h5 class="card-title">Putusan Sela</h5>
                <p class="card-desc">Daftar perkara banding yang memiliki putusan sela/provisi.</p>
                <div class="btn-action btn btn-outline-indigo mt-auto" style="border-color: var(--indigo-mewah); color: var(--indigo-mewah);">Buka Laporan</div>
            </a>
        </div>

    </div>
</main>
@endsection