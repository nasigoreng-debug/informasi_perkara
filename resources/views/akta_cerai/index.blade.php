@extends('layouts.app')

@section('content')
<style>
    .content-container {
        max-width: 1050px;
        margin: auto;
    }

    .filter-card {
        background: #fdfdfd;
        border: 1px solid #e3e6f0;
        border-radius: 12px;
    }

    .table thead th {
        background-color: #f8f9fc;
        text-transform: uppercase;
        font-size: 0.8rem !important;
        font-weight: 700 !important;
        color: #4e73df;
        padding: 15px;
    }

    .table tbody td {
        font-size: 0.85rem !important;
        vertical-align: middle !important;
        color: #2d3748;
    }

    /* Style khusus untuk baris Grand Total di paling bawah */
    .bg-grand-total {
        background-color: #ffff00 !important;
        /* Kuning pekat */
        font-weight: 800 !important;
    }

    .bg-grand-total td {
        color: #000 !important;
        border-top: 2px solid #2d3748 !important;
    }

    .text-satker {
        text-align: left !important;
        padding-left: 20px !important;
        font-weight: 700;
    }

    .status-badge {
        width: 100%;
        display: block;
        padding: 0.4rem;
        font-weight: 700;
        font-size: 0.75rem;
        border-radius: 6px;
        text-decoration: none !important;
    }

    .alert-filter {
        background-color: #e0f2fe;
        border-left: 5px solid #0ea5e9;
        color: #0369a1;
        border-radius: 8px;
    }
</style>

<div class="container py-4">
    <div class="content-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="font-weight-bold text-gray-800 mb-0">Monitoring Akta Cerai</h3>
                <p class="text-muted small mb-0 font-italic">Urutan Berdasarkan Kinerja Penerbitan Tertinggi</p>
            </div>
            <a href="{{ route('monitoring') }}" class="btn btn-sm btn-outline-primary px-3 shadow-sm bg-white font-weight-bold">
                <i class="fas fa-th-large mr-1"></i> DASHBOARD MONITORING
            </a>
        </div>

        <div class="card filter-card shadow-sm mb-4 border-0">
            <div class="card-body py-4">
                <form action="" method="GET" class="row align-items-end justify-content-center">
                    <div class="col-md-3">
                        <label class="small font-weight-bold text-secondary mb-1 text-uppercase">Mulai Tanggal</label>
                        <input type="date" name="tgl_awal" class="form-control form-control-sm shadow-sm" value="{{ $tglAwal }}">
                    </div>
                    <div class="col-md-3">
                        <label class="small font-weight-bold text-secondary mb-1 text-uppercase">Sampai Tanggal</label>
                        <input type="date" name="tgl_akhir" class="form-control form-control-sm shadow-sm" value="{{ $tglAkhir }}">
                    </div>
                    <div class="col-md-5 mt-3 mt-md-0">
                        <div class="btn-group w-100 shadow-sm">
                            <button type="submit" class="btn btn-primary btn-sm font-weight-bold">
                                <i class="fas fa-filter mr-1"></i> FILTER
                            </button>
                            <a href="{{ route('akta-cerai.export', ['tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhir]) }}"
                                target="_blank" class="btn btn-success btn-sm font-weight-bold shadow-sm">
                                <i class="fas fa-file-excel mr-1"></i> EXPORT
                            </a>
                            <a href="{{ route('akta-cerai.index') }}" class="btn btn-white btn-sm border px-3 text-dark">
                                <i class="fas fa-sync-alt mr-1"></i> RESET
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="alert alert-filter shadow-sm mb-4 d-flex align-items-center">
            <i class="fas fa-info-circle mr-3 fa-lg"></i>
            <div>Menampilkan hasil monitoring periode: <strong>{{ \Carbon\Carbon::parse($tglAwal)->translatedFormat('d F Y') }}</strong> s.d <strong>{{ \Carbon\Carbon::parse($tglAkhir)->translatedFormat('d F Y') }}</strong></div>
        </div>

        <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
            <div class="card-body p-0 text-dark">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="text-center text-uppercase">
                            <tr>
                                <th width="60">RANK</th>
                                <th class="text-left px-4">SATUAN KERJA</th>
                                <th width="100">TOTAL AC</th>
                                <th width="100">TEPAT</th>
                                <th width="100">TERLAMBAT</th>
                                <th class="text-danger" width="130">AC MENDAHULUI BHT</th>
                                <th width="160">KINERJA</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @foreach($data as $index => $row)
                            <tr>
                                <td class="font-weight-bold text-muted">
                                    @if($index === 0 && $row->persen_tepat_waktu > 0)
                                    <i class="fas fa-crown text-warning"></i>
                                    @else
                                    {{ $index + 1 }}
                                    @endif
                                </td>
                                <td class="text-satker text-primary text-uppercase">{{ $row->satker }}</td>
                                <td class="px-1">
                                    <a href="{{ route('akta-cerai.detail', [$row->satker, 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhir, 'kategori' => 'all']) }}" class="status-badge bg-light text-dark border shadow-sm">{{ number_format($row->total) }}</a>
                                </td>
                                <td class="px-1">
                                    <a href="{{ route('akta-cerai.detail', [$row->satker, 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhir, 'kategori' => 'tepat']) }}" class="status-badge text-success shadow-sm" style="background: #ecfdf5; border: 1px solid #d1fae5;">{{ number_format($row->tepat_waktu) }}</a>
                                </td>
                                <td class="px-1">
                                    <a href="{{ route('akta-cerai.detail', [$row->satker, 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhir, 'kategori' => 'terlambat']) }}" class="status-badge text-warning shadow-sm" style="background: #fffbeb; border: 1px solid #fef3c7;">{{ number_format($row->terlambat) }}</a>
                                </td>
                                <td class="px-1">
                                    <a href="{{ route('akta-cerai.detail', [$row->satker, 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhir, 'kategori' => 'anomali']) }}" class="status-badge {{ $row->anomali > 0 ? 'bg-danger text-white' : 'bg-light text-danger border' }} shadow-sm">{{ number_format($row->anomali) }}</a>
                                </td>
                                <td class="px-3">
                                    <div class="d-flex align-items-center justify-content-end">
                                        <div class="progress shadow-sm flex-grow-1 mr-2" style="height: 6px; border-radius: 10px; background: #f1f5f9;">
                                            <div class="progress-bar {{ $row->persen_tepat_waktu > 80 ? 'bg-success' : ($row->persen_tepat_waktu > 50 ? 'bg-warning' : 'bg-danger') }}" style="width: {{ $row->persen_tepat_waktu }}%"></div>
                                        </div>
                                        <span class="font-weight-bold" style="min-width: 45px;">{{ $row->persen_tepat_waktu }}%</span>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="text-center bg-grand-total">
                            <tr>
                                <td></td>
                                <td class="text-left px-4">TOTAL SE-JAWA BARAT</td>
                                <td>{{ number_format($totals['total']) }}</td>
                                <td>{{ number_format($totals['tepat']) }}</td>
                                <td>{{ number_format($totals['lambat']) }}</td>
                                <td>{{ number_format($totals['anomali']) }}</td>
                                <td>
                                    <div class="d-flex align-items-center justify-content-end">
                                        <span class="font-weight-bold">{{ $totals['kinerja'] }}%</span>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection