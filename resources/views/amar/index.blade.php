@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center d-print-none py-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('monitoring') }}" class="btn btn-outline-light btn-sm" title="Kembali ke Dashboard">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
                <h5 class="mb-0">
                    <i class="fas fa-exclamation-triangle me-2 text-warning"></i> Monitoring Amar Putusan Tidak Lengkap
                </h5>
            </div>

            <div class="d-flex align-items-center gap-2">
                <button onclick="window.print()" class="btn btn-danger btn-sm fw-bold shadow">
                    <i class="fas fa-print me-1"></i> CETAK LAPORAN
                </button>
                <div class="bg-warning text-dark px-3 py-1 rounded fw-bold shadow-sm">
                    Total: {{ $data->count() }} Temuan
                </div>
            </div>
        </div>

        <div class="d-none d-print-block text-center mb-4">
            <h3 class="mb-0">LAPORAN MONITORING AMAR PUTUSAN TIDAK LENGKAP</h3>
            <h5 class="text-uppercase mt-1">SIPP SATKER WILAYAH JAWA BARAT</h5>
            <p class="mb-0">Periode Putusan: <strong>{{ date('d/m/Y', strtotime($tglAwal)) }}</strong> s/d <strong>{{ date('d/m/Y', strtotime($tglAkhir)) }}</strong></p>
            <hr style="border: 2px solid #000; opacity: 1;">
        </div>

        <div class="card-body bg-light">
            <form action="{{ route('monitoring.amar') }}" method="GET" class="row g-2 mb-4 p-3 bg-white rounded shadow-sm border d-print-none">
                <div class="col-md-3">
                    <label class="form-label fw-bold small text-muted">Dari Tanggal Putusan</label>
                    <input type="date" name="tgl_awal" class="form-control" value="{{ $tglAwal }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold small text-muted">Sampai Tanggal Putusan</label>
                    <input type="date" name="tgl_akhir" class="form-control" value="{{ $tglAkhir }}">
                </div>
                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1 fw-bold">
                        <i class="fas fa-search me-1"></i> TAMPILKAN
                    </button>
                    <a href="{{ route('monitoring.amar') }}" class="btn btn-outline-secondary px-3" title="Reset Filter">
                        <i class="fas fa-sync-alt"></i> RESET
                    </a>
                </div>
            </form>

            <div class="table-responsive rounded shadow-sm bg-white">
                <table class="table table-hover table-bordered align-middle mb-0">
                    <thead class="table-danger text-center small uppercase">
                        <tr>
                            <th width="40">No</th>
                            <th width="120">Satker</th>
                            <th width="200">Nomor Perkara</th>
                            <th width="120">Tgl Putusan</th>
                            <th>Isi Amar Bermasalah</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 0.85rem;">
                        @forelse($data as $key => $row)
                        @php
                        $amar = $row->amar_putusan;
                        $isResidu = str_contains($amar, '...') ||
                        str_contains($amar, '#') ||
                        str_contains($amar, '.......');

                        $isKosong = empty(trim(str_replace(['.', ' ', "\n", "\r"], '', $amar)));
                        @endphp
                        <tr class="{{ $isResidu || $isKosong ? 'bg-issue' : '' }}">
                            <td class="text-center text-muted">{{ $key + 1 }}</td>
                            <td class="text-center fw-bold">{{ $row->satker_nama }}</td>
                            <td class="fw-bold">{{ $row->nomor_perkara }}</td>
                            <td class="text-center">{{ date('d-m-Y', strtotime($row->tanggal_putusan)) }}</td>
                            <td>
                                <div class="box-amar p-2 border rounded {{ $isResidu || $isKosong ? 'border-danger-subtle' : '' }}">
                                    @if($isKosong)
                                    <span class="text-danger fw-bold"><i class="fas fa-ban me-1"></i> KOSONG / BELUM INPUT</span>
                                    @else
                                    {!! nl2br(e($amar)) !!}
                                    @endif
                                </div>
                                @if($isResidu && !$isKosong)
                                <div class="mt-1 text-danger fw-bold d-print-none" style="font-size: 0.75rem;">
                                    <i class="fas fa-exclamation-triangle"></i> Terdeteksi Tidak Lengkap
                                </div>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="fas fa-check-circle fa-3x text-success mb-3 opacity-25"></i>
                                <h5 class="text-muted">Data Bersih. Tidak ada amar bermasalah.</h5>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white text-center text-muted small d-print-none py-3">
            Sistem Monitoring Pengadilan Tinggi Agama Bandung - Tahun 2026
        </div>
    </div>
</div>

<style>
    .bg-issue {
        background-color: #fff9e6 !important;
    }

    .box-amar {
        max-height: 100px;
        overflow-y: auto;
        background-color: #ffffff;
        font-family: 'Courier New', Courier, monospace;
        line-height: 1.4;
    }

    .table td {
        vertical-align: top !important;
        padding: 8px !important;
    }

    @media print {
        @page {
            size: A4 landscape;
            margin: 1cm;
        }

        .d-print-none {
            display: none !important;
        }

        .container-fluid {
            padding: 0 !important;
        }

        .card {
            border: none !important;
        }

        .table {
            width: 100% !important;
            border: 1px solid #000 !important;
        }

        .table th {
            background-color: #f8d7da !important;
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
    }
</style>
@endsection