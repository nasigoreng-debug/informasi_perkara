@extends('layouts.app')

@section('title', "Detail $jenis - " . ($satker == 'ALL' ? 'Global' : "PA $satker"))

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    :root {
        --dark-navy: #1e293b;
        --border-color: #e2e8f0;
        --bg-body: #f8fafc;
        --accent-blue: #4f46e5;
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: var(--bg-body);
        color: #334155;
        letter-spacing: -0.01em;
    }

    /* Header Styling */
    .btn-back {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: white;
        border: 1px solid var(--border-color);
        color: var(--dark-navy);
        text-decoration: none !important;
        transition: all 0.2s ease;
    }

    .btn-back:hover {
        background: var(--dark-navy);
        color: white;
        transform: translateX(-3px);
    }

    /* Info Strip & Search */
    .info-strip {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 2rem;
    }

    .search-field {
        border-radius: 10px;
        border: 1px solid var(--border-color);
        padding: 8px 16px;
        font-size: 0.9rem;
        outline: none;
        transition: border-color 0.2s;
        background: #fbfcfe;
    }

    .search-field:focus {
        border-color: var(--accent-blue);
    }

    /* Table DNA - Classic & Clean Grid */
    .table-container {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .table-luxury {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 0;
    }

    .table-luxury thead th {
        background: #f8fafc;
        border: 1px solid var(--border-color);
        padding: 16px 12px;
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        color: #64748b;
        letter-spacing: 0.05em;
    }

    .table-luxury tbody td {
        border: 1px solid var(--border-color);
        padding: 14px 12px;
        font-size: 0.85rem;
        vertical-align: middle;
    }

    .table-luxury tbody tr:hover td {
        background-color: #fcfcfd;
    }

    /* Badge & Text Styling */
    .age-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.75rem;
        white-space: nowrap;
    }

    .bg-soft-green {
        background: #f0fdf4;
        color: #16a34a;
        border: 1px solid #dcfce7;
    }

    .bg-soft-yellow {
        background: #fffbeb;
        color: #d97706;
        border: 1px solid #fef3c7;
    }

    .bg-soft-red {
        background: #fff1f2;
        color: #e11d48;
        border: 1px solid #ffe4e6;
    }

    .status-pill {
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 800;
        font-size: 0.65rem;
        text-transform: uppercase;
        border: 1px solid transparent;
    }

    .no-perkara {
        color: var(--accent-blue);
        font-weight: 700;
        font-size: 0.85rem;
    }

    .no-asal {
        font-size: 0.8rem;
        color: #64748b;
    }

    @media print {

        .btn-back,
        .btn,
        .search-box,
        .no-print,
        .breadcrumb {
            display: none !important;
        }

        .table-container {
            border: none;
            box-shadow: none;
        }

        body {
            background: white;
            padding: 0;
        }
    }
</style>

