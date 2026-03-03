@extends('layouts.app')

@section('title', 'Panel Monitoring & Arsip | PTA Bandung')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<style>
    :root {
        --primary-dark: #0f2027;
        --bg-light: #f8fafc;
        --gold-mewah: #d4af37;
        --indigo-mewah: #4f46e5;
    }

    .hero-header {
        background: linear-gradient(135deg, var(--primary-dark) 0%, #203a43 50%, #2c5364 100%);
        padding: 60px 0 120px;
        color: white;
        border-bottom-left-radius: 60px;
        border-bottom-right-radius: 60px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        margin-top: -20px;
    }

    .main-container {
        margin-top: -80px;
        padding-bottom: 80px;
    }

    .section-title {
        font-weight: 800;
        color: #1e293b;
        letter-spacing: 1px;
        border-left: 5px solid var(--gold-mewah);
        padding-left: 15px;
        margin-bottom: 25px;
        margin-top: 40px;
        text-transform: uppercase;
        font-size: 1.1rem;
    }

    /* Grid 4 Kolom untuk efisiensi menu banyak */
    .luxury-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
    }

    @media (max-width: 1200px) {
        .luxury-grid {
            grid-template-columns: repeat(3, 1fr);
        }
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
        border-radius: 25px;
        background: white;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        text-decoration: none !important;
        padding: 25px 20px;
        height: 100%;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.03);
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .menu-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        background: #fff;
    }

    .icon-box {
        width: 65px;
        height: 65px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
        font-size: 1.8rem;
        transition: 0.3s;
    }

    .menu-card:hover .icon-box {
        transform: scale(1.1) rotate(5deg);
    }

    .card-title {
        font-size: 1rem;
        font-weight: 800;
        color: #334155;
        margin-bottom: 8px;
        line-height: 1.3;
    }

    .card-desc {
        font-size: 0.75rem;
        color: #64748b;
        line-height: 1.4;
        margin-bottom: 0;
    }

    .badge-status {
        position: absolute;
        top: 15px;
        right: 15px;
        font-size: 0.65rem;
        font-weight: 700;
    }
</style>
@endpush

@section('content')
<header class="hero-header text-center">
    <div class="container px-4">
        <div class="animate__animated animate__fadeInDown">
            <h1 class="display-6 fw-800 mb-2">Pusat Data & Arsip Digital</h1>
            <p class="opacity-75 mx-auto mb-0" style="max-width: 700px; font-size: 1rem;">
                Sistem integrasi arsip perkara, produk hukum, dan administrasi persuratan PTA Bandung.
            </p>
        </div>
    </div>
</header>

<main class="main-container container px-4">
    <br><br><br>

    {{-- SECTION 1: ARSIP & PERKARA --}}
    <div class="section-title animate__animated animate__fadeInLeft">Arsip & Manajemen Perkara</div>
    <div class="luxury-grid">
        <a href="#" class="menu-card animate__animated animate__zoomIn">
            <div class="icon-box bg-primary bg-opacity-10 text-primary"><i class="fas fa-archive"></i></div>
            <h5 class="card-title">Arsip Perkara<br><small class="text-muted">1986 - 2018</small></h5>
            <p class="card-desc">Database arsip perkara lama versi digital.</p>
        </a>

        <a href="#" class="menu-card animate__animated animate__zoomIn" style="animation-delay: 0.1s">
            <div class="icon-box bg-success bg-opacity-10 text-success"><i class="fas fa-box-open"></i></div>
            <h5 class="card-title">Arsip Perkara<br><small class="text-muted">2019 - 2026</small></h5>
            <p class="card-desc">Arsip perkara aktif dan terbaru dalam sistem.</p>
        </a>

        <a href="#" class="menu-card animate__animated animate__zoomIn" style="animation-delay: 0.2s">
            <div class="icon-box bg-info bg-opacity-10 text-info"><i class="fas fa-university"></i></div>
            <h5 class="card-title">Bank Putusan</h5>
            <p class="card-desc">Kumpulan salinan putusan yang telah berkekuatan hukum.</p>
        </a>

        <a href="#" class="menu-card animate__animated animate__zoomIn" style="animation-delay: 0.4s">
            <div class="icon-box bg-warning bg-opacity-10 text-warning"><i class="fas fa-user-clock"></i></div>
            <h5 class="card-title">Peminjam Berkas</h5>
            <p class="card-desc">Log data peminjaman berkas perkara oleh Hakim/PP.</p>
        </a>
    </div>

    {{-- SECTION 2: ADMINISTRASI & PERSURATAN --}}
    <div class="section-title animate__animated animate__fadeInLeft">Administrasi & Surat</div>
    <div class="luxury-grid">
        <a href="{{ route('surat.masuk.dashboard') }}" class="menu-card animate__animated animate__zoomIn">
            <div class="icon-box bg-primary bg-opacity-10 text-primary"><i class="fas fa-envelope-open-text"></i></div>
            <h5 class="card-title">Surat Masuk</h5>
            <p class="card-desc">Log arsip digital seluruh surat masuk.</p>
        </a>

        <a href="{{ route('surat.keluar.index') }}" class="menu-card animate__animated animate__zoomIn" style="animation-delay: 0.1s">
            <div class="icon-box bg-success bg-opacity-10 text-success"><i class="fas fa-paper-plane"></i></div>
            <h5 class="card-title">Surat Keluar</h5>
            <p class="card-desc">Log arsip digital seluruh surat keluar.</p>
        </a>

        <a href="{{ route('sk.dashboard') }}" class="menu-card animate__animated animate__zoomIn" style="animation-delay: 0.2s">
            <div class="icon-box bg-info bg-opacity-10 text-info"><i class="fas fa-file-contract"></i></div>
            <h5 class="card-title">Surat Keputusan (SK)</h5>
            <p class="card-desc">Himpunan SK Ketua/Sekretaris PTA Bandung.</p>
        </a>

        <a href="{{ route('pengaduan.dashboard') }}" class="menu-card animate__animated animate__zoomIn" style="animation-delay: 0.3s">
            <div class="icon-box bg-warning bg-opacity-10 text-warning"><i class="fas fa-bullhorn"></i></div>
            <h5 class="card-title">Pengaduan</h5>
            <p class="card-desc">Manajemen data pengaduan.</p>
        </a>

        <a href="{{ route('peraturan.index') }}" class="menu-card animate__animated animate__zoomIn" style="animation-delay: 0.4s">
            <div class="icon-box bg-danger bg-opacity-10 text-danger"><i class="fas fa-balance-scale"></i></div>
            <h5 class="card-title">Himpunan Peraturan</h5>
            <p class="card-desc">Kumpulan regulasi, SEMA, dan PERMA terbaru.</p>
        </a>
    </div>

    {{-- SECTION 3: LAPORAN --}}
    <div class="section-title animate__animated animate__fadeInLeft">Pelaporan & Kinerja</div>
    <div class="luxury-grid">
        <a href="#" class="menu-card animate__animated animate__zoomIn" style="border-bottom: 4px solid var(--gold-mewah) !important;">
            <div class="icon-box bg-opacity-10" style="background: rgba(212, 175, 55, 0.1); color: var(--gold-mewah);"><i class="fas fa-file-invoice"></i></div>
            <h5 class="card-title">Laporan Tahunan / LKjIP</h5>
            <p class="card-desc">Dokumen laporan kinerja instansi pemerintah.</p>
        </a>
    </div>

</main>
@endsection