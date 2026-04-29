@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white shadow-sm border-0">
                <div class="card-body py-3">
                    <small class="d-block text-uppercase font-weight-bold">Total Non-Verstek</small>
                    <h3 class="mb-0 font-weight-bold">{{ number_format($totals['non_verstek']) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white shadow-sm border-0">
                <div class="card-body py-3">
                    <small class="d-block text-uppercase font-weight-bold">Total Sudah Mediasi</small>
                    <h3 class="mb-0 font-weight-bold">{{ number_format($totals['sudah']) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-secondary text-white shadow-sm border-0">
                <div class="card-body py-3">
                    <small class="d-block text-uppercase font-weight-bold">Total Cabut/Gugur</small>
                    <h3 class="mb-0 font-weight-bold">{{ number_format($totals['cabut']) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white shadow-sm border-0">
                <div class="card-body py-3">
                    <small class="d-block text-uppercase font-weight-bold">Total Tidak Mediasi</small>
                    <h3 class="mb-0 font-weight-bold">{{ number_format($totals['lalai']) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3 shadow-sm border-0">
        <div class="card-body py-2">
            <form action="{{ route('non-mediasi-gugatan.index') }}" method="GET" class="row align-items-end small font-weight-bold">
                <div class="col-md-3">
                    <label class="mb-1">TANGGAL AWAL</label>
                    <input type="date" name="tgl_awal" class="form-control form-control-sm" value="{{ $tgl_awal }}">
                </div>
                <div class="col-md-3">
                    <label class="mb-1">TANGGAL AKHIR</label>
                    <input type="date" name="tgl_akhir" class="form-control form-control-sm" value="{{ $tgl_akhir }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary btn-sm px-4 shadow-sm">
                        <i class="fa fa-filter mr-1"></i> FILTER DATA
                    </button>
                    <a href="{{ route('non-mediasi-gugatan.index') }}" class="btn btn-light btn-sm px-4 border shadow-sm ml-1">
                        <i class="fa fa-undo mr-1"></i> RESET
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-dark d-flex justify-content-between align-items-center py-2">
            <h6 class="mb-0 text-white font-weight-bold">Rekapitulasi Kepatuhan Mediasi Per Satker</h6>
            <span class="badge badge-light px-3">{{ count($rekap) }} Satker Terdata</span>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0 text-center small">
                <thead class="bg-light font-weight-bold">
                    <tr>
                        <th rowspan="3" class="align-middle border-0">NO</th>
                        <th rowspan="3" class="align-middle text-left border-0">SATUAN KERJA</th>
                        <th rowspan="3" class="align-middle bg-white text-primary">NON-VERSTEK</th>
                        <th colspan="3" class="bg-light">STATUS MEDIASI</th>
                        <th rowspan="3" class="align-middle text-danger">% KELALAIAN</th>
                    </tr>
                    <tr>
                        <th rowspan="2" class="align-middle text-success">MEDIASI</th>
                        <th colspan="2" class="text-danger border-bottom">TIDAK</th>
                    </tr>
                    <tr>
                        <th class="text-muted font-weight-normal small">CABUT/GUGUR</th>
                        <th class="bg-warning text-dark font-weight-bold">TIDAK MEDIASI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rekap as $row)
                    @php $persen = $row->jml_non_verstek > 0 ? ($row->tidak_mediasi / $row->jml_non_verstek) * 100 : 0; @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="text-left font-weight-bold">{{ $row->satker }}</td>
                        <td class="font-weight-bold text-primary">{{ number_format($row->jml_non_verstek) }}</td>
                        <td class="text-success">{{ number_format($row->sudah_mediasi) }}</td>
                        <td class="text-muted italic">{{ number_format($row->jml_cabut_gugur) }}</td>
                        <td class="bg-light font-weight-bold h6 mb-0">
                            <a href="{{ route('non-mediasi-gugatan.detail', [$row->satker, 'tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]) }}" class="text-danger">
                                {{ number_format($row->tidak_mediasi) }}
                            </a>
                        </td>
                        <td class="font-weight-bold {{ $persen > 10 ? 'text-danger' : 'text-success' }}">
                            {{ number_format($persen, 1) }}%
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-dark text-white font-weight-bold">
                    <tr>
                        <td colspan="2">TOTAL</td>
                        <td>{{ number_format($totals['non_verstek']) }}</td>
                        <td>{{ number_format($totals['sudah']) }}</td>
                        <td>{{ number_format($totals['cabut']) }}</td>
                        <td>{{ number_format($totals['lalai']) }}</td>
                        <td>
                            @php $grand_persen = $totals['non_verstek'] > 0 ? ($totals['lalai'] / $totals['non_verstek']) * 100 : 0; @endphp
                            {{ number_format($grand_persen, 1) }}%
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection