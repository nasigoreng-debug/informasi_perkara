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
            --bg-light: #f0f4f8;
            --indigo-mewah: #4f46e5;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-light);
            color: #2d3436;
        }

        .hero-header {
            background: linear-gradient(135deg, var(--primary-dark) 0%, #2a4858 100%);
            padding: 80px 0 120px;
            color: white;
            border-bottom-left-radius: 60px;
            border-bottom-right-radius: 60px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .main-container {
            margin-top: -80px;
            padding-bottom: 100px;
        }

        /* Grid 3 Kolom agar Kartu Besar & Mantap */
        .luxury-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
        }

        @media (max-width: 992px) {
            .luxury-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 600px) {
            .luxury-grid { grid-template-columns: 1fr; }
        }

        .menu-card {
            border: none;
            border-radius: 30px;
            background: white;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            text-decoration: none !important;
            padding: 40px 30px;
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
            top: 0; left: 0; width: 100%; height: 5px;
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
            width: 90px;
            height: 90px;
            border-radius: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 25px;
            font-size: 2.8rem;
            transition: 0.3s;
        }

        .menu-card:hover .icon-box {
            transform: scale(1.1) rotate(5deg);
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 12px;
        }

        .card-desc {
            font-size: 0.95rem;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .badge-custom {
            font-size: 0.8rem;
            padding: 6px 16px;
            border-radius: 50px;
            margin-bottom: 15px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .btn-action {
            font-weight: 700;
            padding: 10px 25px;
            border-radius: 15px;
            transition: 0.3s;
            width: 100%;
        }
    </style>
</head>

<body>
    <header class="hero-header text-center">
        <div class="container px-4">
            <a href="{{ route('welcome') }}" class="btn btn-outline-light rounded-pill mb-4 px-4 shadow-sm">
                <i class="fas fa-arrow-left me-2"></i> Kembali ke Portal Utama
            </a>
            <h1 class="display-4 fw-800 animate__animated animate__fadeInDown">Laporan Perkara</h1>
            <p class="fs-5 opacity-75 mx-auto mb-0" style="max-width: 700px;">
                Akses cepat rekapitulasi data statistik perkara (RK1 - RK4) dan Laporan Putusan Sela secara real-time.
            </p>
        </div>
    </header>

    <main class="main-container container">
        <div class="luxury-grid">

            {{-- RK1 --}}
            <div class="animate__animated animate__fadeInUp">
                <a href="{{ route('laporan.banding.diterima') }}" class="menu-card text-primary">
                    <div class="icon-box bg-primary bg-opacity-10">
                        <i class="fas fa-file-import"></i>
                    </div>
                    <span class="badge-custom bg-primary text-white">RK1</span>
                    <h5 class="card-title">Perkara Diterima Banding</h5>
                    <p class="card-desc">Monitoring statistik harian perkara banding yang masuk ke sistem.</p>
                    <div class="btn-action btn btn-outline-primary mt-auto">Buka Laporan</div>
                </a>
            </div>

            {{-- RK2 --}}
            <div class="animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
                <a href="{{ route('laporan.banding.putus') }}" class="menu-card text-info">
                    <div class="icon-box bg-info bg-opacity-10">
                        <i class="fas fa-gavel"></i>
                    </div>
                    <span class="badge-custom bg-info text-white">RK2</span>
                    <h5 class="card-title">Perkara Diputus Banding</h5>
                    <p class="card-desc">Data rekapitulasi perkara banding yang telah selesai diputus.</p>
                    <div class="btn-action btn btn-outline-info mt-auto">Buka Laporan</div>
                </a>
            </div>

            {{-- RK3 --}}
            <div class="animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
                <a href="{{ route('laporan.index') }}" class="menu-card text-warning">
                    <div class="icon-box bg-warning bg-opacity-10">
                        <i class="fas fa-folder-plus"></i>
                    </div>
                    <span class="badge-custom bg-warning text-dark">RK3</span>
                    <h5 class="card-title">Perkara Diterima Satker</h5>
                    <p class="card-desc">Statistik penerimaan perkara pada Pengadilan Agama (Satker).</p>
                    <div class="btn-action btn btn-outline-warning mt-auto">Buka Laporan</div>
                </a>
            </div>

            {{-- RK4 --}}
            <div class="animate__animated animate__fadeInUp" style="animation-delay: 0.3s">
                <a href="{{ route('laporan-putus.index') }}" class="menu-card text-danger">
                    <div class="icon-box bg-danger bg-opacity-10">
                        <i class="fas fa-balance-scale"></i>
                    </div>
                    <span class="badge-custom bg-danger text-white">RK4</span>
                    <h5 class="card-title">Perkara Diputus Satker</h5>
                    <p class="card-desc">Laporan perkara yang telah diputus oleh Pengadilan Agama.</p>
                    <div class="btn-action btn btn-outline-danger mt-auto">Buka Laporan</div>
                </a>
            </div>

            {{-- JENIS PERKARA --}}
            <div class="animate__animated animate__fadeInUp" style="animation-delay: 0.4s">
                <a href="{{ route('laporan.banding.jenis') }}" class="menu-card text-success">
                    <div class="icon-box bg-success bg-opacity-10">
                        <i class="fas fa-list-ul"></i>
                    </div>
                    <span class="badge-custom bg-success text-white">STATISTIK</span>
                    <h5 class="card-title">Rekap Jenis Perkara</h5>
                    <p class="card-desc">Klasifikasi statistik perkara berdasarkan jenis sengketa.</p>
                    <div class="btn-action btn btn-outline-success mt-auto">Buka Laporan</div>
                </a>
            </div>

            {{-- PUTUSAN SELA --}}
            <div class="animate__animated animate__fadeInUp" style="animation-delay: 0.5s">
                <a href="{{ route('laporan-putus.putusan.sela') }}" class="menu-card" style="color: var(--indigo-mewah);">
                    <div class="icon-box" style="background: rgba(79, 70, 229, 0.1);">
                        <i class="fas fa-file-signature"></i>
                    </div>
                    <span class="badge-custom" style="background: var(--indigo-mewah); color: white;">KHUSUS</span>
                    <h5 class="card-title">Laporan Putusan Sela</h5>
                    <p class="card-desc">Daftar perkara banding yang memiliki putusan sela/provisi.</p>
                    <div class="btn-action btn btn-outline-indigo mt-auto" style="border-color: var(--indigo-mewah); color: var(--indigo-mewah);">Buka Laporan</div>
                </a>
            </div>

        </div>
    </main>

    <footer class="text-center">
        <div class="container border-top pt-4">
            <p class="mb-0 fw-bold opacity-50">&copy; {{ date('Y') }} Pengadilan Tinggi Agama Bandung</p>
            <small class="text-muted">Sistem Informasi Laporan Perkara Terintegrasi</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>