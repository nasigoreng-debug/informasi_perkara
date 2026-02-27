@extends('layouts.app')

@section('title', 'Executive Dashboard | PTA Bandung')

@section('content')
{{-- JALUR EKSPRES: Panggil library ApexCharts di sini --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<div class="container-fluid py-4 px-4">
    {{-- STATS CARDS --}}
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small fw-bold mb-0">ARSIP SURAT</p>
                        <h3 class="fw-bold mb-0">{{ number_format($statsSurat['total'], 0, ',', '.') }}</h3>
                        <small class="text-success fw-bold">+{{ $statsSurat['hari_ini'] }} hari ini</small>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded-3 text-primary"><i class="fas fa-envelope-open-text fa-2x"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small fw-bold mb-0">SIDANG HARI INI</p>
                        <h3 class="fw-bold mb-0">{{ $totalSidang }}</h3>
                        <small class="text-warning fw-bold text-uppercase">Perkara</small>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-3 rounded-3 text-warning"><i class="fas fa-gavel fa-2x"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small fw-bold mb-0">PENGUNJUNG</p>
                        <h3 class="fw-bold mb-0">{{ number_format($visitors['today'], 0, ',', '.') }}</h3>
                        <small class="text-success fw-bold">{{ $visitors['online'] }} Online</small>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded-3 text-success"><i class="fas fa-eye fa-2x"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-dark border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small fw-bold mb-0">TOTAL USER</p>
                        <h3 class="fw-bold mb-0">{{ $totalUser }}</h3>
                        <small class="text-muted">Terdaftar</small>
                    </div>
                    <div class="bg-dark bg-opacity-10 p-3 rounded-3 text-dark"><i class="fas fa-users fa-2x"></i></div>
                </div>
            </div>
        </div>
    </div>

    {{-- CHARTS ROW --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold mb-0">Grafik Surat Masuk {{ date('Y') }}</h5>
                </div>
                <div class="card-body">
                    <div id="chartSurat" style="min-height: 320px;"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 text-center">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold mb-0">Kinerja Eksekusi Se-Jabar</h5>
                </div>
                <div class="card-body">
                    <div id="chartEksekusi" style="min-height: 280px;"></div>
                    <div class="mt-3">
                        <div class="row border-top pt-3 g-0">
                            <div class="col-6 border-end">
                                <small class="text-muted d-block">Beban</small>
                                <span class="fw-bold fs-5">{{ $totalBeban }}</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Selesai</small>
                                <span class="fw-bold fs-5 text-success">{{ $totalSelesai }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- LOG TABLE --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">Log Aktivitas Terbaru</h5>
            <a href="{{ url('/activity-log') }}" class="btn btn-sm btn-light rounded-pill px-3">Lihat Semua</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light small fw-bold">
                    <tr>
                        <th class="ps-4">USER</th>
                        <th>AKTIVITAS</th>
                        <th>MODUL</th>
                        <th>WAKTU</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentLogs as $log)
                    <tr>
                        <td class="ps-4"><strong>{{ optional($log->user)->name }}</strong></td>
                        <td>{{ $log->activity }}</td>
                        <td><span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $log->model }}</span></td>
                        <td>{{ $log->created_at->diffForHumans() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- SCRIPT PEMANGGIL CHART --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof ApexCharts !== 'undefined') {
            // 1. Bar Chart
            var barOptions = {
                series: [{
                    name: 'Surat',
                    data: @json($dataSurat)
                }],
                chart: {
                    type: 'bar',
                    height: 320,
                    toolbar: {
                        show: false
                    }
                },
                colors: ['#2c5364'],
                xaxis: {
                    categories: @json($labels)
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        columnWidth: '50%'
                    }
                }
            };
            new ApexCharts(document.querySelector("#chartSurat"), barOptions).render();

            // 2. Donut Chart
            var donutOptions = {
                series: [{
                    {
                        $eksekusiSelesai
                    }
                }, {
                    {
                        $eksekusiSisa
                    }
                }],
                chart: {
                    type: 'donut',
                    height: 280
                },
                labels: ['Selesai (%)', 'Sisa (%)'],
                colors: ['#16a34a', '#dc2626'],
                plotOptions: {
                    pie: {
                        donut: {
                            size: '75%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Rasio',
                                    formatter: function() {
                                        return '{{ $eksekusiSelesai }}%'
                                    }
                                }
                            }
                        }
                    }
                },
                legend: {
                    position: 'bottom'
                }
            };
            new ApexCharts(document.querySelector("#chartEksekusi"), donutOptions).render();
        }
    });
</script>
@endsection