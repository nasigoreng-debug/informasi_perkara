<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Layanan Perkara | PTA Bandung</title>

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
            --card-hover-shadow: 0 30px 60px rgba(0, 0, 0, 0.12);
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

        /* Hero Section */
        .hero-header {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--secondary-dark) 100%);
            padding: 120px 0 160px;
            color: white;
            border-bottom-left-radius: 100px;
            border-bottom-right-radius: 100px;
            position: relative;
            overflow: hidden;
        }

        /* Dekorasi Background Hero */
        .hero-header::after {
            content: '';
            position: absolute;
            top: -10%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 50%;
        }

        .main-container {
            margin-top: -100px;
            flex: 1;
            padding-bottom: 80px;
        }

        /* Card Styling */
        .menu-card {
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 35px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 50px 35px;
            height: 100%;
            box-shadow: var(--card-shadow);
        }

        .menu-card:hover {
            transform: translateY(-20px);
            box-shadow: var(--card-hover-shadow) !important;
            background: #ffffff;
            border-color: #fff;
        }

        /* Icon Wrapper */
        .icon-wrapper {
            width: 100px;
            height: 100px;
            border-radius: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px;
            font-size: 2.8rem;
            transition: all 0.5s ease;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.05);
        }

        .menu-card:hover .icon-wrapper {
            transform: scale(1.15) rotate(8deg);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            font-size: 1.4rem;
            letter-spacing: -0.5px;
        }

        .card-text {
            line-height: 1.6;
            opacity: 0.8;
            font-size: 0.95rem;
        }

        /* Button Styling */
        .btn-custom {
            border-radius: 18px;
            padding: 14px 20px;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            transition: all 0.3s;
            border: none;
        }

        .menu-card:hover .btn-custom {
            transform: scale(1.05);
        }

        /* Badge Online */
        .badge-online {
            font-size: 0.75rem;
            padding: 8px 18px;
            border-radius: 50px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            letter-spacing: 1px;
        }

        footer {
            padding: 50px 0;
            background: #ffffff;
            border-top: 1px solid #eef2f6;
            color: #636e72;
        }

        .footer-logo {
            font-weight: 800;
            color: var(--primary-dark);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Staggered Animation Delay */
        .card-delay-1 {
            animation-delay: 0.1s;
        }

        .card-delay-2 {
            animation-delay: 0.2s;
        }

        .card-delay-3 {
            animation-delay: 0.3s;
        }

        .card-delay-4 {
            animation-delay: 0.4s;
        }

        .card-delay-5 {
            animation-delay: 0.5s;
        }
    </style>
</head>

<body>

    <header class="hero-header text-center">
        <div class="container">
            <div class="animate__animated animate__fadeInDown">
                <span class="badge-online mb-3 d-inline-block text-uppercase fw-bold">
                    <i class="fas fa-circle text-success me-2 animate__animated animate__flash animate__infinite"></i> Sistem Terintegrasi Online
                </span>
                <h1 class="display-3 fw-extrabold mb-3">Portal Informasi Perkara</h1>
                <p class="lead opacity-75 mx-auto mb-0" style="max-width: 650px; font-weight: 500;">
                    Monitoring dan Informasi Kepaniteraan Muda Hukum <br>
                    <span style="color: var(--accent-gold)">Pengadilan Tinggi Agama Bandung</span>
                </p>
            </div>
        </div>
    </header>

    <main class="main-container container">
        <div class="row justify-content-center g-4">

            {{-- 1. KASASI --}}
            <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp card-delay-1">
                <a href="{{ route('kasasi.index') }}" class="card menu-card">
                    <div class="icon-wrapper bg-primary bg-opacity-10 text-primary">
                        <i class="fas fa-balance-scale-right"></i>
                    </div>
                    <h4 class="card-title fw-bold text-dark mb-3">Monitoring Kasasi</h4>
                    <p class="card-text text-muted mb-4">
                        Monitoring perkara kasasi dari seluruh Satuan Kerja secara real-time.
                    </p>
                    <div class="mt-auto w-100">
                        <div class="btn btn-primary btn-custom w-100 shadow-sm">Lihat Detail Perkara</div>
                    </div>
                </a>
            </div>

            {{-- 2. JADWAL SIDANG --}}
            <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp card-delay-2">
                <a href="{{ route('sidang.index') }}" class="card menu-card">
                    <div class="icon-wrapper bg-success bg-opacity-10 text-success">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h4 class="card-title fw-bold text-dark mb-3">Jadwal Sidang</h4>
                    <p class="card-text text-muted mb-4">
                        Lihat jadwal persidangan Pengadilan Tinggi Agama Bandung.
                    </p>
                    <div class="mt-auto w-100">
                        <div class="btn btn-success btn-custom w-100 shadow-sm">Lihat Jadwal Sidang</div>
                    </div>
                </a>
            </div>

            {{-- 3. PERKARA DITERIMA --}}
            <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp card-delay-3">
                <a href="{{ route('laporan.index') }}" class="card menu-card">
                    <div class="icon-wrapper bg-warning bg-opacity-10 text-warning">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                    <h4 class="card-title fw-bold text-dark mb-3">Perkara Diterima</h4>
                    <p class="card-text text-muted mb-4">
                        Rekapitulasi laporan perkara yang diterima Pengadilan Agama se-Jawa Barat.
                    </p>
                    <div class="mt-auto w-100">
                        <div class="btn btn-warning text-dark btn-custom w-100 shadow-sm">Lihat Laporan</div>
                    </div>
                </a>
            </div>

            {{-- 4. PERKARA DIPUTUS --}}
            <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp card-delay-4">
                <a href="{{ route('laporan-putus.index') }}" class="card menu-card">
                    <div class="icon-wrapper bg-danger bg-opacity-10 text-danger">
                        <i class="fas fa-gavel"></i>
                    </div>
                    <h4 class="card-title fw-bold text-dark mb-3">Perkara Diputus</h4>
                    <p class="card-text text-muted mb-4">
                        Rekapitulasi laporan perkara yang diputus Pengadilan Agama se-Jawa Barat.
                    </p>
                    <div class="mt-auto w-100">
                        <div class="btn btn-danger btn-custom w-100 shadow-sm">Lihat Laporan</div>
                    </div>
                </a>
            </div>

            {{-- 5. MONITORING EKSEKUSI --}}
            <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp card-delay-5">
                <a href="{{ route('laporan.eksekusi.index') }}" class="card menu-card">
                    <div class="icon-wrapper bg-info bg-opacity-10 text-info">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <h4 class="card-title fw-bold text-dark mb-3">Monitoring Eksekusi</h4>
                    <p class="card-text text-muted mb-4">
                        Informasi penyelesaian eksekusi putusan di wilayah hukum Pengadilan Tinggi Agama Bandung.
                    </p>
                    <div class="mt-auto w-100">
                        <div class="btn btn-info text-white btn-custom w-100 shadow-sm">Lihat Eksekusi</div>
                    </div>
                </a>
            </div>

        </div>
    </main>

    <footer>
        <div class="container text-center">
            <div class="footer-logo mb-3">PTA BANDUNG</div>
            <p class="mb-1 fw-semibold text-dark">Pengadilan Tinggi Agama Bandung</p>
            <p class="small mb-0 opacity-50">Menuju Peradilan Modern yang Transparan dan Akuntabel &copy; {{ date('Y') }}</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>