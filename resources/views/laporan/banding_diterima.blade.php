@extends('layouts.app')

@section('title', 'RK.1 | Banding Diterima')

@push('styles')
<style>
    /* Scoped styling to avoid affecting other tables */
    .rk1-container .table-rk1 {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.75rem;
        color: #333;
    }

    .rk1-container .table-rk1 th,
    .rk1-container .table-rk1 td {
        border: 1px solid #cbd5e1;
        padding: 8px 6px;
        text-align: center;
        vertical-align: middle;
    }

    /* Header Atas Sticky */
    .rk1-container thead tr th {
        position: sticky;
        top: 0;
        z-index: 20;
        background-color: #1e293b !important;
        color: #f8fafc;
        font-weight: 600;
        border-color: #334155;
    }

    .rk1-container .text-vertical {
        writing-mode: vertical-rl;
        transform: rotate(180deg);
        height: 180px;
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
        width: 50px;
    }

    .rk1-container .fz-2 {
        left: 50px;
        min-width: 220px;
        border-right: 2px solid #94a3b8 !important;
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
        border-right: 2px solid #0f172a !important;
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
        z-index: 26;
    }

    .rk1-container .bg-jumlah {
        background-color: #0284c7 !important;
        color: #0e0d0d !important;
    }

    .rk1-container tbody tr:not(.sticky-footer):hover td:not(.sticky-col) {
        background-color: #f1f5f9;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4 rk1-container">
    {{-- HEADER JUDUL (SENADA DENGAN RK.2) --}}
    <div class="text-center mb-4">
        <h3 class="fw-bold text-uppercase" style="color: #2c3e50;">
            Laporan Perkara Banding Diterima (RK.1)
        </h3>
        <h5 class="text-muted fw-normal">
            PENGADILAN AGAMA SE-JAWA BARAT | 
            <span class="badge bg-secondary">Periode: {{ $tgl_awal }} s/d {{ $tgl_akhir }}</span>
        </h5>
        <div class="mx-auto" style="width: 60px; height: 4px; background: #3498db; border-radius: 10px; margin-top: 10px;"></div>
    </div>

    {{-- FILTER & EXPORT CARD (SENADA DENGAN RK.2) --}}
    <div class="card shadow-sm border-0 mb-4 bg-light">
        <div class="card-body">
            <form action="{{ route('laporan.banding.diterima') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted">Tgl Awal</label>
                    <input type="date" name="tgl_awal" class="form-control form-control-sm" value="{{ $tgl_awal }}" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted">Tgl Akhir</label>
                    <input type="date" name="tgl_akhir" class="form-control form-control-sm" value="{{ $tgl_akhir }}" required>
                </div>
                <div class="col-md-8 d-flex gap-2">
                    <button type="submit" class="btn btn-dark btn-sm px-3 shadow-sm">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('laporan.banding.diterima') }}" class="btn btn-outline-danger btn-sm px-3 shadow-sm">
                        <i class="fas fa-undo me-1"></i> Reset
                    </a>
                    <a href="{{ route('laporan.banding.diterima.export', request()->all()) }}" class="btn btn-success btn-sm px-3 shadow-sm" target="_blank">
                        <i class="fas fa-file-excel me-1"></i> Excel
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- TABLE CARD --}}
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 70vh; overflow: auto;">
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
                                    'Izin Poligami',
                                    'Pencegahan Perkawinan',
                                    'Penolakan Kawin PPN',
                                    'Pembatalan Perkawinan',
                                    'Kelalaian Kewajiban',
                                    'Cerai Talak',
                                    'Cerai Gugat',
                                    'Harta Bersama',
                                    'Penguasaan Anak',
                                    'Nafkah Anak',
                                    'Hak Bekas Isteri',
                                    'Pengesahan Anak',
                                    'Cabut Kuasa Ortu',
                                    'Perwalian',
                                    'Cabut Kuasa Wali',
                                    'Penunjukan Wali',
                                    'Ganti Rugi Wali',
                                    'Asal Usul Anak',
                                    'Tolak Kawin Campur',
                                    'Isbath Nikah',
                                    'Izin Kawin',
                                    'Dispensasi Kawin',
                                    'Wali Adhol'
                                ];
                            @endphp
                            @foreach($sub as $l)
                                <th class="text-vertical" title="{{ $l }}"><span>{{ $l }}</span></th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($results as $index => $row)
                            @php $isTotal = ($row->satker == 'JUMLAH KESELURUHAN'); @endphp
                            <tr class="{{ $isTotal ? 'sticky-footer' : '' }}">
                                <td class="fz-1 sticky-col {{ $isTotal ? '' : 'bg-white' }}">
                                    {{ $isTotal ? '' : $index + 1 }}
                                </td>
                                <td class="text-start px-3 fz-2 sticky-col {{ $isTotal ? '' : 'bg-white fw-bold text-uppercase' }}">
                                    {{ $row->satker }}
                                </td>

                                {{-- A. Perkawinan --}}
                                <td>{{ number_format($row->iz ?? 0) }}</td>
                                <td>{{ number_format($row->pp ?? 0) }}</td>
                                <td>{{ number_format($row->p_ppn ?? 0) }}</td>
                                <td>{{ number_format($row->pb ?? 0) }}</td>
                                <td>{{ number_format($row->lks ?? 0) }}</td>
                                <td>{{ number_format($row->ct ?? 0) }}</td>
                                <td>{{ number_format($row->cg ?? 0) }}</td>
                                <td>{{ number_format($row->hb ?? 0) }}</td>
                                <td>{{ number_format($row->pa ?? 0) }}</td>
                                <td>{{ number_format($row->nai ?? 0) }}</td>
                                <td>{{ number_format($row->hbi ?? 0) }}</td>
                                <td>{{ number_format($row->psa ?? 0) }}</td>
                                <td>{{ number_format($row->pkot ?? 0) }}</td>
                                <td>{{ number_format($row->pw ?? 0) }}</td>
                                <td>{{ number_format($row->phw ?? 0) }}</td>
                                <td>{{ number_format($row->pol ?? 0) }}</td>
                                <td>{{ number_format($row->grw ?? 0) }}</td>
                                <td>{{ number_format($row->aua ?? 0) }}</td>
                                <td>{{ number_format($row->pkc ?? 0) }}</td>
                                <td>{{ number_format($row->isbath ?? 0) }}</td>
                                <td>{{ number_format($row->ik ?? 0) }}</td>
                                <td>{{ number_format($row->dk ?? 0) }}</td>
                                <td>{{ number_format($row->wa ?? 0) }}</td>

                                {{-- B-I --}}
                                <td>{{ number_format($row->es ?? 0) }}</td>
                                <td>{{ number_format($row->kw ?? 0) }}</td>
                                <td>{{ number_format($row->wst ?? 0) }}</td>
                                <td>{{ number_format($row->hb_h ?? 0) }}</td>
                                <td>{{ number_format($row->wkf ?? 0) }}</td>
                                <td>{{ number_format($row->zkt_infq ?? 0) }}</td>
                                <td>{{ number_format($row->p3hp ?? 0) }}</td>
                                <td>{{ number_format($row->ll ?? 0) }}</td>

                                {{-- Total --}}
                                <td class="fw-bold bg-jumlah">
                                    {{ number_format($row->jml ?? 0) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="35" class="text-center py-5 text-muted">
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