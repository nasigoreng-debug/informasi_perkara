@extends('layouts.app')

@section('content')
<style>
    /* Styling Dasar Hukum SEMA */
    .dasar-hukum-box {
        background-color: #fdfdfd;
        border-left: 5px solid #1a5c2e;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .text-sema {
        color: #1a5c2e;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.85rem;
    }

    .quote-sema {
        font-style: italic;
        color: #444;
        font-size: 0.95rem;
    }

    /* Styling link agar tidak terlihat seperti link biasa tapi tetap interaktif */
    .link-detail {
        text-decoration: none !important;
        display: block;
        width: 100%;
        height: 100%;
        padding: 5px;
        border-radius: 4px;
        transition: background 0.2s;
    }

    .link-detail:hover {
        background-color: rgba(0, 0, 0, 0.05);
    }

    @media print {

        .no-print,
        .main-footer,
        .sidebar,
        .navbar,
        .btn-group,
        .d-print-none {
            display: none !important;
        }

        .container-fluid {
            width: 100%;
            padding: 0;
            margin: 0;
        }

        .card {
            border: none !important;
            box-shadow: none !important;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #000 !important;
            color: #000 !important;
        }

        .link-detail {
            color: #000 !important;
            background: none !important;
        }

        .bg-success {
            background-color: #28a745 !important;
            -webkit-print-color-adjust: exact;
        }

        .bg-warning {
            background-color: #ffc107 !important;
            -webkit-print-color-adjust: exact;
        }

        .bg-danger {
            background-color: #dc3545 !important;
            -webkit-print-color-adjust: exact;
        }
    }
</style>

<div class="container-fluid py-4">
    <div class="text-center mb-4">
        <h4 class="font-weight-bold text-gray-800 mb-1">MONITORING KETEPATAN WAKTU PENYELESAIAN PERKARA</h4>
        <h5 class="text-gray-700 text-uppercase">WILAYAH HUKUM PTA CIAMIS</h5>
        <div class="mt-2">
            <span class="badge badge-primary px-3 py-2 shadow-sm">Periode: {{ date('d-m-Y', strtotime($tglAwal)) }} s/d {{ date('d-m-Y', strtotime($tglAkhir)) }}</span>
        </div>
    </div>

    <div class="card dasar-hukum-box mb-4">
        <div class="card-body py-3">
            <div class="row align-items-center">
                <div class="col-auto"><i class="fas fa-gavel fa-2x text-success"></i></div>
                <div class="col">
                    <div class="text-sema">Dasar Hukum: SURAT EDARAN MAHKAMAH AGUNG NOMOR 2 TAHUN 2014</div>
                    <div class="quote-sema">"Penyelesaian perkara pada Pengadilan Tingkat Pertama paling lambat dalam waktu <strong>5 (lima) bulan</strong>."</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4 no-print">
        <div class="card-body">
            <form method="GET" action="{{ url()->current() }}" class="row align-items-end">
                <div class="col-md-3">
                    <label class="small font-weight-bold text-uppercase">Tanggal Awal</label>
                    <input type="date" name="tgl_awal" class="form-control shadow-sm" value="{{ $tglAwal }}">
                </div>
                <div class="col-md-3">
                    <label class="small font-weight-bold text-uppercase">Tanggal Akhir</label>
                    <input type="date" name="tgl_akhir" class="form-control shadow-sm" value="{{ $tglAkhir }}">
                </div>
                <div class="col-md-2">
                    <label class="small font-weight-bold text-uppercase">Batas Hari</label>
                    <input type="number" name="batas_hari" class="form-control shadow-sm" value="{{ $batasHari }}">
                </div>
                <div class="col-md-4">
                    <div class="btn-group w-100 shadow-sm">
                        <button type="submit" class="btn btn-primary font-weight-bold">
                            <i class="fas fa-search mr-1"></i> PROSES
                        </button>
                        <a href="{{ url()->current() }}" class="btn btn-dark">
                            <i class="fas fa-undo mr-1"></i> RESET
                        </a>
                        <button type="button" onclick="window.print()" class="btn btn-secondary text-white">
                            <i class="fas fa-print mr-1"></i> CETAK
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%">
                    <thead class="bg-light text-center small font-weight-bold text-gray-800 text-uppercase">
                        <tr>
                            <th width="40">NO</th>
                            <th>SATUAN KERJA</th>
                            <th width="120">TOTAL PUTUS</th>
                            <th width="120">TEPAT WAKTU</th>
                            <th width="120">TERLAMBAT</th>
                            <th width="250">PERSENTASE (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($results as $index => $row)
                        <tr>
                            <td class="text-center align-middle">{{ $index + 1 }}</td>
                            <td class="font-weight-bold text-primary text-uppercase align-middle">{{ $row->nama_satker }}</td>
                            <td class="text-center align-middle font-weight-bold">{{ number_format($row->jumlah_putus) }}</td>

                            <td class="text-center align-middle p-0">
                                <a href="{{ route('perkara.tepatwaktu.detail', ['koneksi_satker' => $row->koneksi_satker, 'status' => 'tepat', 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhir, 'batas_hari' => $batasHari]) }}"
                                    class="link-detail text-success font-weight-bold">
                                    {{ number_format($row->tepat_waktu) }}
                                </a>
                            </td>

                            <td class="text-center align-middle p-0">
                                <a href="{{ route('perkara.tepatwaktu.detail', ['koneksi_satker' => $row->koneksi_satker, 'status' => 'terlambat', 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhir, 'batas_hari' => $batasHari]) }}"
                                    class="link-detail text-danger font-weight-bold">
                                    {{ number_format($row->terlambat) }}
                                </a>
                            </td>

                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1 mr-2" style="height: 15px; border-radius: 10px;">
                                        @php
                                        $color = ($row->persentase >= 90) ? 'bg-success' : (($row->persentase >= 75) ? 'bg-warning' : 'bg-danger');
                                        @endphp
                                        <div class="progress-bar {{ $color }} progress-bar-striped progress-bar-animated"
                                            role="progressbar" style="width: {{ $row->persentase }}%"></div>
                                    </div>
                                    <span class="small font-weight-bold text-dark">{{ $row->persentase }}%</span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted font-italic">
                                <i class="fas fa-database mb-2 fa-2x d-block"></i>
                                Data tidak ditemukan. Pastikan data detail perkara sudah disinkronkan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="row mt-3 d-print-none">
                <div class="col-md-12 text-right small text-muted">
                    <i class="fas fa-info-circle mr-1"></i> Terakhir sinkronisasi: <strong>{{ $results->first()->updated_at ?? '-' }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection