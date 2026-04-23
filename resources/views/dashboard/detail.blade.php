@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: #f8f9fc;
        color: #333;
    }

    .breadcrumb-item a {
        color: #4e73df;
        text-decoration: none;
        font-weight: 600;
    }

    /* Tabel Header */
    .table thead th {
        background-color: #f1f4f9;
        font-size: 0.75rem;
        font-weight: 800;
        letter-spacing: 0.05em;
        color: #2c3e50;
        border-top: none;
        text-transform: uppercase;
    }

    .perkara-row:hover {
        background-color: #f1f4ff !important;
        transition: 0.3s;
    }

    /* Identitas Perkara */
    .no-banding {
        font-weight: 800;
        color: #1a237e;
        font-size: 0.95rem;
    }

    .info-sub {
        font-size: 0.75rem;
        font-weight: 600;
        color: #546e7a;
    }

    /* Timeline Badges (NO WHITE FONT) */
    .timeline-mini {
        display: flex;
        gap: 5px;
        margin-top: 8px;
    }

    .step-badge {
        font-size: 9px;
        padding: 4px 8px;
        border-radius: 4px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .badge-done {
        background-color: #c8e6c9;
        color: #1b5e20;
        border: 1px solid #81c784;
    }

    .badge-wait {
        background-color: #eceff1;
        color: #607d8b;
        border: 1px solid #cfd8dc;
    }

    /* Durasi Warna Soft (Teks Gelap) */
    .durasi-hijau {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .durasi-kuning-muda {
        background-color: #fff3cd;
        color: #856404;
        border: 1px solid #ffeeba;
    }

    .durasi-kuning-tua {
        background-color: #ffe8d1;
        color: #af5c00;
        border: 1px solid #ffcc99;
    }

    .durasi-merah {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    @media print {
        .no-print {
            display: none !important;
        }

        .card {
            border: none !important;
        }
    }
</style>

<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 no-print">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1" style="background: transparent; padding: 0;">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active fw-600">Detail Perkara</li>
                </ol>
            </nav>
            <h2 class="h3 mb-0 text-gray-800 fw-800">
                Data: <span style="color: #1a237e;">{{ strtoupper(str_replace(['_', 'n_o'], [' ', 'N.O'], $type)) }}</span>
                @if($jenis) <small class="text-muted">({{ $jenis }})</small> @endif
            </h2>
            <div class="text-muted small fw-600">
                <i class="far fa-calendar-alt me-1"></i> {{ date('d/m/Y', strtotime($tgl_awal)) }} — {{ date('d/m/Y', strtotime($tgl_akhir)) }}
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm fw-600">Kembali</a>
            <button onclick="window.print()" class="btn btn-primary btn-sm fw-600 shadow-sm">
                <i class="fas fa-print me-1"></i> Cetak Laporan
            </button>
        </div>
    </div>

    {{-- Main Table --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-center ps-4">NO</th>
                            <th>IDENTITAS PERKARA</th>
                            <th>SATKER & JENIS</th>
                            <th>PIHAK BERPERKARA</th>
                            <th>MAJELIS & TIMELINE</th>
                            <th class="text-center pe-4">HASIL PUTUSAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $key => $row)
                        @php
                        $tReg = \Carbon\Carbon::parse($row->tgl_register);
                        $tPut = $row->tgl_putusan ? \Carbon\Carbon::parse($row->tgl_putusan) : now();
                        $diff = $tReg->diffInDays($tPut);

                        if($diff > 90) $cls = 'durasi-merah';
                        elseif($diff > 60) $cls = 'durasi-kuning-tua';
                        elseif($diff > 30) $cls = 'durasi-kuning-muda';
                        else $cls = 'durasi-hijau';
                        @endphp
                        <tr class="perkara-row">
                            <td class="text-center ps-4 text-muted small fw-600">{{ $key + 1 }}</td>
                            <td>
                                <div class="no-banding">{{ $row->nomor_perkara_banding }}</div>
                                <div class="info-sub">PA: {{ $row->nomor_perkara_pa }}</div>
                            </td>
                            <td>
                                <div class="fw-800 text-dark small">{{ $row->nama_satker }}</div>
                                <div class="info-sub text-uppercase" style="color: #2e59d9;">{{ $row->jenis_perkara }}</div>
                            </td>
                            <td>
                                <div class="small text-truncate" style="max-width: 220px;">
                                    <strong style="color: #0d47a1;">P:</strong> {{ $row->nama_pembanding }}
                                </div>
                                <div class="small text-truncate" style="max-width: 220px;">
                                    <strong style="color: #b71c1c;">T:</strong> {{ $row->nama_terbanding }}
                                </div>
                            </td>
                            <td>
                                <div class="small mb-1 text-dark">
                                    <span class="text-muted">Hakim:</span> <strong>{{ $row->nama_km ?? '-' }}</strong>
                                </div>
                                <div class="small mb-2 text-dark">
                                    Reg: {{ date('d/m/y', strtotime($row->tgl_register)) }}
                                    @if($row->tgl_putusan)
                                    | <span style="color: #1b5e20; font-weight: 700;">Putus: {{ date('d/m/y', strtotime($row->tgl_putusan)) }}</span>
                                    @endif
                                </div>
                                <div class="timeline-mini">
                                    <span class="step-badge {{ $row->tgl_minutasi ? 'badge-done' : 'badge-wait' }}">Minutasi</span>
                                    <span class="step-badge {{ $row->tgl_kirim_pa ? 'badge-done' : 'badge-wait' }}">Kirim</span>
                                    <span class="step-badge {{ $row->tgl_upload ? 'badge-done' : 'badge-wait' }}">Upload</span>
                                    <span class="step-badge {{ $cls }} ms-auto">{{ $diff }} Hari</span>
                                </div>
                            </td>
                            <td class="text-center pe-4">
                                @if($row->jenis_putus_text)
                                <div class="fw-800 text-dark small mb-1">{{ $row->jenis_putus_text }}</div>
                                <span class="badge" style="background-color: #e8f5e9; color: #2e7d32; border: 1px solid #a5d6a7; font-size: 9px;">SELESAI</span>
                                @else
                                <span class="badge" style="background-color: #fff3e0; color: #e65100; border: 1px solid #ffcc80; font-size: 9px;">PROSES</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted fw-600">Data perkara tidak ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection