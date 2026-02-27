<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem Statistik Perkara Pengadilan Agama">
    <title>@yield('title', 'Panmud Hukum | PTA Bandung')</title>

    <link rel="shortcut icon" href="{{ asset('/favicon/favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            --pta-gold: #ffd700;
            --bg-body: #f8f9fa;
            --font-main: 'Inter', sans-serif;
        }

        /* --- LOADING SCREEN CSS --- */
        #loader-wrapper {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: var(--primary-gradient);
            z-index: 99999;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            transition: opacity 0.4s ease, visibility 0.4s;
        }

        .loader-content { text-align: center; }

        .logo-loader {
            font-size: 70px;
            color: var(--pta-gold);
            margin-bottom: 20px;
            animation: pulse-gold 2s infinite;
        }

        .loader-text h5 {
            letter-spacing: 4px;
            font-weight: 800;
            margin-bottom: 5px;
        }

        .loader-text p {
            font-size: 0.85rem;
            opacity: 0.8;
            font-style: italic;
        }

        .spinner-custom {
            width: 40px;
            height: 40px;
            border: 3px solid rgba(255,255,255,0.1);
            border-top: 3px solid var(--pta-gold);
            border-radius: 50%;
            margin: 25px auto;
            animation: spin 1s linear infinite;
        }

        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        @keyframes pulse-gold {
            0% { transform: scale(0.95); filter: drop-shadow(0 0 0 rgba(255, 215, 0, 0.7)); }
            70% { transform: scale(1); filter: drop-shadow(0 0 20px rgba(255, 215, 0, 0)); }
            100% { transform: scale(0.95); filter: drop-shadow(0 0 0 rgba(255, 215, 0, 0)); }
        }

        .loader-hidden {
            opacity: 0;
            visibility: hidden;
        }
        /* --- END LOADING SCREEN CSS --- */

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

        .navbar-brand { font-weight: 700; font-size: 1.1rem; color: #fff !important; }
        .navbar-brand small { display: block; font-size: 0.65rem; font-weight: 400; opacity: 0.8; text-transform: uppercase; }

        .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            font-weight: 500; font-size: 0.85rem;
            padding: 0.6rem 1.2rem !important;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .nav-link:hover, .nav-link.active {
            background-color: rgba(255, 255, 255, 0.15);
            color: #fff !important;
        }

        .user-dropdown .dropdown-toggle {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white; padding: 0.5rem 1rem;
            border-radius: 50px; font-size: 0.85rem;
        }

        .header-clock {
            color: #fff; text-align: right; line-height: 1.2;
            border-left: 1px solid rgba(255,255,255,0.2);
            padding-left: 15px; margin-left: 15px;
        }

        .header-clock .time { font-size: 1.1rem; font-weight: 700; }
        .header-clock .date { font-size: 0.7rem; opacity: 0.8; }

        main { flex: 1; padding: 0; }

        .footer-public {
            background-color: #fff; border-top: 1px solid #dee2e6;
            padding: 1.5rem 0; color: #6c757d; font-size: 0.85rem;
        }
    </style>

    @stack('styles')
</head>

<body>
    <div id="loader-wrapper">
        <div class="loader-content">
            <div class="logo-loader">
                <i class="fas fa-balance-scale"></i>
            </div>
            <div class="loader-text">
                <h5>PANMUD HUKUM</h5>
                <p>Sinkronisasi data sedang berlangsung...</p>
            </div>
            <div class="spinner-custom"></div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-dark navbar-public sticky-top">
        <div class="container-fluid px-4">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
                <div class="bg-white rounded-circle d-flex justify-content-center align-items-center" style="width: 35px; height: 35px;">
                    <i class="fas fa-balance-scale text-dark fs-6"></i>
                </div>
                <div>
                    PANMUD HUKUM CONNECTION
                    <small>
                        @auth
                            {{ Auth::user()->satker ? Auth::user()->satker->nama : 'PTA BANDUNG (ADMIN)' }}
                        @else
                            Pengadilan Agama Se-Jawa Barat
                        @endauth
                    </small>
                </div>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#publicNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="publicNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-3">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                            <i class="fas fa-home me-1"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="fas fa-chart-line me-1"></i> Dashboard
                        </a>
                    </li>
                    @auth
                        @if(Auth::user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                                <i class="fas fa-users-cog me-1"></i> Manajemen User
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('activity-log*') ? 'active' : '' }}" href="{{ url('/activity-log') }}">
                                <i class="fas fa-fingerprint me-1"></i> Log Aktivitas
                            </a>
                        </li>
                        @endif
                    @endauth
                </ul>

                <div class="d-flex align-items-center">
                    @auth
                        <div class="dropdown user-dropdown">
                            <button class="btn dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle fs-5 text-warning"></i>
                                <div class="text-start d-none d-sm-block">
                                    <div class="fw-bold" style="font-size: 0.75rem; line-height: 1;">{{ Auth::user()->name }}</div>
                                    <div style="font-size: 0.65rem; opacity: 0.8;">{{ optional(Auth::user()->role)->nama_role ?? 'Pengguna' }}</div>
                                </div>
                                <i class="fas fa-chevron-down ms-1" style="font-size: 0.6rem;"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius: 15px;">
                                <li class="px-3 py-2 border-bottom mb-1">
                                    <span class="d-block small text-muted">ID: {{ Auth::user()->username }}</span>
                                </li>
                                <li>
                                    <form id="logout-form-main" action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2">
                                            <i class="fas fa-power-off"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endauth

                    <div class="d-none d-lg-block header-clock">
                        <div class="time" id="digital-clock">00:00:00</div>
                        <div class="date" id="current-date"></div>
                    </div>
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
            <p class="mb-0 small text-muted">&copy; {{ date('Y') }} Sistem Statistik Perkara - Bidang Kepaniteraan Hukum.</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // --- LOGIKA LOADING SCREEN SULTAN ---
        const loader = document.getElementById('loader-wrapper');

        // 1. Menghilangkan loader saat halaman selesai dimuat 100%
        window.addEventListener('load', function() {
            setTimeout(() => {
                loader.classList.add('loader-hidden');
            }, 500); 
        });

        // 2. Memunculkan loader saat klik menu (inter-page loading)
        document.addEventListener('DOMContentLoaded', function() {
            // Target semua link yang berpindah halaman dalam aplikasi
            const links = document.querySelectorAll('a:not([target="_blank"]):not([href^="#"]):not([href^="javascript"]):not(.no-loader)');

            links.forEach(link => {
                link.addEventListener('click', function(e) {
                    // Hanya jika klik kiri dan link valid (tidak kosong)
                    if (e.button === 0 && !e.ctrlKey && !e.metaKey && this.getAttribute('href') !== null) {
                        loader.classList.remove('loader-hidden');
                    }
                });
            });

            // Munculkan loader saat submit form (seperti Login, Filter, Search)
            const forms = document.querySelectorAll('form:not(.no-loader)');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    loader.classList.remove('loader-hidden');
                });
            });
        });

        // --- SCRIPT JAM DIGITAL ---
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false });
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