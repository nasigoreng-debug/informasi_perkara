@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: #f4f7fa;
    }

    .detail-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    }

    .table-detail thead th {
        background: #f8f9fc;
        color: #4e73df;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 15px;
        border: none;
    }

    .perkara-row {
        border-bottom: 1px solid #ebedf2;
        transition: all 0.2s;
    }

    .perkara-row:hover {
        background-color: #f8faff;
    }

    .no-banding {
        font-size: 14px;
        font-weight: 800;
        color: #2e59d9;
        margin-bottom: 2px;
    }

    .info-sub {
        font-size: 11px;
        color: #858796;
        display: block;
    }

    .badge-status {
        font-size: 10px;
        padding: 5px 12px;
        border-radius: 50px;
        font-weight: 700;
    }

    .timeline-mini {
        font-size: 10px;
        color: #a0aec0;
        margin-top: 5px;
    }

    .timeline-step {
        display: inline-block;
        margin-right: 10px;
    }

    .step-done {
        color: #00b894;
        font-weight: 600;
    }
</style>

<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-800 text-dark mb-0">Detail Monitoring Perkara</h4>
            <span class="badge bg-primary text-uppercase">{{ str_replace('_', ' ', $type) }}</span>
        </div>
        <a href="{{ route('dashboard', ['tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]) }}" class="btn btn-primary btn-sm rounded-3 px-3 fw-600 shadow-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card detail-card overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 table-detail text-nowrap">
                    <thead>
                        <tr>
                            <th class="ps-4 text-center">No</th>
                            <th width="300">Nomor Perkara (Banding / PA)</th>
                            <th>Satker & Jenis</th>
                            <th>Pihak (P / T)</th>
                            <th>Register & Putus</th>
                            <th class="text-center">Durasi & Status</th>
                            <th>Majelis & PP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $key => $row)
                        @php
                        $tReg = \Carbon\Carbon::parse($row->tgl_register);
                        $tPut = $row->tgl_putusan ? \Carbon\Carbon::parse($row->tgl_putusan) : \Carbon\Carbon::now();
                        $diff = $tReg->diffInDays($tPut);
                        $color = $diff > 90 ? 'danger' : ($diff > 30 ? 'warning text-dark' : 'success');
                        @endphp
                        <tr class="perkara-row">
                            <td class="text-center text-muted small ps-4">{{ $key + 1 }}</td>
                            <td>
                                {{-- PAKAI nomor_perkara_banding --}}
                                <div class="no-banding">{{ $row->nomor_perkara_banding }}</div>
                                <div class="info-sub text-muted">PA: {{ $row->nomor_perkara_pa }}</div>
                            </td>
                            <td>
                                <div class="fw-700 text-dark small">{{ $row->nama_satker }}</div>
                                <div class="info-sub text-uppercase">{{ $row->jenis_perkara }}</div>
                            </td>
                            <td>
                                <div class="small text-truncate" style="max-width: 200px;"><strong class="text-primary">P:</strong> {{ $row->nama_pembanding }}</div>
                                <div class="small text-truncate" style="max-width: 200px;"><strong class="text-danger">T:</strong> {{ $row->nama_terbanding }}</div>
                            </td>
                            <td>
                                <div class="small mb-1">Reg: <strong>{{ date('d/m/y', strtotime($row->tgl_register)) }}</strong></div>
                                @if($row->tgl_putusan)
                                <div class="small text-success">Putus: <strong>{{ date('d/m/y', strtotime($row->tgl_putusan)) }}</strong></div>
                                @else
                                <div class="small text-muted italic">Proses Sidang</div>
                                @endif
                                {{-- Timeline Mini Tracker --}}
                                <div class="timeline-mini">
                                    <span class="timeline-step {{ $row->tgl_minutasi ? 'step-done' : '' }}"><i class="fas fa-check-circle"></i> Min</span>
                                    <span class="timeline-step {{ $row->tgl_kirim_pa ? 'step-done' : '' }}"><i class="fas fa-check-circle"></i> Kirim</span>
                                    <span class="timeline-step {{ $row->tgl_upload ? 'step-done' : '' }}"><i class="fas fa-check-circle"></i> Up</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="h5 fw-800 mb-0 {{ $diff > 90 ? 'text-danger' : 'text-dark' }}">{{ $diff }}</div>
                                <div class="text-muted mb-1" style="font-size: 8px;">HARI</div>
                                <span class="badge badge-status bg-{{ $color }}">{{ $row->jenis_putus_text ?? 'Aktif' }}</span>
                            </td>
                            <td>
                                <div class="small fw-700 text-dark">{{ $row->nama_km }}</div>
                                <div class="info-sub">PP: {{ $row->nama_pp }}</div>
                                @if($row->tgl_putusan_sela)
                                <div class="mt-1"><span class="badge bg-danger" style="font-size: 8px;">PUTUSAN SELA</span></div>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">Data tidak ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection