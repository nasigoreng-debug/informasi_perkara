@extends('layouts.app')

@section('content')
<style>
    .card-header {
        border-bottom: 1px solid #f0f0f0;
    }

    .table-scroll {
        max-height: 380px;
        overflow-y: auto;
    }

    .font-monospace {
        font-family: 'Courier New', Courier, monospace;
    }

    .sticky-header th {
        position: sticky;
        top: 0;
        background: #f8f9fa;
        z-index: 5;
    }

    #loader {
        display: none;
    }

    /* Memastikan konten tidak menabrak navbar jika navbar bersifat fixed */
    .container-fluid {
        position: relative;
        z-index: 1;
    }
</style>

<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="fw-bold text-primary mb-0">
                    <i class="fas fa-satellite-dish me-2"></i> PUSAT KENDALI SINKRONISASI SATKER
                </h5>
                <div class="d-flex align-items-center">
                    <div id="loader" class="spinner-border text-primary spinner-border-sm me-2" role="status"></div>
                    <span class="text-muted small italic">Auto-refresh: 5s</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Cards Section -->
    <div class="row">
        @foreach($laporans as $key => $label)
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <span class="fw-bold small text-uppercase text-muted d-block">{{ $label }}</span>
                        <small id="last-update-{{ $key }}" class="text-muted" style="font-size: 10px;">Update: -</small>
                    </div>
                    <span id="badge-{{ $key }}" class="badge bg-secondary">0/26 Satker</span>
                </div>
                <div class="card-body p-0 table-scroll">
                    <table class="table table-sm table-hover mb-0" style="font-size: 11px;">
                        <thead class="sticky-header">
                            <tr class="text-muted">
                                <th class="ps-3 py-2">NAMA SATKER</th>
                                <th class="text-center">DATA</th>
                                <th class="text-end pe-3">WAKTU & TGL</th>
                            </tr>
                        </thead>
                        <tbody id="body-{{ $key }}">
                            <tr>
                                <td colspan="3" class="text-center py-5 text-muted">
                                    <i class="fas fa-sync fa-spin me-2"></i> Menghubungkan ke database...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-light py-2">
                    <div class="progress" style="height: 8px; border-radius: 10px;">
                        <div id="progress-{{ $key }}"
                            class="progress-bar bg-success progress-bar-striped progress-bar-animated"
                            style="width: 0%"></div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<script>
    async function updateDashboard() {
        const loader = document.getElementById('loader');
        try {
            if (loader) loader.style.display = 'block';
            
            const response = await fetch("{{ route('admin.sync.status_json') }}");
            const data = await response.json();
            const daftarModul = @json(array_keys($laporans));

            daftarModul.forEach(modul => {
                const logs = data[modul] || [];
                const totalSatker = 26;
                const sukses = logs.filter(l => l.status && (l.status.toLowerCase() === 'berhasil' || l.status.toLowerCase() === 'success')).length;
                const persen = Math.round((sukses / totalSatker) * 100);

                const progressBar = document.getElementById(`progress-${modul}`);
                const badge = document.getElementById(`badge-${modul}`);
                const timeHeader = document.getElementById(`last-update-${modul}`);
                const tableBody = document.getElementById(`body-${modul}`);

                if (progressBar) progressBar.style.width = persen + '%';
                
                if (badge) {
                    badge.innerText = `${sukses}/${totalSatker} Satker`;
                    badge.className = persen === 100 ? 'badge bg-success shadow-sm' : 'badge bg-warning text-dark shadow-sm';
                }

                let html = '';
                let latestTime = '-';

                if (logs.length > 0) {
                    logs.forEach(log => {
                        let isBerhasil = log.status.toLowerCase() === 'berhasil' || log.status.toLowerCase() === 'success';
                        let isGagal = log.status.toLowerCase() === 'gagal' || log.status.toLowerCase() === 'failed';
                        let statusColor = isBerhasil ? 'text-success' : (isGagal ? 'text-danger' : 'text-primary');
                        let icon = isBerhasil ? 'fa-check-circle' : (isGagal ? 'fa-times-circle' : 'fa-spinner fa-spin');

                        let d = log.updated_at ? new Date(log.updated_at) : null;
                        let displayTime = '-';
                        if (d) {
                            const tgl = d.getDate().toString().padStart(2, '0') + '/' + (d.getMonth() + 1).toString().padStart(2, '0');
                            const jam = d.getHours().toString().padStart(2, '0') + ':' + d.getMinutes().toString().padStart(2, '0');
                            displayTime = `${tgl} ${jam}`;
                            latestTime = displayTime;
                        }

                        html += `<tr>
                            <td class="ps-3 py-2 fw-medium"><i class="fas ${icon} ${statusColor} me-2"></i>${log.nama_satker}</td>
                            <td class="text-center fw-bold">${log.jumlah_data || 0}</td>
                            <td class="text-end pe-3 font-monospace text-muted" style="font-size: 10px;">${displayTime}</td>
                        </tr>`;
                    });
                } else {
                    html = `<tr><td colspan="3" class="text-center py-4 text-muted small">Belum ada data sinkronisasi</td></tr>`;
                }

                if (timeHeader && latestTime !== '-') timeHeader.innerText = `Update: ${latestTime}`;
                if (tableBody) tableBody.innerHTML = html;
            });

            setTimeout(() => {
                if (loader) loader.style.display = 'none';
            }, 800);
        } catch (e) {
            console.error("Monitor Fetch Error:", e);
            if (loader) loader.style.display = 'none';
        }
    }

    // Jalankan saat load pertama
    document.addEventListener('DOMContentLoaded', function() {
        updateDashboard();
        setInterval(updateDashboard, 5000);
    });
</script>
@endsection