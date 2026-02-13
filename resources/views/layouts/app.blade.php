<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem Statistik Perkara Pengadilan Agama">
    <title>@yield('title', 'Portal Data')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            /* Elegan Dark Blue */
            --accent-color: #ffd700;
            /* Warna Emas untuk highlight */
            --bg-body: #f8f9fa;
            --font-main: 'Inter', sans-serif;
        }

        body {
            font-family: var(--font-main);
            background-color: var(--bg-body);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Navbar Styling - Publik */
        .navbar-public {
            background: var(--primary-gradient);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.35rem;
            letter-spacing: 0.5px;
            color: #fff !important;
        }

        .navbar-brand small {
            display: block;
            font-size: 0.75rem;
            font-weight: 400;
            opacity: 0.8;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .nav-link:hover,
        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.15);
            color: #fff !important;
            transform: translateY(-2px);
        }

        /* Clock Widget di Pojok Kanan */
        .header-clock {
            color: #fff;
            text-align: right;
            line-height: 1.2;
        }

        .header-clock .time {
            font-size: 1.2rem;
            font-weight: 700;
            font-variant-numeric: tabular-nums;
            /* Agar angka tidak goyang saat berubah */
        }

        .header-clock .date {
            font-size: 0.75rem;
            opacity: 0.8;
        }

        /* Content Animation */
        main {
            flex: 1;
            padding-bottom: 2rem;
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Footer */
        .footer-public {
            background-color: #fff;
            border-top: 1px solid #dee2e6;
            padding: 1.5rem 0;
            color: #6c757d;
            font-size: 0.9rem;
        }
    </style>

    @stack('styles')
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-public sticky-top">
        <div class="container-fluid px-4">
            <a class="navbar-brand d-flex align-items-center gap-3" href="{{ url('/') }}">
                <div class="bg-white rounded-circle d-flex justify-content-center align-items-center" style="width: 45px; height: 45px;">
                    <i class="fas fa-balance-scale text-dark fs-5"></i>
                </div>
                <div>
                    PANMUD HUKUM CONNECT
                    <small>Pengadilan Agama Se-Jawa Barat</small>
                </div>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#publicNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-center" id="publicNav">
                <ul class="navbar-nav mb-2 mb-lg-0 gap-2">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                            <i class="fas fa-home me-1"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('jadwal-sidang*') ? 'active' : '' }}" href="{{ route('sidang.index') }}">
                            <i class="fas fa-chart-pie me-1"></i> Jadwal Sidang
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('kasasi*') ? 'active' : '' }}" href="{{ route('kasasi.index') }}">
                            <i class="fas fa-chart-pie me-1"></i> Data Kasasi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-search me-1"></i> Penelusuran Perkara
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-info-circle me-1"></i> Informasi
                        </a>
                    </li>
                </ul>
            </div>

            <div class="d-none d-lg-block header-clock">
                <div class="time" id="digital-clock">00:00:00</div>
                <div class="date">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</div>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>

    <footer class="footer-public mt-auto">
        <div class="container text-center">
            <div class="row justify-content-center align-items-center">
                <div class="col-md-8">
                    <h6 class="fw-bold text-dark mb-1">PENGADILAN TINGGI AGAMA BANDUNG</h6>
                    <p class="mb-0 small">Jl. Soekarno Hatta No.714, Bandung, Jawa Barat</p>
                    <hr class="my-3 w-50 mx-auto opacity-25">
                    <p class="mb-0 small text-muted">
                        &copy; {{ date('Y') }} Sistem Statistik Perkara Terintegrasi. All Rights Reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            }).replace(/\./g, ':');

            document.getElementById('digital-clock').textContent = timeString;
        }

        // Update setiap detik
        setInterval(updateClock, 1000);
        updateClock(); // Jalankan langsung saat load
    </script>

    @stack('scripts')
</body>

</html>