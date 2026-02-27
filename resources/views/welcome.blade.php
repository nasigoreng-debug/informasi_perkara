<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Utama | PTA Bandung</title>

    <link rel="shortcut icon" href="{{ asset('/favicon/favicon.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-dark: #1a2a6c;
            --secondary-dark: #2a4858;
            --accent-gold: #ffd700;
            --bg-light: #f4f7fa;
            --card-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
            --card-hover-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-light);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            margin: 0;
            color: #2d3436;
        }

        /* Nav Bar / Top Profile */
        .user-nav {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 15px;
            background: rgba(255, 255, 255, 0.1);
            padding: 8px 20px;
            border-radius: 50px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .user-info-text {
            color: white;
            text-align: right;
            line-height: 1.2;
        }

        .user-info-text small {
            font-size: 0.7rem;
            opacity: 0.8;
            display: block;
        }

        .logout-btn {
            background: rgba(255, 50, 50, 0.2);
            color: #ffbaba;
            border: 1px solid rgba(255, 50, 50, 0.3);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            cursor: pointer;
        }

        .logout-btn:hover {
            background: #ff4757;
            color: white;
            transform: scale(1.1);
        }

        .hero-header {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--secondary-dark) 100%);
            padding: 100px 0 160px;
            color: white;
            border-bottom-left-radius: 60px;
            border-bottom-right-radius: 60px;
            position: relative;
            overflow: hidden;
        }

        .main-container {
            margin-top: -100px;
            flex: 1;
            padding-bottom: 80px;
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
            padding: 50px 30px;
            height: 100%;
            box-shadow: var(--card-shadow);
            position: relative;
            overflow: hidden;
        }

        .welcome-card:hover {
            transform: translateY(-15px);
            box-shadow: var(--card-hover-shadow);
        }

        .welcome-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 8px;
            transition: all 0.3s;
        }

        .card-monitoring::before { background: #0d6efd; }
        .card-laporan::before { background: #ffc107; }
        .card-administrasi::before { background: #198754; }

        .icon-box {
            width: 100px;
            height: 100px;
            border-radius: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 25px;
            font-size: 3rem;
            transition: all 0.5s ease;
        }

        .welcome-card:hover .icon-box {
            transform: scale(1.15) rotate(5deg);
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--primary-dark);
            margin-bottom: 15px;
        }

        .card-desc {
            font-size: 0.9rem;
            color: #636e72;
            line-height: 1.6;
            min-height: 72px;
        }

        .badge-online {
            font-size: 0.75rem;
            padding: 8px 18px;
            border-radius: 50px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }

        footer {
            padding: 40px 0;
            background: #ffffff;
            border-top: 1px solid #eef2f6;
        }
    </style>
</head>

<body>

    <header class="hero-header text-center">
        {{-- USER NAVIGATION --}}
        <div class="user-nav animate__animated animate__fadeInRight">
            <div class="user-info-text d-none d-sm-block">
                <span class="fw-bold">{{ Auth::user()->name }}</span>
                <small>
                    {{ Auth::user()->satker ? Auth::user()->satker->nama : 'PTA BANDUNG (ADMIN)' }}
                </small>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn" title="Keluar Sistem">
                    <i class="fas fa-power-off"></i>
                </button>
            </form>
        </div>

        <div class="container px-4">
            <div class="animate__animated animate__fadeInDown">
                <span class="badge-online mb-3 d-inline-block text-uppercase fw-bold text-white">
                    <i class="fas fa-shield-alt me-2 text-success"></i>
                    @if(Auth::user()->satker)
                        {{ Auth::user()->satker->namapa }}
                    @else
                        Wilayah Hukum PTA Bandung
                    @endif
                </span>
                <h1 class="display-5 fw-bold mb-3">Selamat Datang</h1>
                <p class="lead opacity-75 mx-auto mb-0" style="max-width: 600px;">
                    Portal integrasi data informasi perkara untuk mewujudkan transparansi dan akurasi data.
                </p>
            </div>
        </div>
    </header>

    <main class="main-container container px-4">
        <div class="row justify-content-center g-4">

            {{-- MENU MONITORING (Terbuka untuk Semua) --}}
            <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp">
                <a href="{{ route('monitoring') }}" class="card welcome-card card-monitoring">
                    <div class="icon-box bg-primary bg-opacity-10 text-primary">
                        <i class="fas fa-desktop"></i>
                    </div>
                    <h2 class="card-title text-uppercase">Monitoring</h2>
                    <p class="card-desc">
                        Monitoring kinerja satuan kerja secara real-time Se-Wilayah PTA Bandung.
                    </p>
                    <div class="btn btn-primary w-100 py-3 mt-4 fw-bold rounded-pill shadow-sm">
                        MASUK MONITORING <i class="fas fa-arrow-right ms-2"></i>
                    </div>
                </a>
            </div>

            {{-- MENU KHUSUS ADMIN PTA BANDUNG --}}
            @if(Auth::user()->satker && Auth::user()->satker->tabel == 'ptabandung')
                
                {{-- ADMINISTRASI --}}
                <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
                    <a href="{{ route('errors.under_construction') }}" class="card welcome-card card-administrasi">
                        <div class="icon-box bg-success bg-opacity-10 text-success">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <h2 class="card-title text-uppercase">Administrasi</h2>
                        <p class="card-desc">
                            Pengelolaan data administrasi kepaniteraan muda hukum.
                        </p>
                        <div class="btn btn-success w-100 py-3 mt-4 fw-bold rounded-pill shadow-sm">
                            MASUK ADMINISTRASI <i class="fas fa-arrow-right ms-2"></i>
                        </div>
                    </a>
                </div>

                {{-- LAPORAN --}}
                <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
                    <a href="{{ route('laporan-utama') }}" class="card welcome-card card-laporan">
                        <div class="icon-box bg-warning bg-opacity-10 text-warning">
                            <i class="fas fa-file-signature"></i>
                        </div>
                        <h2 class="card-title text-uppercase">Laporan</h2>
                        <p class="card-desc">
                            Rekapitulasi laporan perkara diterima dan diputus per periode.
                        </p>
                        <div class="btn btn-warning w-100 py-3 mt-4 fw-bold rounded-pill shadow-sm">
                            MASUK LAPORAN <i class="fas fa-arrow-right ms-2"></i>
                        </div>
                    </a>
                </div>

            @else
                {{-- TAMPILAN JIKA LOGIN SEBAGAI SATKER DAERAH --}}
                <div class="col-lg-8 animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
                    <div class="alert alert-info border-0 shadow-sm p-4 rounded-5 bg-white border-start border-primary border-5">
                        <div class="d-flex gap-4 align-items-center">
                            <i class="fas fa-info-circle fs-1 text-primary"></i>
                            <div>
                                <h5 class="fw-bold mb-1">Informasi Akses</h5>
                                <p class="mb-0 text-muted opacity-75">
                                    Saat ini Anda masuk sebagai perwakilan Satker. Akses penuh Monitoring Kasasi tersedia di dalam menu Monitoring. Menu Administrasi dan Laporan Utama dikelola oleh PTA Bandung.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </main>

    <footer class="text-center mt-auto">
        <div class="container">
            <div class="fw-bold text-primary mb-1" style="letter-spacing: 2px;">PTA BANDUNG</div>
            <p class="small text-muted mb-0">&copy; {{ date('Y') }} Pengadilan Tinggi Agama Bandung - Terintegrasi & Transparan</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>