@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card card-outline card-primary shadow">
        <div class="card-header bg-white py-3">
            <h3 class="card-title font-weight-bold text-primary text-uppercase">
                <i class="fas fa-balance-scale mr-2"></i> Rekapitulasi Mediasi se-Jawa Barat
            </h3>
        </div>

        <div class="card-body">
            <form action="{{ route('mediasi.index') }}" method="GET" class="row mb-4 align-items-end">
                <div class="col-md-3">
                    <label class="small font-weight-bold text-muted text-uppercase">Periode Awal</label>
                    <input type="date" name="tgl_awal" class="form-control shadow-sm" value="{{ $tgl_awal }}">
                </div>
                <div class="col-md-3">
                    <label class="small font-weight-bold text-muted text-uppercase">Periode Akhir</label>
                    <input type="date" name="tgl_akhir" class="form-control shadow-sm" value="{{ $tgl_akhir }}">
                </div>
                <div class="col-md-4">
                    <div class="btn-group w-100 shadow-sm">
                        <button type="submit" class="btn btn-primary font-weight-bold">
                            <i class="fas fa-search"></i> FILTER
                        </button>
                        <a href="{{ route('mediasi.index', array_merge(request()->query(), ['export' => 'excel'])) }}"
                            class="btn btn-success font-weight-bold"
                            target="_blank">
                            <i class="fas fa-file-excel mr-1"></i> EXCEL
                        </a>
                        <a href="{{ route('mediasi.index') }}" class="btn btn-secondary font-weight-bold">
                            <i class="fas fa-undo"></i>
                        </a>
                        <a href="{{ route('monitoring') }}" class="btn btn-dark font-weight-bold">
                            <i class="fas fa-arrow-left"></i> KEMBALI
                        </a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center table-sm small">
                    <thead class="bg-navy text-white text-uppercase">
                        <tr>
                            <th rowspan="2" class="align-middle">No</th>
                            <th rowspan="2" class="align-middle" style="min-width: 180px">Satuan Kerja</th>
                            <th rowspan="2" class="align-middle">Sisa<br>Lalu</th>
                            <th rowspan="2" class="align-middle">Masuk</th>
                            <th rowspan="2" class="align-middle bg-warning text-dark border-light">Jml Dimediasi</th>
                            <th colspan="3" class="bg-success text-white border-light">Berhasil</th>
                            <th rowspan="2" class="align-middle bg-success text-white border-light">Total Berhasil</th>
                            <th rowspan="2" class="align-middle">Tdk Berhasil</th>
                            <th rowspan="2" class="align-middle">Tdk Dapat Dilaksanakan</th>
                            <th rowspan="2" class="align-middle bg-info text-white">Mediasi Berjalan</th>
                            <th rowspan="2" class="align-middle bg-dark text-white border-light">%</th>
                        </tr>
                        <tr class="bg-success text-white">
                            <th>Seluruhnya</th>
                            <th>Sebagian</th>
                            <th>Pencabutan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $i => $row)
                        <tr>
                            <td class="bg-light">{{ $i + 1 }}</td>
                            <td class="text-left font-weight-bold">{{ $row->SATKER }}</td>
                            @if(isset($row->error))
                            <td colspan="11" class="text-danger font-italic small text-center">{{ $row->error }}</td>
                            @else
                            <td>{{ number_format($row->sisa_lalu) }}</td>
                            <td>{{ number_format($row->masuk) }}</td>
                            <td class="bg-warning-light font-weight-bold text-orange text-lg">{{ number_format($row->jml_dimediasi) }}</td>
                            <td>{{ number_format($row->b_akta) }}</td>
                            <td>{{ number_format($row->b_sebagian) }}</td>
                            <td>{{ number_format($row->b_cabut) }}</td>
                            <td class="bg-success-light font-weight-bold text-success">{{ number_format($row->total_berhasil) }}</td>
                            <td>{{ number_format($row->t_berhasil) }}</td>
                            <td>{{ number_format($row->t_dapat) }}</td>
                            <td class="bg-info-light font-weight-bold text-primary">{{ number_format($row->sisa_akhir) }}</td>
                            <td class="font-weight-bold bg-dark text-white">{{ number_format($row->persentase, 2) }}%</td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-dark text-white font-weight-bold">
                        <tr>
                            <td colspan="2" class="text-left pl-3 text-uppercase">Total</td>
                            @php
                            $valid = collect($reports)->where('error', null);
                            $total_dimediasi = $valid->sum('jml_dimediasi');
                            $total_berhasil = $valid->sum('total_berhasil');
                            $persen_total = ($total_dimediasi > 0) ? ($total_berhasil / $total_dimediasi) * 100 : 0;
                            @endphp
                            <td>{{ number_format($valid->sum('sisa_lalu')) }}</td>
                            <td>{{ number_format($valid->sum('masuk')) }}</td>
                            <td class="text-warning">{{ number_format($total_dimediasi) }}</td>
                            <td>{{ number_format($valid->sum('b_akta')) }}</td>
                            <td>{{ number_format($valid->sum('b_sebagian')) }}</td>
                            <td>{{ number_format($valid->sum('b_cabut')) }}</td>
                            <td class="text-success">{{ number_format($total_berhasil) }}</td>
                            <td>{{ number_format($valid->sum('t_berhasil')) }}</td>
                            <td>{{ number_format($valid->sum('t_dapat')) }}</td>
                            <td class="text-info">{{ number_format($valid->sum('sisa_akhir')) }}</td>
                            <td class="text-white bg-secondary">{{ number_format($persen_total, 2) }}%</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-navy {
        background-color: #001f3f !important;
    }

    .text-orange {
        color: #e67e22 !important;
    }

    .bg-warning-light {
        background-color: #fff9eb !important;
    }

    .bg-success-light {
        background-color: #f0fff4 !important;
    }

    .bg-info-light {
        background-color: #f0f7ff !important;
    }
</style>
@endsection