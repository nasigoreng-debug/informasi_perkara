<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Utama | PTA</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f0f2f5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            margin: 0;
        }

        .hero-header {
            background: linear-gradient(135deg, #1a2a6c 0%, #2a4858 100%);
            padding: 100px 0 120px;
            color: white;
            border-bottom-left-radius: 80px;
            border-bottom-right-radius: 80px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .main-container {
            margin-top: -80px;
            flex: 1;
        }

        .menu-card {
            border: none;
            border-radius: 30px;
            background: white;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 50px 30px;
            height: 100%;
        }

        .menu-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1) !important;
        }

        .icon-wrapper {
            width: 100px;
            height: 100px;
            border-radius: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px;
            font-size: 3rem;
            transition: transform 0.3s ease;
        }

        .menu-card:hover .icon-wrapper {
            transform: scale(1.1) rotate(5deg);
        }

        .btn-custom {
            border-radius: 15px;
            padding: 10px 30px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s;
        }

        footer {
            padding: 40px 0;
            color: #636e72;
            text-align: center;
        }

        .badge-online {
            font-size: 0.7rem;
            padding: 5px 12px;
            border-radius: 50px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
        }
    </style>
</head>

<body>

    <header class="hero-header text-center">
        <div class="container">
            <span class="badge-online mb-3 d-inline-block text-uppercase fw-bold">
                <i class="fas fa-circle text-success me-2"></i> Sistem Terintegrasi Online
            </span>
            <h1 class="display-4 fw-bold mb-2">Portal Layanan Perkara</h1>
            <p class="lead opacity-75 mx-auto" style="max-width: 600px;">
                Selamat datang di portal monitoring perkara <br> Pengadilan Tinggi Agama Bandung
            </p>
        </div>
    </header>

    <main class="main-container container">
        <div class="row justify-content-center g-4">

            <div class="col-md-5 col-xl-4">
                <a href="{{ route('kasasi.index') }}" class="card menu-card shadow-sm">
                    <div class="icon-wrapper bg-primary bg-opacity-10 text-primary">
                        <i class="fas fa-file-contract"></i>
                    </div>
                    <h3 class="fw-bold text-dark mb-3">Monitoring Kasasi</h3>
                    <p class="text-muted mb-4">
                        Pantau pendaftaran, register, hingga status perkara kasasi dari seluruh Satuan Kerja.
                    </p>
                    <div class="mt-auto w-100">
                        <div class="btn btn-primary btn-custom w-100">Lihat Perkara</div>
                    </div>
                </a>
            </div>

            <div class="col-md-5 col-xl-4">
                {{-- Ganti URL di bawah dengan route jadwal sidang Anda --}}
                <a href="/jadwal-sidang" class="card menu-card shadow-sm">
                    <div class="icon-wrapper bg-success bg-opacity-10 text-success">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h3 class="fw-bold text-dark mb-3">Jadwal Sidang</h3>
                    <p class="text-muted mb-4">
                        Lihat jadwal persidangan Pengadilan Tinggi Agama Bandung yang sedang berlangsung maupun yang akan datang secara aktual.
                    </p>
                    <div class="mt-auto w-100">
                        <div class="btn btn-success btn-custom w-100">Lihat Jadwal</div>
                    </div>
                </a>
            </div>

        </div>
    </main>

    <footer>
        <div class="container">
            <p class="mb-1 fw-bold">Pengadilan Tinggi Agama Bandung</p>
            <p class="small mb-0 opacity-75">Dikembangkan untuk kemudahan akses informasi internal & publik &copy; 2026</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>