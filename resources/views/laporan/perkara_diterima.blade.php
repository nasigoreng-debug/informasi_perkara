@extends('layouts.app')

@section('content')
<style>
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

    .sync-pill {
        display: inline-flex;
        align-items: center;
        background: #ffffff;
        border: 1px solid #dee2e6;
        padding: 3px 12px;
        border-radius: 50px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }
</style>

<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="fw-bold mb-0">LAPORAN PERKARA YANG DITERIMA (RK3)</h5>
            <div class="d-flex align-items-center mt-1">
                <small class="text-muted fw-bold me-3">
                    <i class="fas fa-calendar-alt me-1"></i>
                    Periode: {{ \Carbon\Carbon::parse($start)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($end)->format('d/m/Y') }}
                </small>

                @php
                $lastSync = \DB::table('sync_logs')->where('modul', 'laporan_rk3')->latest('updated_at')->first();
                @endphp

                @if($lastSync)
                <div class="sync-pill">
                    <i class="fas fa-sync-alt fa-spin text-success me-2" style="font-size: 10px;"></i>
                    <small class="text-muted" style="font-size: 11px;">
                        Terakhir Sinkron: <strong class="text-dark">{{ \Carbon\Carbon::parse($lastSync->updated_at)->format('d/m/Y H:i') }}</strong>
                    </small>
                </div>
                @endif
            </div>
        </div>
        <div class="badge bg-primary px-3 py-2">PTA BANDUNG</div>
    </div>

    <div class="card mb-3 border-0 shadow-sm rounded border">
        <div class="card-body py-2 bg-light">
            <form action="{{ route('laporan.diterima.index') }}" method="GET" id="filterForm" class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="small fw-bold text-muted">DARI TANGGAL</label>
                    <input type="date" name="start" id="start" class="form-control form-control-sm" value="{{ $start }}">
                </div>
                <div class="col-md-2">
                    <label class="small fw-bold text-muted">SAMPAI TANGGAL</label>
                    <input type="date" name="end" id="end" class="form-control form-control-sm" value="{{ $end }}">
                </div>
                <div class="col-md-6 d-flex gap-2">
                    <button type="submit" class="btn btn-dark btn-sm px-4 shadow-sm">
                        <i class="fas fa-filter me-1"></i> FILTER
                    </button>

                    <a href="{{ route('laporan.diterima.index') }}" class="btn btn-outline-secondary btn-sm px-3 shadow-sm bg-white">
                        <i class="fas fa-undo me-1"></i> RESET FILTER
                    </a>

                    <button type="button" onclick="window.print()" class="btn btn-outline-danger btn-sm px-3 shadow-sm bg-white">
                        <i class="fas fa-print me-1"></i> CETAK
                    </button>
                </div>
                <div class="col-md-2 text-end">
                    <a href="{{ route('laporan.diterima.export') }}" class="btn btn-success btn-sm w-100 shadow-sm" target="_blank">
                        <i class="fas fa-file-excel me-1"></i> EXCEL
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive shadow-sm" style="max-height: 75vh;">
        <table class="table table-rk3 w-100 mb-0">
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
                @php $grandTotal = array_fill_keys(array_keys($jenisPerkara), 0); $totalSemua = 0; @endphp
                @foreach($laporan as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-start-important fw-bold text-uppercase">{{ $row->satker }}</td>
                    @foreach($jenisPerkara as $key => $label)
                    @php $nilai = $row->$key ?? 0; $grandTotal[$key] += $nilai; @endphp
                    <td>{{ $nilai > 0 ? number_format($nilai) : '-' }}</td>
                    @endforeach
                    <td class="bg-primary bg-opacity-10 fw-bold">{{ number_format($row->total_baris) }}</td>
                    @php $totalSemua += $row->total_baris; @endphp
                </tr>
                @endforeach
            </tbody>
            <tfoot class="header-gray sticky-bottom border-top-2 fw-bold">
                <tr>
                    <td colspan="2" class="py-2">TOTAL SE-WILAYAH JAWA BARAT</td>
                    @foreach($jenisPerkara as $key => $label)
                    <td>{{ number_format($grandTotal[$key]) }}</td>
                    @endforeach
                    <td class="bg-primary text-white">{{ number_format($totalSemua) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection