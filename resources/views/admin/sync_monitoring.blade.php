@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold text-primary"><i class="fas fa-satellite-dish me-2"></i> PUSAT KENDALI SINKRONISASI SATKER</h5>
        <div id="loader" class="spinner-border text-primary spinner-border-sm" role="status"></div>
    </div>

    <div class="row">
        @foreach($laporans as $key => $label)
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <span class="fw-bold small text-uppercase text-muted">{{ $label }}</span>
                    <span id="badge-{{ $key }}" class="badge bg-secondary">0/26</span>
                </div>
                <div class="card-body p-0" style="max-height: 350px; overflow-y: auto;">
                    <table class="table table-sm table-hover mb-0" style="font-size: 11px;">
                        <tbody id="body-{{ $key }}">
                            <tr>
                                <td class="text-center py-4 text-muted italic">Belum ada data sinkronisasi...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-light py-2">
                    <div class="progress" style="height: 6px;">
                        <div id="progress-{{ $key }}" class="progress-bar bg-success" style="width: 0%"></div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<script>
    async function updateDashboard() {
        try {
            const response = await fetch("{{ route('admin.sync.status_json') }}");
            const data = await response.json();

            Object.keys(data).forEach(modul => {
                const logs = data[modul];
                const totalSatker = 26;
                const sukses = logs.filter(l => l.status.toLowerCase() === 'berhasil' || l.status.toLowerCase() === 'success').length;
                const persen = Math.round((sukses / totalSatker) * 100);

                // Update UI Bar & Badge
                const progressBar = document.getElementById(`progress-${modul}`);
                const badge = document.getElementById(`badge-${modul}`);
                if (progressBar) progressBar.style.width = persen + '%';
                if (badge) {
                    badge.innerText = `${sukses}/${totalSatker} Satker`;
                    badge.className = persen === 100 ? 'badge bg-success' : 'badge bg-warning';
                }

                // Update List Satker
                let html = '';
                logs.forEach(log => {
                    let isBerhasil = log.status.toLowerCase() === 'berhasil' || log.status.toLowerCase() === 'success';
                    let isGagal = log.status.toLowerCase() === 'gagal' || log.status.toLowerCase() === 'failed';
                    let statusColor = isBerhasil ? 'text-success' : (isGagal ? 'text-danger' : 'text-primary');
                    let icon = isBerhasil ? 'fa-check-circle' : (isGagal ? 'fa-times-circle' : 'fa-spinner fa-spin');

                    html += `<tr>
                        <td class="ps-3 py-2"><i class="fas ${icon} ${statusColor} me-2"></i>${log.nama_satker}</td>
                        <td class="text-end pe-3 text-muted">${log.jumlah_data || 0} data</td>
                    </tr>`;
                });
                const tableBody = document.getElementById(`body-${modul}`);
                if (tableBody) tableBody.innerHTML = html;
            });
        } catch (e) {
            console.error("Monitor Error");
        }
    }

    setInterval(updateDashboard, 5000);
    updateDashboard();
</script>
@endsection