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

    .role-badge-super {
        background: linear-gradient(135deg, #dc3545, #c82333);
        padding: 8px 20px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: bold;
        display: inline-block;
    }

    .role-badge-admin {
        background: linear-gradient(135deg, #ffc107, #e0a800);
        padding: 8px 20px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: bold;
        display: inline-block;
        color: #000;
    }

    .role-badge-user {
        background: linear-gradient(135deg, #17a2b8, #138496);
        padding: 8px 20px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: bold;
        display: inline-block;
    }

    .role-badge-viewer {
        background: linear-gradient(135deg, #6c757d, #5a6268);
        padding: 8px 20px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: bold;
        display: inline-block;
    }

    .info-card {
        border-radius: 20px;
        background: #f8f9fa;
        border-left: 4px solid;
        transition: all 0.3s ease;
    }

    .info-card:hover {
        transform: translateX(5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .disabled-card {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .disabled-card:hover {
        transform: none;
        box-shadow: var(--card-shadow);
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

            {{-- Role Badge - Menampilkan level akses user --}}
            <div class="mb-3">
                @if(Auth::user()->isSuperAdmin())
                <div class="role-badge-super">
                    <i class="fas fa-crown me-2"></i> SUPER ADMINISTRATOR - Akses Penuh
                </div>
                @elseif(Auth::user()->isAdmin())
                <div class="role-badge-admin">
                    <i class="fas fa-user-shield me-2"></i> ADMINISTRATOR - Akses Administrasi & Laporan
                </div>
                @elseif(Auth::user()->isUser())
                <div class="role-badge-user">
                    <i class="fas fa-user me-2"></i> USER - Akses Monitoring & Laporan
                </div>
                @elseif(Auth::user()->isViewer())
                <div class="role-badge-viewer">
                    <i class="fas fa-eye me-2"></i> VIEWER - Akses Baca (Read Only)
                </div>
                @endif
            </div>

            <p class="lead opacity-75 mx-auto mb-0" style="max-width: 600px; font-size: 1rem;">
                Portal integrasi data informasi perkara wilayah hukum PTA Bandung.
            </p>
        </div>
    </div>
</header>

{{-- MAIN MENU SECTION --}}
<main class="main-container container px-4">
    <div class="row justify-content-center g-4">

        {{-- 1. MENU MONITORING (Semua Role Bisa Akses - Super Admin, Admin, User, Viewer) --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp">
            <a href="{{ route('monitoring') }}" class="welcome-card">
                <div class="icon-box bg-primary bg-opacity-10 text-primary">
                    <i class="fas fa-desktop"></i>
                </div>
                <h2 class="card-title text-uppercase">Monitoring</h2>
                <p class="card-desc">Monitoring kinerja satuan kerja secara real-time Se-Wilayah PTA Bandung.</p>
                <div class="btn btn-primary w-100 py-2 mt-auto fw-bold rounded-pill">LIHAT</div>
            </a>
        </div>

        {{-- 2. MENU ADMINISTRASI (Hanya Super Admin & Admin) --}}
        @if(Auth::user()->canAccessAdministration())
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
            <a href="{{ route('administrasi') }}" class="welcome-card">
                <div class="icon-box bg-success bg-opacity-10 text-success">
                    <i class="fas fa-user-shield"></i>
                </div>
                <h2 class="card-title text-uppercase">Administrasi</h2>
                <p class="card-desc">Pengelolaan data administrasi kepaniteraan muda hukum.</p>
                <div class="btn btn-success w-100 py-2 mt-auto fw-bold rounded-pill">LIHAT</div>
            </a>
        </div>
        @endif

        {{-- 3. MENU LAPORAN (Super Admin, Admin, dan User - Viewer TIDAK Bisa) --}}
        @if(Auth::user()->canAccessReports())
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
            <a href="{{ route('laporan-utama') }}" class="welcome-card">
                <div class="icon-box bg-warning bg-opacity-10 text-warning">
                    <i class="fas fa-file-signature"></i>
                </div>
                <h2 class="card-title text-uppercase">Laporan</h2>
                <p class="card-desc">Rekapitulasi laporan perkara diterima dan diputus per periode.</p>
                <div class="btn btn-warning w-100 py-2 mt-auto fw-bold rounded-pill text-dark">LIHAT</div>
            </a>
        </div>
        @endif

        {{-- 4. MENU PENGATURAN USER (Hanya Super Admin) --}}
        @if(Auth::user()->canManageUsers())
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

        {{-- 5. MENU SYNC CONTROL (Hanya Super Admin) --}}
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.4s">
            <a href="{{ route('admin.sync.index') }}" class="welcome-card border-top border-5 border-info">
                <div class="icon-box bg-info bg-opacity-10 text-info">
                    <i class="fas fa-sync-alt"></i>
                </div>
                <h2 class="card-title text-uppercase text-info">Control Center Sync</h2>
                <p class="card-desc">Monitoring sinkronisasi data ke database SIPP 26 Satker secara real-time.</p>
                <div class="btn btn-info w-100 py-2 mt-auto fw-bold rounded-pill text-white">MONITORING DATA</div>
            </a>
        </div>
        @endif

        {{-- 6. MENU KHUSUS VIEWER - Menggunakan route monitoring saja (karena hanya bisa view) --}}
        @if(Auth::user()->isViewer())
        <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.3s">
            <div class="welcome-card disabled-card">
                <div class="icon-box bg-secondary bg-opacity-10 text-secondary">
                    <i class="fas fa-lock"></i>
                </div>
                <h2 class="card-title text-uppercase text-secondary">Akses Terbatas</h2>
                <p class="card-desc">Sebagai Viewer, Anda hanya memiliki akses baca (Read Only) pada data monitoring.</p>
                <div class="btn btn-secondary w-100 py-2 mt-auto fw-bold rounded-pill" disabled>
                    <i class="fas fa-eye me-2"></i> READ ONLY
                </div>
            </div>
        </div>
        @endif

    </div>

    {{-- INFORMASI HAK AKSES BERDASARKAN ROLE --}}
    <div class="row mt-5">
        <div class="col-12">
            <div class="info-card p-4" style="border-left-color: {{ Auth::user()->isSuperAdmin() ? '#dc3545' : (Auth::user()->isAdmin() ? '#ffc107' : (Auth::user()->isUser() ? '#17a2b8' : '#6c757d')) }}">
                <div class="d-flex align-items-start">
                    <div class="me-3">
                        @if(Auth::user()->isSuperAdmin())
                        <i class="fas fa-crown fa-2x text-danger"></i>
                        @elseif(Auth::user()->isAdmin())
                        <i class="fas fa-user-shield fa-2x text-warning"></i>
                        @elseif(Auth::user()->isUser())
                        <i class="fas fa-user fa-2x text-info"></i>
                        @else
                        <i class="fas fa-eye fa-2x text-secondary"></i>
                        @endif
                    </div>
                    <div>
                        <h6 class="mb-2 fw-bold">Hak Akses Anda Sebagai
                            @if(Auth::user()->isSuperAdmin())
                            SUPER ADMINISTRATOR
                            @elseif(Auth::user()->isAdmin())
                            ADMINISTRATOR
                            @elseif(Auth::user()->isUser())
                            USER
                            @else
                            VIEWER
                            @endif
                        </h6>
                        <ul class="mb-0 small text-muted" style="padding-left: 20px;">
                            @if(Auth::user()->isSuperAdmin())
                            <li>✅ Akses penuh ke <strong>SEMUA</strong> fitur dan menu</li>
                            <li>✅ Dapat mengelola user dan mengubah role pengguna</li>
                            <li>✅ Dapat mengakses Control Center Sync untuk monitoring database</li>
                            <li>✅ Dapat mengedit, menghapus, dan menambah data</li>
                            <li class="text-danger fw-bold">⭐ Level 1 - Super Administrator (Akses Tertinggi)</li>
                            @elseif(Auth::user()->isAdmin())
                            <li>✅ Akses ke menu <strong>Monitoring, Administrasi, dan Laporan</strong></li>
                            <li>✅ Dapat mengelola data administrasi kepaniteraan</li>
                            <li>✅ Dapat melihat laporan rekapitulasi perkara</li>
                            <li>❌ Tidak dapat mengelola user (hanya Super Admin)</li>
                            <li class="text-warning fw-bold">⭐ Level 2 - Administrator</li>
                            @elseif(Auth::user()->isUser())
                            <li>✅ Akses ke menu <strong>Monitoring dan Laporan</strong></li>
                            <li>✅ Dapat melihat data monitoring kinerja satuan kerja</li>
                            <li>❌ Tidak dapat melihat laporan perkara</li>
                            <li>❌ Tidak dapat mengakses menu Administrasi</li>
                            <li>❌ Tidak dapat mengelola user</li>
                            <li class="text-info fw-bold">⭐ Level 3 - User</li>
                            @elseif(Auth::user()->isViewer())
                            <li>✅ Akses ke menu <strong>Monitoring (Read Only)</strong></li>
                            <li>✅ Dapat melihat data perkara dan statistik</li>
                            <li>❌ <strong>TIDAK DAPAT</strong> mengedit/menghapus/menambah data</li>
                            <li>❌ Tidak dapat mengakses menu Administrasi & Laporan</li>
                            <li>❌ Tidak dapat melakukan perubahan apapun pada sistem</li>
                            <li class="text-secondary fw-bold">👁️ Level 4 - Viewer (View Only / Read Only)</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection