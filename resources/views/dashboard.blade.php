@extends('layouts.app')

@section('title', 'Executive Dashboard | PTA Bandung')

@section('content')
<div class="container-fluid py-4 px-4">
    {{-- KARTU STATISTIK --}}
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-primary border-4 animate__animated animate__fadeInUp">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small fw-bold mb-0">ARSIP SURAT</p>
                        <h3 class="fw-bold mb-0">{{ $statsSurat['total'] }}</h3>
                        <small class="text-success fw-bold">+{{ $statsSurat['hari_ini'] }} hari ini</small>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded-3 text-primary"><i class="fas fa-envelope-open-text fa-2x"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-warning border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
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
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-success border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small fw-bold mb-0">PENGUNJUNG</p>
                        <h3 class="fw-bold mb-0">{{ $visitors['today'] }}</h3>
                        <small class="text-success fw-bold">{{ $visitors['online'] }} Online</small>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded-3 text-success"><i class="fas fa-eye fa-2x"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-dark border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.3s">
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

    {{-- BARIS GRAFIK (DIUBAH KE APEXCHARTS) --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 py-3"><h5 class="fw-bold mb-0">Grafik Surat Masuk {{ date('Y') }}</h5></div>
                <div class="card-body">
                    <div id="chartSurat"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 py-3 text-center"><h5 class="fw-bold mb-0">Penyelesaian Eksekusi</h5></div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <div id="chartEksekusi" style="width: 100%;"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- LOG AKTIVITAS --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">Log Aktivitas Terbaru</h5>
            <a href="{{ url('/activity-log') }}" class="btn btn-sm btn-light rounded-pill px-3">Lihat Semua</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-light small fw-bold">
                    <tr><th class="ps-4">USER</th><th>AKTIVITAS</th><th>MODUL</th><th>WAKTU</th></tr>
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
@endsection

@push('scripts')
{{-- Library ApexCharts --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // 1. Grafik Batang (Surat Masuk)
        var optionsSurat = {
            series: [{
                name: 'Jumlah Surat',
                data: @json($dataSurat)
            }],
            chart: {
                type: 'bar',
                height: 320,
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif'
            },
            colors: ['#2c5364'],
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    columnWidth: '55%',
                }
            },
            dataLabels: { enabled: false },
            xaxis: {
                categories: @json($labels),
                labels: { style: { cssClass: 'text-muted fw-bold' } }
            },
            yaxis: {
                labels: { style: { cssClass: 'text-muted fw-bold' } }
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 4,
            }
        };

        var chartSurat = new ApexCharts(document.querySelector("#chartSurat"), optionsSurat);
        chartSurat.render();


        // 2. Grafik Donut (Eksekusi)
        var optionsEksekusi = {
            series: [{{ $eksekusiSelesai }}, {{ $eksekusiSisa }}],
            chart: {
                type: 'donut',
                height: 300,
                fontFamily: 'Inter, sans-serif'
            },
            labels: ['Selesai', 'Sisa'],
            colors: ['#16a34a', '#dc2626'],
            plotOptions: {
                pie: {
                    donut: {
                        size: '75%',
                        labels: {
                            show: true,
                            name: { show: true },
                            value: {
                                show: true,
                                formatter: function (val) { return val + "%" }
                            },
                            total: {
                                show: true,
                                showAlways: true,
                                label: 'Selesai',
                                formatter: function (w) { return w.globals.seriesTotals[0] + "%" }
                            }
                        }
                    }
                }
            },
            dataLabels: { enabled: false },
            legend: { position: 'bottom' },
            stroke: { width: 0 }
        };

        var chartEksekusi = new ApexCharts(document.querySelector("#chartEksekusi"), optionsEksekusi);
        chartEksekusi.render();
    });
</script>
@endpush