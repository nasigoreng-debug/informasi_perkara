@extends('layouts.app')

@section('title', 'Laporan Statistik Perkara')

@section('content')
<div class="container-fluid py-4">

    <div class="text-center mb-4">
        <h3 class="fw-bold text-uppercase" style="color: #2c5364;">Laporan Perkara Diterima Pengadilan Agama Se-Jawa Barat</h3>
        <h5 class="text-secondary">
            Periode:
            @if($month)
            Bulan {{ date('F', mktime(0, 0, 0, $month, 1)) }}
            @elseif($quarter)
            Triwulan {{ $quarter }}
            @else
            Tahun
            @endif
            {{ $year }}
        </h5>
        <hr class="mx-auto" style="width: 100px; height: 3px; background: #2c5364; border-radius: 5px;">
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-white">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter me-2"></i>Filter Laporan
            </h6>
        </div>
        <div class="card-body">
            <form action="{{ route('laporan.index') }}" method="GET" class="row g-3 align-items-end">

                <div class="col-md-2">
                    <label class="form-label small fw-bold">Tahun</label>
                    <select name="tahun" class="form-select">
                        @for($t=date('Y'); $t>=2020; $t--)
                        <option value="{{$t}}" {{$year == $t ? 'selected' : ''}}>{{$t}}</option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-bold">Bulan</label>
                    <select name="bulan" class="form-select">
                        <option value="">-- Semua Bulan --</option>
                        @foreach(range(1,12) as $m)
                        <option value="{{$m}}" {{$month == $m ? 'selected' : ''}}>{{date('F', mktime(0,0,0,$m,1))}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-bold">Triwulan</label>
                    <select name="triwulan" class="form-select">
                        <option value="">-- Semua TW --</option>
                        <option value="1" {{$quarter == 1 ? 'selected' : ''}}>Triwulan I</option>
                        <option value="2" {{$quarter == 2 ? 'selected' : ''}}>Triwulan II</option>
                        <option value="3" {{$quarter == 3 ? 'selected' : ''}}>Triwulan III</option>
                        <option value="4" {{$quarter == 4 ? 'selected' : ''}}>Triwulan IV</option>
                    </select>
                </div>

                <div class="col-md-6 d-flex justify-content-start gap-2">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-search me-1"></i> Terapkan
                    </button>
                    <a href="{{ route('laporan.export', request()->all()) }}" class="btn btn-success px-4">
                        <i class="fas fa-file-excel me-1"></i> Export Excel
                    </a>
                    <a href="{{ route('laporan.index') }}" class="btn btn-outline-secondary px-4">
                        <i class="fas fa-undo me-1"></i> Reset
                    </a>
                </div>

            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 75vh;">
                <table class="table table-bordered table-sm text-nowrap align-middle mb-0">
                    <thead class="table-dark sticky-top">
                        <tr>
                            <th class="fz-1 text-center">No</th>
                            <th class="fz-2 text-center">Satuan Kerja</th>
                            @foreach($jenisPerkara as $alias => $label)
                            <th class="v-head">{{ $label }}</th>
                            @endforeach
                            <th class="v-head bg-primary text-white">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($laporan as $row)
                        <tr class="{{ $row->no_urut == 'TOTAL' ? 'table-secondary fw-bold' : '' }}">
                            <td class="text-center fz-1">{{ $row->no_urut }}</td>
                            <td class="fz-2 px-2">{{ $row->satker }}</td>
                            @foreach($jenisPerkara as $alias => $label)
                            <td class="text-center">{{ number_format($row->$alias) }}</td>
                            @endforeach
                            <td class="text-center fw-bold">{{ number_format($row->jml) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* CSS Header Berdiri */
    .v-head {
        writing-mode: vertical-rl;
        transform: rotate(180deg);
        text-align: left;
        padding: 15px 5px !important;
        height: 180px;
        font-size: 11px;
        min-width: 38px;
    }

    /* Membekukan Kolom 1 & 2 */
    .sticky-top {
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    thead th.fz-1,
    thead th.fz-2 {
        background-color: #212529 !important;
        color: white !important;
        z-index: 1001;
        position: sticky;
    }

    tbody td.fz-1,
    tbody td.fz-2 {
        position: sticky;
        background-color: #ffffff !important;
        color: #000000 !important;
        z-index: 10;
    }

    .fz-1 {
        left: 0;
        width: 45px;
    }

    .fz-2 {
        left: 45px;
        border-right: 2px solid #dee2e6 !important;
        min-width: 180px;
    }

    /* Efek Zebra & Hover */
    tbody tr:nth-child(even) td.fz-1,
    tbody tr:nth-child(even) td.fz-2 {
        background-color: #f8f9fa !important;
    }

    tr.table-secondary td.fz-1,
    tr.table-secondary td.fz-2 {
        background-color: #e9ecef !important;
        font-weight: bold;
    }

    .table td {
        font-size: 12.5px;
    }
</style>
@endpush