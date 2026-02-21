@extends('layouts.app')

@section('title', "Detail $jenis - " . ($satker == 'ALL' ? 'Global' : "PA $satker"))

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    /* Font Bookman Old Style Seragam */
    body {
        background-color: #f4f7fa;
        font-family: "Bookman Old Style", serif;
        color: #2d3436;
    }

    .page-header {
        background: linear-gradient(135deg, #1a2a6c 0%, #2a4858 100%);
        padding: 2.2rem 2.5rem;
        border-radius: 20px;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(26, 42, 108, 0.15);
    }

    .page-header h3 {
        font-weight: bold;
    }

    .table-container {
        background: white;
        border-radius: 25px;
        padding: 2rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.03);
    }

    .custom-table thead th {
        background-color: #f8fafc;
        color: #64748b;
        font-weight: bold;
        text-transform: uppercase;
        font-size: 0.8rem;
        padding: 1.2rem;
        border-bottom: 2px solid #dee2e6;
    }

    .age-badge {
        display: inline-flex;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: bold;
        border: 1px solid rgba(0, 0, 0, 0.1);
        font-size: 0.95rem;
    }

    /* Aturan Warna Baru */
    .bg-green {
        background-color: #d1fae5;
        color: #065f46;
        border: 1px solid #10b981;
    }

    /* <= 5 Bulan */
    .bg-yellow {
        background-color: #fef3c7;
        color: #92400e;
        border: 1px solid #f59e0b;
    }

    /* 5 - 12 Bulan */
    .bg-red {
        background-color: #fee2e2;
        color: #991b1b;
        border: 1px solid #ef4444;
    }

    /* > 12 Bulan */

    @media print {

        .navbar,
        .btn,
        .search-box,
        .navbar-public,
        .footer-public {
            display: none !important;
        }

        @page {
            size: A4 portrait;
            margin: 1.5cm;
        }

        .page-header {
            background: none !important;
            color: black !important;
            border-bottom: 2px solid black !important;
            border-radius: 0 !important;
            text-align: center !important;
            padding: 0 !important;
        }

        th,
        td {
            border: 1px solid black !important;
            font-size: 9pt !important;
            color: black !important;
        }

        .print-only {
            display: block !important;
            margin-top: 30px;
            text-align: right;
        }
    }

    .print-only {
        display: none;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4 px-xl-5">
    <div class="page-header d-flex justify-content-between align-items-center animate__animated animate__fadeIn">
        <div>
            <h3 class="mb-1 text-uppercase">Rincian Perkara: {{ str_replace(['_', 'TOTAL '], ' ', $jenis) }}</h3>
            <p class="mb-0 opacity-90">
                Satker: <b>{{ $satker == 'ALL' ? 'SELURUH SATKER' : 'PA ' . $satker }}</b> |
                Periode: <b>{{ date('d/m/Y', strtotime($tglAwal)) }} s/d {{ date('d/m/Y', strtotime($tglAkhir)) }}</b>
            </p>
        </div>
        <a href="{{ route('laporan.eksekusi.index', ['tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhir]) }}" class="btn btn-light fw-bold px-4 rounded-pill shadow-sm">KEMBALI</a>
    </div>

    <div class="table-container border-0 shadow-sm animate__animated animate__fadeInUp">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
            <h5 class="fw-bold mb-0">Daftar Rincian Perkara</h5>
            <div class="d-flex gap-2 search-box">
                <input type="text" id="tableSearch" class="form-control form-control-sm shadow-sm" placeholder="Cari data..." style="width: 250px;">
                <button onclick="exportToExcel('detailTable', 'Detail_{{ $satker }}_{{ $tglAwal }}')" class="btn btn-success btn-sm fw-bold px-3"><i class="bi bi-file-earmark-excel"></i> Excel</button>
                <button onclick="window.print()" class="btn btn-outline-dark btn-sm fw-bold px-3"><i class="bi bi-printer"></i> Cetak</button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table custom-table align-middle text-center mb-0" id="detailTable">
                <thead>
                    <tr>
                        <th>NO</th>
                        @if($satker == 'ALL') <th>SATKER</th> @endif
                        <th class="text-start">NOMOR REGISTER EKSEKUSI</th>
                        <th class="text-start">NOMOR PERKARA PA</th>
                        <th>TGL MOHON</th>
                        <th>TGL SELESAI</th>
                        <th width="22%">LAMA PROSES</th>
                        <th>JENIS EKSEKUSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $row)
                    @php
                    $isValid = !empty($row->tanggal_permohonan) && substr($row->tanggal_permohonan, 0, 4) != '0000';
                    $usiaText = '-'; $colorClass = '';

                    if ($isValid) {
                    $start = \Carbon\Carbon::parse($row->tanggal_permohonan);
                    $end = (!empty($row->tanggal_selesai) && substr($row->tanggal_selesai, 0, 4) != '0000')
                    ? \Carbon\Carbon::parse($row->tanggal_selesai)
                    : \Carbon\Carbon::now();

                    $days = $start->diffInDays($end);
                    $diff = $start->diff($end);

                    // LOGIKA ATURAN BARU
                    if ($days <= 155) {
                        $colorClass='bg-green' ; // Normal (<=5 Bulan)
                        } elseif ($days <=365) {
                        $colorClass='bg-yellow' ; // Terlambat (5 - 12 Bulan)
                        } else {
                        $colorClass='bg-red' ; // Sangat Terlambat (> 12 Bulan)
                        }

                        $p = [];
                        if ($diff->y > 0) $p[] = "{$diff->y} Tahun";
                        if ($diff->m > 0) $p[] = "{$diff->m} Bulan";
                        if ($diff->d > 0) $p[] = "{$diff->d} Hari";
                        $usiaText = count($p) > 0 ? implode(', ', $p) : '0 Hr';
                        }
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            @if($satker == 'ALL') <td class="fw-bold">{{ $row->satker_nama }}</td> @endif
                            <td class="text-start fw-bold text-primary">{{ $row->nomor_eksekusi ?? '-' }}</td>
                            <td class="text-start fw-bold">{{ $row->nomor_perkara_asal ?? '-' }}</td>
                            <td>{{ $isValid ? date('d/m/Y', strtotime($row->tanggal_permohonan)) : '-' }}</td>
                            <td>
                                @if(!empty($row->tanggal_selesai) && substr($row->tanggal_selesai, 0, 4) != '0000')
                                <span class="badge bg-success">{{ date('d/m/Y', strtotime($row->tanggal_selesai)) }}</span>
                                @else
                                <span class="badge bg-warning text-dark">PROSES</span>
                                @endif
                            </td>
                            <td>
                                @if($usiaText != '-')
                                <div class="age-badge {{ $colorClass }}" title="{{ $days }} Hari">
                                    {{ $usiaText }}
                                </div>
                                @else
                                -
                                @endif
                            </td>
                            <td><span class="badge bg-light text-dark border px-2">{{ $row->jenis_eksekusi ?? 'EKSEKUSI' }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ $satker == 'ALL' ? '8' : '7' }}" class="py-5 fw-bold text-muted">Data rincian tidak ditemukan.</td>
                        </tr>
                        @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="print-only">
        <p>Bandung, {{ date('d F Y') }}</p><br><br><br>
        <p><b>__________________________</b><br>Panitera Muda Hukum</p>
    </div>
</div>

<script>
    // Search Function
    document.getElementById('tableSearch').addEventListener('keyup', function() {
        let val = this.value.toLowerCase();
        let rows = document.querySelectorAll('#detailTable tbody tr');
        rows.forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(val) ? '' : 'none';
        });
    });

    // Excel Function
    function exportToExcel(tableID, filename = '') {
        let tableSelect = document.getElementById(tableID);
        let tableHTML = tableSelect.outerHTML.replace(/<a[^>]*>|<\/a>|<i[^>]*>|<\/i>|<span[^>]*>|<\/span>/g, "");
        let template = `<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><meta charset="UTF-8"><style>table { border-collapse: collapse; } th, td { border: 1px solid #000; padding: 5px; text-align: center; font-family: serif; } th { background-color: #f2f2f2; }</style></head><body>${tableHTML}</body></html>`;
        let downloadLink = document.createElement("a");
        downloadLink.href = 'data:application/vnd.ms-excel,' + encodeURIComponent(template);
        downloadLink.download = filename + '.xls';
        downloadLink.click();
    }
</script>
@endsection