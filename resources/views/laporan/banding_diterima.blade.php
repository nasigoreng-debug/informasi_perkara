@extends('layouts.app')

@section('title', 'RK.1 Banding Diterima')

@push('styles')
<style>
    /* Scoped styling to avoid affecting other tables */
    .rk1-container .table-rk1 {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.75rem;
        color: #333; /* Slightly softer than pure black */
    }

    .rk1-container .table-rk1 th,
    .rk1-container .table-rk1 td {
        border: 1px solid #cbd5e1; /* Lighter border for a cleaner look */
        padding: 8px 6px; /* Slightly more vertical padding */
        text-align: center;
        vertical-align: middle;
    }

    /* Header Atas Sticky */
    .rk1-container thead tr th {
        position: sticky;
        top: 0;
        z-index: 20;
        background-color: #1e293b !important; /* Darker, sleeker header */
        color: #f8fafc;
        font-weight: 600;
        border-color: #334155;
    }

    .rk1-container .text-vertical {
        writing-mode: vertical-rl;
        transform: rotate(180deg);
        height: 180px; /* Slightly reduced height if possible */
        white-space: nowrap;
        padding: 10px 4px !important;
        text-align: left;
        font-weight: 500;
        letter-spacing: 0.5px;
    }

    /* Kolom Kiri Sticky */
    .rk1-container .sticky-col {
        position: sticky;
        left: 0;
        z-index: 10;
        background-color: #ffffff;
    }

    .rk1-container .fz-1 {
        left: 0;
        width: 50px; /* Slightly wider for double-digit numbers */
    }

    .rk1-container .fz-2 {
        left: 50px;
        min-width: 220px; /* Give names a bit more room */
        border-right: 2px solid #94a3b8 !important; /* Visible separator */
    }

    /* Titik Temu Kiri-Atas */
    .rk1-container .sticky-header-col {
        position: sticky;
        left: 0;
        top: 0;
        z-index: 30 !important;
        background-color: #1e293b !important;
    }
    
    .rk1-container .fz-2.sticky-header-col {
        left: 50px;
        border-right: 2px solid #0f172a !important; /* Darker separator in header */
    }

    /* Baris Total Bawah Sticky */
    .rk1-container .sticky-footer td {
        position: sticky;
        bottom: 0;
        z-index: 25;
        background-color: #e2e8f0 !important;
        font-weight: bold;
        color: #0f172a;
        border-top: 2px solid #94a3b8;
    }
    
    .rk1-container .sticky-footer .sticky-col {
        z-index: 26; /* Ensure bottom-left stays above both scroll areas */
    }

    .rk1-container .bg-jumlah {
        background-color: #0284c7 !important; /* A more vibrant blue */
        color: #ffffff !important;
    }
    
    /* Hover effect for rows (excluding total row) */
    .rk1-container tbody tr:not(.sticky-footer):hover td:not(.sticky-col) {
        background-color: #f1f5f9;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4 rk1-container">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6 mb-3 mb-md-0">
            <h1 class="h4 fw-bold mb-1 text-uppercase text-gray-800">RK.1 Laporan Perkara Banding Diterima</h1>
            <span class="badge bg-secondary fs-6 fw-normal">
                Periode: {{ date('d-m-Y', strtotime($tgl_awal)) }} s/d {{ date('d-m-Y', strtotime($tgl_akhir)) }}
            </span>
        </div>
        <div class="col-md-6 text-md-end">
            <form action="{{ route('laporan.banding.diterima') }}" method="GET" class="d-flex flex-wrap justify-content-md-end gap-2 align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <input type="date" name="tgl_awal" class="form-control form-control-sm" value="{{ $tgl_awal }}" required>
                    <span class="text-muted small">s/d</span>
                    <input type="date" name="tgl_akhir" class="form-control form-control-sm" value="{{ $tgl_akhir }}" required>
                </div>
                <div class="d-flex gap-2 mt-2 mt-sm-0">
                    <button type="submit" class="btn btn-dark btn-sm px-3 shadow-sm">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('laporan.banding.diterima.export', request()->all()) }}" class="btn btn-success btn-sm px-3 shadow-sm">
                        <i class="fas fa-file-excel me-1"></i> Excel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 70vh; overflow-y: auto; overflow-x: auto;">
                <table class="table-rk1 mb-0">
                    <thead>
                        <tr>
                            <th rowspan="2" class="fz-1 sticky-header-col">NO</th>
                            <th rowspan="2" class="fz-2 sticky-header-col">PENGADILAN AGAMA</th>
                            <th colspan="23">A. PERKAWINAN</th>
                            <th rowspan="2" class="text-vertical">B. Ekonomi Syari'ah</th>
                            <th rowspan="2" class="text-vertical">C. Kewarisan</th>
                            <th rowspan="2" class="text-vertical">D. Wasiat</th>
                            <th rowspan="2" class="text-vertical">E. Hibah</th>
                            <th rowspan="2" class="text-vertical">F. Wakaf</th>
                            <th rowspan="2" class="text-vertical">G. Zakat/Infaq</th>
                            <th rowspan="2" class="text-vertical">H. P3HP/Ahli Waris</th>
                            <th rowspan="2" class="text-vertical">I. Lain-lain</th>
                            <th rowspan="2" class="text-vertical bg-jumlah">TOTAL JUMLAH</th>
                        </tr>
                        <tr>
                            @php 
                                $sub = [
                                    'Izin Poligami', 'Pencegahan Perkawinan', 'Penolakan Kawin PPN', 
                                    'Pembatalan Perkawinan', 'Kelalaian Kewajiban', 'Cerai Talak', 
                                    'Cerai Gugat', 'Harta Bersama', 'Penguasaan Anak', 'Nafkah Anak', 
                                    'Hak Bekas Isteri', 'Pengesahan Anak', 'Cabut Kuasa Ortu', 
                                    'Perwalian', 'Cabut Kuasa Wali', 'Penunjukan Wali', 'Ganti Rugi Wali', 
                                    'Asal Usul Anak', 'Tolak Kawin Campur', 'Isbath Nikah', 'Izin Kawin', 
                                    'Dispensasi Kawin', 'Wali Adhol'
                                ]; 
                            @endphp
                            @foreach($sub as $l) 
                                <th class="text-vertical" title="{{ $l }}">{{ $l }}</th> 
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($results as $index => $row)
                            @php $isT = ($row->satker == 'JUMLAH KESELURUHAN'); @endphp
                            <tr class="{{ $isT ? 'sticky-footer' : '' }}">
                                <td class="fz-1 sticky-col {{ $isT ? '' : 'bg-white' }}">
                                    {{ $isT ? '' : $index + 1 }}
                                </td>
                                <td class="text-start px-3 fz-2 sticky-col {{ $isT ? '' : 'bg-white fw-bold text-uppercase' }}">
                                    {{ $row->satker }}
                                </td>
                                
                                {{-- A. Perkawinan --}}
                                <td>{{ $row->iz ?? 0 }}</td>
                                <td>{{ $row->pp ?? 0 }}</td>
                                <td>{{ $row->p_ppn ?? 0 }}</td>
                                <td>{{ $row->pb ?? 0 }}</td>
                                <td>{{ $row->lks ?? 0 }}</td>
                                <td>{{ $row->ct ?? 0 }}</td>
                                <td>{{ $row->cg ?? 0 }}</td>
                                <td>{{ $row->hb ?? 0 }}</td>
                                <td>{{ $row->pa ?? 0 }}</td>
                                <td>{{ $row->nai ?? 0 }}</td>
                                <td>{{ $row->hbi ?? 0 }}</td>
                                <td>{{ $row->psa ?? 0 }}</td>
                                <td>{{ $row->pkot ?? 0 }}</td>
                                <td>{{ $row->pw ?? 0 }}</td>
                                <td>{{ $row->phw ?? 0 }}</td>
                                <td>{{ $row->pol ?? 0 }}</td>
                                <td>{{ $row->grw ?? 0 }}</td>
                                <td>{{ $row->aua ?? 0 }}</td>
                                <td>{{ $row->pkc ?? 0 }}</td>
                                <td>{{ $row->isbath ?? 0 }}</td>
                                <td>{{ $row->ik ?? 0 }}</td>
                                <td>{{ $row->dk ?? 0 }}</td>
                                <td>{{ $row->wa ?? 0 }}</td>
                                
                                {{-- B-I --}}
                                <td>{{ $row->es ?? 0 }}</td>
                                <td>{{ $row->kw ?? 0 }}</td>
                                <td>{{ $row->wst ?? 0 }}</td>
                                <td>{{ $row->hb_h ?? 0 }}</td>
                                <td>{{ $row->wkf ?? 0 }}</td>
                                <td>{{ ($row->zkt ?? 0) + ($row->infq ?? 0) }}</td>
                                <td>{{ $row->p3hp ?? 0 }}</td>
                                <td>{{ $row->ll ?? 0 }}</td>
                                
                                {{-- Total --}}
                                <td class="fw-bold" style="background-color:#e0f2fe; color:#0284c7;">
                                    {{ $row->jml ?? 0 }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="35" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block text-black-50"></i>
                                    Tidak ada data untuk periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection