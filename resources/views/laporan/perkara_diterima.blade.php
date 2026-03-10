@extends('layouts.app')

@section('content')
<style>
    /* Identik dengan RK4: Font 8px dan Narrow */
    .table-rk3 {
        font-size: 8px;
        border: 1px solid #000;
        font-family: 'Arial Narrow', Arial, sans-serif;
    }

    .table-rk3 th,
    .table-rk3 td {
        border: 1px solid #000 !important;
        padding: 2px 1px !important;
        vertical-align: middle;
        text-align: center;
    }

    /* Header Vertikal identik RK4 */
    .v-text {
        writing-mode: vertical-rl;
        transform: rotate(180deg);
        white-space: nowrap;
        height: 150px;
        font-weight: bold;
        line-height: 1;
        padding: 5px 0 !important;
    }

    .header-gray {
        background: #f2f2f2;
        font-weight: bold;
    }

    .sticky-header {
        position: sticky;
        top: 0;
        background: white;
        z-index: 100;
    }

    .text-start-important {
        text-align: left !important;
        padding-left: 5px !important;
    }
</style>

<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="fw-bold mb-0">LAPORAN PERKARA YANG DITERIMA (RK3)</h5>
            <small class="text-muted">Periode: {{ \Carbon\Carbon::parse($start)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($end)->format('d/m/Y') }}</small>
        </div>
        <div class="badge bg-primary px-3 py-2">PTA BANDUNG</div>
    </div>

    {{-- Filter Bar Identik RK4 --}}
    <div class="card mb-3 border-0 shadow-sm">
        <div class="card-body py-2 bg-light">
            <form action="{{ route('laporan.diterima.index') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="small fw-bold">DARI TANGGAL</label>
                    <input type="date" name="start" class="form-select form-select-sm" value="{{ $start }}">
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold">SAMPAI TANGGAL</label>
                    <input type="date" name="end" class="form-select form-select-sm" value="{{ $end }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-dark btn-sm w-100">
                        <i class="fas fa-filter me-1"></i> FILTER
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="#" class="btn btn-success btn-sm w-100">
                        <i class="fas fa-file-excel me-1"></i> EXCEL
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive shadow-sm" style="max-height: 80vh;">
        <table class="table table-rk3 w-100">
            <thead class="sticky-header">
                <tr class="header-gray">
                    <th rowspan="2" style="width: 25px;">NO</th>
                    <th rowspan="2" style="min-width: 150px;">NAMA PENGADILAN</th>
                    <th colspan="{{ count($jenisPerkara) }}">A. PERKAWINAN</th>
                    <th rowspan="2" class="v-text bg-primary text-white">JUMLAH</th>
                </tr>
                <tr class="header-gray">
                    @foreach($jenisPerkara as $label)
                    <th class="v-text">{{ $label }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @php
                $grandTotal = array_fill_keys(array_keys($jenisPerkara), 0);
                $totalSemuaPerkara = 0;
                @endphp

                @foreach($laporan as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-start-important fw-bold">{{ $row->satker }}</td>

                    @foreach($jenisPerkara as $key => $label)
                    @php $grandTotal[$key] += $row->$key; @endphp
                    <td>{{ number_format($row->$key) }}</td>
                    @endforeach

                    <td class="bg-primary bg-opacity-10 fw-bold">{{ number_format($row->total_baris) }}</td>
                    @php $totalSemuaPerkara += $row->total_baris; @endphp
                </tr>
                @endforeach
            </tbody>
            <tfoot class="header-gray sticky-bottom">
                <tr class="fw-bold">
                    <td colspan="2">TOTAL SE-WILAYAH JAWA BARAT</td>
                    @foreach($jenisPerkara as $key => $label)
                    <td>{{ number_format($grandTotal[$key]) }}</td>
                    @endforeach
                    <td class="bg-primary text-white">{{ number_format($totalSemuaPerkara) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection