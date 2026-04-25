@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 mt-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 fw-bold">Monitoring Perkara Gugur & Digugurkan</h1>
            <p class="text-muted small mb-0">Statistik putusan perkara periode {{ date('d/m/Y', strtotime($tgl_awal)) }} s/d {{ date('d/m/Y', strtotime($tgl_akhir)) }}</p>
        </div>
        <div class="col-auto">
            <form action="{{ route('perkara_gugur.index') }}" method="GET" class="d-flex gap-2">
                <input type="date" name="tgl_awal" class="form-control form-control-sm shadow-sm" value="{{ $tgl_awal }}">
                <input type="date" name="tgl_akhir" class="form-control form-control-sm shadow-sm" value="{{ $tgl_akhir }}">

                <div class="btn-group shadow-sm">
                    <button type="submit" class="btn btn-primary btn-sm fw-bold">
                        <i class="fas fa-filter fa-sm"></i> FILTER
                    </button>
                    <a href="{{ route('perkara_gugur.index') }}" class="btn btn-danger btn-sm fw-bold">
                        <i class="fas fa-undo fa-sm"></i> RESET
                    </a>
                </div>
            </form>
        </div>
    </div>

    @php
    // Ambil baris total untuk ditampilkan di Dashboard Card
    $totalRow = collect($data)->firstWhere('nama_satker', 'GRAND TOTAL');
    @endphp

    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-0 border-start border-primary border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1 small">Total Perkara Gugur</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalRow->gugur ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-gavel fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-0 border-start border-warning border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1 small">Total Digugurkan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalRow->digugurkan ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-ban fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-12 mb-4">
            <div class="card border-0 border-start border-success border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1 small">Jumlah Keseluruhan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalRow->jumlah ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-pie fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow border-0 mb-4">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="m-0 font-weight-bold text-primary text-uppercase"><i class="fas fa-table me-2"></i>Rincian Statistik Per Satker</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="bg-light text-center small fw-bold text-dark">
                        <tr>
                            <th width="50">NO</th>
                            <th>SATUAN KERJA</th>
                            <th width="150">GUGUR</th>
                            <th width="150">DIGUGURKAN</th>
                            <th width="150">JUMLAH</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @forelse($data as $row)
                        @if($row->nama_satker !== 'GRAND TOTAL')
                        <tr>
                            <td class="text-center text-muted small">{{ $no++ }}</td>
                            <td class="fw-bold text-dark">{{ $row->nama_satker }}</td>
                            <td class="text-center">
                                <a href="{{ route('perkara_gugur.detail', ['satker'=>$row->nama_satker, 'tgl_awal'=>$tgl_awal, 'tgl_akhir'=>$tgl_akhir, 'jenis'=>'gugur']) }}"
                                    class="btn btn-sm btn-outline-primary fw-bold rounded-pill px-4">
                                    {{ number_format($row->gugur, 0, ',', '.') }}
                                </a>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('perkara_gugur.detail', ['satker'=>$row->nama_satker, 'tgl_awal'=>$tgl_awal, 'tgl_akhir'=>$tgl_akhir, 'jenis'=>'digugurkan']) }}"
                                    class="btn btn-sm btn-outline-warning fw-bold rounded-pill px-4 text-dark">
                                    {{ number_format($row->digugurkan, 0, ',', '.') }}
                                </a>
                            </td>
                            <td class="text-center bg-light font-weight-bold">
                                <a href="{{ route('perkara_gugur.detail', ['satker'=>$row->nama_satker, 'tgl_awal'=>$tgl_awal, 'tgl_akhir'=>$tgl_akhir]) }}"
                                    class="text-decoration-none text-primary fw-bold">
                                    {{ number_format($row->jumlah, 0, ',', '.') }}
                                </a>
                            </td>
                        </tr>
                        @endif
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Data tidak ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-dark text-center fw-bold align-middle border-0">
                        @if($totalRow)
                        <tr>
                            <td colspan="2" class="py-3">GRAND TOTAL</td>
                            <td class="text-warning h5 mb-0">{{ number_format($totalRow->gugur, 0, ',', '.') }}</td>
                            <td class="text-warning h5 mb-0">{{ number_format($totalRow->digugurkan, 0, ',', '.') }}</td>
                            <td class="text-info h5 mb-0">{{ number_format($totalRow->jumlah, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styling agar mirip dashboard SB Admin 2 */
    .border-left-primary {
        border-left: .25rem solid #4e73df !important;
    }

    .border-left-warning {
        border-left: .25rem solid #f6c23e !important;
    }

    .border-left-success {
        border-left: .25rem solid #1cc88a !important;
    }

    .card-header {
        border-bottom: 1px solid #e3e6f0;
    }
</style>
@endsection