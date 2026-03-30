@extends('layouts.app')

@section('title', 'RK.2 | Banding Diputus')

@push('styles')
<style>
    .table-rk {
        font-size: 10px;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-rk td,
    .table-rk th {
        padding: 5px !important;
        border: 1px solid #dee2e6 !important;
    }

    thead th {
        vertical-align: middle !important;
        text-align: center !important;
        font-weight: 700;
        text-transform: uppercase;
    }

    .header-dark {
        background-color: #2c3e50 !important;
        color: #ffffff !important;
    }

    .header-gray {
        background-color: #5d6d7e !important;
        color: #ffffff !important;
    }

    .header-blue {
        background-color: #2980b9 !important;
        color: #ffffff !important;
    }

    .header-orange {
        background-color: #f39c12 !important;
        color: #000 !important;
    }

    .v-head {
        height: 200px;
        min-width: 30px;
        position: relative;
    }

    .v-head span {
        writing-mode: vertical-rl;
        transform: rotate(180deg);
        display: inline-block;
        text-align: center;
        height: 100%;
        margin: 0 auto;
    }

    .table-responsive {
        position: relative;
        border-radius: 8px;
        border: 1px solid #dee2e6;
        overflow: auto;
    }

    thead tr.main-header th {
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    thead tr.sub-header th {
        position: sticky;
        top: 41px;
        z-index: 999;
    }

    .sticky-col {
        position: sticky !important;
        z-index: 10;
        background-color: white;
    }

    .fz-1 {
        left: 0;
        width: 35px;
    }

    .fz-2 {
        left: 35px;
        width: 160px;
    }

    .fz-3 {
        left: 195px;
        width: 55px;
    }

    .fz-4 {
        left: 250px;
        width: 55px;
    }

    .fz-5 {
        left: 305px;
        width: 55px;
    }

    .sticky-footer td {
        position: sticky;
        bottom: 0;
        z-index: 1000;
        background-color: #2c3e50 !important;
        color: white !important;
        font-weight: bold;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    @php
        $jenisPerkara = [
            'iz'      => 'Izin Poligami',
            'pp'      => 'Pencegahan Perkawinan',
            'p_ppn'   => 'Penolakan PPN',
            'pb'      => 'Pembatalan Perkawinan',
            'lks'     => 'Kelalaian Kewajiban',
            'ct'      => 'Cerai Talak',
            'cg'      => 'Cerai Gugat',
            'hb'      => 'Harta Bersama',
            'pa'      => 'Penguasaan Anak',
            'nai'     => 'Nafkah Anak',
            'hbi'     => 'Hak Bekas Isteri',
            'psa'     => 'Pengesahan Anak',
            'pkot'    => 'Cabut Kuasa Ortu',
            'pw'      => 'Perwalian',
            'phw'     => 'Cabut Kuasa Wali',
            'pol'     => 'Penunjukan Wali',
            'grw'     => 'Ganti Rugi Wali',
            'aua'     => 'Asal Usul Anak',
            'pkc'     => 'Tolak Kawin Campur',
            'isbath'  => 'Isbath Nikah',
            'ik'      => 'Izin Kawin',
            'dk'      => 'Dispensasi Kawin',
            'wa'      => 'Wali Adhol',
            'es'      => 'Ekonomi Syari',
            'kw'      => 'Kewarisan',
            'wst'     => 'Wasiat',
            'hb_h'    => 'Hibah',
            'wkf'     => 'Wakaf',
            'zkt_infq'=> 'Zakat/Infaq',
            'p3hp'    => 'P3HP/Ahli Waris',
            'll'      => 'Lain-lain'
        ];
    @endphp

    <div class="text-center mb-4">
        <h3 class="fw-bold text-uppercase" style="color: #2c3e50;">
            Laporan Perkara Banding Diputus (RK.2)
        </h3>
        <h5 class="text-muted fw-normal">
            PENGADILAN AGAMA SE-JAWA BARAT | 
            <span class="badge bg-secondary">Periode: {{ $tgl_awal }} s/d {{ $tgl_akhir }}</span>
        </h5>
        <div class="mx-auto" style="width: 60px; height: 4px; background: #3498db; border-radius: 10px; margin-top: 10px;"></div>
    </div>

    {{-- FILTER & EXPORT CARD --}}
    <div class="card shadow-sm border-0 mb-4 bg-light">
        <div class="card-body">
            <form action="{{ route('laporan.banding.putus') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted">Tgl Awal</label>
                    <input type="date" name="tgl_awal" class="form-control form-control-sm" value="{{ $tgl_awal }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted">Tgl Akhir</label>
                    <input type="date" name="tgl_akhir" class="form-control form-control-sm" value="{{ $tgl_akhir }}">
                </div>
                <div class="col-md-8 d-flex gap-2">
                    <button type="submit" class="btn btn-dark btn-sm px-3 shadow-sm">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('laporan.banding.putus') }}" class="btn btn-outline-danger btn-sm px-3 shadow-sm">
                        <i class="fas fa-undo me-1"></i> Reset
                    </a>
                    <a href="{{ route('laporan.banding.putus.export', request()->all()) }}" class="btn btn-success btn-sm px-3 shadow-sm" target="_blank">
                        <i class="fas fa-file-excel me-1"></i> Excel
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- TABLE CARD --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 80vh;">
                <table class="table-rk table table-bordered align-middle mb-0">
                    <thead class="text-center align-middle">
                        <tr class="main-header">
                            <th rowspan="2" class="fz-1 sticky-col header-dark">NO</th>
                            <th rowspan="2" class="fz-2 sticky-col header-dark">PENGADILAN AGAMA</th>
                            <th rowspan="2" class="v-head fz-3 sticky-col header-gray"><span>SISA LALU</span></th>
                            <th rowspan="2" class="v-head fz-4 sticky-col header-gray"><span>DITERIMA</span></th>
                            <th rowspan="2" class="v-head fz-5 sticky-col header-gray border-end-strong"><span>JUMLAH (BEBAN)</span></th>
                            <th rowspan="2" class="v-head header-orange"><span>DICABUT</span></th>
                            <th colspan="{{ count($jenisPerkara) }}" class="header-blue text-white">A. PERKAWINAN</th>
                            <th colspan="7" class="header-orange text-dark">Status Putusan Akhir</th>
                            <th rowspan="2" class="v-head header-blue border-start-strong text-white"><span>JUMLAH PUTUS</span></th>
                            <th rowspan="2" class="v-head header-dark text-white"><span>SISA AKHIR</span></th>
                        </tr>
                        <tr class="sub-header">
                            @foreach($jenisPerkara as $alias => $label)
                                <th class="v-head" title="{{ $label }}"><span>{{ $label }}</span></th>
                            @endforeach
                            <th class="v-head"><span>DIKUATKAN</span></th>
                            <th class="v-head"><span>DIBATALKAN</span></th>
                            <th class="v-head"><span>DIPERBAIKI</span></th>
                            <th class="v-head"><span>TIDAK DITERIMA</span></th>
                            <th class="v-head"><span>DITOLAK</span></th>
                            <th class="v-head"><span>GUGUR</span></th>
                            <th class="v-head"><span>DICORET</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalRow = null; @endphp
                        @foreach($results as $index => $row)
                            @if($row->satker == 'JUMLAH KESELURUHAN')
                                @php $totalRow = $row; continue; @endphp
                            @endif
                            @php
                                // RUMUS JUMLAH PUTUS: Hanya menghitung STATUS AKHIR
                                $rowJmlPutus = ($row->dicabut ?? 0) + ($row->Dikuatkan ?? 0) + ($row->Dibatalkan ?? 0) + ($row->Diperbaiki ?? 0) +
                                                ($row->tidak_diterima ?? 0) + ($row->ditolak ?? 0) + ($row->gugur ?? 0) + ($row->dicoret ?? 0);
                                $rowSisaAkhir = ($row->beban ?? 0) - $rowJmlPutus;
                            @endphp
                            <tr>
                                <td class="text-center fz-1 sticky-col">{{ $index + 1 }}</td>
                                <td class="fz-2 sticky-col fw-bold px-3 text-uppercase" style="font-size: 10px;">
                                    {{ $row->satker }}
                                </td>
                                <td class="text-center fz-3 sticky-col bg-light fw-bold text-primary">
                                    {{ number_format($row->sisa_lalu ?? 0) }}
                                </td>
                                <td class="text-center fz-4 sticky-col bg-light fw-bold text-success">
                                    {{ number_format($row->diterima ?? 0) }}
                                </td>
                                <td class="text-center fz-5 sticky-col bg-light fw-bold border-end-strong">
                                    {{ number_format($row->beban ?? 0) }}
                                </td>
                                <td class="text-center">{{ number_format($row->dicabut ?? 0) }}</td>
                                
                                @foreach(array_keys($jenisPerkara) as $key)
                                    <td class="text-center border-sub">{{ number_format($row->$key ?? 0) }}</td>
                                @endforeach
                                
                                <td class="text-center bg-light-blue">{{ number_format($row->Dikuatkan ?? 0) }}</td>
                                <td class="text-center bg-light-blue">{{ number_format($row->Dibatalkan ?? 0) }}</td>
                                <td class="text-center bg-light-blue">{{ number_format($row->Diperbaiki ?? 0) }}</td>
                                <td class="text-center">{{ number_format($row->tidak_diterima ?? 0) }}</td>
                                <td class="text-center">{{ number_format($row->ditolak ?? 0) }}</td>
                                <td class="text-center">{{ number_format($row->gugur ?? 0) }}</td>
                                <td class="text-center">{{ number_format($row->dicoret ?? 0) }}</td>
                                <td class="text-center fw-bold bg-light-blue border-start-strong">
                                    {{ number_format($rowJmlPutus) }}
                                </td>
                                <td class="text-center fw-bold {{ $rowSisaAkhir > 0 ? 'text-danger' : 'text-success' }} bg-light">
                                    {{ number_format($rowSisaAkhir) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    
                    @if($totalRow)
                        @php
                            $grandJmlPutus = ($totalRow->dicabut ?? 0) + ($totalRow->Dikuatkan ?? 0) + ($totalRow->Dibatalkan ?? 0) + ($totalRow->Diperbaiki ?? 0) +
                                            ($totalRow->tidak_diterima ?? 0) + ($totalRow->ditolak ?? 0) + ($totalRow->gugur ?? 0) + ($totalRow->dicoret ?? 0);
                        @endphp
                        <tfoot>
                            <tr class="sticky-footer">
                                <td class="fz-1 sticky-col" colspan="2">JUMLAH KESELURUHAN</td>
                                <td class="text-center fz-3 sticky-col">{{ number_format($totalRow->sisa_lalu ?? 0) }}</td>
                                <td class="text-center fz-4 sticky-col">{{ number_format($totalRow->diterima ?? 0) }}</td>
                                <td class="text-center fz-5 sticky-col border-end-strong">{{ number_format($totalRow->beban ?? 0) }}</td>
                                <td class="text-center">{{ number_format($totalRow->dicabut ?? 0) }}</td>
                                
                                @foreach(array_keys($jenisPerkara) as $key)
                                    <td class="text-center">{{ number_format($totalRow->$key ?? 0) }}</td>
                                @endforeach
                                
                                <td class="text-center">{{ number_format($totalRow->Dikuatkan ?? 0) }}</td>
                                <td class="text-center">{{ number_format($totalRow->Dibatalkan ?? 0) }}</td>
                                <td class="text-center">{{ number_format($totalRow->Diperbaiki ?? 0) }}</td>
                                <td class="text-center">{{ number_format($totalRow->tidak_diterima ?? 0) }}</td>
                                <td class="text-center">{{ number_format($totalRow->ditolak ?? 0) }}</td>
                                <td class="text-center">{{ number_format($totalRow->gugur ?? 0) }}</td>
                                <td class="text-center">{{ number_format($totalRow->dicoret ?? 0) }}</td>
                                <td class="text-center border-start-strong">{{ number_format($grandJmlPutus) }}</td>
                                <td class="text-center">{{ number_format(($totalRow->beban ?? 0) - $grandJmlPutus) }}</td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection