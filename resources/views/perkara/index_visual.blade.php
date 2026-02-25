@extends('layouts.app')

@section('title', 'Display Jadwal Sidang')

@section('content')

{{-- Progress Bar & Loading Overlay --}}
<div class="progress fixed-top" style="height: 4px; z-index: 2000;">
    <div id="refreshBar" class="progress-bar bg-primary" role="progressbar" style="width: 100%"></div>
</div>

<div class="loading-overlay active" id="loadingOverlay">
    <div class="text-center">
        <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;"></div>
        <div class="fw-bold text-dark h5">Sinkronisasi Data Sidang...</div>
    </div>
</div>

@if(count($perkaras) > 0)
    <div class="container-fluid px-4 py-3">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4 animate__animated animate__fadeInDown">
            <div>
                <h5 class="text-uppercase text-primary small fw-bold mb-0 ls-1">
                    <i class="fas fa-building me-1"></i> Pengadilan Tinggi Agama Bandung
                </h5>
                <h1 class="fw-extrabold text-dark mb-0 display-6">Jadwal Sidang Hari Ini</h1>
                <p class="text-muted mb-0">
                    <i class="far fa-calendar-alt me-1"></i> {{ $hariIniFormatted ?? date('d F Y') }}
                    <span class="mx-2">|</span>
                    <i class="far fa-clock me-1 text-primary"></i> Jam: <span class="liveClock fw-bold">00:00:00</span>
                </p>
            </div>
            <div class="text-end d-none d-md-block">
                <div class="badge bg-white shadow-sm text-dark p-3 border rounded-3">
                    <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.6rem;">Total Perkara</small>
                    <span class="h4 fw-bold mb-0 text-primary">{{ count($perkaras) }}</span>
                </div>
            </div>
        </div>

        {{-- WIDGET VISITOR (DATA DARI DATABASE) --}}
        <div class="row g-3 mb-4 animate__animated animate__fadeInUp">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-3 p-2 border-start border-primary border-4">
                    <div class="d-flex align-items-center ps-2">
                        <i class="fas fa-users text-primary me-3"></i>
                        <div>
                            <small class="text-muted d-block fw-bold small">ONLINE</small>
                            <span class="h5 fw-bold mb-0">{{ $visitorStats['online'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-3 p-2 border-start border-success border-4">
                    <div class="d-flex align-items-center ps-2">
                        <i class="fas fa-user-check text-success me-3"></i>
                        <div>
                            <small class="text-muted d-block fw-bold small">HARI INI</small>
                            <span class="h5 fw-bold mb-0">{{ $visitorStats['today'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-3 p-2 border-start border-info border-4">
                    <div class="d-flex align-items-center ps-2">
                        <i class="fas fa-calendar-day text-info me-3"></i>
                        <div>
                            <small class="text-muted d-block fw-bold small">BULAN INI</small>
                            <span class="h5 fw-bold mb-0">{{ number_format($visitorStats['month'] ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-3 p-2 border-start border-dark border-4">
                    <div class="d-flex align-items-center ps-2">
                        <i class="fas fa-eye text-dark me-3"></i>
                        <div>
                            <small class="text-muted d-block fw-bold small">TOTAL PENGUNJUNG</small>
                            <span class="h5 fw-bold mb-0">{{ number_format($visitorStats['total'] ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel Jadwal --}}
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden animate__animated animate__fadeIn">
            <div class="card-body p-0">
                <div class="table-responsive" id="scrollContainer">
                    <table class="table table-clean w-100 mb-0" id="sidangTable">
                        <thead>
                            <tr>
                                <th class="ps-4">No</th>
                                <th>No. Perkara</th>
                                <th class="text-center">Jenis Perkara</th>
                                <th>Ketua Majelis</th>
                                <th class="text-center">Ruangan</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            @foreach($perkaras as $index => $perkara)
                            @php
                                $jp = strtolower($perkara->jenis_perkara ?? '');
                                $badgeJenis = 'badge-soft-secondary';
                                if(str_contains($jp, 'cerai')) $badgeJenis = 'badge-soft-warning';
                                elseif(str_contains($jp, 'waris')) $badgeJenis = 'badge-soft-success';
                                elseif(str_contains($jp, 'ekonomi')) $badgeJenis = 'badge-soft-info';
                            @endphp
                            <tr>
                                <td class="ps-4 fw-bold text-muted">{{ $index + 1 }}</td>
                                <td>
                                    {{-- NOMOR PERKARA BERGERAK --}}
                                    <div class="fw-bold text-primary font-mono h5 mb-0 moving-number">{{ $perkara->nomor_perkara_banding ?? '-' }}</div> <br>
                                    <small class="text-muted">{{ $perkara->nomor_perkara_pa ?? '' }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $badgeJenis }} px-3 py-2">
                                        {{ strtoupper($perkara->jenis_perkara ?? 'Lain-lain') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2 bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; border: 1px solid #eee;">
                                            <i class="fas fa-user-tie text-secondary"></i>
                                        </div>
                                        <span class="fw-bold">{{ $perkara->kode_hakim ?? $perkara->ketua_majelis ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border px-3 py-2">
                                        <i class="fas fa-door-open me-1 text-primary"></i> {{ $perkara->ruangan ?? 'R. Sidang Utama' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success px-3 py-2 pulse-green">Terjadwal</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot id="tableFooter" class="d-none">
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted border-0">
                                    <i class="fas fa-sync fa-spin me-2 text-primary"></i> Mengulang Daftar Dari Awal...
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4 text-muted small px-2">
            <div>
                <i class="fas fa-info-circle me-1 text-primary"></i> Layar akan dimuat ulang otomatis dalam <span id="countdownText" class="fw-bold">5:00</span> menit.
            </div>
            <div class="fw-bold">
                PENGADILAN TINGGI AGAMA BANDUNG
            </div>
        </div>
    </div>
@else
    {{-- LAYAR KOSONG SAAT TIDAK ADA SIDANG --}}
    <div class="d-flex justify-content-center align-items-center vh-100 bg-white shadow-sm rounded-4 m-3">
        <div class="text-center animate__animated animate__fadeIn">
            <div class="mb-4 animate__animated animate__pulse animate__infinite animate__slower">
                <i class="fas fa-calendar-check fa-5x text-success opacity-75"></i>
            </div>
            <h2 class="text-dark fw-bold mb-1">Informasi Perkara</h2>
            <h4 class="text-muted fw-light">Tidak Ada Jadwal Persidangan Hari Ini</h4>
            <div class="mt-4 pt-3 border-top">
                <p class="text-muted mb-1 h5">
                    <i class="far fa-clock me-2"></i> Jam: <span class="liveClock fw-bold">00:00:00</span>
                </p>
                <p class="text-primary small fw-bold ls-1 mt-2 text-uppercase">Pengadilan Tinggi Agama Bandung</p>
            </div>
        </div>
    </div>
@endif

@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
<style>
    body { background-color: #f8f9fa; overflow: hidden; }
    .ls-1 { letter-spacing: 1px; }
    .font-mono { font-family: 'JetBrains Mono', monospace; }
    
    /* Animasi Bergerak Nomor Perkara */
    @keyframes moveSlide {
        0% { transform: translateX(0); }
        50% { transform: translateX(8px); color: #0d6efd; text-shadow: 0 0 8px rgba(13, 110, 253, 0.2); }
        100% { transform: translateX(0); }
    }
    .moving-number { display: inline-block; animation: moveSlide 3s infinite ease-in-out; }

    /* Efek Berdenyut Status */
    @keyframes pulse-green {
        0% { box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(25, 135, 84, 0); }
        100% { box-shadow: 0 0 0 0 rgba(25, 135, 84, 0); }
    }
    .pulse-green { animation: pulse-green 2s infinite; border-radius: 50px; }

    .table-responsive { height: 60vh; overflow-y: hidden; position: relative; background: #fff; }
    .table-clean thead th { background: #f8f9fa; position: sticky; top: 0; z-index: 100; padding: 20px; text-transform: uppercase; font-size: 0.8rem; color: #6c757d; border-bottom: 2px solid #dee2e6; box-shadow: 0 2px 5px rgba(0,0,0,0.02); }
    .table-clean tbody td { padding: 22px 20px; vertical-align: middle; background: #fff; border-bottom: 1px solid #edf2f7; transition: all 0.3s ease; }
    .row-focus td { background-color: #fff3cd !important; color: #856404 !important; transform: scale(1.002); box-shadow: inset 4px 0 0 #ffc107; }
    
    .badge-soft-warning { background: #fff3cd; color: #856404; }
    .badge-soft-success { background: #d4edda; color: #155724; }
    .badge-soft-info { background: #d1ecf1; color: #0c5460; }
    .badge-soft-secondary { background: #e2e3e5; color: #383d41; }
    
    .loading-overlay { position: fixed; inset: 0; background: white; z-index: 9999; display: flex; align-items: center; justify-content: center; opacity: 0; visibility: hidden; transition: 0.5s; }
    .loading-overlay.active { opacity: 1; visibility: visible; }
</style>
@endpush

@push('scripts')
<script>
    (function() {
        // --- SAFETY GUARD: PAKSA HAPUS LOADING ---
        function forceHideLoading() {
            const overlay = document.getElementById('loadingOverlay');
            if(overlay) {
                overlay.classList.remove('active');
                console.log("Loading removed");
            }
        }
        window.onload = forceHideLoading;
        setTimeout(forceHideLoading, 2000); // Pasti hilang dalam 2 detik

        const hasData = {{ count($perkaras) > 0 ? 'true' : 'false' }};

        // 1. Timer Refresh Global
        let timeLeft = 300; 
        setInterval(() => {
            timeLeft--;
            const bar = document.getElementById('refreshBar');
            const text = document.getElementById('countdownText');
            if (bar) bar.style.width = (timeLeft / 300 * 100) + '%';
            if (text) {
                let m = Math.floor(timeLeft / 60), s = timeLeft % 60;
                text.textContent = m + ":" + (s < 10 ? '0' : '') + s;
            }
            if (timeLeft <= 0) window.location.reload();
        }, 1000);

        // 2. Jam Digital
        setInterval(() => {
            const clocks = document.querySelectorAll('.liveClock');
            const now = new Date().toLocaleTimeString('id-ID', {hour12: false});
            clocks.forEach(clock => { if(clock) clock.textContent = now.replace(/\./g, ':'); });
        }, 1000);

        // 3. Auto Scroll
        if (hasData) {
            initAutoScroll();
        }

        function initAutoScroll() {
            const container = document.getElementById('scrollContainer');
            if (!container) return;
            const rows = document.querySelectorAll('#tableBody tr');
            if (rows.length <= 4) return;

            let scrollPos = 0;
            let isPaused = false;
            const speed = 0.6; 
            const footer = document.getElementById('tableFooter');

            function move() {
                if (!isPaused) {
                    scrollPos += speed;
                    container.scrollTop = scrollPos;
                    const center = container.getBoundingClientRect().top + (container.offsetHeight / 2.5);
                    
                    rows.forEach(row => {
                        const rect = row.getBoundingClientRect();
                        if (rect.top < center && rect.bottom > center) row.classList.add('row-focus');
                        else row.classList.remove('row-focus');
                    });

                    if (scrollPos >= (container.scrollHeight - container.offsetHeight - 1)) {
                        isPaused = true;
                        if(footer) footer.classList.remove('d-none');
                        setTimeout(() => {
                            scrollPos = 0;
                            container.scrollTop = 0;
                            if(footer) footer.classList.add('d-none');
                            isPaused = false;
                        }, 4000);
                    }
                }
                requestAnimationFrame(move);
            }
            setTimeout(() => requestAnimationFrame(move), 3000);
        }
    })();
</script>
@endpush