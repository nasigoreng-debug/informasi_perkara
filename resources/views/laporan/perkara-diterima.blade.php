@extends('layouts.app')

@section('title', 'Laporan Perkara Diterima')

@section('content')
<div class="container-fluid py-4">

    @php
        // Helper untuk nama bulan Indonesia
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
    @endphp

    <div class="text-center mb-4">
        <h3 class="fw-bold text-uppercase" style="color: #2c3e50; letter-spacing: 1px;">Laporan Perkara Diterima</h3>
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
            <form action="{{ route('laporan.index') }}" method="GET" class="row g-2 align-items-end">
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
                    <a href="{{ route('laporan.export', request()->all()) }}" class="btn btn-success btn-sm px-3 shadow-sm"><i class="fas fa-file-excel me-1"></i> Excel</a>
                    <a href="{{ route('laporan-putus.index') }}" class="btn btn-outline-primary btn-sm px-3 shadow-sm ms-auto">Lihat Diputus <i class="fas fa-chevron-right ms-1"></i></a>
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
                        <tr>
                            <th class="fz-1 sticky-col header-dark">NO</th>
                            <th class="fz-2 sticky-col header-dark border-end-strong">SATUAN KERJA</th>
                            
                            @foreach($jenisPerkara as $alias => $label)
                                <th class="v-head" title="{{ $label }}"><span>{{ $label }}</span></th>
                            @endforeach
                            
                            <th class="v-head header-blue text-white border-start-strong"><span>TOTAL DITERIMA</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($laporan as $row)
                        @php $isTotal = ($row->no_urut == 'TOTAL' || $row->satker == 'JUMLAH KESELURUHAN'); @endphp
                        <tr class="{{ $isTotal ? 'fw-bold sticky-footer' : '' }}">
                            <td class="text-center fz-1 sticky-col {{ $isTotal ? 'header-dark' : 'bg-white' }}">{{ $row->no_urut }}</td>
                            <td class="fz-2 sticky-col {{ $isTotal ? 'header-dark' : 'bg-white fw-bold' }} px-3 text-uppercase border-end-strong" style="font-size: 10px;">
                                {{ $row->satker }}
                            </td>
                            
                            @foreach($jenisPerkara as $alias => $label)
                                <td class="text-center border-sub {{ $isTotal ? 'bg-dark text-white' : '' }}">
                                    {{ number_format($row->$alias) }}
                                </td>
                            @endforeach
                            
                            <td class="text-center fw-bold border-start-strong {{ $isTotal ? 'header-blue text-white' : 'bg-light-blue text-primary' }}">
                                {{ number_format($row->jml) }}
                            </td>
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
    /* Global Table Style */
    .table { font-size: 11px; border-collapse: separate; border-spacing: 0; }
    .table td, .table th { padding: 8px 4px !important; border: 1px solid #dee2e6 !important; }
    
    /* Perfect Center Header */
    thead th { 
        vertical-align: middle !important; 
        text-align: center !important; 
        font-weight: 700; 
        text-transform: uppercase; 
        letter-spacing: 0.5px; 
        position: sticky;
        top: 0;
        z-index: 1000;
    }
    
    /* Header Colors */
    .header-dark { background-color: #2c3e50 !important; color: #ffffff !important; }
    .header-blue { background-color: #2980b9 !important; color: #ffffff !important; }
    
    /* Vertical Header Logic */
    .v-head {
        height: 250px; 
        min-width: 40px;
        max-width: 55px;
        white-space: normal !important;
        background-color: #2c3e50;
        color: white;
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

    /* Sticky Columns */
    .table-responsive { position: relative; border-radius: 8px; border: 1px solid #dee2e6; overflow: auto; }
    .sticky-col { position: sticky !important; z-index: 10; }
    .fz-1 { left: 0; width: 45px; }
    .fz-2 { left: 45px; min-width: 180px; }

    /* Pertemuan Header & Sticky Col */
    thead th.sticky-col { z-index: 1100 !important; }

    /* Border Strong - Pemisah Kategori */
    .border-end-strong { border-right: 3px solid #2c3e50 !important; }
    .border-start-strong { border-left: 3px solid #2c3e50 !important; }
    .border-sub { border-right: 1px solid #dee2e6 !important; }

    /* Row Styling */
    tbody tr:nth-child(even) td:not(.sticky-col) { background-color: #fcfcfc; }
    tbody tr:hover td { background-color: #f1f7ff !important; transition: 0.1s; }
    .bg-light-blue { background-color: #ebf5fb !important; }

    /* Footer / Total Row Sticky */
    .sticky-footer td {
        position: sticky;
        bottom: 0;
        z-index: 1000;
        background-color: #2c3e50 !important;
        color: white !important;
    }
</style>
@endpush