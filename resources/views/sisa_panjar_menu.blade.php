<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Sisa Panjar | PTA Bandung</title>

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
            background: linear-gradient(135deg, #b22222 0%, #8b0000 100%);
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
                <i class="fas fa-arrow-left me-2"></i> Kembali ke Dashboard
            </a>
            <h1 class="fw-bold animate__animated animate__fadeInDown">Sisa Panjar Perkara</h1>
            <p class="opacity-75 mx-auto mb-0" style="max-width: 600px;">
                Monitoring sisa panjar biaya perkara tingkat Banding, Kasasi, dan PK yang sudah putus dan 6 bulan sejak pemberitahuan putusan belum PSP/Setor ke kas negara.
            </p>
        </div>
    </header>

    <main class="main-container container px-4">
        <div class="row g-4 justify-content-center">

            <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp">
                <a href="{{ route('sisa.pertama') }}" class="menu-card border-top border-info border-4">
                    <div class="icon-box bg-info bg-opacity-10 text-info">
                        <i class="fas fa-balance-scale"></i>
                    </div>
                    <h5 class="card-title text-dark">Sisa Panjar TK.I</h5>
                    <p class="text-muted small mb-4">Monitoring sisa panjar perkara tingkat pertama di wilayah PTA Bandung.</p>
                    <div class="btn btn-outline-info btn-sm rounded-pill w-100 mt-auto fw-bold">Lihat</div>
                </a>
            </div>

            <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp">
                <a href="{{ route('sisa.banding') }}" class="menu-card border-top border-primary border-4">
                    <div class="icon-box bg-primary bg-opacity-10 text-primary">
                        <i class="fas fa-balance-scale"></i>
                    </div>
                    <h5 class="card-title text-dark">Sisa Panjar Banding</h5>
                    <p class="text-muted small mb-4">Monitoring sisa panjar perkara tingkat Banding di wilayah PTA Bandung.</p>
                    <div class="btn btn-outline-primary btn-sm rounded-pill w-100 mt-auto fw-bold">Lihat</div>
                </a>
            </div>

            <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
                <a href="{{ route('sisa.kasasi') }}" class="menu-card border-top border-success border-4">
                    <div class="icon-box bg-success bg-opacity-10 text-success">
                        <i class="fas fa-gavel"></i>
                    </div>
                    <h5 class="card-title text-dark">Sisa Panjar Kasasi</h5>
                    <p class="text-muted small mb-4">Pantau data sisa panjar biaya perkara tingkat Kasasi secara real-time.</p>
                    <div class="btn btn-outline-success btn-sm rounded-pill w-100 mt-auto fw-bold">Lihat</div>
                </a>
            </div>

            <div class="col-md-6 col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
                <a href="{{ route('sisa.pk') }}" class="menu-card border-top border-danger border-4">
                    <div class="icon-box bg-danger bg-opacity-10 text-danger">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <h5 class="card-title text-dark">Sisa Panjar PK</h5>
                    <p class="text-muted small mb-4">Informasi sisa panjar biaya perkara Peninjauan Kembali (PK) se-Jawa Barat.</p>
                    <div class="btn btn-outline-danger btn-sm rounded-pill w-100 mt-auto fw-bold">Lihat</div>
                </a>
            </div>

        </div>
    </main>

    <footer class="text-center">
        <p>&copy; {{ date('Y') }} Pengadilan Tinggi Agama Bandung | Sistem Statistik Perkara</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>