@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-5">
        <div class="col-lg-10">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-3">
                    <li class="breadcrumb-item"><a href="{{ route('monitoring') }}" class="text-decoration-none text-primary fw-medium">Monitoring</a></li>
                    <li class="breadcrumb-item active text-muted">Sisa Panjar</li>
                </ol>
            </nav>
            <h2 class="fw-extrabold text-dark display-5 mb-4">Monitoring Sisa Panjar</h2>

            <div class="position-relative p-4 rounded-4 border-0 shadow-sm bg-white overflow-hidden">
                <div class="position-absolute top-0 start-0 h-100 bg-primary" style="width: 6px;"></div>
                <div class="d-flex align-items-start">
                    <div class="info-icon-pulse me-3 mt-1">
                        <i class="fas fa-info-circle text-primary fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-2">Dasar Hukum: SE Ditjen Badilag No. 2 Tahun 2021</h6>
                        <p class="text-muted mb-0 lh-base" style="font-size: 0.92rem; text-align: justify;">
                            Tentang <span class="fw-semibold text-dark">"Pemberitahuan dan Pengembalian Sisa Panjar Biaya Perkara"</span>.
                            Berdasarkan poin 5 huruf (c) dan (d), Pengadilan wajib memberitahukan sisa panjar paling lambat 3 hari kerja setelah putusan.
                            Jika tidak diambil dalam <span class="badge bg-warning text-dark px-2">6 Bulan</span>, sisa panjar dicatat sebagai
                            <span class="text-primary fw-bold">Uang Tak Bertuan</span> dan disetorkan ke Kas Negara (SEMA No. 4 Tahun 2008).
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        @php
        $icons = [
        'pertama' => ['icon' => 'fa-gavel', 'color' => '#4e73df'],
        'banding' => ['icon' => 'fa-balance-scale', 'color' => '#1cc88a'],
        'kasasi' => ['icon' => 'fa-university', 'color' => '#f6c23e'],
        'pk' => ['icon' => 'fa-file-signature', 'color' => '#36b9cc']
        ];
        @endphp

        @foreach(['pertama', 'banding', 'kasasi', 'pk'] as $j)
        @php
        $item = $rekap->where('jenis', $j)->first();
        $route = route('sisa.panjar.' . $j);
        $conf = $icons[$j];
        @endphp
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm rounded-4 hover-lift">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="icon-shape rounded-3 shadow-sm" style="background: {{ $conf['color'] }}15; color: {{ $conf['color'] }}">
                            <i class="fas {{ $conf['icon'] }} fa-lg"></i>
                        </div>
                        <span class="text-uppercase fw-bold small tracking-wider" style="color: {{ $conf['color'] }}">
                            {{ $j }}
                        </span>
                    </div>

                    <p class="text-muted mb-1 small fw-medium">Total Perkara</p>
                    <h2 class="fw-bold mb-0 text-dark">{{ number_format($item->total_perkara ?? 0, 0, ',', '.') }}</h2>

                    <div class="mt-4 pt-3 border-top border-light">
                        <p class="text-muted mb-1 small fw-medium">Estimasi Sisa Saldo</p>
                        <h4 class="text-danger fw-bold mb-4">
                            <span class="fs-6 fw-medium">Rp</span> {{ number_format($item->total_sisa ?? 0, 0, ',', '.') }}
                        </h4>

                        <a href="{{ $route }}" class="btn w-100 rounded-3 py-2 fw-semibold d-flex align-items-center justify-content-center transition-all"
                            style="background-color: {{ $conf['color'] }}; color: white; border: none;">
                            Lihat Detail <i class="fas fa-chevron-right ms-2 small"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- <div class="row mt-5 mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white py-4 border-0 d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="fas fa-robot me-2 text-primary"></i>Log Aktivitas Robot Sinkronisasi
                    </h5>
                    <span class="badge bg-light text-muted border fw-medium rounded-pill px-3">Riwayat Terakhir</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="small text-muted text-uppercase fw-bold" style="letter-spacing: 0.05em;">
                                <th class="ps-4 py-3">Waktu Eksekusi</th>
                                <th>Jenis</th>
                                <th>Data Terproses</th>
                                <th>Status</th>
                                <th class="pe-4">Keterangan Sistem</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                            <tr>
                                <td class="ps-4 small fw-medium text-dark">
                                    {{ \Carbon\Carbon::parse($log->created_at)->translatedFormat('d M Y, H:i') }}
                                </td>
                                <td>
                                    <span class="badge rounded-pill px-3 py-2 fw-semibold text-uppercase"
                                        style="background: #eef2ff; color: #4e73df; font-size: 0.7rem;">
                                        {{ $log->jenis }}
                                    </span>
                                </td>
                                <td class="fw-bold text-dark">
                                    {{ number_format($log->jumlah_data, 0, ',', '.') }} <small class="text-muted fw-normal">Perkara</small>
                                </td>
                                <td>
                                    @if($log->status == 'Berhasil')
                                    <div class="d-flex align-items-center text-success fw-bold small">
                                        <i class="fas fa-check-circle me-2"></i> Sukses
                                    </div>
                                    @else
                                    <div class="d-flex align-items-center text-danger fw-bold small">
                                        <i class="fas fa-exclamation-triangle me-2"></i> Gagal
                                    </div>
                                    @endif
                                </td>
                                <td class="pe-4 small text-muted font-italic">
                                    {{ $log->pesan }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <p class="text-muted mb-0 small">Belum ada riwayat sinkronisasi.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> -->
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap');

    body {
        font-family: 'Inter', sans-serif;
        background-color: #f8f9fc;
    }

    .fw-extrabold {
        font-weight: 800;
    }

    .hover-lift {
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .hover-lift:hover {
        transform: translateY(-8px);
        box-shadow: 0 1.5rem 3rem rgba(0, 0, 0, 0.12) !important;
    }

    .icon-shape {
        width: 52px;
        height: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .info-icon-pulse {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: rgba(78, 115, 223, 0.1);
        animation: pulse-blue 2s infinite;
    }

    @keyframes pulse-blue {
        0% {
            box-shadow: 0 0 0 0 rgba(78, 115, 223, 0.4);
        }

        70% {
            box-shadow: 0 0 0 10px rgba(78, 115, 223, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(78, 115, 223, 0);
        }
    }

    .transition-all {
        transition: filter 0.2s ease;
    }

    .btn:hover {
        filter: brightness(90%);
    }

    .tracking-wider {
        letter-spacing: 0.05em;
    }
</style>
@endsection