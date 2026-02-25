@extends('layouts.app')

@section('title', "Detail $jenis - " . ($satker == 'ALL' ? 'Global' : "PA $satker"))

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    body {
        background-color: #f4f7fa;
        /* Mengganti font ke Sans-Serif Standar Modern */
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        color: #2d3436;
        line-height: 1.6;
    }

    .page-header {
        background: linear-gradient(135deg, #1a2a6c 0%, #2a4858 100%);
        padding: 2.2rem 2.5rem;
        border-radius: 20px;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(26, 42, 108, 0.15);
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
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        padding: 1.2rem;
        border-bottom: 2px solid #edf2f7;
    }

    .age-badge {
        display: inline-flex;
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.85rem;
    }

    .bg-green {
        background-color: #d1fae5;
        color: #065f46;
        border: 1px solid #10b981;
    }

    .bg-yellow {
        background-color: #fef3c7;
        color: #92400e;
        border: 1px solid #f59e0b;
    }

    .bg-red {
        background-color: #fee2e2;
        color: #991b1b;
        border: 1px solid #ef4444;
    }

    /* Form control styling agar lebih senada */
    #tableSearch {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding: 0.5rem 1rem;
    }

    .print-only {
        display: none;
    }

    @media print {
        .navbar,
        .btn,
        .search-box,
        .back-btn {
            display: none !important;
        }

        .page-header {
            background: none !important;
            color: black !important;
            border-bottom: 2px solid black !important;
            border-radius: 0 !important;
            padding: 1rem 0 !important;
        }

        th,
        td {
            border: 1px solid #ccc !important;
            font-size: 8pt !important;
        }

        .print-only {
            display: block !important;
            margin-top: 30px;
            text-align: right;
            font-size: 10pt;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4 px-xl-5">
    <div class="page-header d-flex justify-content-between align-items-center animate__animated animate__fadeIn">
        <div>
            <h3 class="mb-1 text-uppercase fw-bold" style="letter-spacing: -0.5px;">Rincian Perkara: {{ str_replace(['_', 'TOTAL '], ' ', $jenis) }}</h3>
            <p class="mb-0 opacity-90 small">
                Satker: <b>{{ $satker == 'ALL' ? 'SELURUH SATKER' : 'PA ' . $satker }}</b> |
                Periode: <b>{{ date('d/m/Y', strtotime($tglAwal)) }} s/d {{ date('d/m/Y', strtotime($tglAkhir)) }}</b>
            </p>
        </div>
        <div class="back-btn">
            <a href="{{ route('laporan.eksekusi.index', ['tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhir]) }}" class="btn btn-light fw-bold px-4 rounded-pill shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> KEMBALI
            </a>
        </div>
    </div>

    <div class="table-container border-0 shadow-sm animate__animated animate__fadeInUp">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
            <h5 class="fw-bold mb-0 text-dark">Daftar Rincian Perkara</h5>
            <div class="d-flex gap-2 search-box">
                <input type="text" id="tableSearch" class="form-control form-control-sm shadow-sm" placeholder="Cari data..." style="width: 250px;">
                <button onclick="exportToExcel('detailTable', 'Detail_{{ $satker }}')" class="btn btn-success btn-sm fw-bold px-3 rounded-pill">
                    <i class="bi bi-file-earmark-excel me-1"></i> Excel
                </button>
                <button onclick="window.print()" class="btn btn-outline-dark btn-sm fw-bold px-3 rounded-pill">
                    <i class="bi bi-printer me-1"></i> Cetak
                </button>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table custom-table align-middle text-center mb-0" id="detailTable">
                <thead>
                    <tr>
                        <th>NO</th>
                        @if($satker == 'ALL') <th>SATKER</th> @endif
                        <th class="text-start">NO. REGISTER EKSEKUSI</th>
                        <th class="text-start">NO. PERKARA PA</th>
                        <th>JENIS</th>
                        <th>TGL PERMOHONAN</th>
                        <th>TGL SELESAI</th>
                        <th>LAMA PROSES</th>
                        <th class="text-start" width="20%">KETERANGAN</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $row)
                        @php
                            $isValid = !empty($row->tanggal_permohonan) && substr($row->tanggal_permohonan, 0, 4) != '0000';
                            $usiaText = '-'; $colorClass = '';
                            if ($isValid) {
                                $start = \Carbon\Carbon::parse($row->tanggal_permohonan);
                                $end = (!empty($row->tanggal_selesai) && substr($row->tanggal_selesai, 0, 4) != '0000') ? \Carbon\Carbon::parse($row->tanggal_selesai) : \Carbon\Carbon::now();
                                $days = $start->diffInDays($end); 
                                $diff = $start->diff($end);

                                if ($days <= 155) $colorClass='bg-green';
                                elseif ($days <= 365) $colorClass='bg-yellow';
                                else $colorClass='bg-red';

                                $p=[];
                                if ($diff->y > 0) $p[] = "{$diff->y} Thn";
                                if ($diff->m > 0) $p[] = "{$diff->m} Bln";
                                if ($diff->d > 0) $p[] = "{$diff->d} Hr";
                                $usiaText = count($p) > 0 ? implode(', ', $p) : '0 Hr';
                            }
                        @endphp
                        <tr>
                            <td class="small text-muted">{{ $index + 1 }}</td>
                            @if($satker == 'ALL') <td class="fw-bold">{{ $row->satker_nama }}</td> @endif
                            <td class="text-start fw-bold text-primary">{{ $row->nomor_eksekusi ?? '-' }}</td>
                            <td class="text-start small">{{ $row->nomor_perkara_asal ?? '-' }}</td>
                            <td><span class="badge bg-light text-dark border px-2 fw-normal">{{ $row->jenis_eksekusi }}</span></td>
                            <td class="small">{{ $isValid ? date('d/m/Y', strtotime($row->tanggal_permohonan)) : '-' }}</td>
                            <td>
                                @if(!empty($row->tanggal_selesai) && substr($row->tanggal_selesai, 0, 4) != '0000')
                                    <span class="badge bg-success" style="font-weight: 500;">{{ date('d/m/Y', strtotime($row->tanggal_selesai)) }}</span>
                                @else
                                    <span class="badge bg-warning text-dark" style="font-weight: 500;">PROSES</span>
                                @endif
                            </td>
                            <td>
                                @if($usiaText != '-') 
                                    <div class="age-badge {{ $colorClass }}">{{ $usiaText }}</div> 
                                @else 
                                    - 
                                @endif
                            </td>
                            <td class="text-start"><div style="font-size: 0.8rem; max-height: 50px; overflow-y: auto;">{{ $row->keterangan ?? '-' }}</div></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-5 text-muted">Data rincian tidak ditemukan.</td>
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
    // Search Functionality
    document.getElementById('tableSearch').addEventListener('keyup', function() {
        let val = this.value.toLowerCase();
        let rows = document.querySelectorAll('#detailTable tbody tr');
        rows.forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(val) ? '' : 'none';
        });
    });

    // Excel Export
    function exportToExcel(tableID, filename = '') {
        let tableSelect = document.getElementById(tableID);
        // Menghapus icon dan elemen HTML tertentu saat export agar bersih
        let tableHTML = tableSelect.outerHTML.replace(/<a[^>]*>|<\/a>|<i[^>]*>|<\/i>|<span[^>]*>|<\/span>/g, "");
        let template = `
            <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
            <head>
                <meta charset="UTF-8">
                <style>
                    table { border-collapse: collapse; } 
                    th, td { border: 1px solid #000; padding: 5px; text-align: center; font-family: sans-serif; } 
                    th { background-color: #f2f2f2; font-weight: bold; }
                    .text-start { text-align: left; }
                </style>
            </head>
            <body>
                <h2 style="text-align:center">RINCIAN PERKARA EKSEKUSI</h2>
                ${tableHTML}
            </body>
            </html>`;
            
        let downloadLink = document.createElement("a");
        downloadLink.href = 'data:application/vnd.ms-excel,' + encodeURIComponent(template);
        downloadLink.download = filename + '.xls';
        downloadLink.click();
    }
</script>
@endsection