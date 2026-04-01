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

    .dashboard-container { background-color: #f8f9fc; padding-bottom: 2rem; min-height: calc(100vh - 72px); }
    .stat-card { border: none; border-radius: 15px; transition: all 0.3s ease; overflow: hidden; position: relative; }
    .stat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important; }
    .stat-card .card-body { position: relative; z-index: 1; }
    .icon-watermark { position: absolute; right: -10px; bottom: -10px; font-size: 5rem; opacity: 0.15; color: #fff; transform: rotate(-15deg); }
    .chart-card { border: none; border-radius: 20px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); }
    .chart-header { background: transparent; border-bottom: 1px solid #f1f1f1; padding: 1.25rem; }
    .progress-custom { height: 12px; border-radius: 50px; background-color: #eaecf4; overflow: hidden; }
    .quick-period { transition: all 0.2s ease; font-weight: 500; }
    .quick-period:hover, .quick-period.active { background-color: #4e73df !important; color: white !important; }
    @keyframes slideIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .row>[class*="col-"] { animation: slideIn 0.5s ease-out forwards; }

    /* CSS Tambahan untuk List Hakim agar Rapi & Gelar Tidak Pecah */
    .hakim-item {
        font-size: 12px;
        padding: 5px 10px;
        margin-bottom: 4px;
        background: rgba(78, 115, 223, 0.05);
        border-left: 3px solid #4e73df;
        border-radius: 0 5px 5px 0;
        color: #2e59d9;
        font-weight: 500;
    }
</style>

<div class="dashboard-container">
    <div class="container-fluid">
        {{-- Header & Filter --}}
        <div class="d-flex flex-wrap align-items-center justify-content-between mb-4 pt-4">
            <div>
                <h1 class="h3 mb-1 text-gray-800 fw-bold">Monitoring Penyelesaian Perkara Banding</h1>
                <p class="text-muted small mb-0">Pengadilan Tinggi Agama Bandung</p>
            </div>
            <div class="mt-3 mt-md-0">
                <div class="card border-0 shadow-sm rounded-4 bg-white">
                    <div class="card-body p-3">
                        <form action="{{ route('dashboard') }}" method="GET" id="filterForm" class="d-flex flex-wrap align-items-center gap-2">
                            <input type="hidden" name="tgl_awal" id="tgl_awal" value="{{ $tgl_awal }}">
                            <input type="hidden" name="tgl_akhir" id="tgl_akhir" value="{{ $tgl_akhir }}">
                            <div class="btn-group">
                                <button type="button" class="btn btn-light border-0 px-4 py-2 rounded-start-3 quick-period" data-period="today">Hari Ini</button>
                                <button type="button" class="btn btn-light border-0 px-4 py-2 quick-period" data-period="week">Minggu Ini</button>
                                <button type="button" class="btn btn-light border-0 px-4 py-2 quick-period" data-period="month">Bulan Ini</button>
                                <button type="button" class="btn btn-light border-0 px-4 py-2 rounded-end-3 quick-period" data-period="year">Tahun Ini</button>
                            </div>
                            <div class="vr mx-2" style="height: 30px;"></div>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <input type="date" class="form-control form-control-sm bg-light border-0 rounded-3" id="custom_tgl_awal" value="{{ $tgl_awal }}" style="width: 140px;">
                                <input type="date" class="form-control form-control-sm bg-light border-0 rounded-3" id="custom_tgl_akhir" value="{{ $tgl_akhir }}" style="width: 140px;">
                                <button type="submit" class="btn btn-primary rounded-3 px-4 shadow-sm">Filter</button>
                                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary rounded-3 px-3"><i class="fas fa-undo"></i></a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Row 1: Statistik Cards --}}
        <div class="row g-3 mb-4">
            @php
            $cards = [
                ['label' => 'Sisa Lalu', 'val' => $cardData->sisa_lalu, 'grad' => 'var(--secondary-gradient)', 'icon' => 'fa-history'],
                ['label' => 'Diterima', 'val' => $cardData->diterima, 'grad' => 'var(--primary-gradient)', 'icon' => 'fa-file-medical'],
                ['label' => 'Beban Kerja', 'val' => $beban, 'grad' => 'var(--info-gradient)', 'icon' => 'fa-tasks'],
                ['label' => 'Putusan Sela', 'val' => $putusanSela, 'grad' => 'var(--warning-gradient)', 'icon' => 'fa-gavel'],
                ['label' => 'Selesai', 'val' => $cardData->selesai, 'grad' => 'var(--success-gradient)', 'icon' => 'fa-check-double'],
                ['label' => 'Sisa', 'val' => $cardData->sisa, 'grad' => 'var(--danger-gradient)', 'icon' => 'fa-hourglass-half'],
            ];
            @endphp
            @foreach($cards as $idx => $c)
            <div class="col-xl-2 col-md-4" style="animation-delay: {{ $idx * 0.1 }}s">
                <div class="card stat-card shadow-sm h-100" style="background: {{ $c['grad'] }}">
                    <div class="card-body py-3">
                        <div class="text-white small fw-bold mb-1 opacity-75 text-uppercase">{{ $c['label'] }}</div>
                        <h2 class="text-white fw-bold mb-0">{{ number_format($c['val']) }}</h2>
                        <i class="fas {{ $c['icon'] }} icon-watermark"></i>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Row 2: Grafik Ratio & Performance --}}
        <div class="row g-4 mb-4">
            <div class="col-lg-5">
                <div class="card chart-card h-100">
                    <div class="chart-header d-flex justify-content-between align-items-center border-0">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-bolt me-2"></i>Pendaftaran Perkara</h6>
                    </div>
                    <div class="card-body">
                        @php
                            $total_ec = ($rekapEcourt->total_ecourt ?? 0) + ($rekapEcourt->total_manual ?? 0);
                            $p_ec = $total_ec > 0 ? ($rekapEcourt->total_ecourt / $total_ec) * 100 : 0;
                        @endphp
                        <div class="d-flex justify-content-around mb-4 text-center">
                            <div><h3 class="fw-bold text-success mb-0">{{ number_format($rekapEcourt->total_ecourt) }}</h3><small class="text-success fw-bold">{{ round($p_ec) }}% E-Court</small></div>
                            <div class="vr"></div>
                            <div><h3 class="fw-bold text-warning mb-0">{{ number_format($rekapEcourt->total_manual) }}</h3><small class="text-warning fw-bold">{{ round(100 - $p_ec) }}% Manual</small></div>
                        </div>
                        <div class="progress progress-custom shadow-sm"><div class="progress-bar bg-success" style="width:{{ $p_ec }}%"></div><div class="progress-bar bg-warning" style="width:{{ 100-$p_ec }}%"></div></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card chart-card h-100">
                    <div class="chart-header d-flex justify-content-between align-items-center border-0">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-tachometer-alt me-2"></i>Waktu Putus</h6>
                        <div class="small fw-bold text-muted">Total: {{ number_format($totalPutus) }}</div>
                    </div>
                    <div class="card-body">
                        @php
                            $tp = max($totalPutus, 1);
                            $z = [
                                ['label' => '0-30', 'val' => $zonaWarna->hijau_tua, 'color' => '#1a5928', 'bg' => '#d4edda', 'p' => ($zonaWarna->hijau_tua / $tp) * 100],
                                ['label' => '31-60', 'val' => $zonaWarna->hijau_muda, 'color' => '#28a745', 'bg' => '#e2f5e9', 'p' => ($zonaWarna->hijau_muda / $tp) * 100],
                                ['label' => '61-90', 'val' => $zonaWarna->kuning, 'color' => '#dda20a', 'bg' => '#fff3cd', 'p' => ($zonaWarna->kuning / $tp) * 100],
                                ['label' => '> 90', 'val' => $zonaWarna->merah, 'color' => '#dc3545', 'bg' => '#f8d7da', 'p' => ($zonaWarna->merah / $tp) * 100],
                            ];
                        @endphp
                        <div class="progress mb-4 border" style="height: 35px; border-radius: 10px;">
                            @foreach($z as $item)
                                @if($item['p'] > 0)<div class="progress-bar d-flex align-items-center justify-content-center fw-bold text-white" style="width:{{ $item['p'] }}%; background-color:{{ $item['color'] }};">{{ round($item['p']) }}%</div>@endif
                            @endforeach
                        </div>
                        <div class="row g-2">
                            @foreach($z as $item)
                            <div class="col-md-3"><div class="p-2 rounded-3 text-center" style="background-color:{{ $item['bg'] }}"><small class="text-muted d-block">{{ $item['label'] }} Hari</small><span class="fw-bold" style="color:{{ $item['color'] }}">{{ number_format($item['val']) }}</span></div></div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Row 3: Rekap Jenis Perkara & Hakim (DAFTAR KE BAWAH) --}}
        <div class="row">
            <div class="col-12">
                <div class="card chart-card border-0 shadow-sm">
                    <div class="chart-header text-primary fw-bold border-0"><i class="fas fa-balance-scale me-2"></i>Rekap Per Jenis Perkara & Hakim Tinggi</div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4" width="70">No</th>
                                        <th width="280">Jenis Perkara</th>
                                        <th class="text-center" width="150">Jumlah</th>
                                        <th>Hakim Tinggi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($rekapJenis as $idx => $item)
                                    <tr>
                                        <td class="ps-4 text-muted">{{ $idx + 1 }}</td>
                                        <td class="fw-bold text-dark text-uppercase small">{{ $item->jenis }}</td>
                                        <td class="text-center">
                                            <div class="h4 fw-bold text-primary mb-0">{{ number_format($item->total) }}</div>
                                            <small class="text-muted text-uppercase" style="font-size: 9px;">Berkas</small>
                                        </td>
                                        <td>
                                            <div style="max-height: 150px; overflow-y: auto;">
                                                @php $hList = explode('; ', $item->hakim_penangani); @endphp
                                                @foreach($hList as $hName)
                                                    @if(trim($hName) != "")<div class="hakim-item"><i class="fas fa-user-circle me-2"></i>{{ trim($hName) }}</div>@endif
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="text-center py-5">Belum ada data.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // JS Filter Quick Period lu di sini (SAMA PERSIS)
</script>
@endpush
@endsection