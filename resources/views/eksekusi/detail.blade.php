@extends('layouts.app')

@section('title', "Detail $jenis - " . ($satker == 'ALL' ? 'Global' : "PA $satker"))

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    body { 
        font-family: 'Plus Jakarta Sans', sans-serif; 
        background-color: #f4f7fa; 
    }
    
    .page-heading { padding: 2rem 0; }
    
    /* Card Luxury DNA */
    .card-luxury {
        background: #ffffff;
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.02);
        overflow: hidden;
    }

    /* Tabel Detail Presisi */
    .table-luxury thead th {
        background: #f8fafc;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        font-weight: 800;
        color: #64748b;
        padding: 1.2rem;
        border-bottom: 2px solid #f1f5f9;
    }

    .table-luxury tbody td {
        padding: 1.2rem;
        vertical-align: middle;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.85rem;
    }

    /* Tombol Kembali Melingkar DNA */
    .btn-back {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        border: 1px solid #e2e8f0;
        color: #4f46e5;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        transition: all 0.3s;
        text-decoration: none !important;
    }

    .btn-back:hover {
        background: #4f46e5;
        color: white;
        transform: translateX(-5px);
    }

    /* DNA Age Badge Luxury */
    .age-badge {
        display: inline-flex;
        padding: 6px 14px;
        border-radius: 10px;
        font-weight: 800;
        font-size: 0.75rem;
        text-transform: uppercase;
    }

    .bg-soft-green { background-color: #f0fdf4; color: #16a34a; border: 1px solid #dcfce7; }
    .bg-soft-yellow { background-color: #fffbeb; color: #d97706; border: 1px solid #fef3c7; }
    .bg-soft-red { background-color: #fff1f2; color: #e11d48; border: 1px solid #ffe4e6; }

    /* Badge Status DNA */
    .badge-status {
        padding: 5px 12px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.7rem;
    }

    /* Custom Search Field */
    .search-field {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 0.5rem 1rem;
        background: #fbfcfe;
        font-weight: 500;
    }

    @media print {
        .btn-back, .btn, .search-box, .breadcrumb { display: none !important; }
        .card-luxury { box-shadow: none; border: 1px solid #eee; }
        body { background: white; }
    }
</style>

<div class="container px-4">
    <div class="page-heading d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <a href="{{ route('laporan.eksekusi.index', ['tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhir]) }}" class="btn-back me-3 shadow-sm" title="Kembali ke Monitoring">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h1 class="h3 fw-800 mb-1" style="color: #1e293b;">Rincian {{ str_replace(['_', 'TOTAL '], ' ', $jenis) }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                        <li class="breadcrumb-item"><a href="{{ url('laporan-utama') }}" class="text-decoration-none text-muted">Panel</a></li>
                        <li class="breadcrumb-item text-muted text-uppercase fw-bold">{{ $satker == 'ALL' ? 'SELURUH SATKER' : 'PA ' . $satker }}</li>
                        <li class="breadcrumb-item active fw-bold text-primary">EKSEKUSI</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <div class="text-end me-3 d-none d-md-block">
                <div class="small fw-bold text-muted text-uppercase mb-0">Jumlah Data</div>
                <div class="h5 fw-800 text-primary mb-0">{{ count($data) }} Perkara</div>
            </div>
            <button onclick="window.print()" class="btn btn-white border shadow-sm fw-bold px-4 py-2 rounded-pill bg-white">
                <i class="bi bi-printer me-2"></i> Cetak
            </button>
        </div>
    </div>

    <div class="card card-luxury border-0">
        <div class="p-4 d-flex justify-content-between align-items-center border-bottom bg-white search-box">
            <h5 class="fw-800 mb-0">Daftar Rincian Perkara</h5>
            <div class="d-flex gap-2">
                <input type="text" id="tableSearch" class="form-control search-field" placeholder="Cari nomor perkara..." style="width: 250px;">
                <button onclick="exportToExcel('detailTable', 'Detail_Eksekusi_{{ $satker }}')" class="btn btn-success fw-bold px-4 rounded-pill shadow-sm">
                    <i class="bi bi-file-earmark-excel me-2"></i> Excel
                </button>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-luxury align-middle text-center mb-0" id="detailTable">
                <thead>
                    <tr>
                        <th width="50">NO</th>
                        @if($satker == 'ALL') <th>SATKER</th> @endif
                        <th class="text-start">NO. REGISTER EKSEKUSI</th>
                        <th class="text-start">NO. PERKARA ASAL</th>
                        <th>JENIS</th>
                        <th>TGL PERMOHONAN</th>
                        <th>STATUS / SELESAI</th>
                        <th>LAMA PROSES</th>
                        <th class="text-start" width="15%">KETERANGAN</th>
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

                            if ($days <= 180) $colorClass='bg-soft-green' ;
                            elseif ($days <= 365) $colorClass='bg-soft-yellow' ;
                            else $colorClass='bg-soft-red' ;

                            $p=[];
                            if ($diff->y > 0) $p[] = "{$diff->y} Thn";
                            if ($diff->m > 0) $p[] = "{$diff->m} Bln";
                            if ($diff->d > 0) $p[] = "{$diff->d} Hr";
                            $usiaText = count($p) > 0 ? implode(', ', $p) : '0 Hari';
                        }
                    @endphp
                    <tr>
                        <td class="small fw-bold text-muted">{{ $index + 1 }}</td>
                        @if($satker == 'ALL') <td class="fw-800 text-uppercase" style="font-size: 0.75rem;">{{ $row->satker_nama }}</td> @endif
                        <td class="text-start fw-800" style="color: #4f46e5;">{{ $row->nomor_eksekusi ?? '-' }}</td>
                        <td class="text-start fw-bold text-dark small">{{ $row->nomor_perkara_asal ?? '-' }}</td>
                        <td><span class="badge bg-light text-dark border fw-bold text-uppercase" style="font-size: 0.65rem;">{{ $row->jenis_eksekusi }}</span></td>
                        <td class="fw-medium">{{ $isValid ? date('d/m/Y', strtotime($row->tanggal_permohonan)) : '-' }}</td>
                        <td>
                            @if(!empty($row->tanggal_selesai) && substr($row->tanggal_selesai, 0, 4) != '0000')
                                <span class="badge bg-success text-white fw-bold" style="padding: 6px 12px; border-radius: 8px;">
                                    <i class="bi bi-check2-circle me-1"></i> {{ date('d/m/Y', strtotime($row->tanggal_selesai)) }}
                                </span>
                            @else
                                <span class="badge bg-warning text-dark fw-bold" style="padding: 6px 12px; border-radius: 8px;">
                                    <i class="bi bi-clock me-1"></i> PROSES
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($usiaText != '-')
                                <div class="age-badge {{ $colorClass }}">{{ $usiaText }}</div>
                            @else
                                <span class="text-muted opacity-50">-</span>
                            @endif
                        </td>
                        <td class="text-start">
                            <div class="text-muted" style="font-size: 0.75rem; max-height: 50px; overflow-y: auto; line-height: 1.4;">
                                {{ $row->keterangan ?? '-' }}
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="py-5">
                            <div class="opacity-25">
                                <i class="bi bi-folder2-open h1"></i>
                                <h6 class="fw-bold mt-2">Data rincian tidak ditemukan.</h6>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
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
                <h2 style="text-align:center">RINCIAN PERKARA EKSEKUSI <br> {{ $satker == 'ALL' ? 'SELURUH SATKER' : 'PA ' . $satker }} <br> Periode {{ date('d-m-Y', strtotime($tglAwal)) }} s.d {{ date('d-m-Y', strtotime($tglAkhir)) }}</h2>
                ${tableHTML}
            </body>
            </html>`;

        let downloadLink = document.createElement("a");
        downloadLink.href = 'data:application/vnd.ms-excel,' + encodeURIComponent(template);
        downloadLink.download = filename + '_' + new Date().getTime() + '.xls';
        downloadLink.click();
    }
</script>
@endsection