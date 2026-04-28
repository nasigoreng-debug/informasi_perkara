@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header Section dengan Animasi -->
        <div class="row mb-4 align-items-center fade-in">
            <div class="col-lg-6">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-gradient-primary rounded-3 p-3 shadow-sm">
                        <i class="fas fa-chart-line fa-2x text-white"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold text-dark mb-1">Monitoring Penyelesaian Perkara melalui E-Court</h3>
                        <p class="text-muted small mb-0">
                            Real-time monitoring • Periode: {{ \Carbon\Carbon::parse($tgl_awal)->format('d/m/Y') }} -
                            {{ \Carbon\Carbon::parse($tgl_akhir)->format('d/m/Y') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card bg-white border-0 shadow-sm rounded-3">
                    <div class="card-body p-2">
                        <form action="{{ route('ecourt.index') }}" method="GET" class="d-flex gap-2">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fas fa-calendar-alt text-primary"></i>
                                </span>
                                <input type="date" name="tgl_awal" class="form-control form-control-sm border-0 bg-light"
                                    value="{{ $tgl_awal }}">
                            </div>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fas fa-calendar-check text-primary"></i>
                                </span>
                                <input type="date" name="tgl_akhir"
                                    class="form-control form-control-sm border-0 bg-light" value="{{ $tgl_akhir }}">
                            </div>
                            <div class="btn-group btn-group-sm">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-1"></i>Filter
                                </button>
                                <a href="{{ route('ecourt.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-sync-alt me-1"></i>Reset
                                </a>
                                <a href="{{ route('ecourt.index', array_merge(request()->all(), ['export' => 1])) }}"
                                    class="btn btn-success" target="_blank">
                                    <i class="fas fa-file-excel me-1"></i>Export
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards dengan Efek Hover -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card stat-card border-0 shadow-sm bg-gradient-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="small text-uppercase fw-bold opacity-75 mb-2">Total Diterima</h6>
                                <h2 class="fw-bold mb-0">{{ number_format($total_terima, 0, ',', '.') }}</h2>
                                <small class="opacity-75">Seluruh perkara masuk</small>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-inbox fa-3x opacity-25"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card border-0 shadow-sm bg-gradient-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="small text-uppercase fw-bold opacity-75 mb-2">Total E-Court</h6>
                                <h2 class="fw-bold mb-0">{{ number_format($total_ecourt, 0, ',', '.') }}</h2>
                                <small class="opacity-75">Online filing</small>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-laptop fa-3x opacity-25"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card border-0 shadow-sm bg-gradient-danger text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="small text-uppercase fw-bold opacity-75 mb-2">Total Non-E-Court</h6>
                                <h2 class="fw-bold mb-0">{{ number_format($total_manual, 0, ',', '.') }}</h2>
                                <small class="opacity-75">Conventional filing</small>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-file-alt fa-3x opacity-25"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card border-0 shadow-sm bg-gradient-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="small text-uppercase fw-bold opacity-75 mb-2">Persentase Total</h6>
                                <h2 class="fw-bold mb-0">{{ number_format($total_persentase, 2, ',', '.') }}%</h2>
                                <small class="opacity-75">E-Court coverage</small>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-chart-pie fa-3x opacity-25"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel dengan Desain Modern -->
        <div class="card border-0 shadow-sm rounded-4 modern-table">
            <div class="card-header bg-white border-0 py-3 px-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <i class="fas fa-table me-2 text-primary"></i>
                        <span class="fw-bold">Detail Data Per Satuan Kerja</span>
                    </div>
                    <div>
                        <span class="badge bg-light text-dark">
                            <i class="fas fa-building me-1"></i>{{ count($reports) }} Satuan Kerja
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-gradient-table text-muted small fw-bold">
                            <tr class="text-center">
                                <th width="60" class="py-3">NO</th>
                                <th class="text-start">Satuan Kerja</th>
                                <th width="140">Total Diterima</th>
                                <th width="140">E-Court</th>
                                <th width="140">Non-E-Court</th>
                                <th width="280">Persentase E-Court</th>
                                <th width="150">Status Capaian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reports as $index => $item)
                                @php
                                    $p = $item->persentase ?? 0;
                                    // Warna dan status berdasarkan persentase
                                    if ($p >= 98) {
                                        $color = 'success';
                                        $icon = 'fa-trophy';
                                        $status = 'PROGRAM PRIORITAS 98%';
                                        $badgeClass = 'bg-success';
                                    } elseif ($p >= 80) {
                                        $color = 'warning';
                                        $icon = 'fa-clock';
                                        $status = 'DALAM PROSES';
                                        $badgeClass = 'bg-warning text-dark';
                                    } else {
                                        $color = 'danger';
                                        $icon = 'fa-exclamation-triangle';
                                        $status = 'PERLU PERHATIAN';
                                        $badgeClass = 'bg-danger';
                                    }
                                @endphp
                                <tr class="text-center table-row-animate">
                                    <td class="fw-semibold text-secondary">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="text-start">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="fas fa-balance-scale text-primary"></i>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $item->SATKER ?? '-' }}</div>
                                                @if (isset($item->error))
                                                    <span class="badge bg-danger bg-opacity-10 text-danger">
                                                        <i class="fas fa-wifi me-1"></i>OFFLINE
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-dark fw-bold">
                                        {{ number_format($item->total ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td class="text-success fw-bold">

                                        {{ number_format($item->ecourt ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td class="text-danger">

                                        {{ number_format($item->non_ecourt ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height: 8px; border-radius: 4px;">
                                                <div class="progress-bar bg-{{ $color }} progress-bar-striped"
                                                    role="progressbar" style="width: {{ max(0, $p) }}%">
                                                </div>
                                            </div>
                                            <span class="fw-bold text-{{ $color }}" style="min-width: 70px;">
                                                {{ number_format($p, 2, ',', '.') }}%
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column align-items-center gap-1">
                                            <span class="badge {{ $badgeClass }} px-3 py-2">
                                                <i class="fas {{ $icon }} me-1"></i>
                                                {{ $status }}
                                            </span>
                                            @if ($p >= 98)
                                                <small class="text-success fw-bold">
                                                    <i class="fas fa-check-circle"></i> Target Tercapai
                                                </small>
                                            @elseif($p >= 80)
                                                <small class="text-warning">
                                                    <i class="fas fa-chart-line"></i> Menuju Target
                                                </small>
                                            @else
                                                <small class="text-danger">
                                                    <i class="fas fa-flag-checkered"></i> Perlu Aksi
                                                </small>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="fas fa-database fa-3x text-muted mb-3 d-block"></i>
                                        <h5 class="text-muted">Tidak ada data</h5>
                                        <p class="text-muted small">Silakan pilih periode tanggal yang berbeda</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if (count($reports) > 0)
                            <tfoot class="bg-dark text-white">
                                <tr class="text-center">
                                    <td colspan="2" class="py-3 text-end fw-bold">TOTAL KESELURUHAN</td>
                                    <td class="fw-bold">{{ number_format($total_terima, 0, ',', '.') }}</td>
                                    <td class="fw-bold text-success">{{ number_format($total_ecourt, 0, ',', '.') }}</td>
                                    <td class="fw-bold text-danger">{{ number_format($total_manual, 0, ',', '.') }}</td>
                                    <td colspan="2">
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            <div class="progress flex-grow-1" style="height: 8px;">
                                                <div class="progress-bar bg-warning"
                                                    style="width: {{ $total_persentase }}%"></div>
                                            </div>
                                            <span
                                                class="fw-bold">{{ number_format($total_persentase, 2, ',', '.') }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Animations */
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

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        .table-row-animate {
            animation: slideIn 0.4s ease-out;
            animation-fill-mode: both;
        }

        .table-row-animate:nth-child(n) {
            animation-delay: calc(0.05s * var(--row-index, 0));
        }

        /* Gradient Backgrounds */
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
        }

        .bg-gradient-danger {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        }

        .bg-gradient-table {
            background: linear-gradient(135deg, #f5f7fa 0%, #f3f4f6 100%);
        }

        /* Stat Cards */
        .stat-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.02) !important;
        }

        .stat-icon {
            transition: all 0.3s ease;
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.1) rotate(5deg);
        }

        /* Modern Table */
        .modern-table {
            overflow: hidden;
        }

        .modern-table .table {
            margin-bottom: 0;
        }

        .modern-table .table> :not(caption)>*>* {
            padding: 1rem 0.75rem;
        }

        .modern-table tbody tr {
            transition: all 0.2s ease;
            border-bottom: 1px solid #f0f0f0;
        }

        .modern-table tbody tr:hover {
            background-color: #f8f9ff;
            transform: scale(1.01);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        /* Clean Number Style - No Bulat */
        .table tbody td:first-child {
            font-size: 14px;
            font-weight: 500;
            color: #6c757d;
            letter-spacing: 0.3px;
        }

        /* Progress Bar Custom */
        .progress {
            background-color: #e9ecef;
            overflow: hidden;
        }

        .progress-bar {
            transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .progress-bar::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        /* Custom Scrollbar */
        .table-responsive::-webkit-scrollbar {
            height: 8px;
        }

        .table-responsive::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
        }

        /* Badge Styles */
        .badge {
            font-weight: 500;
            padding: 0.35rem 0.75rem;
        }

        .bg-opacity-10 {
            background-color: rgba(0, 0, 0, 0.05);
        }

        /* Additional Utility */
        .bg-opacity-10.text-success {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745 !important;
        }

        .bg-opacity-10.text-primary {
            background-color: rgba(0, 123, 255, 0.1);
            color: #007bff !important;
        }

        .bg-opacity-10.text-warning {
            background-color: rgba(255, 193, 7, 0.1);
            color: #ffc107 !important;
        }

        .bg-opacity-10.text-danger {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545 !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .stat-card .card-body {
                padding: 1rem;
            }

            .stat-card h2 {
                font-size: 1.5rem;
            }

            .modern-table .table> :not(caption)>*>* {
                padding: 0.75rem 0.5rem;
            }

            .badge {
                font-size: 0.7rem;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Add row index for staggered animation
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.table-row-animate').forEach((row, index) => {
                row.style.setProperty('--row-index', index);
            });

            // Animate progress bars on load
            setTimeout(() => {
                document.querySelectorAll('.progress-bar').forEach(bar => {
                    const width = bar.style.width;
                    bar.style.width = '0%';
                    setTimeout(() => {
                        bar.style.width = width;
                    }, 100);
                });
            }, 200);
        });
    </script>
@endpush
