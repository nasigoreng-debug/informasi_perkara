@extends('layouts.app')

@section('title', 'Laporan Perkara Diputus')

@section('content')
<div class="container-fluid py-4">

    @php
    $namaBulan = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
    7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
    @endphp

    <div class="text-center mb-4">
        <h3 class="fw-bold text-uppercase" style="color: #2c3e50; letter-spacing: 1px;">Laporan Perkara Diputus</h3>
        <h5 class="text-muted fw-normal">
            Pengadilan Agama Se-Jawa Barat |
            <span class="badge bg-secondary">
                @if(!empty($month)) Bulan {{ $namaBulan[(int)$month] }}
                @elseif(!empty($quarter)) Triwulan {{ $quarter }}
                @else Tahun @endif {{ $year }}
            </span>
        </h5>
        <div class="mx-auto" style="width: 60px; height: 4px; background: #3498db; border-radius: 10px; margin-top: 10px;"></div>
    </div>

    {{-- Filter Card --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body bg-light">
            <form action="{{ route('laporan-putus.index') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-uppercase text-muted" style="font-size: 10px;">Tahun</label>
                    <select name="tahun" class="form-select form-select-sm shadow-sm">
                        @for($t=date('Y'); $t>=2020; $t--)
                        <option value="{{$t}}" {{$year == $t ? 'selected' : ''}}>{{$t}}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-uppercase text-muted" style="font-size: 10px;">Bulan</label>
                    <select name="bulan" class="form-select form-select-sm shadow-sm">
                        <option value="">Semua Bulan</option>
                        @foreach($namaBulan as $num => $nama)
                        <option value="{{$num}}" {{ ($month == $num) ? 'selected' : '' }}>{{$nama}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-uppercase text-muted" style="font-size: 10px;">Triwulan</label>
                    <select name="triwulan" class="form-select form-select-sm shadow-sm">
                        <option value="">Semua Triwulan</option>
                        @for($i=1; $i<=4; $i++)
                            <option value="{{$i}}" {{ $quarter == $i ? 'selected' : '' }}>Triwulan {{ $i }}</option>
                            @endfor
                    </select>
                </div>
                <div class="col-md-6 d-flex gap-2">
                    <button type="submit" class="btn btn-dark btn-sm px-3 shadow-sm"><i class="fas fa-filter me-1"></i> Filter</button>
                    <a href="{{ route('laporan-putus.export', request()->all()) }}" class="btn btn-success btn-sm px-3 shadow-sm"><i class="fas fa-file-excel me-1"></i> Excel</a>
                    <a href="{{ route('laporan.index') }}" class="btn btn-outline-primary btn-sm px-3 shadow-sm ms-auto">Lihat Diterima <i class="fas fa-chevron-right ms-1"></i></a>
                </div>
            </form>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 78vh;">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="text-center align-middle">
                        <tr class="main-header">
                            <th rowspan="2" class="fz-1 sticky-col header-dark">NO</th>
                            <th rowspan="2" class="fz-2 sticky-col header-dark">PENGADILAN AGAMA</th>

                            {{-- Kolom Kiri Vertikal --}}
                            <th rowspan="2" class="v-head fz-3 sticky-col header-gray text-white"><span>SISA TAHUN LALU</span></th>
                            <th rowspan="2" class="v-head fz-4 sticky-col header-gray text-white"><span>DITERIMA</span></th>
                            <th rowspan="2" class="v-head fz-5 sticky-col header-gray text-white border-end-strong"><span>BEBAN</span></th>

                            <th colspan="{{ count($jenisPerkara) }}" class="header-blue">JENIS PERKARA DIPUTUS (DIKABULKAN)</th>

                            <th rowspan="2" class="header-blue border-start-strong v-head"><span>TOTAL PUTUS</span></th>
                            <th colspan="6" class="header-orange text-dark">STATUS PUTUSAN</th>
                            <th rowspan="2" class="header-blue border-start-strong v-head"><span>PERSENTASE</span></th>
                            <th rowspan="2" class="v-head header-dark"><span>SISA AKHIR</span></th>
                        </tr>
                        <tr class="sub-header">
                            @foreach($jenisPerkara as $alias => $label)
                            <th class="v-head" title="{{ $label }}"><span>{{ $label }}</span></th>
                            @endforeach

                            <th class="v-head header-orange text-dark"><span>DICABUT</span></th>
                            <th class="v-head bg-danger text-white"><span>DITOLAK</span></th>
                            <th class="v-head bg-success text-white"><span>DIKABULKAN</span></th>
                            <th class="v-head bg-info text-white"><span>TIDAK DITERIMA</span></th>
                            <th class="v-head bg-secondary text-white"><span>GUGUR</span></th>
                            <th class="v-head bg-dark text-white"><span>DICORET</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalRow = null; @endphp
                        @foreach($laporan as $row)
                        @if(in_array($row->satker, ['JUMLAH KESELURUHAN', 'TOTAL']))
                        @php $totalRow = $row; @endphp
                        @continue
                        @endif
                        <tr>
                            <td class="text-center fz-1 sticky-col bg-white">{{ $row->no_urut }}</td>
                            <td class="fz-2 sticky-col bg-white fw-bold px-3 text-uppercase" style="font-size: 10px;">{{ $row->satker }}</td>
                            <td class="text-center fz-3 sticky-col bg-light fw-bold text-primary border-sub">{{ number_format($row->sisa_tahun_lalu) }}</td>
                            <td class="text-center fz-4 sticky-col bg-light fw-bold text-success border-sub">{{ number_format($row->diterima) }}</td>
                            <td class="text-center fz-5 sticky-col bg-light fw-bold border-end-strong">{{ number_format($row->beban) }}</td>

                            @foreach($jenisPerkara as $key => $label)
                            <td class="text-center border-sub" title="{{ $label }}">{{ number_format($row->$key ?? 0) }}</td>
                            @endforeach

                            <td class="text-center fw-bold bg-light-blue border-start-strong">{{ number_format($row->jml) }}</td>
                            <td class="text-center border-sub">{{ number_format($row->dicabut) }}</td>
                            <td class="text-center border-sub">{{ number_format($row->ditolak) }}</td>
                            <td class="text-center fw-bold text-success border-sub bg-light-success">{{ number_format($row->dikabulkan) }}</td>
                            <td class="text-center border-sub">{{ number_format($row->tidak_diterima) }}</td>
                            <td class="text-center border-sub">{{ number_format($row->gugur) }}</td>
                            <td class="text-center border-sub">{{ number_format($row->dicoret) }}</td>
                            <td class="text-center fw-bold text-primary border-start-strong">{{ number_format($row->persentase, 2) }}%</td>
                            <td class="text-center fw-bold {{ $row->sisa > 0 ? 'text-danger' : 'text-success' }} bg-light">
                                {{ number_format($row->sisa) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    @if($totalRow)
                    <tfoot class="sticky-footer">
                        <tr class="fw-bold">
                            <td class="text-center fz-1 sticky-col-foot" colspan="2">TOTAL KESELURUHAN</td>
                            <td class="text-center fz-3 sticky-col-foot">{{ number_format($totalRow->sisa_tahun_lalu) }}</td>
                            <td class="text-center fz-4 sticky-col-foot">{{ number_format($totalRow->diterima) }}</td>
                            <td class="text-center fz-5 sticky-col-foot border-end-strong">{{ number_format($totalRow->beban) }}</td>

                            @foreach(array_keys($jenisPerkara) as $key)
                            <td class="text-center">{{ number_format($totalRow->$key ?? 0) }}</td>
                            @endforeach

                            <td class="text-center border-start-strong">{{ number_format($totalRow->jml) }}</td>
                            <td class="text-center">{{ number_format($totalRow->dicabut) }}</td>
                            <td class="text-center">{{ number_format($totalRow->ditolak) }}</td>
                            <td class="text-center text-success">{{ number_format($totalRow->dikabulkan) }}</td>
                            <td class="text-center">{{ number_format($totalRow->tidak_diterima) }}</td>
                            <td class="text-center">{{ number_format($totalRow->gugur) }}</td>
                            <td class="text-center">{{ number_format($totalRow->dicoret) }}</td>
                            <td class="text-center border-start-strong">{{ number_format($totalRow->persentase, 2) }}%</td>
                            <td class="text-center text-white">{{ number_format($totalRow->sisa) }}</td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Global Table Style */
    .table {
        font-size: 11px;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table td,
    .table th {
        padding: 8px 4px !important;
        border: 1px solid #dee2e6 !important;
    }

    /* Perfect Center Header - Horizontal & Vertikal */
    thead th {
        vertical-align: middle !important;
        text-align: center !important;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Header Colors */
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
        color: #000000 !important;
    }

    /* Vertical Header Logic - Center Centered */
    .v-head {
        height: 250px;
        min-width: 40px;
        max-width: 55px;
        white-space: normal !important;
        position: relative;
        vertical-align: middle !important;
    }

    .v-head span {
        writing-mode: vertical-rl;
        transform: rotate(180deg);
        display: inline-block;
        text-align: center;
        line-height: 1.1;
        height: 100%;
        margin: 0 auto;
    }

    /* Sticky Header & Column */
    .table-responsive {
        position: relative;
        border-radius: 8px;
        border: 1px solid #dee2e6;
        overflow: auto;
    }

    /* Mengamankan Z-Index Header agar tidak tertumpuk saat scroll ke bawah */
    thead tr.main-header th {
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    /* Top disesuaikan dengan perkiraan tinggi baris header pertama, jika ada jeda, sesuaikan px ini */
    thead tr.sub-header th {
        position: sticky;
        top: 40px;
        z-index: 999;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    /* === PENGUNCIAN LEBAR STICKY COLUMNS === */
    /* Menambahkan min-width & max-width agar tidak terjadi kebocoran (layout shift) antar kolom */
    .sticky-col {
        position: sticky !important;
        z-index: 10;
    }

    .fz-1 {
        left: 0;
        width: 45px;
        min-width: 45px;
        max-width: 45px;
    }

    .fz-2 {
        left: 45px;
        width: 180px;
        min-width: 180px;
        max-width: 180px;
    }

    .fz-3 {
        left: 225px;
        width: 65px;
        min-width: 65px;
        max-width: 65px;
    }

    .fz-4 {
        left: 290px;
        width: 65px;
        min-width: 65px;
        max-width: 65px;
    }

    .fz-5 {
        left: 355px;
        width: 65px;
        min-width: 65px;
        max-width: 65px;
    }

    /* Sudut Kiri Atas (Pertemuan Header & Sticky Col) */
    thead th.sticky-col {
        z-index: 1100 !important;
    }

    .border-end-strong {
        border-right: 3px solid #2c3e50 !important;
    }

    .border-start-strong {
        border-left: 3px solid #2c3e50 !important;
    }

    .border-sub {
        border-right: 1px solid #dee2e6 !important;
    }

    tbody tr:nth-child(even) td:not(.sticky-col) {
        background-color: #fcfcfc;
    }

    tbody tr:hover td {
        background-color: #f1f7ff !important;
        transition: 0.1s;
    }

    .bg-light-blue {
        background-color: #ebf5fb !important;
    }

    .bg-light-success {
        background-color: #eafaf1 !important;
    }

    /* Footer / Tfoot Sticky */
    .sticky-footer td {
        position: sticky;
        bottom: 0;
        z-index: 1000;
        background-color: #2c3e50 !important;
        color: white !important;
    }

    /* Sudut Kiri Bawah (Pertemuan Footer & Sticky Col) */
    .sticky-footer td.sticky-col-foot {
        position: sticky !important;
        z-index: 1100 !important;
        background-color: #2c3e50 !important;
    }
</style>
@endpush