@extends('layouts.app')

@section('title', 'Rekapitulasi Kinerja Eksekusi')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<style>
    :root {
        --pta-navy: #1a2a6c;
        --pta-slate: #2a4858;
        --bg-light: #f4f7fa;
    }

    body {
        background-color: var(--bg-light);
        font-family: "Bookman Old Style", "Bookman", serif;
        color: #2d3436;
    }

    .dashboard-header {
        background: linear-gradient(135deg, var(--pta-navy) 0%, var(--pta-slate) 100%);
        border-radius: 25px;
        padding: 2rem;
        color: white;
        box-shadow: 0 10px 30px rgba(26, 42, 108, 0.2);
    }

    .global-overview {
        background: #1e293b;
        border-radius: 20px;
        color: white;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }

    .global-stat-box {
        border-right: 1px solid rgba(255, 255, 255, 0.1);
    }

    .kpi-card {
        border: none;
        border-radius: 20px;
        background: white;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    .table-container {
        border-radius: 25px;
        background: white;
        padding: 2rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.03);
    }

    .custom-table thead th {
        background-color: #f8fafc;
        color: #64748b;
        font-weight: bold;
        text-transform: uppercase;
        padding: 1.2rem;
    }

    .angka-link {
        color: inherit;
        text-decoration: none;
        font-weight: bold;
        padding: 4px 8px;
        border-radius: 6px;
    }

    .angka-link:hover {
        background: rgba(26, 42, 108, 0.08);
        color: var(--pta-navy) !important;
        text-decoration: underline;
    }

    .badge-sisa {
        background-color: #fff1f2;
        color: #e11d48;
        padding: 0.5rem 0.8rem;
        border-radius: 10px;
        font-weight: bold;
        border: 1px solid #fecdd3;
    }

    .tfoot-total {
        background-color: var(--pta-navy) !important;
        color: white !important;
        font-weight: bold;
    }

    input[type="date"] {
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 5px 10px;
        font-weight: bold;
        color: var(--pta-navy);
    }

    .btn-reset {
        background: rgba(255, 255, 255, 0.15);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s;
    }

    .btn-reset:hover {
        background: rgba(255, 255, 255, 0.3);
        color: white;
        transform: scale(1.05);
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4 px-xl-5">
    @php
    $urutanManual = ['BANDUNG', 'INDRAMAYU', 'MAJALENGKA', 'SUMBER', 'CIAMIS', 'TASIKMALAYA', 'KARAWANG', 'CIMAHI', 'SUBANG', 'SUMEDANG', 'PURWAKARTA', 'SUKABUMI', 'CIANJUR', 'KUNINGAN', 'CIBADAK', 'CIREBON', 'GARUT', 'BOGOR', 'BEKASI', 'CIBINONG', 'CIKARANG', 'DEPOK', 'TASIKKOTA', 'BANJAR', 'SOREANG', 'NGAMPRAH'];
    $sortedData = collect($data)->sortBy(function($item) use ($urutanManual) {
    $namaClean = strtoupper(trim(str_replace(['PA ', 'PENGADILAN AGAMA '], '', $item->satker)));
    $index = array_search($namaClean, $urutanManual);
    return ($index === false) ? 999 : $index;
    });
    $hariIni = date('Y-m-d');
    $tglAkhirFix = ($tglAkhir >= date('Y-12-31') || $tglAkhir > $hariIni) ? $hariIni : $tglAkhir;
    @endphp

    <div class="dashboard-header mb-4 animate__animated animate__fadeIn">
        <div class="row align-items-center">
            <div class="col-md-4">
                <h3 class="mb-1 text-uppercase fw-bold">Rekapitulasi Kinerja Eksekusi</h3>
                <p class="mb-0 opacity-75">PTA Bandung | Periode Pencarian</p>
            </div>
            <div class="col-md-8">
                <form action="{{ route('laporan.eksekusi.index') }}" method="GET" class="d-flex justify-content-md-end align-items-center gap-2">
                    <span class="small fw-bold">DARI:</span>
                    <input type="date" name="tgl_awal" id="tgl_awal" value="{{ $tglAwal }}" max="{{ $hariIni }}">
                    <span class="small fw-bold">SAMPAI:</span>
                    <input type="date" name="tgl_akhir" id="tgl_akhir" value="{{ $tglAkhirFix }}" max="{{ $hariIni }}">

                    <button type="submit" class="btn btn-light btn-sm rounded-pill px-4 fw-bold shadow-sm">
                        <i class="bi bi-search me-1"></i> CARI
                    </button>

                    <a href="{{ route('laporan.eksekusi.index') }}" class="btn btn-reset btn-sm rounded-pill px-4 fw-bold shadow-sm">
                        <i class="bi bi-arrow-clockwise me-1"></i> RESET
                    </a>
                </form>
            </div>
        </div>
    </div>

    {{-- Global Overview & KPI tetap sama --}}
    <div class="global-overview shadow-lg mb-4 animate__animated animate__fadeInUp">
        <h6 class="text-white-50 fw-bold mb-3 text-uppercase small"><i class="bi bi-globe me-2"></i> Akumulasi Seluruh Perkara (Global)</h6>
        <div class="row align-items-center text-center text-md-start">
            <div class="col-md-3 global-stat-box">
                <p class="text-white-50 mb-1 small">TOTAL DITERIMA</p>
                <h2 class="fw-bold mb-0"><a href="{{ route('laporan.eksekusi.detail', ['satker' => 'ALL', 'jenis' => 'TOTAL_DITERIMA', 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhirFix]) }}" class="text-info text-decoration-none">{{ number_format($allTime['diterima'], 0, ',', '.') }}</a></h2>
            </div>
            <div class="col-md-3 global-stat-box">
                <p class="text-white-50 mb-1 small">TOTAL SELESAI</p>
                <h2 class="fw-bold mb-0"><a href="{{ route('laporan.eksekusi.detail', ['satker' => 'ALL', 'jenis' => 'TOTAL_SELESAI', 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhirFix]) }}" class="text-success text-decoration-none">{{ number_format($allTime['selesai'], 0, ',', '.') }}</a></h2>
            </div>
            <div class="col-md-2 global-stat-box">
                <p class="text-white-50 mb-1 small">SISA</p>
                <h2 class="fw-bold mb-0"><a href="{{ route('laporan.eksekusi.detail', ['satker' => 'ALL', 'jenis' => 'TOTAL_SISA', 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhirFix]) }}" class="text-warning text-decoration-none">{{ number_format($allTime['sisa'], 0, ',', '.') }}</a></h2>
            </div>
            <div class="col-md-4 ps-md-4 mt-3 mt-md-0">
                <div class="d-flex justify-content-between mb-2 small fw-bold">
                    <span class="text-white-50">RASIO PENYELESAIAN</span>
                    <span class="text-white">{{ $allTime['persentase'] }}%</span>
                </div>
                <div class="progress" style="height: 10px; background: rgba(255,255,255,0.1); border-radius: 10px;">
                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" style="width: {{ $allTime['persentase'] }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5 animate__animated animate__fadeInUp">
        @foreach([['Sisa Lalu', $summary['SISA']], ['Diterima', $summary['DITERIMA']], ['Total Beban', $summary['BEBAN']], ['Selesai', $summary['SELESAI']], ['Sisa Kini', $summary['SISA_TAHUN_INI']]] as $c)
        <div class="col-md col-6">
            <div class="card kpi-card border-0 shadow-sm h-100 p-4 text-center">
                <h6 class="small fw-bold text-uppercase text-muted mb-2">{{ $c[0] }}</h6>
                <h3 class="mb-0 fw-bold text-dark">{{ number_format($c[1] ?? 0, 0, ',', '.') }}</h3>
            </div>
        </div>
        @endforeach
    </div>

    <div class="table-container border-0 animate__animated animate__fadeInUp">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
            <h5 class="fw-bold text-dark mb-0">Rincian Per Satuan Kerja</h5>
            <div class="d-flex gap-2">
                {{-- Tombol Export Baru --}}
                <button onclick="exportToExcel()" class="btn btn-success fw-bold px-4 rounded-pill shadow-sm">
                    <i class="bi bi-file-earmark-excel me-2"></i> Ekspor Excel
                </button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table custom-table text-center align-middle" id="rekapTable">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th class="text-start">SATUAN KERJA</th>
                        <th>SISA LALU</th>
                        <th>DITERIMA</th>
                        <th>BEBAN</th>
                        <th>SELESAI</th>
                        <th>PERSEN</th>
                        <th>SISA</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach($sortedData as $row)
                    @php $persen = $row->BEBAN > 0 ? ($row->SELESAI / $row->BEBAN) * 100 : 0; @endphp
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td class="text-start fw-bold">{{ $row->satker }}</td>
                        <td><a href="{{ route('laporan.eksekusi.detail', ['satker' => $row->satker, 'jenis' => 'SISA', 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhirFix]) }}" class="angka-link">{{ number_format($row->SISA, 0, ',', '.') }}</a></td>
                        <td><a href="{{ route('laporan.eksekusi.detail', ['satker' => $row->satker, 'jenis' => 'DITERIMA', 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhirFix]) }}" class="angka-link">{{ number_format($row->DITERIMA, 0, ',', '.') }}</a></td>
                        <td><a href="{{ route('laporan.eksekusi.detail', ['satker' => $row->satker, 'jenis' => 'BEBAN', 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhirFix]) }}" class="angka-link text-primary">{{ number_format($row->BEBAN, 0, ',', '.') }}</a></td>
                        <td><a href="{{ route('laporan.eksekusi.detail', ['satker' => $row->satker, 'jenis' => 'SELESAI', 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhirFix]) }}" class="angka-link text-success">{{ number_format($row->SELESAI, 0, ',', '.') }}</a></td>
                        <td><b>{{ number_format($persen, 1, ',', '.') }}%</b></td>
                        <td><a href="{{ route('laporan.eksekusi.detail', ['satker' => $row->satker, 'jenis' => 'SISA_TAHUN_INI', 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhirFix]) }}" class="angka-link {{ $row->SISA_TAHUN_INI > 0 ? 'badge-sisa' : '' }}">{{ number_format($row->SISA_TAHUN_INI, 0, ',', '.') }}</a></td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="tfoot-total">
                    <tr class="align-middle">
                        <td colspan="2" class="py-3 text-end">TOTAL SELURUH SATKER :</td>
                        <td>{{ number_format($summary['SISA'], 0, ',', '.') }}</td>
                        <td>{{ number_format($summary['DITERIMA'], 0, ',', '.') }}</td>
                        <td>{{ number_format($summary['BEBAN'], 0, ',', '.') }}</td>
                        <td>{{ number_format($summary['SELESAI'], 0, ',', '.') }}</td>
                        <td>{{ number_format(($summary['BEBAN']>0?($summary['SELESAI']/$summary['BEBAN'])*100:0), 1, ',', '.') }}%</td>
                        <td>{{ number_format($summary['SISA_TAHUN_INI'], 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tglAwal = document.getElementById('tgl_awal');
        const tglAkhir = document.getElementById('tgl_akhir');
        const today = new Date().toISOString().split('T')[0];
        tglAwal.max = today;
        tglAkhir.max = today;

        function updateMinMax() {
            tglAkhir.min = tglAwal.value;
            if (tglAkhir.value < tglAwal.value && tglAkhir.value !== "") {
                tglAkhir.value = tglAwal.value;
            }
        }
        updateMinMax();
        tglAwal.addEventListener('change', updateMinMax);
    });

    function exportToExcel() {
        let table = document.getElementById("rekapTable");
        let html = table.outerHTML;

        // Tambahkan style CSS agar Excel menampilkan border dan warna
        let style = `
            <style>
                table { border-collapse: collapse; width: 100%; font-family: 'Bookman Old Style', serif; }
                th, td { border: 1px solid black; padding: 8px; text-align: center; }
                th { background-color: #f2f2f2; font-weight: bold; text-transform: uppercase; }
                .tfoot-total { background-color: #1a2a6c; color: white; font-weight: bold; }
                .text-start { text-align: left; }
                h2 { text-align: center; margin-bottom: 20px; }
            </style>
        `;

        // Buat judul laporan di atas tabel
        let header = `
            <h2>REKAPITULASI KINERJA EKSEKUSI PTA BANDUNG</h2>
            <p>Periode: ${document.getElementById('tgl_awal').value} s/d ${document.getElementById('tgl_akhir').value}</p>
        `;

        let uri = 'data:application/vnd.ms-excel;base64,';
        let template = `
            <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
            <head><meta charset="UTF-8">${style}</head>
            <body>${header}${html}</body></html>
        `;

        let base64 = function(s) {
            return window.btoa(unescape(encodeURIComponent(s)))
        };

        let link = document.createElement("a");
        link.href = uri + base64(template);
        link.download = 'Rekap_Eksekusi_Bandung_' + new Date().toLocaleDateString() + '.xls';
        link.click();
    }
</script>
@endpush