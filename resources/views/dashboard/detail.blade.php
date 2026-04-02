@extends('layouts.app')

@section('content')
<style>
    .table-detail thead th {
        background-color: #f8f9fc;
        color: #4e73df;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.5px;
    }

    .no-banding {
        font-size: 14px;
        font-weight: 700;
        color: #2e59d9;
        margin-bottom: 0;
    }

    .no-pa {
        font-size: 11px;
        color: #858796;
    }
</style>

<div class="container-fluid pt-4 pb-5">
    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h4 mb-0 text-gray-800 fw-bold">Detail Perkara Banding</h1>
            <p class="text-muted small mb-0">
                Kategori: <span class="badge bg-primary px-2">{{ strtoupper(str_replace('_', ' ', $type)) }}</span>
                @if($jenis) | Jenis: <span class="badge bg-info text-dark px-2">{{ $jenis }}</span> @endif
            </p>
        </div>
        <a href="{{ route('dashboard', ['tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]) }}" class="btn btn-sm btn-outline-secondary rounded-3 px-3 shadow-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Dashboard
        </a>
    </div>

    {{-- Info Periode --}}
    <div class="alert alert-light border-0 shadow-sm rounded-4 mb-4 py-2 px-3 small d-flex align-items-center">
        <i class="fas fa-calendar-alt text-primary me-2"></i>
        <span>Periode Data: <strong>{{ date('d M Y', strtotime($tgl_awal)) }}</strong> s/d <strong>{{ date('d M Y', strtotime($tgl_akhir)) }}</strong></span>
    </div>

    {{-- Main Table Card --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 table-detail">
                    <thead>
                        <tr class="text-center">
                            <th width="50" class="ps-4">No</th>
                            <th class="text-start">Nomor Perkara (Banding / PA)</th>
                            <th>Tgl Register</th>
                            <th>Tgl Putus</th>
                            <th>Durasi</th>
                            <th class="text-start">Ketua Majelis</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $key => $row)
                        @php
                        // Logika Hitung Durasi (Carbon)
                        $tReg = \Carbon\Carbon::parse($row->tgl_register);
                        $tPutus = $row->tgl_putusan ? \Carbon\Carbon::parse($row->tgl_putusan) : \Carbon\Carbon::now();
                        $days = $tReg->diffInDays($tPutus);

                        // Warna Badge Durasi
                        $color = $days > 90 ? 'danger' : ($days > 30 ? 'warning text-dark' : 'success');
                        @endphp
                        <tr class="text-center">
                            <td class="ps-4 text-muted small">{{ $key + 1 }}</td>
                            <td class="text-start">
                                <p class="no-banding">{{ $row->nomor_perkara_banding }}</p>
                                <p class="no-pa mb-0"><i class="fas fa-reply fa-rotate-180 me-1"></i> Asal: {{ $row->nomor_perkara_pa }}</p>
                            </td>
                            <td class="small">{{ date('d/m/Y', strtotime($row->tgl_register)) }}</td>
                            <td>
                                @if($row->tgl_putusan)
                                <span class="text-success small fw-bold">{{ date('d/m/Y', strtotime($row->tgl_putusan)) }}</span>
                                @else
                                <span class="text-muted italic small">Belum Putus</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $color }} rounded-pill px-3" style="font-size: 11px;">
                                    {{ $days }} Hari
                                </span>
                            </td>
                            <td class="text-start">
                                <div class="small text-dark fw-bold">{{ $row->nama_km }}</div>
                                <div style="font-size: 9px;" class="text-muted text-uppercase">{{ $row->jenis_perkara }}</div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted italic">
                                <i class="fas fa-folder-open fa-2x d-block mb-3 opacity-25"></i>
                                Data perkara tidak ditemukan untuk filter ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white py-3 border-0">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">Total Data: <strong>{{ count($data) }}</strong> Berkas</small>
                <div class="small text-muted">
                    <span class="badge bg-success me-1"> </span>
                    < 30 hr
                        <span class="badge bg-warning text-dark mx-1"> </span> 30-90 hr
                        <span class="badge bg-danger ms-1"> </span> > 90 hr
                </div>
            </div>
        </div>
    </div>
</div>
@endsection