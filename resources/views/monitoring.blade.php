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
        padding: 40px 0 50px;
        color: white;
        border-bottom-left-radius: 50px;
        border-bottom-right-radius: 50px;
        margin-top: -20px;
    }

    .main-container {
        margin-top: -40px;
        padding-bottom: 60px;
    }

    /* LIST MENU STYLES */
    .list-menu-container {
        background: white;
        border-radius: 25px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .list-menu-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 25px;
        border-bottom: 1px solid #eef2f7;
        text-decoration: none;
        transition: all 0.3s ease;
        background: white;
    }

    .list-menu-item:last-child {
        border-bottom: none;
    }

    .list-menu-item:hover {
        background-color: #f8fafd;
        transform: translateX(5px);
    }

    .menu-info {
        display: flex;
        align-items: center;
        gap: 18px;
        flex: 1;
    }

    /* NOMOR URUT - TANPA BULAT */
    .menu-number {
        width: auto;
        min-width: 30px;
        color: #6c757d;
        font-weight: 600;
        font-size: 0.9rem;
        flex-shrink: 0;
    }

    .list-icon-box {
        width: 50px;
        height: 50px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }

    .menu-text {
        flex: 1;
    }

    .menu-title {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 5px;
        color: #2d3436;
    }

    .menu-desc {
        font-size: 0.8rem;
        color: #636e72;
        line-height: 1.4;
    }

    .btn-list {
        padding: 8px 20px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        white-space: nowrap;
    }

    .btn-list:hover {
        transform: scale(1.02);
        filter: brightness(0.9);
    }

    /* Warna Icon Box */
    .bg-primary-light {
        background-color: rgba(13, 110, 253, 0.1);
        color: #0d6efd;
    }

    .bg-info-light {
        background-color: rgba(13, 202, 240, 0.1);
        color: #0dcaf0;
    }

    .bg-success-light {
        background-color: rgba(25, 135, 84, 0.1);
        color: #198754;
    }

    .bg-mint-light {
        background-color: rgba(0, 210, 211, 0.1);
        color: #00d2d3;
    }

    .bg-slate-light {
        background-color: rgba(44, 62, 80, 0.1);
        color: #2c3e50;
    }

    .bg-saldo-light {
        background-color: rgba(231, 76, 60, 0.1);
        color: #e74c3c;
    }

    .bg-coral-light {
        background-color: rgba(255, 107, 107, 0.1);
        color: #ff6b6b;
    }

    .bg-warning-light {
        background-color: rgba(255, 193, 7, 0.1);
        color: #ffc107;
    }

    .bg-orange-light {
        background-color: rgba(253, 126, 20, 0.1);
        color: #fd7e14;
    }

    .bg-indigo-light {
        background-color: rgba(102, 16, 242, 0.1);
        color: #6610f2;
    }

    .bg-dark-light {
        background-color: rgba(33, 37, 41, 0.1);
        color: #212529;
    }

    .bg-teal-light {
        background-color: rgba(0, 184, 148, 0.1);
        color: #00b894;
    }

    .bg-pink-light {
        background-color: rgba(232, 62, 140, 0.1);
        color: #e83e8c;
    }

    .bg-purple-light {
        background-color: rgba(111, 66, 193, 0.1);
        color: #6f42c1;
    }

    .bg-navy-light {
        background-color: rgba(30, 57, 153, 0.1);
        color: #1e3799;
    }

    .bg-salem-light {
        background-color: rgba(56, 173, 169, 0.1);
        color: #38ada9;
    }

    .bg-maroon-light {
        background-color: rgba(214, 48, 49, 0.1);
        color: #d63031;
    }

    /* Warna Button */
    .btn-primary-custom {
        background-color: #0d6efd;
        color: white;
    }

    .btn-info-custom {
        background-color: #0dcaf0;
        color: white;
    }

    .btn-success-custom {
        background-color: #198754;
        color: white;
    }

    .btn-mint-custom {
        background-color: #00d2d3;
        color: white;
    }

    .btn-slate-custom {
        background-color: #2c3e50;
        color: white;
    }

    .btn-saldo-custom {
        background-color: #e74c3c;
        color: white;
    }

    .btn-coral-custom {
        background-color: #ff6b6b;
        color: white;
    }

    .btn-warning-custom {
        background-color: #ffc107;
        color: #000;
    }

    .btn-orange-custom {
        background-color: #fd7e14;
        color: white;
    }

    .btn-indigo-custom {
        background-color: #6610f2;
        color: white;
    }

    .btn-dark-custom {
        background-color: #212529;
        color: white;
    }

    .btn-teal-custom {
        background-color: #00b894;
        color: white;
    }

    .btn-pink-custom {
        background-color: #e83e8c;
        color: white;
    }

    .btn-purple-custom {
        background-color: #6f42c1;
        color: white;
    }

    .btn-navy-custom {
        background-color: #1e3799;
        color: white;
    }

    .btn-salem-custom {
        background-color: #38ada9;
        color: white;
    }

    .btn-maroon-custom {
        background-color: #d63031;
        color: white;
    }

    .btn-secondary-custom {
        background-color: #6c757d;
        color: white;
    }

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

    @media (max-width: 768px) {
        .hero-header {
            padding: 30px 0 40px;
        }

        .hero-header h1 {
            font-size: 1.5rem;
        }

        .list-menu-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .menu-info {
            width: 100%;
        }

        .btn-list {
            width: 100%;
            text-align: center;
        }
    }
