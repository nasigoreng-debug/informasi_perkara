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
            --accent-color: #ffd700;
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

        .navbar-public {
            background: var(--primary-gradient);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            padding: 0.75rem 0;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.1rem;
            color: #fff !important;
        }

        .navbar-brand small {
            display: block;
            font-size: 0.65rem;
            font-weight: 400;
            opacity: 0.8;
            text-transform: uppercase;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            font-weight: 500;
            font-size: 0.85rem;
            padding: 0.6rem 1.2rem !important;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .nav-link:hover,
        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.15);
            color: #fff !important;
        }

        .header-clock {
            color: #fff;
            text-align: right;
            line-height: 1.2;
        }

        .header-clock .time {
            font-size: 1.1rem;
            font-weight: 700;
        }

        .header-clock .date {
            font-size: 0.7rem;
            opacity: 0.8;
        }

        main {
            flex: 1;
            /* Padding dikurangi agar hero header landing page bisa menempel ke atas */
            padding: 0; 
        }

        .footer-public {
            background-color: #fff;
            border-top: 1px solid #dee2e6;
            padding: 1.5rem 0;
            color: #6c757d;
            font-size: 0.85rem;
        }
    </style>

    @stack('styles')
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-public sticky-top">
        <div class="container-fluid px-4">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
                <div class="bg-white rounded-circle d-flex justify-content-center align-items-center" style="width: 35px; height: 35px;">
                    <i class="fas fa-balance-scale text-dark fs-6"></i>
                </div>
                <div>
                    PANMUD HUKUM CONNECT
                    <small>Pengadilan Agama Se-Jawa Barat</small>
                </div>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#publicNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="publicNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-3">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('welcome') ? 'active' : '' }}" href="{{ url('/') }}">
                            <i class="fas fa-home me-1"></i> Beranda
                        </a>
                    </li>
                </ul>

                <div class="d-none d-lg-block header-clock">
                    <div class="time" id="digital-clock">00:00:00</div>
                    <div class="date" id="current-date"></div>
                </div>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="footer-public mt-auto">
        <div class="container text-center">
            <h6 class="fw-bold text-dark mb-1">PENGADILAN TINGGI AGAMA BANDUNG</h6>
            <p class="mb-0 small text-muted">&copy; {{ date('Y') }} Sistem Statistik Perkara.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', {
                hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false
            });
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            
            const clockEl = document.getElementById('digital-clock');
            const dateEl = document.getElementById('current-date');
            
            if (clockEl) clockEl.textContent = timeString.replace(/\./g, ':');
            if (dateEl) dateEl.textContent = now.toLocaleDateString('id-ID', options);
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>

    @stack('scripts')
</body>
</html>