<div class="container py-5 px-xl-5">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div class="d-flex align-items-center">
            <a href="{{ route('laporan.eksekusi.index', ['tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhir]) }}" class="btn-back me-3">
                <i class="bi bi-chevron-left"></i>
            </a>
            <div>
                <h2 class="fw-800 mb-1" style="color: var(--dark-navy); letter-spacing: -0.02em;">Rincian {{ str_replace(['_', 'TOTAL '], ' ', $jenis) }}</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small text-uppercase fw-bold">
                        <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Eksekusi</a></li>
                        <li class="breadcrumb-item active text-primary">{{ $satker == 'ALL' ? 'Seluruh Satuan Kerja' : 'PA ' . $satker }}</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="text-end no-print">
            <div class="small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.65rem; letter-spacing: 0.05em;">Jumlah Data</div>
            <div class="h4 fw-800 text-dark mb-0">{{ count($data) }} <span class="text-muted fw-500" style="font-size: 1rem;">Perkara</span></div>
        </div>
    </div>

    <div class="info-strip shadow-sm d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div class="d-flex align-items-center gap-4">
            <div class="small fw-bold text-muted">
                <span class="opacity-50 text-uppercase me-2">Periode:</span>
                <span class="text-dark">{{ date('d M Y', strtotime($tglAwal)) }} — {{ date('d M Y', strtotime($tglAkhir)) }}</span>
            </div>
        </div>
        <div class="d-flex gap-2 no-print">
            <input type="text" id="tableSearch" class="search-field" placeholder="Cari nomor perkara..." style="width: 240px;">
            <button onclick="exportToExcel('detailTable', 'Detail_Eksekusi')" class="btn btn-success fw-bold px-4 rounded-3 btn-sm shadow-sm">
                <i class="bi bi-file-earmark-excel me-2"></i> EXCEL
            </button>
            <button onclick="window.open(window.location.href, '_blank').print()" class="btn btn-dark fw-bold px-4 rounded-3 btn-sm shadow-sm">
                <i class="bi bi-printer me-2"></i> CETAK
            </button>
        </div>
    </div>

    <div class="table-container shadow-sm border-0">
        <div class="table-responsive">
            <table class="table-luxury align-middle text-center" id="detailTable">
                <thead>
                    <tr>
                        <th width="60">NO</th>
                        @if($satker == 'ALL') <th width="150">SATKER</th> @endif
                        <th class="text-start">NOMOR REGISTER EKSEKUSI</th>
                        <th class="text-start">NOMOR PERKARA ASAL</th>
                        <th>JENIS</th>
                        <th>TGL PERMOHONAN</th>
                        <th>STATUS</th>
                        <th>LAMA PROSES</th>
                        <th class="text-start">KETERANGAN</th>
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

                    if ($days <= 180) $colorClass='bg-soft-green' ;
                        elseif ($days <=365) $colorClass='bg-soft-yellow' ;
                        else $colorClass='bg-soft-red' ;

                        $p=[];
                        if ($diff->y > 0) $p[] = "{$diff->y} Tahun,";
                        if ($diff->m > 0) $p[] = "{$diff->m} Bulan,";
                        $p[] = "{$diff->d} Hari";
                        $usiaText = implode(' ', $p);
                        }
                        @endphp
                        <tr>
                            <td class="text-muted fw-bold small">{{ $index + 1 }}</td>
                            @if($satker == 'ALL')
                            <td class="fw-bold text-uppercase" style="font-size: 0.7rem; color: #64748b;">{{ $row->satker_nama }}</td>
                            @endif
                            <td class="text-start"><span class="no-perkara">{{ $row->nomor_eksekusi ?? '-' }}</span></td>
                            <td class="text-start"><span class="no-asal fw-medium">{{ $row->nomor_perkara_asal ?? '-' }}</span></td>
                            <td><span class="badge bg-light text-dark border-0 fw-bold py-2 px-3" style="font-size: 0.65rem;">{{ $row->jenis_eksekusi }}</span></td>
                            <td class="fw-600">{{ $isValid ? date('d/m/Y', strtotime($row->tanggal_permohonan)) : '-' }}</td>
                            <td>
                                @if(!empty($row->tanggal_selesai) && substr($row->tanggal_selesai, 0, 4) != '0000')
                                <span class="status-pill bg-success text-white">SELESAI</span>
                                @else
                                <span class="status-pill bg-warning text-dark">PROSES</span>
                                @endif
                            </td>
                            <td>
                                @if($usiaText != '-')
                                <div class="age-badge {{ $colorClass }}">{{ $usiaText }}</div>
                                @else
                                <span class="text-muted opacity-50">—</span>
                                @endif
                            </td>
                            <td class="text-start">
                                <div class="text-muted small" style="line-height: 1.4; max-width: 200px;">
                                    {{ $row->keterangan ?: '-' }}
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="py-5 text-center">
                                <i class="bi bi-inbox h1 d-block opacity-10"></i>
                                <span class="text-muted fw-bold">Tidak ada data rincian ditemukan.</span>
                            </td>
                        </tr>
                        @endforelse
                </tbody>
            </table>
        </div>
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

    // Excel Export
    function exportToExcel(tableID, filename = '') {
        let tableSelect = document.getElementById(tableID);
        let tableHTML = tableSelect.outerHTML.replace(/<a[^>]*>|<\/a>|<i[^>]*>|<\/i>|<span[^>]*>|<\/span>/g, "");
        let template = `
            <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
            <head><meta charset="UTF-8"><style>
                table { border-collapse: collapse; } 
                th, td { border: 1px solid #000; padding: 8px; text-align: center; font-family: sans-serif; font-size: 10pt; } 
                th { background-color: #f2f2f2; font-weight: bold; }
                .text-start { text-align: left; }
            </style></head>
            <body>
                <h3 style="text-align:center">LAPORAN DETAIL EKSEKUSI ({{ $jenis }})</h3>
                <h4 style="text-align:center">{{ $satker == 'ALL' ? 'PTA BANDUNG' : 'PA ' . $satker }}</h4>
                ${tableHTML}
            </body></html>`;
        let downloadLink = document.createElement("a");
        downloadLink.href = 'data:application/vnd.ms-excel,' + encodeURIComponent(template);
        downloadLink.download = filename + '_' + new Date().getTime() + '.xls';
        downloadLink.click();
    }
</script>
@endsection