</style>
@endpush

@section('content')
{{-- HERO HEADER --}}
<header class="hero-header text-center">
    <div class="container px-4">
        <div class="animate__animated animate__fadeInDown">
            <div
                class="role-badge mb-3
                    @if (Auth::user()->isSuperAdmin()) role-super
                    @elseif(Auth::user()->isAdmin()) role-admin
                    @elseif(Auth::user()->isUser()) role-user
                    @else role-viewer @endif">
                <i
                    class="fas
                        @if (Auth::user()->isSuperAdmin()) fa-crown
                        @elseif(Auth::user()->isAdmin()) fa-user-shield
                        @elseif(Auth::user()->isUser()) fa-user
                        @else fa-eye @endif me-2"></i>
                {{ Auth::user()->getRoleLabel() }}
            </div>
            <h1 class="fw-bold mb-3">Dashboard Monitoring</h1>
            <p class="opacity-75 mx-auto mb-0" style="max-width: 650px; font-size: 1rem;">
                @if (Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                Monitoring administrasi perkara seluruh Pengadilan Agama sewilayah Pengadilan Tinggi Agama Bandung
                secara real-time.
                @elseif(Auth::user()->isUser())
                Anda hanya memiliki akses untuk melihat Monitoring Kasasi.
                @elseif(Auth::user()->isViewer())
                Anda memiliki akses untuk melihat seluruh menu monitoring (Read Only).
                @endif
            </p>
        </div>
    </div>
</header>

{{-- MAIN CONTENT - LIST VIEW WITH NUMBER (TANPA BULAT) --}}
<main class="main-container container px-4">
    <div class="list-menu-container animate__animated animate__fadeInUp">

        {{-- ==================== MENU UNTUK SUPER ADMIN & ADMIN ==================== --}}
        @if (Auth::user()->canSeeAllMenus())
        @php $no = 1; @endphp

        <a href="{{ route('kasasi.index') }}" class="list-menu-item">
            <div class="menu-info">
                <div class="menu-number">{{ $no++ }}.</div>
                <div class="list-icon-box bg-primary-light"><i class="fas fa-file-signature"></i></div>
                <div class="menu-text">
                    <div class="menu-title"> Kasasi</div>
                    <div class="menu-desc">Monitoring permohonan perkara banding yang dimohonkan kasasi se-Jawa
                        Barat.</div>
                </div>
            </div>
            <div class="btn-list btn-primary-custom">Lihat Detail →</div>
        </a>

        <a href="{{ route('laporan.eksekusi.index') }}" class="list-menu-item">
            <div class="menu-info">
                <div class="menu-number">{{ $no++ }}.</div>
                <div class="list-icon-box bg-info-light"><i class="fas fa-gavel"></i></div>
                <div class="menu-text">
                    <div class="menu-title"> Eksekusi</div>
                    <div class="menu-desc">Monitoring penyelesaian perkara eksekusi se-Jawa Barat.</div>
                </div>
            </div>
            <div class="btn-list btn-info-custom">Lihat Detail →</div>
        </a>

        <a href="{{ route('sidang.index') }}" class="list-menu-item">
            <div class="menu-info">
                <div class="menu-number">{{ $no++ }}.</div>
                <div class="list-icon-box bg-success-light"><i class="fas fa-calendar-check"></i></div>
                <div class="menu-text">
                    <div class="menu-title"> Jadwal Sidang</div>
                    <div class="menu-desc">Informasi jadwal persidangan di PTA Bandung.</div>
                </div>
            </div>
            <div class="btn-list btn-success-custom">Lihat Detail →</div>
        </a>

        <a href="{{ route('mediasi.index') }}" class="list-menu-item">
            <div class="menu-info">
                <div class="menu-number">{{ $no++ }}.</div>
                <div class="list-icon-box bg-mint-light"><i class="fas fa-hands-helping"></i></div>
                <div class="menu-text">
                    <div class="menu-title"> Mediasi</div>
                    <div class="menu-desc">Monitoring keberhasilan mediasi se-Jawa Barat.</div>
                </div>
            </div>
            <div class="btn-list btn-mint-custom">Lihat Detail →</div>
        </a>

        <a href="{{ route('non-mediasi-gugatan.index') }}" class="list-menu-item">
            <div class="menu-info">
                <div class="menu-number">{{ $no++ }}.</div>
                <div class="list-icon-box bg-slate-light"><i class="fas fa-hands-helping"></i></div>
                <div class="menu-text">
                    <div class="menu-title">Perkara Gugatan Tidak Mediasi</div>
                    <div class="menu-desc">Monitoring perkara gugatan yang tidak melaksanakan proses mediasi.</div>
                </div>
            </div>
            <div class="btn-list btn-slate-custom">Lihat Detail →</div>
        </a>

        <a href="{{ route('saldo.minus') }}" class="list-menu-item">
            <div class="menu-info">
                <div class="menu-number">{{ $no++ }}.</div>
                <div class="list-icon-box bg-saldo-light"><i class="fas fa-chart-line"></i></div>
                <div class="menu-text">
                    <div class="menu-title"> Saldo Minus</div>
                    <div class="menu-desc">Monitoring perkara dengan saldo minus.</div>
                </div>
            </div>
            <div class="btn-list btn-saldo-custom">Lihat Detail →</div>
        </a>

        <a href="{{ route('sisa.panjar.menu') }}" class="list-menu-item">
            <div class="menu-info">
                <div class="menu-number">{{ $no++ }}.</div>
                <div class="list-icon-box bg-coral-light"><i class="fas fa-wallet"></i></div>
                <div class="menu-text">
                    <div class="menu-title">Sisa Panjar</div>
                    <div class="menu-desc">Monitoring pengelolaan sisa panjar biaya perkara.</div>
                </div>
            </div>
            <div class="btn-list btn-coral-custom">Lihat Detail →</div>
        </a>

        <a href="{{ route('court-calendar.') }}" class="list-menu-item">
            <div class="menu-info">
                <div class="menu-number">{{ $no++ }}.</div>
                <div class="list-icon-box bg-warning-light"><i class="fas fa-tasks"></i></div>
                <div class="menu-text">
                    <div class="menu-title">Court Calendar</div>
                    <div class="menu-desc">Monitoring kepatuhan pengisian Court Calendar pada aplikasi SIPP Satker.
                    </div>
                </div>
            </div>
            <div class="btn-list btn-warning-custom">Lihat Detail →</div>
        </a>

        <a href="{{ route('akta-cerai.index') }}" class="list-menu-item">
            <div class="menu-info">
                <div class="menu-number">{{ $no++ }}.</div>
                <div class="list-icon-box bg-orange-light"><i class="fas fa-certificate"></i></div>
                <div class="menu-text">
                    <div class="menu-title">Akta Cerai</div>
                    <div class="menu-desc">Monitoring kepatuhan penerbitan akta cerai di wilayah PTA Bandung.</div>
                </div>
            </div>
            <div class="btn-list btn-orange-custom">Lihat Detail →</div>
        </a>

        <a href="{{ route('monitoring.index') }}" class="list-menu-item">
            <div class="menu-info">
                <div class="menu-number">{{ $no++ }}.</div>
                <div class="list-icon-box bg-indigo-light"><i class="fas fa-chart-line"></i></div>
                <div class="menu-text">
                    <div class="menu-title"> E-Laporan</div>
                    <div class="menu-desc">Monitoring kepatuhan konfirmasi e-laporan se-Jawa Barat.</div>
                </div>
            </div>
            <div class="btn-list btn-indigo-custom">Lihat Detail →</div>
        </a>

        <a href="{{ route('input.index') }}" class="list-menu-item">
            <div class="menu-info">
                <div class="menu-number">{{ $no++ }}.</div>
                <div class="list-icon-box bg-dark-light"><i class="fas fa-user-shield"></i></div>
                <div class="menu-text">
                    <div class="menu-title">Kedisiplinan User</div>
                    <div class="menu-desc">Monitoring penggunaan akun Admin dalam penginputan data SIPP.</div>
                </div>
            </div>
            <div class="btn-list btn-dark-custom">Lihat Detail →</div>
        </a>

        <a href="{{ route('perkara.tepat_waktu') }}" class="list-menu-item">
            <div class="menu-info">
                <div class="menu-number">{{ $no++ }}.</div>
                <div class="list-icon-box bg-teal-light"><i class="fas fa-stopwatch"></i></div>
                <div class="menu-text">
                    <div class="menu-title">Perkara Tepat Waktu</div>
                    <div class="menu-desc">Monitoring penyelesaian perkara tepat waktu.</div>
                </div>
            </div>
            <div class="btn-list btn-teal-custom">Lihat Detail →</div>
        </a>

        <a href="https://kabayan.pta-bandung.go.id/e_laporan/control_alamat" target="_blank"
            class="list-menu-item">
            <div class="menu-info">
                <div class="menu-number">{{ $no++ }}.</div>
                <div class="list-icon-box bg-pink-light"><i class="fas fa-map-marker-alt"></i></div>
                <div class="menu-text">
                    <div class="menu-title">Kontrol Alamat Pihak</div>
                    <div class="menu-desc">Monitoring kelengkapan data alamat pihak pada aplikasi SIPP.</div>
                </div>
            </div>
            <div class="btn-list btn-pink-custom">Buka →</div>
        </a>

        <a href="https://kabayan.pta-bandung.go.id/e_laporan/control_dk" target="_blank" class="list-menu-item">
            <div class="menu-info">
                <div class="menu-number">{{ $no++ }}.</div>
                <div class="list-icon-box bg-purple-light"><i class="fas fa-child"></i></div>
                <div class="menu-text">
                    <div class="menu-title">Kontrol Data Dispensasi Kawin</div>
                    <div class="menu-desc">Monitoring kelengkapan data perkara dispensasi kawin.</div>
                </div>
            </div>
            <div class="btn-list btn-purple-custom">Buka →</div>
        </a>

        <a href="{{ route('monitoring.amar') }}" class="list-menu-item">
            <div class="menu-info">
                <div class="menu-number">{{ $no++ }}.</div>
                <div class="list-icon-box bg-navy-light"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="menu-text">
                    <div class="menu-title">Amar Putusan Tidak Lengkap</div>
                    <div class="menu-desc">Monitoring amar putusan yang belum lengkap.</div>
                </div>
            </div>
            <div class="btn-list btn-navy-custom">Lihat Detail →</div>
        </a>

        <a href="{{ route('prodeo.index') }}" class="list-menu-item">
            <div class="menu-info">
                <div class="menu-number">{{ $no++ }}.</div>
                <div class="list-icon-box bg-salem-light"><i class="fas fa-balance-scale"></i></div>
                <div class="menu-text">
                    <div class="menu-title"> Prodeo</div>
                    <div class="menu-desc">Monitoring permohonan dan penetapan perkara prodeo (bantuan hukum)
                        se-Jawa
                        Barat.</div>
                </div>
            </div>
            <div class="btn-list btn-salem-custom">Lihat Detail →</div>
        </a>

        <a href="{{ route('perkara_gugur.index') }}" class="list-menu-item">
            <div class="menu-info">
                <div class="menu-number">{{ $no++ }}.</div>
                <div class="list-icon-box bg-maroon-light"><i class="fas fa-file-signature"></i></div>
                <div class="menu-text">
                    <div class="menu-title">Perkara Gugur & Digugurkan</div>
                    <div class="menu-desc">Monitoring statistik perkara yang diputus gugur atau digugurkan oleh
                        majelis
                        hakim.</div>
                </div>
            </div>
            <div class="btn-list btn-maroon-custom">Lihat Detail →</div>
        </a>

        <a href="{{ route('ekonomi-syariah.index') }}" class="list-menu-item">
            <div class="menu-info">
                <div class="menu-number">{{ $no++ }}.</div>
                <div class="list-icon-box bg-salem-light"><i class="fas fa-chart-line"></i></div>
                <div class="menu-text">
                    <div class="menu-title">Perkara Ekonomi Syariah</div>
                    <div class="menu-desc">Monitoring statistik perkara ekonomi syariah se-Jawa Barat.</div>
                </div>
            </div>
            <div class="btn-list btn-salem-custom">Lihat Detail →</div>
        </a>

        <a href="{{ route('efiling.index') }}" class="list-menu-item">
            <div class="menu-info">
                <div class="menu-number">{{ $no++ }}.</div>
                <div class="list-icon-box bg-primary-light"><i class="fas fa-file-invoice"></i></div>
                <div class="menu-text">
                    <div class="menu-title"> E-Filing</div>
                    <div class="menu-desc">Monitoring antrean perkara e-court yang sudah bayar SKUM namun belum
                        register nomor perkara.</div>
                </div>
            </div>
            <div class="btn-list btn-primary-custom">Lihat Detail →</div>
        </a>

        <a href="{{ route('ecourt.index') }}" class="list-menu-item">
            <div class="menu-info">
                <div class="menu-number">{{ $no++ }}.</div>
                {{-- Icon menggunakan fa-binoculars sesuai preferensi monitoring Anda --}}
                <div class="list-icon-box bg-primary-light">
                    <i class="fas fa-binoculars text-primary"></i>
                </div>
                <div class="menu-text">
                    <div class="menu-title">E-Court</div>
                    <div class="menu-desc">
                        Monitoring persentase pendaftaran perkara e-court sewilayah
                        PTA Bandung.
                    </div>
                </div>
            </div>
            <div class="btn-list btn-primary-custom">Lihat Detail →</div>
        </a>

        <a href="{{ route('bht.no.akta.index') }}" class="list-menu-item">
            <div class="menu-info">
                <div class="menu-number">{{ $no++ }}.</div>
                <div class="list-icon-box bg-primary-light"><i class="fas fa-file-invoice"></i></div>
                <div class="menu-text">
                    <div class="menu-title">Sudah BHT Belum Terbit Akta Cerai</div>
                    <div class="menu-desc">Monitoring perkara yang sudah BHT tetapi belum terbit Akta Cerai.</div>
                </div>
            </div>
            <div class="btn-list btn-primary-custom">Lihat Detail →</div>
        </a>

        <a href="{{ route('pertimbangan.index') }}" class="list-menu-item">
            <div class="menu-info">
                <div class="menu-number">{{ $no++ }}.</div>
                {{-- Ikon Gavel (Palu) dengan warna amber/kuning untuk kesan monitoring --}}
                <div class="list-icon-box bg-warning-light">
                    <i class="fas fa-gavel"></i>
                </div>
                <div class="menu-text">
                    <div class="menu-title">Pertimbangan Hukum</div>
                    <div class="menu-desc">Monitoring kelengkapan data pertimbangan hukum pada perkara yang telah putus.</div>
                </div>
            </div>
            <div class="btn-list btn-primary-custom">Lihat Detail →</div>
        </a>

        <a href="{{ route('nik.index') }}" class="list-menu-item">
            <div class="menu-info">
                <div class="menu-number">{{ $no++ }}.</div>
                {{-- Ikon ID Card dengan warna merah untuk masalah NIK --}}
                <div class="list-icon-box bg-danger-light">
                    <i class="fas fa-id-card"></i>
                </div>
                <div class="menu-text">
                    <div class="menu-title">NIK Tidak Valid</div>
                    <div class="menu-desc">Monitoring dan rekapitulasi data NIK yang tidak valid (dispensasi kawin).</div>
                </div>
            </div>
            <div class="btn-list btn-primary-custom">Lihat Detail →</div>
        </a>

        {{-- ==================== MENU UNTUK USER (HANYA MONITORING KASASI) ==================== --}}
        @elseif(Auth::user()->canSeeKasasiOnly())
        <a href="{{ route('kasasi.index') }}" class="list-menu-item">
            <div class="menu-info">
                <div class="menu-number">1.</div>
                <div class="list-icon-box bg-primary-light"><i class="fas fa-file-signature"></i></div>
                <div class="menu-text">
                    <div class="menu-title"> Kasasi</div>
                    <div class="menu-desc">Monitoring permohonan perkara banding yang dimohonkan kasasi se-Jawa
                        Barat.</div>
                </div>
            </div>
            <div class="btn-list btn-primary-custom">Lihat Detail →</div>
        </a>

        <div class="alert alert-info border-0 rounded-0 m-3 text-center">
            <i class="fas fa-info-circle me-2"></i>
            Anda login sebagai <strong>User</strong>. Akses Anda terbatas pada menu <strong>Monitoring
                Kasasi</strong>.
        </div>

        {{-- ==================== MENU UNTUK VIEWER (SEMUA MENU READ ONLY) ==================== --}}
        @elseif(Auth::user()->canSeeAllMonitoring())
        @php $no = 1; @endphp

        <a href="{{ route('kasasi.index') }}" class="list-menu-item">
            <div class="menu-info">
                <div class="menu-number">{{ $no++ }}.</div>
                <div class="list-icon-box bg-primary-light"><i class="fas fa-file-signature"></i></div>
                <div class="menu-text">
                    <div class="menu-title"> Kasasi</div>
                    <div class="menu-desc">Monitoring permohonan perkara banding yang dimohonkan kasasi se-Jawa
                        Barat.</div>
                </div>
            </div>
            <div class="btn-list btn-secondary-custom"><i class="fas fa-eye me-1"></i> Lihat (Read Only) →</div>
        </a>

        <a href="{{ route('laporan.eksekusi.index') }}" class="list-menu-item">
            <div class="menu-info">
                <div class="menu-number">{{ $no++ }}.</div>
                <div class="list-icon-box bg-info-light"><i class="fas fa-gavel"></i></div>
                <div class="menu-text">
                    <div class="menu-title"> Eksekusi</div>
                    <div class="menu-desc">Monitoring penyelesaian perkara eksekusi se-Jawa Barat.</div>
                </div>
            </div>
            <div class="btn-list btn-secondary-custom"><i class="fas fa-eye me-1"></i> Lihat (Read Only) →</div>
        </a>

        <a href="{{ route('mediasi.index') }}" class="list-menu-item">
            <div class="menu-info">
                <div class="menu-number">{{ $no++ }}.</div>
                <div class="list-icon-box bg-mint-light"><i class="fas fa-hands-helping"></i></div>
                <div class="menu-text">
                    <div class="menu-title"> Mediasi</div>
                    <div class="menu-desc">Monitoring keberhasilan mediasi se-Jawa Barat.</div>
                </div>
            </div>
            <div class="btn-list btn-secondary-custom"><i class="fas fa-eye me-1"></i> Lihat (Read Only) →</div>
        </a>

        <a href="{{ route('non-mediasi-gugatan.index') }}" class="list-menu-item">
            <div class="menu-info">
                <div class="menu-number">{{ $no++ }}.</div>
                <div class="list-icon-box bg-slate-light"><i class="fas fa-hands-helping"></i></div>
                <div class="menu-text">
                    <div class="menu-title">Perkara Gugatan Tidak Mediasi</div>
                    <div class="menu-desc">Monitoring perkara gugatan yang tidak melaksanakan proses mediasi.</div>
                </div>
            </div>
            <div class="btn-list btn-secondary-custom"><i class="fas fa-eye me-1"></i> Lihat (Read Only) →</div>
        </a>

        <a href="{{ route('saldo.minus') }}" class="list-menu-item">
            <div class="menu-info">
                <div class="menu-number">{{ $no++ }}.</div>
                <div class="list-icon-box bg-saldo-light"><i class="fas fa-chart-line"></i></div>
                <div class="menu-text">
                    <div class="menu-title"> Saldo Minus</div>
                    <div class="menu-desc">Monitoring perkara dengan saldo minus.</div>
                </div>
            </div>
            <div class="btn-list btn-secondary-custom"><i class="fas fa-eye me-1"></i> Lihat (Read Only) →</div>
        </a>

        <a href="{{ route('sisa.panjar.menu') }}" class="list-menu-item">
            <div class="menu-info">
                <div class="menu-number">{{ $no++ }}.</div>
                <div class="list-icon-box bg-coral-light"><i class="fas fa-wallet"></i></div>
                <div class="menu-text">
                    <div class="menu-title">Sisa Panjar</div>
                    <div class="menu-desc">Monitoring pengelolaan sisa panjar biaya perkara.</div>
                </div>
            </div>
            <div class="btn-list btn-secondary-custom"><i class="fas fa-eye me-1"></i> Lihat (Read Only) →</div>
        </a>

        <a href="{{ route('court-calendar.') }}" class="list-menu-item">
            <div class="menu-info">
                <div class="menu-number">{{ $no++ }}.</div>
                <div class="list-icon-box bg-warning-light"><i class="fas fa-tasks"></i></div>
                <div class="menu-text">
                    <div class="menu-title">Court Calendar</div>
                    <div class="menu-desc">Monitoring kepatuhan pengisian Court Calendar pada aplikasi SIPP Satker.
                    </div>
                </div>
            </div>
            <div class="btn-list btn-secondary-custom"><i class="fas fa-eye me-1"></i> Lihat (Read Only) →</div>
        </a>

        <a href="{{ route('akta-cerai.index') }}" class="list-menu-item">
            <div class="menu-info">
                <div class="menu-number">{{ $no++ }}.</div>
                <div class="list-icon-box bg-orange-light"><i class="fas fa-certificate"></i></div>
                <div class="menu-text">
                    <div class="menu-title">Akta Cerai</div>
                    <div class="menu-desc">Monitoring kepatuhan penerbitan akta cerai di wilayah PTA Bandung.</div>
                </div>
            </div>
            <div class="btn-list btn-secondary-custom"><i class="fas fa-eye me-1"></i> Lihat (Read Only) →</div>
        </a>

        <a href="{{ route('monitoring.index') }}" class="list-menu-item">
            <div class="menu-info">
                <div class="menu-number">{{ $no++ }}.</div>
                <div class="list-icon-box bg-indigo-light"><i class="fas fa-chart-line"></i></div>
                <div class="menu-text">
                    <div class="menu-title"> E-Laporan</div>
                    <div class="menu-desc">Monitoring kepatuhan konfirmasi e-laporan se-Jawa Barat.</div>
                </div>
            </div>
            <div class="btn-list btn-secondary-custom"><i class="fas fa-eye me-1"></i> Lihat (Read Only) →</div>
        </a>

        <a href="{{ route('input.index') }}" class="list-menu-item">
            <div class="menu-info">
                <div class="menu-number">{{ $no++ }}.</div>
                <div class="list-icon-box bg-dark-light"><i class="fas fa-user-shield"></i></div>
                <div class="menu-text">
                    <div class="menu-title">Kedisiplinan User</div>
                    <div class="menu-desc">Monitoring penggunaan akun Admin dalam penginputan data SIPP.</div>
                </div>
            </div>
            <div class="btn-list btn-secondary-custom"><i class="fas fa-eye me-1"></i> Lihat (Read Only) →</div>
        </a>

        <a href="{{ route('perkara.tepat_waktu') }}" class="list-menu-item">
            <div class="menu-info">
                <div class="menu-number">{{ $no++ }}.</div>
                <div class="list-icon-box bg-teal-light"><i class="fas fa-stopwatch"></i></div>
                <div class="menu-text">
                    <div class="menu-title">Perkara Tepat Waktu</div>
                    <div class="menu-desc">Monitoring penyelesaian perkara tepat waktu.</div>
                </div>
            </div>
            <div class="btn-list btn-secondary-custom"><i class="fas fa-eye me-1"></i> Lihat (Read Only) →</div>
        </a>

        <a href="https://kabayan.pta-bandung.go.id/e_laporan/control_alamat" target="_blank"
            class="list-menu-item">
            <div class="menu-info">
                <div class="menu-number">{{ $no++ }}.</div>
                <div class="list-icon-box bg-pink-light"><i class="fas fa-map-marker-alt"></i></div>
                <div class="menu-text">
                    <div class="menu-title">Kontrol Alamat Pihak</div>
                    <div class="menu-desc">Monitoring kelengkapan data alamat pihak pada aplikasi SIPP.</div>
                </div>
            </div>
            <div class="btn-list btn-secondary-custom"><i class="fas fa-external-link-alt me-1"></i> Buka (Read
                Only) →</div>
        </a>

        <a href="https://kabayan.pta-bandung.go.id/e_laporan/control_dk" target="_blank" class="list-menu-item">
            <div class="menu-info">
                <div class="menu-number">{{ $no++ }}.</div>
                <div class="list-icon-box bg-purple-light"><i class="fas fa-child"></i></div>
                <div class="menu-text">
                    <div class="menu-title">Kontrol Data Dispensasi Kawin</div>
                    <div class="menu-desc">Monitoring kelengkapan data perkara dispensasi kawin.</div>
                </div>
            </div>
            <div class="btn-list btn-secondary-custom"><i class="fas fa-external-link-alt me-1"></i> Buka (Read
                Only) →</div>
        </a>

        <a href="{{ route('monitoring.amar') }}" class="list-menu-item">
            <div class="menu-info">
                <div class="menu-number">{{ $no++ }}.</div>
                <div class="list-icon-box bg-navy-light"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="menu-text">
                    <div class="menu-title">Amar Putusan Tidak Lengkap</div>
                    <div class="menu-desc">Monitoring amar putusan yang belum lengkap.</div>
                </div>
            </div>
            <div class="btn-list btn-secondary-custom"><i class="fas fa-eye me-1"></i> Lihat (Read Only) →</div>
        </a>

        <a href="{{ route('prodeo.index') }}" class="list-menu-item">
            <div class="menu-info">
                <div class="menu-number">{{ $no++ }}.</div>
                <div class="list-icon-box bg-salem-light"><i class="fas fa-balance-scale"></i></div>
                <div class="menu-text">
                    <div class="menu-title"> Prodeo</div>
                    <div class="menu-desc">Monitoring permohonan dan penetapan perkara prodeo (bantuan hukum)
                        se-Jawa
                        Barat.</div>
                </div>
            </div>
            <div class="btn-list btn-secondary-custom"><i class="fas fa-eye me-1"></i> Lihat (Read Only) →</div>
        </a>

        <a href="{{ route('perkara_gugur.index') }}" class="list-menu-item">
            <div class="menu-info">
                <div class="menu-number">{{ $no++ }}.</div>
                <div class="list-icon-box bg-maroon-light"><i class="fas fa-file-signature"></i></div>
                <div class="menu-text">
                    <div class="menu-title">Perkara Gugur & Digugurkan</div>
                    <div class="menu-desc">Monitoring statistik perkara yang diputus gugur atau digugurkan oleh
                        majelis
                        hakim.</div>
                </div>
            </div>
            <div class="btn-list btn-secondary-custom"><i class="fas fa-eye me-1"></i> Lihat (Read Only) →</div>
        </a>

        <a href="{{ route('ekonomi-syariah.index') }}" class="list-menu-item">
            <div class="menu-info">
                <div class="menu-number">{{ $no++ }}.</div>
                <div class="list-icon-box bg-salem-light"><i class="fas fa-chart-line"></i></div>
                <div class="menu-text">
                    <div class="menu-title">Perkara Ekonomi Syariah</div>
                    <div class="menu-desc">Monitoring statistik perkara ekonomi syariah se-Jawa Barat.</div>
                </div>
            </div>
            <div class="btn-list btn-secondary-custom"><i class="fas fa-eye me-1"></i> Lihat (Read Only) →</div>
        </a>

        <a href="{{ route('efiling.index') }}" class="list-menu-item">
            <div class="menu-info">
                <div class="menu-number">{{ $no++ }}.</div>
                <div class="list-icon-box bg-primary-light"><i class="fas fa-file-invoice"></i></div>
                <div class="menu-text">
                    <div class="menu-title"> E-Filing</div>
                    <div class="menu-desc">Monitoring antrean perkara e-court yang sudah bayar SKUM namun belum
                        register nomor perkara.</div>
                </div>
            </div>
            <div class="btn-list btn-secondary-custom"><i class="fas fa-eye me-1"></i> Lihat (Read Only) →</div>
        </a>

        <a href="{{ route('ecourt.index') }}" class="list-menu-item">
            <div class="menu-info">
                <div class="menu-number">{{ $no++ }}.</div>
                <div class="list-icon-box bg-primary-light">
                    <i class="fas fa-binoculars text-primary"></i>
                </div>
                <div class="menu-text">
                    <div class="menu-title"> E-Court</div>
                    <div class="menu-desc">
                        Monitoring persentase pendaftaran perkara e-court sewilayah PTA
                        Bandung.
                    </div>
                </div>
            </div>
            <div class="btn-list btn-secondary-custom"><i class="fas fa-eye me-1"></i> Lihat (Read Only) →</div>


            <a href="{{ route('bht.no.akta.index') }}" class="list-menu-item">
                <div class="menu-info">
                    <div class="menu-number">{{ $no++ }}.</div>
                    <div class="list-icon-box bg-primary-light"><i class="fas fa-file-invoice"></i></div>
                    <div class="menu-text">
                        <div class="menu-title"> Sudah BHT Belum Terbit Akta Cerai</div>
                        <div class="menu-desc">Monitoring perkara yang sudah BHT tetapi belum terbit Akta Cerai.</div>
                    </div>
                </div>
                <div class="btn-list btn-secondary-custom"><i class="fas fa-eye me-1"></i> Lihat (Read Only) →</div>
            </a>

            <a href="{{ route('pertimbangan.index') }}" class="list-menu-item">
                <div class="menu-info">
                    <div class="menu-number">{{ $no++ }}.</div>
                    {{-- Ikon 'balance-scale' sangat cocok untuk Pertimbangan Hukum --}}
                    <div class="list-icon-box bg-success-light">
                        <i class="fas fa-balance-scale"></i>
                    </div>
                    <div class="menu-text">
                        <div class="menu-title">Pertimbangan Hukum</div>
                        <div class="menu-desc">Monitoring validasi pengisian data pertimbangan hukum pada perkara yang telah putus.</div>
                    </div>
                </div>
                <div class="btn-list btn-secondary-custom"><i class="fas fa-eye me-1"></i> Lihat (Read Only) →</div>
            </a>

            <a href="{{ route('nik.index') }}" class="list-menu-item">
                <div class="menu-info">
                    <div class="menu-number">{{ $no++ }}.</div>
                    {{-- Ikon ID Card untuk NIK Tidak Valid --}}
                    <div class="list-icon-box bg-danger-light">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <div class="menu-text">
                        <div class="menu-title">NIK Tidak Valid</div>
                        <div class="menu-desc">Monitoring dan rekapitulasi data NIK yang tidak valid pada perkara dispensasi kawin.</div>
                    </div>
                </div>
                <div class="btn-list btn-secondary-custom"><i class="fas fa-eye me-1"></i> Lihat (Read Only) →</div>
            </a>

    </div>
    </a>

    <div class="alert alert-secondary border-0 rounded-0 m-3 text-center">
        <i class="fas fa-eye me-2"></i>
        Anda login sebagai <strong>Viewer</strong>. Semua data ditampilkan dalam mode <strong>Read
            Only</strong>.
    </div>
    @endif

    </div>
</main>
@endsection