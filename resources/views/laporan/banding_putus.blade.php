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
    
    /* Card Mewah DNA Putusan Sela */
    .card-luxury {
        background: #ffffff;
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.02);
        overflow: hidden;
    }

    /* Tabel Presisi */
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
        text-decoration: none;
    }

    .btn-back:hover {
        background: #4f46e5;
        color: white;
        transform: translateX(-5px);
    }

    /* Soft Badge DNA - Mewarnai Angka sesuai Kategori */
    .link-num {
        font-weight: 800;
        padding: 0.6rem 1.2rem;
        border-radius: 12px;
        display: inline-block;
        transition: all 0.2s;
        text-decoration: none !important;
        min-width: 45px;
    }

    .link-sisa-lalu { background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; }
    .link-terima { background: #eef2ff; color: #4f46e5; border: 1px solid #e0e7ff; }
    .link-beban { background: #fffbeb; color: #d97706; border: 1px solid #fef3c7; }
    .link-putus { background: #f0fdf4; color: #16a34a; border: 1px solid #dcfce7; }
    .link-sisa-akhir { background: #fff1f2; color: #e11d48; border: 1px solid #ffe4e6; }

    .link-num:hover {
        transform: translateY(-2px);
        filter: brightness(0.9);
        color: inherit;
    }

    .tfoot-luxury {
        background-color: #1e293b !important;
        color: #ffffff !important;
        font-weight: 800;
    }

    .tfoot-luxury td {
        padding: 1.5rem !important;
        border: none !important;
    }

    .text-zero { color: #cbd5e1; font-weight: 700; opacity: 0.5; }

    /* DataTables Search DNA */
    .dataTables_filter input {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding: 0.5rem 1rem;
        background: #fbfcfe;
    }
</style>

<div class="container-fluid px-4">
    <div class="page-heading d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <a href="{{ url('laporan-utama') }}" class="btn-back me-3 shadow-sm" title="Kembali ke Panel Laporan">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="h3 fw-800 mb-1" style="color: #1e293b;">Laporan RK2</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                        <li class="breadcrumb-item"><a href="{{ url('laporan-utama') }}" class="text-decoration-none text-muted">Panel</a></li>
                        <li class="breadcrumb-item active fw-bold" style="color: #4f46e5;">Keadaan Perkara Banding</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-white border shadow-sm fw-bold px-4 py-2 rounded-pill">
                <i class="fas fa-print me-2 text-muted"></i> Cetak
            </button>
            <a href="{{ route('laporan.banding.putus.export', ['tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]) }}" class="btn btn-success shadow-sm fw-bold px-4 py-2 rounded-pill">
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
                    <div class="small fw-bold text-muted text-uppercase">Data Per Periode</div>
                    <span class="badge bg-soft-primary text-primary border-0 rounded-pill px-3">{{ date('d/m/y', strtotime($tgl_awal)) }} - {{ date('d/m/y', strtotime($tgl_akhir)) }}</span>
                </div>
            </form>
        </div>
    </div>

    <div class="card card-luxury border-0">
        <div class="table-responsive">
            <table id="tableRK2" class="table table-luxury align-middle mb-0 text-center">
                <thead>
                    <tr>
                        <th width="60">No</th>
                        <th class="text-start">Satuan Kerja</th>
                        <th>Sisa Lalu</th>
                        <th>Diterima</th>
                        <th>Beban</th>
                        <th>Putus</th>
                        <th>Sisa Akhir</th>
                    </tr>
                </thead>
                <tbody>
                    @php $tS=0; $tD=0; $tB=0; $tP=0; $tA=0; @endphp
                    @foreach($results as $row)
                    @php
                        $tS += $row->sisa_lalu; $tD += $row->diterima; $tB += $row->beban;
                        $tP += $row->selesai; $tA += $row->sisa_ini;
                    @endphp
                    <tr>
                        <td class="text-muted fw-bold">{{ $loop->iteration }}</td>
                        <td class="text-start">
                            <div class="fw-800 text-dark text-uppercase" style="font-size: 0.75rem;">
                                {{ $row->satker_key == 'TASIKKOTA' ? 'TASIKMALAYA KOTA' : $row->satker_key }}
                            </div>
                        </td>
                        <td>
                            @if($row->sisa_lalu > 0)
                                <a href="{{ route('laporan.banding.putus.detail', ['satker'=>$row->satker_key, 'jenis'=>'sisa_lalu', 'tgl_awal'=>$tgl_awal, 'tgl_akhir'=>$tgl_akhir]) }}" class="link-num link-sisa-lalu">{{ number_format($row->sisa_lalu) }}</a>
                            @else <span class="text-zero">0</span> @endif
                        </td>
                        <td>
                            @if($row->diterima > 0)
                                <a href="{{ route('laporan.banding.putus.detail', ['satker'=>$row->satker_key, 'jenis'=>'diterima', 'tgl_awal'=>$tgl_awal, 'tgl_akhir'=>$tgl_akhir]) }}" class="link-num link-terima">{{ number_format($row->diterima) }}</a>
                            @else <span class="text-zero">0</span> @endif
                        </td>
                        <td>
                            @if($row->beban > 0)
                                <a href="{{ route('laporan.banding.putus.detail', ['satker'=>$row->satker_key, 'jenis'=>'beban', 'tgl_awal'=>$tgl_awal, 'tgl_akhir'=>$tgl_akhir]) }}" class="link-num link-beban">{{ number_format($row->beban) }}</a>
                            @else <span class="text-zero">0</span> @endif
                        </td>
                        <td>
                            @if($row->selesai > 0)
                                <a href="{{ route('laporan.banding.putus.detail', ['satker'=>$row->satker_key, 'jenis'=>'selesai', 'tgl_awal'=>$tgl_awal, 'tgl_akhir'=>$tgl_akhir]) }}" class="link-num link-putus">{{ number_format($row->selesai) }}</a>
                            @else <span class="text-zero">0</span> @endif
                        </td>
                        <td>
                            @if($row->sisa_ini > 0)
                                <a href="{{ route('laporan.banding.putus.detail', ['satker'=>$row->satker_key, 'jenis'=>'sisa_ini', 'tgl_awal'=>$tgl_awal, 'tgl_akhir'=>$tgl_akhir]) }}" class="link-num link-sisa-akhir">{{ number_format($row->sisa_ini) }}</a>
                            @else <span class="text-zero">0</span> @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="tfoot-luxury">
                    <tr>
                        <td colspan="2" class="text-center">TOTAL WILAYAH HUKUM PTA BANDUNG</td>
                        <td>{{ number_format($tS) }}</td>
                        <td>{{ number_format($tD) }}</td>
                        <td>{{ number_format($tB) }}</td>
                        <td>{{ number_format($tP) }}</td>
                        <td>{{ number_format($tA) }}</td>
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
        $('#tableRK2').DataTable({
            "pageLength": 30,
            "ordering": false,
            "dom": '<"p-4 d-flex justify-content-between align-items-center"f>rtip',
            "language": {
                "search": "",
                "searchPlaceholder": "Cari Satker...",
                "paginate": {
                    "next": '<i class="fas fa-chevron-right"></i>',
                    "previous": '<i class="fas fa-chevron-left"></i>'
                }
            }
        });
    });
</script>
@endsection