@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow border-0">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3 d-print-none">
            <h5 class="mb-0">
                <i class="fas fa-exclamation-triangle me-2 text-warning"></i> Monitoring Amar Putusan Tidak Lengkap
            </h5>
            <div class="d-flex gap-2">
                <button onclick="window.print()" class="btn btn-danger btn-sm fw-bold shadow-sm">
                    <i class="fas fa-print me-1"></i> CETAK LAPORAN
                </button>
                <span class="badge bg-warning text-dark d-flex align-items-center px-3">
                    Total Temuan: {{ $data->count() }} Perkara
                </span>
            </div>
        </div>

        <div class="d-none d-print-block text-center mb-4">
            <h3 class="mb-0">LAPORAN TEMUAN AMAR PUTUSAN TIDAK LENGKAP</h3>
            <h5 class="text-uppercase mt-1">SIPP SATKER WILAYAH JAWA BARAT</h5>
            <p class="mb-0">Periode Putusan: <strong>{{ date('d/m/Y', strtotime($tglAwal)) }}</strong> s/d <strong>{{ date('d/m/Y', strtotime($tglAkhir)) }}</strong></p>
            <hr style="border: 2px solid #000; opacity: 1;">
        </div>

        <div class="card-body bg-light">
            <form action="{{ route('monitoring.amar') }}" method="GET" class="row g-2 mb-4 p-3 bg-white rounded shadow-sm border d-print-none">
                <div class="col-md-3">
                    <label class="form-label fw-bold small text-muted">Dari Tanggal Putusan</label>
                    <input type="date" name="tgl_awal" class="form-control form-control-sm" value="{{ $tglAwal }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold small text-muted">Sampai Tanggal Putusan</label>
                    <input type="date" name="tgl_akhir" class="form-control form-control-sm" value="{{ $tglAkhir }}">
                </div>
                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary btn-sm flex-grow-1 fw-bold">
                        <i class="fas fa-search me-1"></i> PROSES DATA
                    </button>
                    <a href="{{ route('monitoring.amar') }}" class="btn btn-outline-secondary btn-sm px-3" title="Kembali ke Default">
                        <i class="fas fa-sync-alt"></i> RESET
                    </a>
                </div>
            </form>

            <div class="table-responsive rounded shadow-sm bg-white">
                <table class="table table-hover table-bordered align-middle mb-0" style="min-width: 1000px;">
                    <thead class="table-danger text-center small text-uppercase">
                        <tr>
                            <th width="40">No</th>
                            <th width="140">Satker</th>
                            <th width="200">Nomor Perkara</th>
                            <th width="120">Tgl Putusan</th>
                            <th>Isi Amar (Bermasalah)</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 0.85rem;">
                        @forelse($data as $key => $row)
                        @php
                        $amar = $row->amar_putusan;
                        // Logika Penandaan Kesalahan
                        $isResidu = str_contains($amar, '...') ||
                        str_contains($amar, '#') ||
                        str_contains($amar, '.......') ||
                        str_contains($amar, 'tanggal .......');

                        $isKosong = empty(trim(str_replace(['.', ' ', "\n", "\r"], '', $amar)));
                        @endphp
                        <tr class="{{ $isResidu || $isKosong ? 'bg-status-issue' : '' }}">
                            <td class="text-center text-muted">{{ $key + 1 }}</td>
                            <td class="text-center">
                                <span class="fw-bold text-dark">{{ $row->satker_nama }}</span>
                            </td>
                            <td class="fw-bold text-nowrap">{{ $row->nomor_perkara }}</td>
                            <td class="text-center">{{ date('d-m-Y', strtotime($row->tanggal_putusan)) }}</td>
                            <td>
                                <div class="box-amar p-2 border rounded {{ $isResidu || $isKosong ? 'border-danger-subtle' : '' }}">
                                    @if($isKosong)
                                    <span class="text-danger fw-bold"><i class="fas fa-ban me-1"></i> AMAR MASIH KOSONG / HANYA TITIK</span>
                                    @else
                                    {!! nl2br(e($amar)) !!}
                                    @endif
                                </div>

                                @if($isResidu && !$isKosong)
                                <div class="mt-2 text-danger fw-bold d-print-none" style="font-size: 0.75rem;">
                                    <i class="fas fa-exclamation-triangle pulse"></i> TERDETEKSI RESIDU TEMPLATE / TITIK-TITIK
                                </div>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="fas fa-check-circle fa-3x text-success mb-3 opacity-25"></i>
                                <h5 class="text-muted fw-light">Tidak ditemukan amar bermasalah pada periode ini.</h5>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white text-muted small d-print-none text-center py-3">
            <i class="fas fa-info-circle me-1 text-primary"></i>
            Data ditarik secara <strong>Real-Time</strong> dari 26 Database Satker melalui koneksi Bridge.
        </div>
    </div>
</div>

<style>
    /* Desain Layar */
    .bg-status-issue {
        background-color: #fffcf0 !important;
    }

    .box-amar {
        max-height: 120px;
        overflow-y: auto;
        background-color: #ffffff;
        font-family: 'Courier New', Courier, monospace;
        line-height: 1.4;
    }

    .table th {
        vertical-align: middle !important;
        letter-spacing: 0.5px;
    }

    .table td {
        vertical-align: top !important;
        padding: 10px !important;
    }

    /* Animasi Berkedip pada Icon Peringatan */
    .pulse {
        animation: pulse-red 2s infinite;
    }

    @keyframes pulse-red {
        0% {
            transform: scale(0.95);
            opacity: 0.7;
        }

        70% {
            transform: scale(1.1);
            opacity: 1;
        }

        100% {
            transform: scale(0.95);
            opacity: 0.7;
        }
    }

    /* Pengaturan Cetak (Media Print) */
    @media print {
        @page {
            size: A4 landscape;
            margin: 1.5cm;
        }

        .d-print-none {
            display: none !important;
        }

        .container-fluid {
            padding: 0 !important;
        }

        .card {
            border: none !important;
            box-shadow: none !important;
        }

        .table {
            width: 100% !important;
            border: 1px solid #000 !important;
        }

        .table th {
            background-color: #e9ecef !important;
            color: #000 !important;
            border: 1px solid #000 !important;
            -webkit-print-color-adjust: exact;
        }

        .table td {
            border: 1px solid #000 !important;
        }

        .box-amar {
            max-height: none !important;
            overflow: visible !important;
            border: none !important;
            padding: 0 !important;
            font-size: 9pt !important;
        }

        .text-danger {
            color: #000 !important;
            font-weight: bold;
        }
    }
</style>
@endsection