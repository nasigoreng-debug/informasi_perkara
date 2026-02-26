<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Monitoring | PTA Bandung</title>

    <link rel="shortcut icon" href="{{ asset('/favicon/favicon.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #1a2a6c;
            --bg-light: #f4f7fa;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-light);
            color: #2d3436;
        }

        .hero-header {
            background: linear-gradient(135deg, #1a2a6c 0%, #2a4858 100%);
            padding: 60px 0 100px;
            color: white;
            border-bottom-left-radius: 50px;
            border-bottom-right-radius: 50px;
        }

        .main-container {
            margin-top: -60px;
            padding-bottom: 80px;
        }

        .menu-card {
            border: none;
            border-radius: 25px;
            background: white;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            text-decoration: none !important;
            padding: 40px 25px;
            height: 100%;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .menu-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
        }

        .icon-box {
            width: 75px;
            height: 75px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            font-size: 2.2rem;
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 12px;
            min-height: 50px;
            display: flex;
            align-items: center;
        }

        footer {
            padding: 30px 0;
            opacity: 0.6;
            font-size: 0.85rem;
        }
    </style>
</head>

<body>
    <header class="hero-header text-center">
        <div class="container px-4">
            <a href="{{ route('welcome') }}" class="btn btn-sm btn-outline-light rounded-pill mb-3 px-3">
                <i class="fas fa-arrow-left me-2"></i> Kembali ke Awal
            </a>
            <h1 class="fw-bold animate__animated animate__fadeInDown">Panel Monitoring</h1>
            <p class="opacity-75 mx-auto mb-0" style="max-width: 600px;">
                Pantau data operasional dan administrasi perkara secara real-time
            </p>
        </div>
    </header>

    <main class="main-container container px-4">
        <div class="row g-4 justify-content-center">
            
            <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp">
                <a href="{{ route('kasasi.index') }}" class="menu-card">
                    <div class="icon-box bg-primary bg-opacity-10 text-primary">
                        <i class="fas fa-gavel"></i>
                    </div>
                    <h5 class="card-title text-dark">Monitoring Kasasi</h5>
                    <p class="text-muted small mb-4">Cek status pengajuan dan proses perkara tingkat Kasasi secara transparan.</p>
                    <div class="btn btn-outline-primary btn-sm rounded-pill w-100 mt-auto fw-bold">Buka Layanan</div>
                </a>
            </div>

            <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
                <a href="{{ route('laporan.eksekusi.index') }}" class="menu-card">
                    <div class="icon-box bg-info bg-opacity-10 text-info">
                        <i class="fas fa-file-signature"></i>
                    </div>
                    <h5 class="card-title text-dark">Monitoring Eksekusi</h5>
                    <p class="text-muted small mb-4">Rekapitulasi data penyelesaian perkara eksekusi Satker se-Jawa Barat.</p>
                    <div class="btn btn-outline-info btn-sm rounded-pill w-100 mt-auto fw-bold">Buka Layanan</div>
                </a>
            </div>

            <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
                <a href="{{ route('sidang.index') }}" class="menu-card">
                    <div class="icon-box bg-success bg-opacity-10 text-success">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h5 class="card-title text-dark">Jadwal Sidang</h5>
                    <p class="text-muted small mb-4">Informasi agenda persidangan harian di wilayah hukum PTA Bandung.</p>
                    <div class="btn btn-outline-success btn-sm rounded-pill w-100 mt-auto fw-bold">Buka Layanan</div>
                </a>
            </div>

            <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
                <a href="#" class="menu-card"> <div class="icon-box bg-danger bg-opacity-10 text-danger">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <h5 class="card-title text-dark">Sisa Panjar Perkara</h5>
                    <p class="text-muted small mb-4">Pantau transparansi pengelolaan sisa panjar biaya perkara di wilayah PTA.</p>
                    <div class="btn btn-outline-danger btn-sm rounded-pill w-100 mt-auto fw-bold">Buka Layanan</div>
                </a>
            </div>

            <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.3s">
                <a href="#" class="menu-card"> <div class="icon-box bg-warning bg-opacity-10 text-warning">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <h5 class="card-title text-dark">Court Calendar</h5>
                    <p class="text-muted small mb-4">Pantau kepatuhan pengisian Court Calendar pada aplikasi SIPP Satker.</p>
                    <div class="btn btn-outline-warning btn-sm rounded-pill w-100 mt-auto fw-bold text-dark">Buka Layanan</div>
                </a>
            </div>

            
            <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.3s">
                <a href="#" class="menu-card"> <div class="icon-box bg-warning bg-opacity-10 text-warning">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <h5 class="card-title text-dark">Penerbitan Akta Cerai</h5>
                    <p class="text-muted small mb-4">Pantau kepatuhan penerbitan akta cerai di wilayah PTA Bandung.</p>
                    <div class="btn btn-outline-warning btn-sm rounded-pill w-100 mt-auto fw-bold text-dark">Buka Layanan</div>
                </a>
            </div>

        </div>
    </main>

    <footer class="text-center">
        <p>&copy; {{ date('Y') }} Pengadilan Tinggi Agama Bandung</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>