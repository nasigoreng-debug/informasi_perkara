<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Laporan Perkara | PTA Bandung</title>

    <link rel="shortcut icon" href="{{ asset('/favicon/favicon.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-dark: #1a2a6c;
            --bg-light: #f4f7fa;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-light);
            color: #2d3436;
        }

        .hero-header {
            background: linear-gradient(135deg, var(--primary-dark) 0%, #2a4858 100%);
            padding: 60px 0 100px;
            color: white;
            border-bottom-left-radius: 50px;
            border-bottom-right-radius: 50px;
        }

        .main-container {
            margin-top: -60px;
            padding-bottom: 80px;
        }

        /* Perbaikan lebar kartu agar pas untuk 5 kolom */
        .custom-col-5 {
            flex: 0 0 auto;
            width: 20%;
            /* 100% dibagi 5 */
        }

        @media (max-width: 1200px) {
            .custom-col-5 {
                width: 33.33%;
                /* Jadi 3 kolom di layar sedang */
            }
        }

        @media (max-width: 768px) {
            .custom-col-5 {
                width: 50%;
                /* Jadi 2 kolom di layar kecil */
            }
        }

        .menu-card {
            border: none;
            border-radius: 25px;
            background: white;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            text-decoration: none !important;
            padding: 30px 20px;
            /* Sedikit dirampingkan agar pas */
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
            width: 70px;
            height: 70px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            font-size: 2.2rem;
        }

        .card-title {
            font-size: 1rem;
            /* Ukuran font disesuaikan agar tidak pecah */
            font-weight: 700;
            margin-bottom: 10px;
            min-height: 50px;
            display: flex;
            align-items: center;
        }

        .badge-custom {
            font-size: 0.75rem;
            padding: 5px 12px;
            border-radius: 50px;
            margin-bottom: 15px;
            font-weight: 800;
        }

        footer {
            padding: 30px 0;
            color: #636e72;
            font-size: 0.9rem;
        }
    </style>
</head>

<body>
    <header class="hero-header text-center">
        <div class="container px-4">
            <a href="{{ route('welcome') }}" class="btn btn-sm btn-outline-light rounded-pill mb-3 px-3">
                <i class="fas fa-arrow-left me-2"></i> Kembali ke Portal
            </a>
            <h1 class="fw-bold animate__animated animate__fadeInDown">Panel Laporan Perkara</h1>
            <p class="opacity-75 mx-auto mb-0" style="max-width: 600px;">
                Rekapitulasi data statistik perkara secara berkala (RK1 s.d RK4)
            </p>
        </div>
    </header>

    <main class="main-container container-fluid px-5">
        <div class="row g-4 justify-content-center">

            {{-- RK1 --}}
            <div class="custom-col-5 animate__animated animate__fadeInUp">
                <a href="{{ route('laporan.banding.diterima') }}" class="menu-card">
                    <div class="icon-box bg-primary bg-opacity-10 text-primary">
                        <i class="fas fa-file-import"></i>
                    </div>
                    <span class="badge-custom bg-primary text-white">RK1</span>
                    <h5 class="card-title text-dark">Perkara Diterima Banding</h5>
                    <p class="text-muted small mb-4">Statistik perkara banding yang masuk.</p>
                    {{-- SERAGAM: btn-outline-primary --}}
                    <div class="btn btn-outline-primary btn-sm w-100 rounded-pill mt-auto">Lihat Laporan</div>
                </a>
            </div>

            {{-- RK2 --}}
            <div class="custom-col-5 animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
                <a href="{{ route('laporan.banding.putus') }}" class="menu-card">
                    <div class="icon-box bg-info bg-opacity-10 text-info">
                        <i class="fas fa-gavel"></i>
                    </div>
                    <span class="badge-custom bg-info text-white">RK2</span>
                    <h5 class="card-title text-dark">Perkara Diputus Banding</h5>
                    <p class="text-muted small mb-4">Statistik perkara banding yang diputus.</p>
                    {{-- SERAGAM: btn-outline-info --}}
                    <div class="btn btn-outline-info btn-sm w-100 rounded-pill mt-auto">Lihat Laporan</div>
                </a>
            </div>

            {{-- RK3 --}}
            <div class="custom-col-5 animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
                <a href="{{ route('laporan.index') }}" class="menu-card">
                    <div class="icon-box bg-warning bg-opacity-10 text-warning">
                        <i class="fas fa-folder-plus"></i>
                    </div>
                    <span class="badge-custom bg-warning text-dark">RK3</span>
                    <h5 class="card-title text-dark">Perkara Diterima Satker</h5>
                    <p class="text-muted small mb-4">Statistik perkara diterima oleh PA.</p>
                    {{-- SERAGAM: btn-outline-warning --}}
                    <div class="btn btn-outline-warning btn-sm w-100 rounded-pill mt-auto">Lihat Laporan</div>
                </a>
            </div>

            {{-- RK4 --}}
            <div class="custom-col-5 animate__animated animate__fadeInUp" style="animation-delay: 0.3s">
                <a href="{{ route('laporan-putus.index') }}" class="menu-card">
                    <div class="icon-box bg-danger bg-opacity-10 text-danger">
                        <i class="fas fa-balance-scale"></i>
                    </div>
                    <span class="badge-custom bg-danger text-white">RK4</span>
                    <h5 class="card-title text-dark">Perkara Diputus Satker</h5>
                    <p class="text-muted small mb-4">Statistik perkara diputus oleh PA.</p>
                    {{-- SERAGAM: btn-outline-danger --}}
                    <div class="btn btn-outline-danger btn-sm w-100 rounded-pill mt-auto">Lihat Laporan</div>
                </a>
            </div>

            {{-- JENIS PERKARA --}}
            <div class="custom-col-5 animate__animated animate__fadeInUp" style="animation-delay: 0.15s">
                <a href="{{ route('laporan.banding.jenis') }}" class="menu-card">
                    <div class="icon-box bg-success bg-opacity-10 text-success">
                        <i class="fas fa-list-ul"></i>
                    </div>
                    <span class="badge-custom bg-success text-white">JENIS</span>
                    <h5 class="card-title text-dark">Rekap Jenis Perkara</h5>
                    <p class="text-muted small mb-4">Statistik berdasarkan klasifikasi jenis.</p>
                    {{-- SERAGAM: btn-outline-success --}}
                    <div class="btn btn-outline-success btn-sm w-100 rounded-pill mt-auto">Lihat Laporan</div>
                </a>
            </div>

        </div>
    </main>

    <footer class="text-center">
        <div class="container">
            <p class="mb-0 opacity-50">&copy; {{ date('Y') }} Pengadilan Tinggi Agama Bandung</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>