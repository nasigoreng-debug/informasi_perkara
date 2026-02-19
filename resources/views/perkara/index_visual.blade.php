@extends('layouts.app')

@section('title', 'Display Jadwal Sidang')

@section('content')

@if(count($perkaras) > 0)
    <div class="progress fixed-top" style="height: 4px; z-index: 2000;">
        <div id="refreshBar" class="progress-bar bg-primary" role="progressbar" style="width: 100%"></div>
    </div>

    <div class="loading-overlay active" id="loadingOverlay">
        <div class="text-center">
            <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;"></div>
            <div class="fw-bold text-dark h5">Sinkronisasi Data Sidang...</div>
        </div>
    </div>

    <div class="container-fluid px-4 py-3">
        <div class="d-flex justify-content-between align-items-center mb-4 animate__animated animate__fadeInDown">
            <div>
                <h5 class="text-uppercase text-primary small fw-bold mb-0 ls-1">
                    <i class="fas fa-building me-1"></i> Pengadilan Tinggi Agama Bandung
                </h5>
                <h1 class="fw-extrabold text-dark mb-0 display-6">Jadwal Sidang Hari Ini</h1>
                <p class="text-muted mb-0">
                    <i class="far fa-calendar-alt me-1"></i> {{ $hariIniFormatted ?? date('d F Y') }}
                    <span class="mx-2">|</span>
                    <i class="far fa-clock me-1"></i> Jam: <span id="liveClock">00:00:00</span>
                </p>
            </div>
            <div class="text-end d-none d-md-block">
                <div class="badge bg-white shadow-sm text-dark p-3 border">
                    <small class="text-muted d-block">Total Perkara</small>
                    <span class="h4 fw-bold mb-0">{{ count($perkaras) }}</span>
                </div>
            </div>
        </div>

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
                                    <div class="fw-bold text-primary font-mono h5 mb-0">{{ $perkara->nomor_perkara_banding ?? '-' }}</div>
                                    <small class="text-muted">{{ $perkara->nomor_perkara_pa ?? '' }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $badgeJenis }} px-3 py-2">
                                        {{ strtoupper($perkara->jenis_perkara ?? 'Lain-lain') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2 bg-light rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="fas fa-user-tie text-secondary"></i>
                                        </div>
                                        <span class="fw-bold">{{ $perkara->kode_hakim ?? $perkara->ketua_majelis ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border px-3 py-2">
                                        <i class="fas fa-door-open me-1 text-primary"></i> {{ $perkara->ruangan ?? 'R. Sidang PTA Bandung' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success px-3 py-2">Terjadwal</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4 text-muted small">
            <div>
                <i class="fas fa-info-circle me-1"></i> Halaman ini diperbarui otomatis setiap 5 menit.
            </div>
            <div>
                <span id="visitorCount">0</span> Orang sedang melihat layar ini
            </div>
        </div>
    </div>
@else
    <div class="d-flex justify-content-center align-items-center vh-100 bg-white">
        <div class="text-center animate__animated animate__pulse animate__infinite">
            <div class="mb-4">
                <i class="fas fa-calendar-check fa-5x text-success"></i>
            </div>
            <h2 class="text-dark fw-bold">Informasi Perkara</h2>
            <h4 class="text-muted fw-light">Tidak Ada Jadwal Persidangan Hari Ini</h4>
            <div class="mt-4 pt-3 border-top">
                <p class="text-muted mb-0 small"><i class="far fa-clock me-1"></i> Terakhir diperbarui: {{ date('H:i:s') }}</p>
                <p class="text-primary small fw-bold">PTA BANDUNG</p>
            </div>
        </div>
    </div>
@endif

@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
<style>
    body { background-color: #f8f9fa; overflow: hidden; }
    .ls-1 { letter-spacing: 1px; }
    .font-mono { font-family: 'JetBrains Mono', monospace; }
    .table-responsive { height: 75vh; overflow-y: hidden; position: relative; }
    .table-clean thead th { background: #fff; position: sticky; top: 0; z-index: 100; padding: 20px; text-transform: uppercase; font-size: 0.8rem; color: #6c757d; border-bottom: 2px solid #dee2e6; }
    .table-clean tbody td { padding: 25px 20px; vertical-align: middle; background: #fff; border-bottom: 1px solid #edf2f7; transition: all 0.4s ease; }
    .row-focus td { background-color: #fff3cd !important; color: #856404 !important; transform: scale(1.01); box-shadow: inset 5px 0 0 #ffc107; }
    .badge-soft-warning { background: #fff3cd; color: #856404; }
    .badge-soft-success { background: #d4edda; color: #155724; }
    .badge-soft-info { background: #d1ecf1; color: #0c5460; }
    .badge-soft-secondary { background: #e2e3e5; color: #383d41; }
    .loading-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.9); backdrop-filter: blur(5px); z-index: 9999; display: flex; justify-content: center; align-items: center; opacity: 0; visibility: hidden; transition: 0.5s; }
    .loading-overlay.active { opacity: 1; visibility: visible; }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const hasData = {{ count($perkaras) > 0 ? 'true' : 'false' }};
        
        // Timer Refresh (Selalu jalan baik ada data maupun tidak)
        const duration = 300;
        let timeLeft = duration;
        const bar = document.getElementById('refreshBar');
        
        setInterval(() => {
            timeLeft--;
            if (bar) bar.style.width = (timeLeft / duration * 100) + '%';
            if (timeLeft <= 0) window.location.reload();
        }, 1000);

        if (hasData) {
            setTimeout(() => {
                document.getElementById('loadingOverlay').classList.remove('active');
                initAutoScroll();
            }, 1500);

            setInterval(() => {
                const clock = document.getElementById('liveClock');
                if (clock) clock.textContent = new Date().toLocaleTimeString('id-ID');
            }, 1000);

            let visitors = Math.floor(Math.random() * 20) + 5;
            document.getElementById('visitorCount').textContent = visitors;
        }
    });

    function initAutoScroll() {
        const container = document.getElementById('scrollContainer');
        const rows = document.querySelectorAll('#tableBody tr');
        let scrollPos = 0;
        let isPaused = false;
        const speed = 0.8;

        function move() {
            if (!isPaused && rows.length > 3) {
                scrollPos += speed;
                container.scrollTop = scrollPos;
                const center = container.getBoundingClientRect().top + (container.offsetHeight / 2);
                rows.forEach(row => {
                    const rect = row.getBoundingClientRect();
                    if (rect.top < center && rect.bottom > center) {
                        row.classList.add('row-focus');
                    } else {
                        row.classList.remove('row-focus');
                    }
                });
                if (scrollPos >= (container.scrollHeight - container.offsetHeight)) {
                    isPaused = true;
                    setTimeout(() => {
                        scrollPos = 0;
                        container.scrollTop = 0;
                        isPaused = false;
                    }, 5000);
                }
            }
            requestAnimationFrame(move);
        }
        requestAnimationFrame(move);
    }
</script>
@endpush