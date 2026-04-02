@extends('layouts.app')

@section('content')
<style>
    :root {
        --glass-bg: rgba(255, 255, 255, 0.9);
        --primary-gradient: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        --success-gradient: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
        --info-gradient: linear-gradient(135deg, #36b9cc 0%, #258391 100%);
        --warning-gradient: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%);
        --danger-gradient: linear-gradient(135deg, #e74a3b 0%, #be2617 100%);
        --secondary-gradient: linear-gradient(135deg, #858796 0%, #60616f 100%);
    }

    .dashboard-container {
        background-color: #f8f9fc;
        padding-bottom: 2rem;
        min-height: calc(100vh - 72px);
    }

    .stat-card {
        border: none;
        border-radius: 15px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        overflow: hidden;
        position: relative;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .icon-watermark {
        position: absolute;
        right: -10px;
        bottom: -10px;
        font-size: 5rem;
        opacity: 0.15;
        color: #fff;
        transform: rotate(-15deg);
    }

    .chart-card {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: box-shadow 0.2s ease;
    }

    .chart-card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }

    .chart-header {
        background-color: #fff;
        padding: 1rem 1.25rem 0 1.25rem;
        font-weight: 600;
        border-bottom: 1px solid #e3e6f0;
    }

    .quick-period {
        transition: all 0.2s ease;
        font-weight: 500;
        cursor: pointer;
    }

    .quick-period.active {
        background-color: #4e73df !important;
        color: white !important;
        border-color: #4e73df;
    }

    .clickable-box {
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .clickable-box:hover {
        opacity: 0.85;
        transform: scale(1.02);
    }

    .hakim-item {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        margin-bottom: 0.25rem;
        background: rgba(78, 115, 223, 0.08);
        border-left: 3px solid #4e73df;
        border-radius: 0 0.375rem 0.375rem 0;
        color: #2e59d9;
        display: inline-block;
    }
</style>

<div class="dashboard-container">
    <div class="container-fluid">
        {{-- Header & Filter --}}
        <div class="d-flex flex-wrap align-items-center justify-content-between mb-4 pt-4">
            <div>
                <h1 class="h3 mb-1 text-gray-800 fw-bold">Monitoring Penyelesaian Perkara Banding</h1>
                <p class="text-muted small mb-0">
                    PTA Bandung | Periode: {{ \Carbon\Carbon::parse($tgl_awal)->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::parse($tgl_akhir)->translatedFormat('d F Y') }}
                </p>
            </div>
            <div class="mt-3 mt-md-0">
                <div class="card border-0 shadow-sm rounded-4 bg-white">
                    <div class="card-body p-2">
                        <form action="{{ route('dashboard') }}" method="GET" id="filterForm" class="d-flex align-items-center gap-2 flex-wrap">
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-light quick-period" data-period="today">Hari Ini</button>
                                <button type="button" class="btn btn-light quick-period" data-period="week">Minggu Ini</button>
                                <button type="button" class="btn btn-light quick-period" data-period="month">Bulan Ini</button>
                                <button type="button" class="btn btn-light quick-period" data-period="year">Tahun Ini</button>
                            </div>
                            <div class="vr mx-1 d-none d-md-block"></div>
                            <div class="d-flex gap-2 align-items-center">
                                <input type="date" class="form-control form-control-sm border-0 bg-light" id="display_tgl_awal" value="{{ $tgl_awal }}">
                                <span class="text-muted small">s.d.</span>
                                <input type="date" class="form-control form-control-sm border-0 bg-light" id="display_tgl_akhir" value="{{ $tgl_akhir }}">
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm px-3">Filter</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Row 1: Statistik Cards --}}
        <div class="row g-3 mb-4">
            @php
            $cards = [
            ['label' => 'Sisa Lalu', 'val' => $cardData->sisa_lalu ?? 0, 'type' => 'sisa_lalu', 'grad' => 'var(--secondary-gradient)', 'icon' => 'fa-history'],
            ['label' => 'Diterima', 'val' => $cardData->diterima ?? 0, 'type' => 'diterima', 'grad' => 'var(--primary-gradient)', 'icon' => 'fa-file-medical'],
            ['label' => 'Beban Kerja', 'val' => $beban ?? 0, 'type' => 'beban_kerja', 'grad' => 'var(--info-gradient)', 'icon' => 'fa-tasks'],
            ['label' => 'Putusan Sela', 'val' => $putusanSela ?? 0, 'type' => 'putusan_sela', 'grad' => 'var(--warning-gradient)', 'icon' => 'fa-gavel'],
            ['label' => 'Selesai', 'val' => $cardData->selesai ?? 0, 'type' => 'selesai', 'grad' => 'var(--success-gradient)', 'icon' => 'fa-check-double'],
            ['label' => 'Sisa', 'val' => $cardData->sisa ?? 0, 'type' => 'sisa', 'grad' => 'var(--danger-gradient)', 'icon' => 'fa-hourglass-half'],
            ];
            @endphp
            @foreach($cards as $c)
            <div class="col-xl-2 col-md-4 col-6">
                <a href="{{ route('dashboard.detail', ['type' => $c['type'], 'tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]) }}" class="text-decoration-none">
                    <div class="card stat-card shadow-sm h-100" style="background: {{ $c['grad'] }}">
                        <div class="card-body py-3">
                            <div class="text-white small fw-bold mb-1 opacity-75 text-uppercase">{{ $c['label'] }}</div>
                            <h2 class="text-white fw-bold mb-0">{{ number_format($c['val'], 0, ',', '.') }}</h2>
                            <i class="fas {{ $c['icon'] }} icon-watermark"></i>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>

        {{-- Row 2: Grafik & Zona --}}
        <div class="row g-4 mb-4">
            {{-- E-Court vs Manual --}}
            <div class="col-lg-5">
                <div class="card chart-card h-100">
                    <div class="chart-header">Pendaftaran Perkara (E-Court vs Manual)</div>
                    <div class="card-body">
                        @php
                        $total_ec = ($rekapEcourt->total_ecourt ?? 0) + ($rekapEcourt->total_manual ?? 0);
                        $p_ec = $total_ec > 0 ? ($rekapEcourt->total_ecourt / $total_ec) * 100 : 0;
                        @endphp
                        <div class="d-flex justify-content-around mb-4 text-center">
                            <a href="{{ route('dashboard.detail', ['type' => 'ecourt', 'tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]) }}" class="text-decoration-none clickable-box">
                                <h3 class="fw-bold text-success mb-0">{{ number_format($rekapEcourt->total_ecourt ?? 0, 0, ',', '.') }}</h3>
                                <small class="text-success fw-bold">E-Court</small>
                            </a>
                            <div class="vr"></div>
                            <a href="{{ route('dashboard.detail', ['type' => 'manual', 'tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]) }}" class="text-decoration-none clickable-box">
                                <h3 class="fw-bold text-warning mb-0">{{ number_format($rekapEcourt->total_manual ?? 0, 0, ',', '.') }}</h3>
                                <small class="text-warning fw-bold">Manual</small>
                            </a>
                        </div>
                        <div class="progress" style="height: 10px; border-radius: 5px;">
                            <div class="progress-bar bg-success" style="width: {{ round($p_ec, 2) }}%"></div>
                            <div class="progress-bar bg-warning" style="width: {{ round(100 - $p_ec, 2) }}%"></div>
                        </div>
                        <p class="text-muted small text-center mt-2 mb-0">Total: {{ number_format($total_ec, 0, ',', '.') }} perkara</p>
                    </div>
                </div>
            </div>

            {{-- Waktu Putus (Zona Warna) --}}
            <div class="col-lg-7">
                <div class="card chart-card h-100">
                    <div class="chart-header d-flex justify-content-between align-items-center">
                        <span>Waktu Penyelesaian Perkara</span>
                        <span class="badge bg-light text-dark border">Total Selesai: {{ number_format($totalPutus ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="card-body">
                        @php
                        $zones = [
                        ['label' => '0-30 Hari', 'val' => $zonaWarna->hijau_tua ?? 0, 'type' => '0_30', 'color' => '#155724', 'bg' => '#d4edda'],
                        ['label' => '31-60 Hari', 'val' => $zonaWarna->hijau_muda ?? 0, 'type' => '31_60', 'color' => '#155724', 'bg' => '#e2f5e9'],
                        ['label' => '61-90 Hari', 'val' => $zonaWarna->kuning ?? 0, 'type' => '61_90', 'color' => '#856404', 'bg' => '#fff3cd'],
                        ['label' => '> 90 Hari', 'val' => $zonaWarna->merah ?? 0, 'type' => '90_up', 'color' => '#721c24', 'bg' => '#f8d7da'],
                        ];
                        @endphp
                        <div class="row g-3">
                            @foreach($zones as $z)
                            <div class="col-md-3 col-6">
                                <a href="{{ route('dashboard.detail', ['type' => $z['type'], 'tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]) }}" class="text-decoration-none">
                                    <div class="p-3 rounded-4 text-center clickable-box" style="background-color:{{ $z['bg'] }}">
                                        <small class="d-block mb-1 fw-semibold" style="color:{{ $z['color'] }}">{{ $z['label'] }}</small>
                                        <h4 class="fw-bold mb-0" style="color:{{ $z['color'] }}">{{ number_format($z['val'], 0, ',', '.') }}</h4>
                                    </div>
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Row 3: Tabel Per Jenis --}}
        <div class="card chart-card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold text-primary border-0 py-3">Rekapitulasi Per Jenis Perkara</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4" style="width: 5%">No</th>
                                <th style="width: 25%">Jenis Perkara</th>
                                <th class="text-center" style="width: 15%">Jumlah Perkara</th>
                                <th style="width: 55%">Daftar Hakim Penangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rekapJenis as $idx => $item)
                            <tr>
                                <td class="ps-4 fw-medium">{{ $idx + 1 }}</td>
                                <td class="fw-semibold text-uppercase small text-dark">{{ $item->jenis ?? '-' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('dashboard.detail', ['type' => 'per_jenis', 'jenis' => $item->jenis ?? '', 'tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]) }}" class="btn btn-sm btn-outline-primary px-3 rounded-pill fw-bold">
                                        {{ number_format($item->total ?? 0, 0, ',', '.') }} Berkas
                                    </a>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        @php
                                        $hakimList = is_string($item->hakim_penangani) ? explode(';', $item->hakim_penangani) : [];
                                        @endphp
                                        @forelse($hakimList as $hName)
                                        @if(trim($hName))
                                        <span class="hakim-item">{{ trim($hName) }}</span>
                                        @endif
                                        @empty
                                        <span class="text-muted small">-</span>
                                        @endforelse
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Tidak ada data untuk periode yang dipilih.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Format tanggal ke YYYY-MM-DD
        const formatYMD = (date) => {
            let d = new Date(date);
            let year = d.getFullYear();
            let month = String(d.getMonth() + 1).padStart(2, '0');
            let day = String(d.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        };

        const filterForm = document.getElementById('filterForm');
        const inputTglAwal = document.getElementById('tgl_awal');
        const inputTglAkhir = document.getElementById('tgl_akhir');
        const displayTglAwal = document.getElementById('display_tgl_awal');
        const displayTglAkhir = document.getElementById('display_tgl_akhir');
        const periodButtons = document.querySelectorAll('.quick-period');

        // Fungsi untuk mengirim form dengan tanggal dari display inputs
        const submitFormWithDisplayDates = (e) => {
            if (e) e.preventDefault();
            inputTglAwal.value = displayTglAwal.value;
            inputTglAkhir.value = displayTglAkhir.value;
            filterForm.submit();
        };

        // Event listener untuk tombol periode
        periodButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const period = this.dataset.period;
                const today = new Date();
                let startDate = new Date();

                switch (period) {
                    case 'today':
                        startDate = new Date(today);
                        break;
                    case 'week':
                        // Minggu ini (Senin - Minggu)
                        const dayOfWeek = today.getDay(); // 0 Minggu, 1 Senin, ...
                        const diffToMonday = dayOfWeek === 0 ? 6 : dayOfWeek - 1;
                        startDate.setDate(today.getDate() - diffToMonday);
                        break;
                    case 'month':
                        startDate = new Date(today.getFullYear(), today.getMonth(), 1);
                        break;
                    case 'year':
                        startDate = new Date(today.getFullYear(), 0, 1);
                        break;
                    default:
                        return;
                }

                // Set display inputs
                displayTglAwal.value = formatYMD(startDate);
                displayTglAkhir.value = formatYMD(today);

                // Kirim form
                submitFormWithDisplayDates();
            });
        });

        // Submit form ketika tombol filter ditekan
        filterForm.addEventListener('submit', submitFormWithDisplayDates);

        // Optional: Highlight active period based on current date range (implementasi sederhana)
        const highlightActivePeriod = () => {
            const awal = new Date(displayTglAwal.value);
            const akhir = new Date(displayTglAkhir.value);
            const today = new Date();
            const startOfToday = new Date(today.getFullYear(), today.getMonth(), today.getDate());
            const endOfToday = new Date(today.getFullYear(), today.getMonth(), today.getDate());

            // Reset active class
            periodButtons.forEach(btn => btn.classList.remove('active'));

            // Check for today
            if (awal.getTime() === startOfToday.getTime() && akhir.getTime() === endOfToday.getTime()) {
                document.querySelector('.quick-period[data-period="today"]')?.classList.add('active');
                return;
            }

            // Check for week (simple comparison, can be enhanced)
            // ... additional logic if needed
        };
        // highlightActivePeriod(); // Uncomment if needed
    });
</script>
@endpush
@endsection