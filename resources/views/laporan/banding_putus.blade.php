@extends('layouts.app')

@section('styles')
{{-- Google Fonts & Font Awesome --}}
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<style>
    :root {
        --pta-primary: #1a2a6c;
        --pta-gradient: linear-gradient(135deg, #1a2a6c, #b21f1f, #fdbb2d);
        --pta-soft-bg: #f8fafc;
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: #f1f5f9;
    }

    /* Hilangkan Garis Bawah Link */
    #tableRK2 a {
        text-decoration: none !important;
        outline: none;
        box-shadow: none;
    }

    .card-luxury {
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        border: none;
        background: #ffffff;
        overflow: hidden;
    }

    .card-header-luxury {
        background: var(--pta-gradient);
        padding: 20px 25px;
        color: white;
    }

    .filter-section {
        background: white;
        border-radius: 16px;
        border-left: 6px solid var(--pta-primary);
    }

    /* Badge & Link Styling Selaras */
    .link-num {
        font-weight: 700;
        padding: 8px 16px;
        border-radius: 10px;
        display: inline-block;
        transition: all 0.2s;
        border: 1px solid transparent;
    }

    .link-sisa-lalu {
        background: #f8fafc;
        color: #64748b !important;
        border-color: #e2e8f0;
    }

    .link-terima {
        background: #eff6ff;
        color: #2563eb !important;
        border-color: #dbeafe;
    }

    .link-beban {
        background: #fffbeb;
        color: #d97706 !important;
        border-color: #fef3c7;
    }

    .link-putus {
        background: #ecfdf5;
        color: #059669 !important;
        border-color: #d1fae5;
    }

    .link-sisa-akhir {
        background: #fff1f2;
        color: #e11d48 !important;
        border-color: #ffe4e6;
    }

    .link-num:hover {
        transform: translateY(-2px);
        filter: brightness(0.95);
    }

    .tfoot-grand-total {
        background-color: #1e293b !important;
        color: #ffffff !important;
        font-weight: 800;
    }

    .tfoot-grand-total td {
        padding: 18px !important;
        font-size: 1rem;
        border: none !important;
    }

    .table thead th {
        background-color: var(--pta-soft-bg);
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        color: #64748b;
        padding: 15px;
        font-weight: 700;
    }

    .text-zero {
        color: #cbd5e1;
        font-weight: 700;
        font-size: 0.9rem;
    }
</style>
@endsection

@section('content')
<div class="container py-4 px-4">
    {{-- Header Page --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="fw-bold text-dark mb-1">Monitoring RK2</h2>
            <p class="text-muted small text-uppercase fw-bold mb-0">PTA BANDUNG • KEADAAN PERKARA BANDING</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <a href="{{ url('laporan-utama') }}" class="btn btn-white shadow-sm border px-4 fw-bold text-primary bg-white" style="border-radius: 10px; text-decoration: none !important;">
                <i class="fas fa-arrow-left me-2"></i> KEMBALI
            </a>
        </div>
    </div>

    {{-- Filter Card (Identik dengan Diterima) --}}
    <div class="card filter-section shadow-sm mb-4 border-0">
        <div class="card-body p-4">
            <form action="{{ url()->current() }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="small fw-bold text-muted mb-2 text-uppercase">Tanggal Awal</label>
                    <input type="date" name="tgl_awal" class="form-control border-0 bg-light py-2" value="{{ $tgl_awal }}">
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold text-muted mb-2 text-uppercase">Tanggal Akhir</label>
                    <input type="date" name="tgl_akhir" class="form-control border-0 bg-light py-2" value="{{ $tgl_akhir }}">
                </div>
                <div class="col-md-4">
                    <div class="btn-group w-100 shadow-sm">
                        <button type="submit" class="btn btn-primary fw-bold py-2">FILTER</button>
                        <a href="{{ url()->current() }}" class="btn btn-outline-danger fw-bold py-2"><i class="fas fa-undo"></i></a>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="d-flex gap-2">
                        <button type="button" onclick="window.print()" class="btn btn-clean btn-light border w-100">
                            <i class="fas fa-print"></i>
                        </button>
                        <a href="{{ route('laporan.banding.putus.export', ['tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]) }}" class="btn btn-clean btn-success w-100">
                            <i class="fas fa-file-excel me-1"></i> Excel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Main Table Card --}}
    <div class="card card-luxury shadow-sm">
        <div class="card-header-luxury d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold"><i class="fas fa-database me-2"></i>REKAPITULASI KEADAAN PERKARA</h5>
            <span class="badge bg-white text-dark py-2 px-3 rounded-pill fw-bold shadow-sm">
                PERIODE: {{ date('d/m/Y', strtotime($tgl_awal)) }} - {{ date('d/m/Y', strtotime($tgl_akhir)) }}
            </span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="tableRK2" class="table table-hover align-middle mb-0 text-center text-nowrap">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
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
                            <td class="text-muted small fw-bold">{{ $loop->iteration }}</td>
                            <td class="text-start fw-bold text-dark">{{ $row->satker_key == 'TASIKKOTA' ? 'TASIKMALAYA KOTA' : $row->satker_key }}</td>

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
                    <tfoot class="tfoot-grand-total">
                        <tr>
                            <td colspan="2" class="text-center py-3 text-uppercase">Total Wilayah Hukum PTA Bandung</td>
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
            "dom": '<"p-3 d-flex justify-content-between align-items-center"f>rtip',
            "language": {
                "search": "Cari Satker:",
                "searchPlaceholder": "Ketik nama...",
                "paginate": {
                    "next": '<i class="fas fa-chevron-right"></i>',
                    "previous": '<i class="fas fa-chevron-left"></i>'
                }
            }
        });
    });
</script>
@endsection