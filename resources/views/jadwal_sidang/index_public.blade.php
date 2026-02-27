@extends('layouts.app')

@section('title', 'Jadwal Sidang - Presisi TV')

@section('content')
{{-- Timer Bar --}}
<div class="progress fixed-top" style="height: 10px; z-index: 2000; background: #e9ecef;">
    <div id="refreshBar" class="progress-bar bg-primary" role="progressbar" style="width: 100%"></div>
</div>

{{-- Overlay Loading --}}
<div class="loading-overlay active" id="loadingOverlay">
    <div class="text-center">
        <div class="spinner-border text-primary mb-3" role="status" style="width: 5rem; height: 5rem; border-width: .5em;"></div>
        <h1 class="fw-bold text-dark">SINKRONISASI DATA...</h1>
    </div>
</div>

<div class="vh-100 d-flex flex-column bg-white overflow-hidden">
    
    {{-- HEADER: Diberi margin agar tidak offside --}}
    <div class="mx-5 my-4 px-4 py-3 d-flex justify-content-between align-items-center border-bottom border-5 border-primary bg-light rounded-4">
        <div class="d-flex align-items-center">
            <i class="fas fa-university fa-4x text-primary me-4"></i>
            <div>
                <h4 class="text-muted fw-bold mb-0 text-uppercase">PTA Bandung</h4>
                <h1 class="fw-black text-dark mb-0" style="font-size: 3.5rem;">JADWAL SIDANG HARI INI</h1>
            </div>
        </div>
        <div class="text-end border-start ps-5 border-3">
            <h1 class="text-primary fw-black mb-0 font-mono" id="liveClock" style="font-size: 4.5rem;">00:00:00</h1>
            <h4 class="text-dark fw-bold mb-0">{{ $hariIniFormatted }}</h4>
        </div>
    </div>

    {{-- AREA TABEL DENGAN SAFE ZONE (MARGIN KIRI-KANAN) --}}
    <div class="flex-grow-1 position-relative overflow-hidden mx-5 mb-4">
        <div id="scrollContainer" class="h-100 overflow-hidden rounded-4 border shadow-sm bg-white">
            <table class="table table-hover w-100 mb-0">
                <thead class="bg-dark text-white sticky-top">
                    <tr>
                        <th class="text-center py-4" width="8%" style="font-size: 1.8rem;">NO</th>
                        <th class="py-4 ps-4" width="45%" style="font-size: 1.8rem;">NOMOR PERKARA</th>
                        <th class="text-center py-4" width="22%" style="font-size: 1.8rem;">TANGGAL SIDANG</th>
                        <th class="py-4 text-center" width="25%" style="font-size: 1.8rem;">MAJELIS / RUANG</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($perkaras as $index => $perkara)
                        <tr class="align-middle display-row">
                            <td class="text-center py-5">
                                <span class="row-number">{{ $index + 1 }}</span>
                            </td>
                            <td class="ps-4">
                                <div class="perkara-title font-mono text-dark">{{ $perkara->nomor_perkara_banding }}</div>
                                <div class="text-primary fw-bold h4 mb-0 mt-2">
                                    <i class="fas fa-tag me-2"></i>{{ $perkara->nomor_perkara_pa }}
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="tgl-box">
                                    {{ $perkara->tgl_sidang_display }}
                                </div>
                            </td>
                            <td class="text-center pe-4">
                                <div class="fw-bold h2 text-dark mb-1">{{ $perkara->kode_hakim ?? $perkara->ketua_majelis }}</div>
                                <div class="ruang-badge">{{ $perkara->ruangan ?? 'R. SIDANG PTA BANDUNG' }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <h1 class="display-1 fw-bold text-muted opacity-25 mt-5">TIDAK ADA JADWAL</h1>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- FOOTER RUNNING TEXT --}}
    <div class="bg-primary text-white py-3 border-top border-dark border-4">
        <marquee behavior="scroll" direction="left" class="display-6 fw-bold mb-0">
            PENGUMUMAN: HARAP ANTRI DENGAN TERTIB • DATA SIDANG TERHUBUNG LANGSUNG KE SERVER SIPP • JAGA KEBERSIHAN LINGKUNGAN PENGADILAN.
        </marquee>
    </div>
</div>
@endsection

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&family=JetBrains+Mono:wght@700&display=swap" rel="stylesheet">
<style>
    body { font-family: 'Inter', sans-serif; background: #e9ecef; overflow: hidden; }
    .fw-black { font-weight: 900; }
    .font-mono { font-family: 'JetBrains Mono', monospace; }

    /* ROW STYLING */
    .display-row { border-bottom: 2px solid #dee2e6; }
    .perkara-title { font-size: 3.5rem; font-weight: 900; line-height: 1; letter-spacing: -1px; }

    /* BADGES & BOXES */
    .tgl-box { background: #fff3cd; color: #856404; padding: 15px; border-radius: 12px; font-size: 2rem; font-weight: 900; display: inline-block; border: 2px solid #ffeeba; }
    .ruang-badge { background: #0d6efd; color: white; padding: 8px 15px; border-radius: 8px; font-size: 1.4rem; font-weight: 800; display: inline-block; }
    .row-number { display: inline-block; width: 65px; height: 65px; line-height: 65px; background: #212529; color: #fff; border-radius: 50%; font-size: 2.2rem; font-weight: 900; }

    /* FOCUS HIGHLIGHT */
    .row-focus { background-color: #f0f7ff !important; border-left: 20px solid #0d6efd !important; transition: 0.3s; }

    .loading-overlay { position: fixed; inset: 0; background: white; z-index: 9999; display: flex; align-items: center; justify-content: center; opacity: 0; visibility: hidden; transition: 0.5s; }
    .loading-overlay.active { opacity: 1; visibility: visible; }
</style>
@endpush

@push('scripts')
<script>
    (function() {
        function updateClock() {
            const now = new Date();
            document.getElementById('liveClock').textContent = now.toLocaleTimeString('id-ID', {hour12:false}).replace(/\./g, ':');
        }
        setInterval(updateClock, 1000);

        let timeLeft = 300;
        setInterval(() => {
            timeLeft--;
            document.getElementById('refreshBar').style.width = (timeLeft / 300 * 100) + '%';
            if(timeLeft <= 0) window.location.reload();
        }, 1000);

        const container = document.getElementById('scrollContainer');
        const rows = document.querySelectorAll('#tableBody tr');
        let scrollPos = 0;
        let isPaused = false;

        function autoScroll() {
            if (rows.length < 3) {
                document.getElementById('loadingOverlay').classList.remove('active');
                return;
            }
            if (!isPaused) {
                scrollPos += 0.8;
                container.scrollTop = scrollPos;
                const centerLimit = container.offsetHeight / 2;
                rows.forEach(row => {
                    const rect = row.getBoundingClientRect();
                    if (rect.top < centerLimit + 150 && rect.top > centerLimit - 150) {
                        row.classList.add('row-focus');
                    } else {
                        row.classList.remove('row-focus');
                    }
                });
                if (scrollPos >= (container.scrollHeight - container.offsetHeight)) {
                    isPaused = true;
                    setTimeout(() => {
                        scrollPos = 0; container.scrollTop = 0; isPaused = false;
                    }, 8000);
                }
            }
            requestAnimationFrame(autoScroll);
        }

        window.onload = function() {
            setTimeout(() => {
                document.getElementById('loadingOverlay').classList.remove('active');
                autoScroll();
            }, 1000);
        };
    })();
</script>
@endpush