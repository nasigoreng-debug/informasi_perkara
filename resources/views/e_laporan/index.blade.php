@extends('layouts.app')

@section('content')
<style>
    /* --- STYLE ASLI ABANG --- */
    .bg-navy {
        background-color: #0d1b2a !important;
        color: white;
        border-bottom: 3px solid #ffc107;
    }

    .rekap-card {
        border-radius: 8px;
        color: #fff;
        text-align: center;
        padding: 15px 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border: none;
    }

    .rekap-card h3 {
        font-size: 28px;
        font-weight: 800;
        margin-bottom: 5px;
    }

    .rekap-card p {
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        margin: 0;
        letter-spacing: 0.5px;
    }

    .table-monitoring {
        font-size: 10.5px;
        border-collapse: separate;
        border-spacing: 0;
        border: 1px solid #343a40;
    }

    .table-monitoring thead th {
        position: sticky;
        top: 0;
        z-index: 100;
        background-color: #e9ecef;
        color: #212529;
        text-align: center;
        vertical-align: middle;
        border: 1px solid #ced4da;
        font-weight: 700;
        box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.2);
    }

    .sticky-col {
        position: sticky;
        left: 0;
        z-index: 95;
        border-right: 3px solid #343a40 !important;
    }

    .sticky-no {
        width: 40px;
        left: 0;
        font-weight: bold;
    }

    .sticky-satker {
        left: 40px;
        min-width: 170px;
        text-align: left;
        padding-left: 12px !important;
        text-transform: uppercase;
    }

    .row-hijau {
        background-color: #198754 !important;
        color: #fff !important;
    }

    .row-kuning {
        background-color: #ffc107 !important;
        color: #000 !important;
    }

    .row-biru {
        background-color: #0dcaf0 !important;
        color: #000 !important;
    }

    .row-merah {
        background-color: #dc3545 !important;
        color: #fff !important;
    }

    .table-monitoring td {
        vertical-align: middle;
        white-space: nowrap;
        padding: 6px 4px !important;
        border: 1px solid #6c757d;
    }

    .tgl-badge {
        font-size: 10.5px;
        display: block;
    }

    .jam-badge {
        font-size: 9px;
        opacity: 0.85;
        display: block;
        margin-top: 1px;
    }

    .status-badge {
        font-weight: 800;
        font-size: 10px;
        display: block;
        letter-spacing: 0.3px;
    }

    /* --- CSS PRINT (AGAR GAK KEPOTONG) --- */
    @media print {
        @page {
            size: landscape;
            margin: 0.5cm;
        }

        nav,
        .navbar,
        .btn,
        .no-print,
        form {
            display: none !important;
        }

        .container-fluid {
            width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        .card {
            border: none !important;
            box-shadow: none !important;
        }

        .table-responsive {
            overflow: visible !important;
            max-height: none !important;
        }

        .table-monitoring {
            font-size: 7.2pt !important;
            width: 100% !important;
            border: 0.5pt solid #333 !important;
        }

        .table-monitoring th,
        .table-monitoring td {
            padding: 1.5px !important;
            border: 0.5pt solid #333 !important;
        }

        .sticky-col {
            position: static !important;
        }

        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .print-only {
            display: block !important;
            text-align: center;
            margin-bottom: 10px;
        }
    }

    .print-only {
        display: none;
    }
</style>

<div class="container-fluid pb-4">
    <div class="print-only">
        <h3 style="margin-bottom: 0; font-weight: bold;">LAPORAN MONITORING KEPATUHAN E-LAPORAN</h3>
        <p>PTA BANDUNG - PERIODE: {{ strtoupper(\Carbon\Carbon::create()->month($bulanSelected)->isoFormat('MMMM')) }} {{ $tahunSelected }}</p>
        <hr style="border: 1px solid #000;">
    </div>

    {{-- HEADER & FILTER (TINGGI SEJAJAR 32PX) --}}
    <div class="d-flex justify-content-between align-items-center mb-3 mt-2 no-print">
        <h4 class="mb-0 font-weight-bold text-dark" style="font-size: 1.2rem;">
            <i class="fas fa-satellite-dish mr-2 text-primary"></i> MONITORING KEPATUHAN KONFIRMASI E-LAPORAN
        </h4>

        <div class="d-flex align-items-center">
            <button onclick="window.print()" class="btn btn-danger btn-sm font-weight-bold mr-2 shadow-sm" style="height: 32px; font-size: 11px; padding: 0 15px; display: flex; align-items: center; border-radius: 4px;">
                <i class="fas fa-print mr-2"></i> CETAK
            </button>

            <form method="GET" class="form-inline bg-white p-1 rounded shadow-sm border" style="height: 32px; display: flex; align-items: center;">
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-transparent border-0 font-weight-bold text-muted" style="font-size: 10px;">PERIODE:</span>
                    </div>
                    <select name="bulan" class="form-control border-0 font-weight-bold" style="width: 105px; height: 24px; font-size: 11px; cursor: pointer;">
                        @foreach([1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'] as $m => $nama)
                        <option value="{{ $m }}" {{ $bulanSelected == $m ? 'selected' : '' }}>{{ $nama }}</option>
                        @endforeach
                    </select>
                    <select name="tahun" class="form-control border-0 font-weight-bold" style="width: 75px; height: 24px; font-size: 11px; cursor: pointer; border-left: 1px solid #ddd !important;">
                        @for($y = date('Y')-2; $y <= date('Y'); $y++)
                            <option value="{{ $y }}" {{ $tahunSelected == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                    </select>
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-dark btn-sm px-3 ml-1" style="height: 24px; line-height: 1; border-radius: 4px;">
                            <i class="fas fa-search fa-xs"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Widget Rekap --}}
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="rekap-card" style="background-color: #198754;">
                <h3>{{ $rekap['tepat_waktu'] }} <small style="font-size: 14px;">Satker</small></h3>
                <p><i class="fas fa-check-circle"></i> LENGKAP & TEPAT WAKTU</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="rekap-card" style="background-color: #ffc107; color: #000;">
                <h3>{{ $rekap['terlambat'] }} <small style="font-size: 14px;">Satker</small></h3>
                <p><i class="fas fa-exclamation-triangle"></i> LENGKAP TERLAMBAT</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="rekap-card" style="background-color: #0dcaf0; color: #000;">
                <h3>{{ $rekap['belum_lengkap'] }} <small style="font-size: 14px;">Satker</small></h3>
                <p><i class="fas fa-spinner fa-spin"></i> PROSES / BELUM LENGKAP</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="rekap-card" style="background-color: #dc3545;">
                <h3>{{ $rekap['belum_isi'] }} <small style="font-size: 14px;">Satker</small></h3>
                <p><i class="fas fa-times-circle"></i> NIHIL / BELUM ISI</p>
            </div>
        </div>
    </div>

    <div class="card shadow border-0">
        <div class="card-header bg-navy py-2 no-print">
            <h6 class="mb-0 font-weight-bold"><i class="fas fa-list-ol mr-2"></i> PERINGKAT SATUAN KERJA</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 70vh;">
                <table class="table table-bordered table-monitoring mb-0 text-center">
                    <thead>
                        <tr>
                            <th class="sticky-col sticky-no">RANK</th>
                            <th class="sticky-col sticky-satker">SATUAN KERJA</th>
                            @php $lipas = ['lipa1','lipa2','lipa3','lipa4','lipa5','lipa5b','lipa5c','lipa5d','lipa6','lipa7a','lipa7b','lipa7c','lipa8','lipa9','lipa10','lipa11','lipa12','lipa13','lipa14','lipa15','lipa16','lipa17','lipa18','lipa19','lipa20','lipa21','lipa22']; @endphp
                            @foreach($lipas as $l) <th>{{ strtoupper($l) }}</th> @endforeach
                            <th>TOTAL</th>
                            <th style="min-width: 150px">STATUS KEPATUHAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $key => $row)
                        @php
                        $rowColor = ''; $labelStatus = '';
                        switch($row->status_kepatuhan) {
                        case 'TEPAT_WAKTU': $rowColor = 'row-hijau'; $labelStatus = 'LENGKAP & TEPAT WAKTU'; break;
                        case 'LENGKAP_TERLAMBAT': $rowColor = 'row-kuning'; $labelStatus = 'LENGKAP TERLAMBAT'; break;
                        case 'BELUM_LENGKAP': $rowColor = 'row-biru'; $labelStatus = 'BELUM LENGKAP'; break;
                        case 'BELUM_ISI': $rowColor = 'row-merah'; $labelStatus = 'BELUM ISI'; break;
                        }
                        @endphp
                        <tr class="{{ $rowColor }}">
                            <td class="text-center sticky-col sticky-no {{ $rowColor }}">{{ $key + 1 }}</td>
                            <td class="sticky-col sticky-satker {{ $rowColor }}"><b>{{ strtoupper($row->satker) }}</b></td>
                            @foreach($lipas as $l)
                            <td class="text-center">
                                @if($row->$l)
                                <span class="tgl-badge">{{ date('d/m/Y', strtotime($row->$l)) }}</span>
                                <span class="jam-badge"><i class="far fa-clock"></i> {{ date('H:i:s', strtotime($row->$l)) }}</span>
                                @else
                                <span style="opacity: 0.5;">-</span>
                                @endif
                            </td>
                            @endforeach
                            <td class="text-center font-weight-bold" style="font-size: 13px;">{{ $row->kelengkapan }}/27</td>
                            <td class="text-center"><span class="status-badge">{{ $labelStatus }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection