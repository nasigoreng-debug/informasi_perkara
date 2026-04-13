@extends('layouts.app')

@section('content')
<div class="container py-4 fade-in">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <div class="header-icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <h4 class="font-weight-bold text-dark m-0">Dashboard Arsip Surat Masuk</h4>
                <span class="badge bg-primary text-white rounded-pill px-3 py-1 small">REAL-TIME</span>
            </div>
            <p class="text-muted small mb-0">
                <i class="fas fa-chart-line me-1 text-primary"></i>
                Ringkasan data & statistik surat masuk
            </p>
        </div>
        <div class="mt-2 mt-md-0">
            <div class="glass-card px-3 py-2 rounded-pill">
                <i class="far fa-calendar-alt text-primary me-2"></i>
                <span class="small font-weight-semibold">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Stats Cards Row 1 -->
    <div class="row g-3 mb-4">
        <div class="col-xl-4 col-md-6">
            <div class="stat-card stat-card-primary h-100">
                <div class="stat-card-body">
                    <div class="stat-icon">
                        <i class="fas fa-database"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-label">Total Arsip Surat</span>
                        <h2 class="stat-value">{{ number_format($totalSurat, 0, ',', '.') }}</h2>
                        <div class="stat-footer">
                            <span class="badge bg-white bg-opacity-25 rounded-pill px-2 py-1 small">
                                <i class="fas fa-archive me-1"></i> Seluruh Data
                            </span>
                        </div>
                    </div>
                </div>
                <div class="stat-progress">
                    <div class="progress-bar" style="width: 100%"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="stat-card stat-card-info h-100">
                <div class="stat-card-body">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-label">Surat Masuk Tahun Ini</span>
                        <h2 class="stat-value">{{ number_format($suratTahunIni, 0, ',', '.') }}</h2>
                        <div class="stat-footer">
                            <span class="badge bg-white bg-opacity-25 rounded-pill px-2 py-1 small">
                                <i class="fas fa-chart-line me-1"></i> {{ date('Y') }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="stat-progress">
                    <div class="progress-bar" style="width: {{ min(($suratTahunIni / max($totalSurat, 1)) * 100, 100) }}%"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-12">
            <div class="stat-card stat-card-success h-100">
                <div class="stat-card-body">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-label">Input Bulan Ini</span>
                        <h2 class="stat-value">{{ number_format($inputBulanIni, 0, ',', '.') }}</h2>
                        <div class="stat-footer">
                            <span class="badge bg-white bg-opacity-25 rounded-pill px-2 py-1 small">
                                <i class="fas fa-calendar-week me-1"></i> {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="stat-progress">
                    <div class="progress-bar" style="width: {{ min(($inputBulanIni / max($suratTahunIni, 1)) * 100, 100) }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Letters Section -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden modern-card">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom flex-wrap">
            <div>
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-primary-soft p-2">
                        <i class="fas fa-history text-primary"></i>
                    </div>
                    <h6 class="m-0 font-weight-bold text-primary">Arsip Surat Terbaru</h6>
                    <span class="badge bg-primary rounded-pill px-2 py-1 small">{{ $recentSurat->count() }} Data</span>
                </div>
                <p class="text-muted small mb-0 mt-1">5 surat terakhir yang masuk</p>
            </div>
            <div class="mt-2 mt-md-0">
                <a href="{{ route('surat.masuk.index') }}" class="btn btn-outline-primary btn-sm rounded-pill px-4">
                    <i class="fas fa-eye me-1"></i> Lihat Semua
                    <i class="fas fa-arrow-right ms-1 small"></i>
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table elegant-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">No. Surat</th>
                            <th>Tanggal</th>
                            <th>Asal Instansi</th>
                            <th>Perihal</th>
                            <th>Disposisi</th>
                            <th class="text-center pe-4">Lampiran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentSurat as $s)
                        <tr class="hover-row">
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="mail-icon">
                                        <i class="fas fa-envelope text-primary"></i>
                                    </div>
                                    <div>
                                        <span class="fw-bold text-dark" style="font-size: 0.85rem;">{{ $s->no_surat }}</span>
                                        <div class="text-muted" style="font-size: 0.65rem;">
                                            <i class="fas fa-hashtag"></i> Indeks #{{ $s->no_indeks }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="date-badge">
                                    <i class="far fa-calendar-alt me-1 text-muted"></i>
                                    {{ \Carbon\Carbon::parse($s->tgl_surat)->translatedFormat('d M Y') }}
                                </div>
                            </td>
                            <td>
                                <span class="institution-badge">
                                    <i class="fas fa-building me-1"></i>
                                    {{ Str::limit($s->asal_surat, 30) }}
                                </span>
                            </td>
                            <td>
                                <div class="perihal-text" title="{{ $s->perihal }}">
                                    {{ Str::limit($s->perihal, 45) }}
                                </div>
                            </td>
                            <td>
                                @if($s->disposisi)
                                <span class="badge bg-warning-soft text-warning rounded-pill px-2 py-1 small">
                                    <i class="fas fa-share-alt me-1"></i> {{ Str::limit($s->disposisi, 15) }}
                                </span>
                                @else
                                <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                @if($s->lampiran)
                                <a href="{{ route('surat.masuk.download', $s->id) }}"
                                    class="btn-download"
                                    target="_blank"
                                    title="Download {{ $s->lampiran }}">
                                    <span class="download-text"><i class="fas fa-file-pdf text-danger"></i></span>
                                </a>
                                @else
                                <span class="text-muted small">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Belum ada data surat masuk</p>
                                    <a href="{{ route('surat.masuk.create') }}" class="btn btn-sm btn-primary rounded-pill mt-3">
                                        <i class="fas fa-plus me-1"></i> Tambah Surat Pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-light border-0 py-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div class="small text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Menampilkan 5 data terbaru berdasarkan tanggal masuk
                </div>
                <a href="{{ route('surat.masuk.index') }}" class="text-primary small text-decoration-none">
                    Lihat semua arsip <i class="fas fa-chevron-right ms-1" style="font-size: 0.7rem;"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    /* Header Icon */
    .header-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
    }

    /* Glass Card */
    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(78, 115, 223, 0.2);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    /* Stat Cards */
    .stat-card {
        border-radius: 1.25rem;
        overflow: hidden;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }

    .stat-card-primary {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        color: white;
    }

    .stat-card-info {
        background: linear-gradient(135deg, #36b9cc 0%, #258391 100%);
        color: white;
    }

    .stat-card-success {
        background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
        color: white;
    }

    .stat-card-body {
        padding: 1.5rem;
        position: relative;
        z-index: 1;
    }

    .stat-icon {
        position: absolute;
        right: 1rem;
        top: 1rem;
        opacity: 0.2;
        font-size: 3rem;
    }

    .stat-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        opacity: 0.8;
        display: block;
        margin-bottom: 0.5rem;
    }

    .stat-value {
        font-size: 2.2rem;
        font-weight: 800;
        margin: 0;
        line-height: 1.2;
    }

    .stat-footer {
        margin-top: 0.75rem;
    }

    .stat-progress {
        height: 4px;
        background: rgba(255, 255, 255, 0.2);
    }

    .stat-progress .progress-bar {
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        border-radius: 2px;
    }

    /* Mini Cards */
    .mini-card {
        background: white;
        border-radius: 1rem;
        padding: 1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
    }

    .mini-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    }

    .mini-card-icon {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
    }

    .mini-card-info {
        flex: 1;
    }

    .mini-card-label {
        font-size: 0.65rem;
        text-transform: uppercase;
        color: #858796;
        letter-spacing: 0.5px;
    }

    .mini-card-value {
        font-size: 1.2rem;
        font-weight: 700;
        margin: 0;
        color: #2c3e50;
    }

    /* Modern Card */
    .modern-card {
        border-radius: 1rem;
        overflow: hidden;
    }

    /* Elegant Table */
    .elegant-table {
        font-size: 0.8rem;
    }

    .elegant-table thead th {
        background: #f8f9fc;
        color: #5a5c69;
        font-weight: 700;
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem 0.75rem;
        border-bottom: 2px solid #e3e6f0;
    }

    .elegant-table tbody td {
        padding: 0.9rem 0.75rem;
        border-bottom: 1px solid #f0f0f0;
    }

    .hover-row {
        transition: all 0.2s ease;
    }

    .hover-row:hover {
        background: #f8f9fc;
    }

    /* Mail Icon */
    .mail-icon {
        width: 32px;
        height: 32px;
        background: rgba(78, 115, 223, 0.1);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Date Badge */
    .date-badge {
        font-size: 0.75rem;
        color: #5a5c69;
    }

    /* Institution Badge */
    .institution-badge {
        font-size: 0.75rem;
        font-weight: 500;
        color: #4e73df;
        background: rgba(78, 115, 223, 0.08);
        padding: 0.2rem 0.6rem;
        border-radius: 20px;
        display: inline-block;
    }

    /* Perihal Text */
    .perihal-text {
        font-size: 0.75rem;
        color: #6c757d;
        max-width: 250px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* File Badge */
    .file-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        background: rgba(28, 200, 138, 0.1);
        padding: 0.2rem 0.6rem;
        border-radius: 20px;
        font-size: 0.7rem;
        color: #1cc88a;
    }

    /* Background Soft */
    .bg-primary-soft {
        background: rgba(78, 115, 223, 0.1);
    }

    .bg-success-soft {
        background: rgba(28, 200, 138, 0.1);
    }

    .bg-info-soft {
        background: rgba(54, 185, 204, 0.1);
    }

    .bg-warning-soft {
        background: rgba(246, 194, 62, 0.1);
    }

    /* Empty State */
    .empty-state {
        padding: 2rem;
        text-align: center;
    }

    /* Animations */
    .fade-in {
        animation: fadeIn 0.6s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .stat-value {
            font-size: 1.5rem;
        }

        .stat-icon {
            font-size: 2rem;
        }

        .perihal-text {
            max-width: 150px;
        }

        .elegant-table thead th,
        .elegant-table tbody td {
            padding: 0.5rem;
        }
    }
</style>
@endsection