@extends('layouts.app')

@section('title', 'Jadwal Sidang')

@section('content')
<div class="progress fixed-top" style="height: 3px; z-index: 2000;">
    <div id="refreshBar" class="progress-bar bg-primary" role="progressbar" style="width: 100%"></div>
</div>

<div class="loading-overlay active" id="loadingOverlay">
    <div class="text-center">
        <div class="spinner-border text-primary mb-2" role="status" style="width: 3rem; height: 3rem;"></div>
        <div class="fw-bold text-muted">Memuat Data Sidang...</div>
    </div>
</div>

<div class="toast-container position-fixed top-0 end-0 p-3 mt-5"></div>

<div class="container-fluid px-4">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-end mb-4 animate__animated animate__fadeInDown"
        style="position: relative; z-index: 9999;">

        <div class="mb-3 mb-md-0">
            <h5 class="text-uppercase text-muted small fw-bold mb-1 ls-1">
                <i class="fas fa-building me-1"></i> Pengadilan Tinggi Agama Bandung
            </h5>
            <h2 class="fw-bold text-dark mb-0">Jadwal Sidang Perkara</h2>
            <p class="text-muted small mb-0 mt-1">
                <i class="far fa-calendar-alt me-1"></i> {{ $hariIniFormatted }}
                <span class="mx-2">•</span>
                <i class="far fa-clock me-1"></i> Update: <span id="lastUpdateTime">Loading...</span>
            </p>
        </div>

        <div class="d-flex gap-2">
            <div class="input-group shadow-sm" style="width: 250px;">
                <span class="input-group-text bg-white border-end-0 text-muted ps-3">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" id="searchInput" class="form-control border-start-0 ps-0"
                    placeholder="Cari Perkara / Hakim..." onkeyup="searchTable()">
            </div>

            <button class="btn btn-light bg-white shadow-sm border text-primary" onclick="refreshData()" title="Refresh Data">
                <i class="fas fa-sync-alt"></i>
            </button>

            <div class="dropdown">
                <button class="btn btn-primary shadow-sm dropdown-toggle" type="button"
                    data-bs-toggle="dropdown"
                    data-bs-display="static"
                    aria-expanded="false">
                    <i class="fas fa-download me-1"></i> Export
                </button>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow mt-2">
                    <li><a class="dropdown-item" href="#" onclick="exportToExcel()"><i class="fas fa-file-excel text-success me-2"></i> Excel (.xlsx)</a></li>
                    <li><a class="dropdown-item" href="#" onclick="printTable()"><i class="fas fa-print text-secondary me-2"></i> Cetak / PDF</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card-stat-modern p-3 border-start border-4 border-success animate__animated animate__fadeInLeft" onclick="filterByStatus('HARI_INI')">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <div class="text-uppercase text-muted small fw-bold">Sidang Hari Ini</div>
                        <div class="fs-2 fw-bold text-dark" id="countHariIni">{{ $sidangHariIni ?? 0 }}</div>
                    </div>
                    <div class="bg-success bg-opacity-10 text-success rounded-circle p-3">
                        <i class="fas fa-gavel fa-xl"></i>
                    </div>
                </div>
                <span class="badge badge-soft-success w-100 filter-badge" data-filter="HARI_INI">
                    <i class="fas fa-filter me-1"></i> Tampilkan Hari Ini
                </span>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-stat-modern p-3 border-start border-4 border-info animate__animated animate__fadeInUp" onclick="filterByStatus('AKAN_DATANG')">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <div class="text-uppercase text-muted small fw-bold">Akan Datang</div>
                        <div class="fs-2 fw-bold text-dark" id="countAkanDatang">{{ $totalSidangAkanDatang ?? 0 }}</div>
                    </div>
                    <div class="bg-info bg-opacity-10 text-info rounded-circle p-3">
                        <i class="fas fa-calendar-plus fa-xl"></i>
                    </div>
                </div>
                <span class="badge badge-soft-info w-100 filter-badge" data-filter="AKAN_DATANG">
                    <i class="fas fa-filter me-1"></i> Tampilkan Berikutnya
                </span>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-stat-modern p-3 border-start border-4 border-secondary animate__animated animate__fadeInRight" onclick="resetFilter()">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <div class="text-uppercase text-muted small fw-bold">Total Terjadwal</div>
                        <div class="fs-2 fw-bold text-dark" id="countTotal">{{ $totalDitampilkan ?? 0 }}</div>
                    </div>
                    <div class="bg-secondary bg-opacity-10 text-secondary rounded-circle p-3">
                        <i class="fas fa-list fa-xl"></i>
                    </div>
                </div>
                <span class="badge badge-soft-secondary w-100 filter-badge active border border-dark" data-filter="ALL">
                    <i class="fas fa-layer-group me-1"></i> Tampilkan Semua
                </span>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden animate__animated animate__fadeIn">
        <div class="card-body p-0">
            <div class="table-responsive" id="tableContainer">
                <table class="table table-clean w-100 mb-0" id="sidangTable">
                    <thead>
                        <tr>
                            <th class="ps-4" width="5%" onclick="sortTable(0)" style="cursor:pointer">No <i class="fas fa-sort small ms-1 text-muted opacity-50"></i></th>
                            <th width="18%" onclick="sortTable(1)" style="cursor:pointer">
                                Tanggal <i class="fas fa-sort small ms-1 text-muted opacity-50"></i>
                            </th>
                            <th width="20%" onclick="sortTable(2)" style="cursor:pointer">
                                No. Perkara <i class="fas fa-sort small ms-1 text-muted opacity-50"></i>
                            </th>
                            <th width="15%" class="text-center" onclick="sortTable(3)" style="cursor:pointer">
                                Jenis <i class="fas fa-sort small ms-1 text-muted opacity-50"></i>
                            </th>
                            <th width="22%" onclick="sortTable(4)" style="cursor:pointer">
                                Ketua Majelis <i class="fas fa-sort small ms-1 text-muted opacity-50"></i>
                            </th>
                            <th width="20%" class="text-center" onclick="sortTable(5)" style="cursor:pointer">
                                Ruangan <i class="fas fa-sort small ms-1 text-muted opacity-50"></i>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @forelse($perkaras as $index => $perkara)
                        @php
                        // Perlindungan jika data tanggal null
                        $tanggalAman = $perkara->tanggal_sidang_terdekat ?? now()->toDateString();
                        $carbonDate = \Carbon\Carbon::parse($tanggalAman);
                        $isToday = $carbonDate->isToday();

                        // Logic Class Baris
                        $rowClass = $isToday ? 'row-hari-ini' : '';

                        // Logic Badge Tanggal
                        $badgeDateClass = $isToday ? 'badge-soft-success' : 'badge-soft-secondary';
                        $badgeDateText = $isToday ? 'HARI INI' : $carbonDate->diffForHumans();

                        // Logic Badge Jenis Perkara
                        $jp = strtolower($perkara->jenis_perkara ?? '');
                        $badgeJenisClass = 'badge-soft-secondary';
                        if(str_contains($jp, 'cerai')) $badgeJenisClass = 'badge-soft-warning';
                        elseif(str_contains($jp, 'ekonomi')) $badgeJenisClass = 'badge-soft-info';
                        elseif(str_contains($jp, 'waris')) $badgeJenisClass = 'badge-soft-success';
                        elseif(str_contains($jp, 'itsbat')) $badgeJenisClass = 'badge-soft-primary';

                        // Hitung nomor urut (mempertimbangkan pagination)
                        $nomorUrut = (method_exists($perkaras, 'firstItem') ? $perkaras->firstItem() : 1) + $index;
                        @endphp

                        <tr class="{{ $rowClass }} animate__animated animate__fadeIn"
                            data-status="{{ $perkara->status_sidang ?? ($isToday ? 'HARI_INI' : 'AKAN_DATANG') }}"
                            data-tanggal="{{ $tanggalAman }}"
                            style="animation-delay: {{ $index * 0.03 }}s;">

                            <td class="ps-4 text-muted fw-bold">{{ $nomorUrut }}</td>

                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-dark">{{ $carbonDate->translatedFormat('d F Y') }}</span>
                                    <div class="mt-1">
                                        <span class="badge {{ $badgeDateClass }}">{{ $badgeDateText }}</span>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div class="d-flex flex-column">
                                    <span class="font-mono fw-bold text-primary">{{ $perkara->nomor_perkara_banding ?? '-' }}</span>
                                    @if(!empty($perkara->nomor_perkara_pa))
                                    <small class="text-muted font-mono mt-1" style="font-size: 0.8rem;">
                                        <i class="fas fa-arrow-turn-up me-1" style="transform: rotate(90deg)"></i>
                                        {{ $perkara->nomor_perkara_pa }}
                                    </small>
                                    @endif
                                </div>
                            </td>

                            <td class="text-center">
                                <span class="badge {{ $badgeJenisClass }}">
                                    {{ $perkara->jenis_perkara ?? 'Lain-lain' }}
                                </span>
                            </td>

                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-light text-secondary rounded-circle d-flex justify-content-center align-items-center me-2" style="width: 32px; height: 32px;">
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                    <span class="fw-500 text-dark small">
                                        {{ $perkara->kode_hakim ?? $perkara->ketua_majelis ?? '-' }}
                                    </span>
                                </div>
                            </td>

                            <td class="text-center">
                                <span class="badge badge-soft-secondary">
                                    <i class="fas fa-door-open me-1"></i>
                                    {{ $perkara->ruangan ?? 'R. Sidang Utama' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="opacity-50">
                                    <i class="fas fa-calendar-times fa-3x mb-3 text-secondary"></i>
                                    <h5 class="text-muted">Tidak Ada Jadwal Sidang</h5>
                                    <p class="small text-muted mb-0">Belum ada data sidang yang terjadwal untuk ditampilkan.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if(method_exists($perkaras, 'hasPages') && $perkaras->hasPages())
        <div class="card-footer bg-white border-top py-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <small class="text-muted">
                    Menampilkan <span class="fw-bold">{{ $perkaras->firstItem() }}</span> - <span class="fw-bold">{{ $perkaras->lastItem() }}</span> dari <span class="fw-bold">{{ $perkaras->total() }}</span> data
                </small>
                <div>
                    {{ $perkaras->onEachSide(1)->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="text-center mt-4">
        <p class="text-muted small mb-0">
            <i class="fas fa-users me-1"></i> <span id="visitorCount">0</span> Pengunjung Sedang Melihat Halaman Ini
        </p>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

<style>
    /* VARIABLES */
    :root {
        --color-primary-soft: rgba(13, 110, 253, 0.1);
        --color-success-soft: rgba(25, 135, 84, 0.1);
        --color-warning-soft: rgba(255, 193, 7, 0.15);
        --color-info-soft: rgba(13, 202, 240, 0.1);
        --color-secondary-soft: rgba(108, 117, 125, 0.1);
    }

    /* CARD STATS */
    .card-stat-modern {
        background: #fff;
        border: none;
        border-radius: 0.75rem;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .card-stat-modern:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    }

    .border-start-4 {
        border-left-width: 4px !important;
    }

    /* TABLE CLEAN STYLE */
    .table-clean {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }

    .table-clean thead th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
        text-transform: uppercase;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        color: #6c757d;
        padding: 1rem 1rem;
        position: sticky;
        top: 0;
        z-index: 10;
        user-select: none;
    }

    .table-clean tbody td {
        padding: 1rem 1rem;
        border-bottom: 1px solid #f1f3f5;
        vertical-align: middle;
        font-size: 0.9rem;
        background-color: #fff;
        transition: background-color 0.2s;
    }

    .table-clean tbody tr:hover td {
        background-color: #f8f9fa;
    }

    /* UTILITIES */
    .font-mono {
        font-family: 'JetBrains Mono', monospace;
        letter-spacing: -0.5px;
    }

    .fw-500 {
        font-weight: 500;
    }

    /* SOFT BADGES */
    .badge-soft {
        padding: 0.4em 0.8em;
        font-size: 0.75em;
        font-weight: 600;
        border-radius: 0.35rem;
        letter-spacing: 0.3px;
    }

    .badge-soft-primary {
        background-color: var(--color-primary-soft);
        color: #0d6efd;
    }

    .badge-soft-success {
        background-color: var(--color-success-soft);
        color: #198754;
    }

    .badge-soft-warning {
        background-color: var(--color-warning-soft);
        color: #b45309;
    }

    .badge-soft-info {
        background-color: var(--color-info-soft);
        color: #0dcaf0;
    }

    .badge-soft-secondary {
        background-color: var(--color-secondary-soft);
        color: #6c757d;
    }

    /* ROW HIGHLIGHT */
    .row-hari-ini td {
        background-color: #f0fff4 !important;
        border-bottom-color: #d1fae5;
    }

    .row-hari-ini:hover td {
        background-color: #dcfce7 !important;
    }

    /* LOADING & TOAST */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(3px);
        z-index: 9999;
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s;
    }

    .loading-overlay.active {
        opacity: 1;
        visibility: visible;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
    // PERBAIKAN: Menggunakan IIFE agar kebal dari bentrok Turbolinks/Livewire
    (function() {
        updateTime();
        startAutoRefresh();
        startVisitorCounter();

        // Hapus loading overlay setelah 800ms
        setTimeout(() => {
            const overlay = document.getElementById('loadingOverlay');
            if (overlay) overlay.classList.remove('active');
        }, 800);
    })();

    // 1. UPDATE WAKTU REAL-TIME
    function updateTime() {
        const now = new Date();
        const options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        };
        // PERBAIKAN: Menggunakan toLocaleString agar jam ikut ter-format
        const timeStr = now.toLocaleString('id-ID', options);
        document.getElementById('lastUpdateTime').textContent = timeStr.replace(/\./g, ':');
    }

    // 2. AUTO REFRESH LOGIC
    function startAutoRefresh() {
        const duration = 300; // 5 Menit dalam detik
        let timeLeft = duration;
        const bar = document.getElementById('refreshBar');

        setInterval(() => {
            timeLeft--;
            if (bar) {
                const percent = (timeLeft / duration) * 100;
                bar.style.width = percent + '%';
            }
            if (timeLeft <= 0) refreshData();
        }, 1000);
    }

    // 3. VISITOR COUNTER SIMULATION
    function startVisitorCounter() {
        let count = Math.floor(Math.random() * 30) + 15;
        const el = document.getElementById('visitorCount');
        if (el) el.textContent = count;

        setInterval(() => {
            count += Math.floor(Math.random() * 3) - 1;
            if (count < 5) count = 5;
            if (el) el.textContent = count;
        }, 10000);
    }

    // 4. CLIENT SIDE FILTERING
    function filterByStatus(status) {
        const rows = document.querySelectorAll('#tableBody tr');
        let visible = 0;

        document.querySelectorAll('.filter-badge').forEach(b => {
            b.classList.remove('active', 'border', 'border-dark');
            if (b.dataset.filter === status) b.classList.add('active', 'border', 'border-dark');
        });

        rows.forEach(row => {
            const rowStatus = row.getAttribute('data-status');
            if (status === 'ALL' || rowStatus === status) {
                row.style.display = '';
                visible++;
            } else {
                row.style.display = 'none';
            }
        });

        showToast(`Menampilkan ${visible} baris (di halaman ini)`, 'info');
    }

    function resetFilter() {
        filterByStatus('ALL');
    }

    // 5. SEARCH FUNCTION
    function searchTable() {
        const input = document.getElementById('searchInput').value.toLowerCase();
        const rows = document.querySelectorAll('#tableBody tr');

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(input) ? '' : 'none';
        });
    }

    // 6. REFRESH DATA
    function refreshData() {
        document.getElementById('loadingOverlay').classList.add('active');
        setTimeout(() => window.location.reload(), 500);
    }

    // 7. SORTING TABLE 
    let sortDir = 1;

    function sortTable(n) {
        const table = document.getElementById("sidangTable");
        let rows, switching, i, x, y, shouldSwitch;
        switching = true;
        sortDir = -sortDir;

        while (switching) {
            switching = false;
            rows = table.rows;
            for (i = 1; i < (rows.length - 1); i++) {
                shouldSwitch = false;
                x = rows[i].getElementsByTagName("TD")[n].innerText.trim().toLowerCase();
                y = rows[i + 1].getElementsByTagName("TD")[n].innerText.trim().toLowerCase();

                let numX = parseFloat(x);
                let numY = parseFloat(y);
                if (!isNaN(numX) && !isNaN(numY)) {
                    x = numX;
                    y = numY;
                }

                if (sortDir == 1) {
                    if (x > y) {
                        shouldSwitch = true;
                        break;
                    }
                } else {
                    if (x < y) {
                        shouldSwitch = true;
                        break;
                    }
                }
            }
            if (shouldSwitch) {
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
            }
        }
    }

    // 8. EXPORT & PRINT 
    function exportToExcel() {
        const table = document.getElementById('sidangTable');
        const wb = XLSX.utils.table_to_book(table, {
            sheet: "Jadwal Sidang"
        });
        XLSX.writeFile(wb, 'Jadwal_Sidang_' + new Date().toISOString().slice(0, 10) + '.xlsx');
        showToast('Berhasil mengunduh Excel', 'success');
    }

    function printTable() {
        const printContent = document.getElementById('tableContainer').innerHTML;
        const printWindow = window.open('', '_blank', 'height=700,width=1000');

        printWindow.document.write('<html><head><title>Cetak Jadwal Sidang</title>');
        printWindow.document.write('<style>');
        printWindow.document.write('body { font-family: Arial, sans-serif; padding: 20px; }');
        printWindow.document.write('h2 { text-align: center; margin-bottom: 5px; }');
        printWindow.document.write('.text-muted { text-align: center; color: #666; font-size: 12px; margin-bottom: 20px; }');
        printWindow.document.write('table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 12px;}');
        printWindow.document.write('th, td { border: 1px solid #000; padding: 8px; text-align: left; }');
        printWindow.document.write('th { background-color: #f2f2f2; }');
        printWindow.document.write('</style></head><body>');
        printWindow.document.write('<h2>Jadwal Sidang - PTA Bandung</h2>');
        printWindow.document.write('<div class="text-muted">Dicetak pada: ' + new Date().toLocaleString('id-ID') + '</div>');
        printWindow.document.write(printContent);
        printWindow.document.write('</body></html>');

        printWindow.document.close();

        setTimeout(() => {
            printWindow.print();
            printWindow.close();
        }, 500);
    }

    // 9. TOAST NOTIFICATION
    function showToast(msg, type = 'info') {
        const container = document.querySelector('.toast-container');
        const toastHTML = `
            <div class="toast show align-items-center text-white bg-${type} border-0 shadow" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body"><i class="fas fa-info-circle me-2"></i> ${msg}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>`;

        const el = document.createElement('div');
        el.innerHTML = toastHTML;
        container.appendChild(el.firstChild);

        setTimeout(() => {
            if (el.firstChild) el.firstChild.remove();
        }, 3000);
    }
</script>
@endpush