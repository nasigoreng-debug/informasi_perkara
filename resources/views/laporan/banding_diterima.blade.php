@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<style>
    body { 
        font-family: 'Plus Jakarta Sans', sans-serif; 
        background-color: #f4f7fa; 
    }
    
    .page-heading { padding: 2rem 0; }
    
    /* Card Mewah sesuai Putusan Sela */
    .card-luxury {
        background: #ffffff;
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.02);
        overflow: hidden;
    }

    /* Header Tabel Presisi */
    .table-luxury thead th {
        background: #f8fafc;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        font-weight: 800;
        color: #64748b;
        padding: 1.2rem 1.5rem;
        border-bottom: 2px solid #f1f5f9;
    }

    .table-luxury tbody td {
        padding: 1.2rem 1.5rem;
        vertical-align: middle;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.875rem;
    }

    /* Tombol Kembali Melingkar DNA Putusan Sela */
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
        text-decoration: none;
    }

    .btn-back:hover {
        background: #4f46e5;
        color: white;
        transform: translateX(-5px);
    }

    /* Badge DNA: Soft & Rounded */
    .badge-soft-total { background: #eef2ff; color: #4f46e5; border: 1px solid #e0e7ff; font-weight: 700; padding: 0.6rem 1.2rem; border-radius: 12px; text-decoration: none; display: inline-block; transition: 0.2s; }
    .badge-soft-ecourt { background: #f0fdf4; color: #16a34a; border: 1px solid #dcfce7; font-weight: 700; padding: 0.6rem 1.2rem; border-radius: 12px; text-decoration: none; display: inline-block; transition: 0.2s; }
    .badge-soft-manual { background: #fff1f2; color: #e11d48; border: 1px solid #ffe4e6; font-weight: 700; padding: 0.6rem 1.2rem; border-radius: 12px; text-decoration: none; display: inline-block; transition: 0.2s; }

    .badge-soft-total:hover { background: #4f46e5; color: white !important; transform: translateY(-2px); }
    .badge-soft-ecourt:hover { background: #16a34a; color: white !important; transform: translateY(-2px); }
    .badge-soft-manual:hover { background: #e11d48; color: white !important; transform: translateY(-2px); }

    .tfoot-luxury { background: #1e293b; color: white; font-weight: 800; }
    .tfoot-luxury td { padding: 1.5rem !important; }

    /* Search DataTables Styling */
    .dataTables_filter input {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding: 0.5rem 1rem;
        background: #fbfcfe;
    }
</style>

<div class="container px-4">
    <div class="page-heading d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <a href="{{ url('laporan-utama') }}" class="btn-back me-3 shadow-sm" title="Kembali ke Panel Laporan">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="h3 fw-800 mb-1" style="color: #1e293b;">Laporan RK1</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                        <li class="breadcrumb-item"><a href="{{ url('laporan-utama') }}" class="text-decoration-none text-muted">Panel Laporan</a></li>
                        <li class="breadcrumb-item active fw-bold" style="color: #4f46e5;">Data Perkara Diterima</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-white border shadow-sm fw-bold px-4 py-2 rounded-pill">
                <i class="fas fa-print me-2 text-muted"></i> Cetak
            </button>
            <a href="{{ route('laporan.banding.diterima.export', ['tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]) }}" class="btn btn-success shadow-sm fw-bold px-4 py-2 rounded-pill">
                <i class="fas fa-file-excel me-2"></i> Export Excel
            </a>
        </div>
    </div>

    <div class="card card-luxury mb-4 border-0">
        <div class="card-body p-4">
            <form action="{{ url()->current() }}" method="GET" class="row g-3 align-items-end">
                <div class="col-lg-3">
                    <label class="small fw-bold text-muted text-uppercase mb-2 d-block">Tanggal Awal</label>
                    <input type="date" name="tgl_awal" class="form-control border-light py-2" style="border-radius: 10px;" value="{{ $tgl_awal }}">
                </div>
                <div class="col-lg-3">
                    <label class="small fw-bold text-muted text-uppercase mb-2 d-block">Tanggal Akhir</label>
                    <input type="date" name="tgl_akhir" class="form-control border-light py-2" style="border-radius: 10px;" value="{{ $tgl_akhir }}">
                </div>
                <div class="col-lg-4">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary fw-800 flex-grow-1 py-2 shadow-sm rounded-pill" style="background: #4f46e5; border: none; height: 45px;">
                            <i class="fas fa-filter me-2"></i> TAMPILKAN
                        </button>
                        <a href="{{ url()->current() }}" class="btn btn-light border d-flex align-items-center justify-content-center shadow-sm rounded-circle" style="width: 45px; height: 45px;">
                            <i class="fas fa-undo-alt text-muted"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-2 text-end d-none d-lg-block">
                    <div class="small fw-bold text-muted text-uppercase">Periode Aktif</div>
                    <div class="fw-800 text-primary small">{{ date('d/m/y', strtotime($tgl_awal)) }} - {{ date('d/m/y', strtotime($tgl_akhir)) }}</div>
                </div>
            </form>
        </div>
    </div>

    <div class="card card-luxury border-0">
        <div class="table-responsive p-0">
            <table id="tableRekap" class="table table-luxury align-middle mb-0">
                <thead>
                    <tr>
                        <th class="text-center" width="60">No</th>
                        <th>Satuan Kerja</th>
                        <th class="text-center">Total Perkara</th>
                        <th class="text-center">E-Court</th>
                        <th class="text-center">Manual</th>
                    </tr>
                </thead>
                <tbody>
                    @php $gTotal=0; $gEcourt=0; $gManual=0; @endphp
                    @foreach($results as $row)
                    @php
                        $gTotal += $row->total_perkara;
                        $gEcourt += $row->jumlah_ecourt;
                        $gManual += $row->jumlah_manual;
                    @endphp
                    <tr>
                        <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                        <td>
                            <div class="fw-800 text-dark">{{ $row->satker }}</div>
                        </td>
                        <td class="text-center">
                            @if($row->total_perkara > 0)
                                <a href="{{ route('laporan.banding.detail', ['satker' => $row->satker_key, 'jenis' => 'total', 'tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]) }}" class="badge-soft-total">
                                    {{ number_format($row->total_perkara) }}
                                </a>
                            @else
                                <span class="text-muted fw-bold opacity-25">0</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($row->jumlah_ecourt > 0)
                                <a href="{{ route('laporan.banding.detail', ['satker' => $row->satker_key, 'jenis' => 'ecourt', 'tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]) }}" class="badge-soft-ecourt">
                                    {{ number_format($row->jumlah_ecourt) }}
                                </a>
                            @else
                                <span class="text-muted fw-bold opacity-25">0</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($row->jumlah_manual > 0)
                                <a href="{{ route('laporan.banding.detail', ['satker' => $row->satker_key, 'jenis' => 'manual', 'tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]) }}" class="badge-soft-manual">
                                    {{ number_format($row->jumlah_manual) }}
                                </a>
                            @else
                                <span class="text-muted fw-bold opacity-25">0</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="tfoot-luxury">
                    <tr>
                        <td colspan="2" class="text-center fw-800">TOTAL SELURUH SATKER</td>
                        <td class="text-center">{{ number_format($gTotal) }}</td>
                        <td class="text-center">{{ number_format($gEcourt) }}</td>
                        <td class="text-center">{{ number_format($gManual) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#tableRekap').DataTable({
            "pageLength": 30,
            "ordering": false,
            "dom": '<"p-4 d-flex justify-content-between align-items-center"f>rtip',
            "language": {
                "search": "",
                "searchPlaceholder": "Cari Satuan Kerja..."
            }
        });
    });
</script>
@endsection