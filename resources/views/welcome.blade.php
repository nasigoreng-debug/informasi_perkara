<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Utama | PTA Bandung</title>

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

        /* Welcome Card Styling */
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
            padding: 60px 40px;
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

        .card-monitoring::before {
            background: #0d6efd;
        }

        .card-laporan::before {
            background: #ffc107;
        }

        .icon-box {
            width: 120px;
            height: 120px;
            border-radius: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px;
            font-size: 3.5rem;
            transition: all 0.5s ease;
        }

        .welcome-card:hover .icon-box {
            transform: scale(1.15) rotate(5deg);
        }

        .card-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--primary-dark);
            margin-bottom: 15px;
        }

        .card-desc {
            font-size: 1rem;
            color: #636e72;
            max-width: 280px;
            line-height: 1.6;
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
        <div class="container px-4">
            <div class="animate__animated animate__fadeInDown">
                <span class="badge-online mb-3 d-inline-block text-uppercase fw-bold text-white">
                    <i class="fas fa-shield-alt me-2 text-success"></i> Portal Resmi PTA Bandung
                </span>
                <h1 class="display-4 fw-bold mb-3">Selamat Datang</h1>
                <p class="lead opacity-75 mx-auto mb-0" style="max-width: 600px;">
                    Silakan pilih kategori layanan informasi perkara yang Anda butuhkan
                </p>
            </div>
        </div>
    </header>

    <main class="main-container container px-4">
        <div class="row justify-content-center g-5">

            <div class="col-md-6 col-lg-5 animate__animated animate__fadeInLeft">
                <a href="{{ route('monitoring') }}" class="card welcome-card card-monitoring">
                    <div class="icon-box bg-primary bg-opacity-10 text-primary">
                        <i class="fas fa-desktop"></i>
                    </div>
                    <h2 class="card-title text-uppercase">Monitoring</h2>
                    <p class="card-desc">
                        Akses layanan real-time: Kasasi, Jadwal Sidang, dan Administrasi Surat Masuk.
                    </p>
                    <div class="btn btn-primary px-5 py-3 mt-4 fw-bold rounded-pill">
                        MASUK MONITORING <i class="fas fa-arrow-right ms-2"></i>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-5 animate__animated animate__fadeInRight">
                <a href="{{ route('laporan-utama') }}" class="card welcome-card card-laporan">
                    <div class="icon-box bg-warning bg-opacity-10 text-warning">
                        <i class="fas fa-file-signature"></i>
                    </div>
                    <h2 class="card-title text-uppercase">Laporan</h2>
                    <p class="card-desc">
                        Rekapitulasi data perkara diterima, perkara diputus, dan laporan eksekusi.
                    </p>
                    <div class="btn btn-warning px-5 py-3 mt-4 fw-bold rounded-pill">
                        MASUK LAPORAN <i class="fas fa-arrow-right ms-2"></i>
                    </div>
                </a>
            </div>

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