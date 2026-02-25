@extends('layouts.app')

@section('styles')
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

    /* HAPUS GARIS BAWAH DAN WARNA DEFAULT LINK */
    #tableRekap a {
        text-decoration: none !important;
        outline: none;
        box-shadow: none;
    }

    .card-luxury {
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        border: none;
        background: #ffffff;
    }

    .card-header-luxury {
        background: var(--pta-gradient);
        padding: 20px 25px;
        color: white;
        border-radius: 16px 16px 0 0;
    }

    .filter-section {
        background: white;
        border-radius: 16px;
        border-left: 6px solid var(--pta-primary);
    }

    /* Badge Link Styling */
    .badge-total {
        background: #eff6ff;
        color: #2563eb !important;
        padding: 8px 16px;
        border-radius: 10px;
        font-weight: 700;
        display: inline-block;
        border: 1px solid #dbeafe;
        transition: all 0.2s;
    }

    .badge-ecourt {
        background: #ecfdf5;
        color: #059669 !important;
        padding: 8px 16px;
        border-radius: 10px;
        font-weight: 700;
        display: inline-block;
        border: 1px solid #d1fae5;
        transition: all 0.2s;
    }

    .badge-manual {
        background: #fff1f2;
        color: #e11d48 !important;
        padding: 8px 16px;
        border-radius: 10px;
        font-weight: 700;
        display: inline-block;
        border: 1px solid #ffe4e6;
        transition: all 0.2s;
    }

    .badge-total:hover {
        background: #2563eb;
        color: #fff !important;
        transform: translateY(-2px);
    }

    .badge-ecourt:hover {
        background: #059669;
        color: #fff !important;
        transform: translateY(-2px);
    }

    .badge-manual:hover {
        background: #e11d48;
        color: #fff !important;
        transform: translateY(-2px);
    }

    .tfoot-grand-total {
        background-color: #1e293b !important;
        color: #ffffff !important;
        font-weight: 800;
    }

    .tfoot-grand-total td {
        padding: 18px !important;
        font-size: 1rem;
    }

    .table thead th {
        background-color: var(--pta-soft-bg);
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        color: #64748b;
        padding: 15px;
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
    {{-- Header --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-6 text-center text-md-start">
            <h2 class="fw-bold text-dark mb-1">Monitoring Banding</h2>
            <p class="text-muted small text-uppercase fw-bold mb-0">PTA BANDUNG • DATA PERKARA DITERIMA</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <a href="{{ url('laporan-utama') }}" class="btn btn-white shadow-sm border px-4 fw-bold text-primary bg-white" style="border-radius: 10px; text-decoration: none !important;">
                <i class="fas fa-arrow-left me-2"></i> KEMBALI
            </a>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="card filter-section shadow-sm mb-4 border-0">
        <div class="card-body p-4">
            <form action="{{ url()->current() }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="small fw-bold text-muted mb-2">TANGGAL AWAL</label>
                    <input type="date" name="tgl_awal" class="form-control border-0 bg-light py-2" value="{{ $tgl_awal }}">
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold text-muted mb-2">TANGGAL AKHIR</label>
                    <input type="date" name="tgl_akhir" class="form-control border-0 bg-light py-2" value="{{ $tgl_akhir }}">
                </div>
                <div class="col-md-4">
                    <div class="btn-group w-100 shadow-sm">
                        <button type="submit" class="btn btn-primary fw-bold">FILTER</button>
                        <a href="{{ url()->current() }}" class="btn btn-outline-danger fw-bold"><i class="fas fa-undo"></i></a>
                    </div>
                </div>
                <div class="col-md-2 text-end">
                    <div class="d-flex gap-2">
                        <button type="button" onclick="window.print()" class="btn btn-clean btn-light border w-100">
                            <i class="fas fa-print"></i>
                        </button>
                        <a href="{{ route('laporan.banding.diterima.export', ['tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]) }}" class="btn btn-clean btn-success w-100">
                            <i class="fas fa-file-excel me-1"></i> Excel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="card card-luxury shadow-sm">
        <div class="card-header-luxury d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold"><i class="fas fa-database me-2"></i>REKAPITULASI 26 SATUAN KERJA</h5>
            <span class="badge bg-white text-dark py-2 px-3 rounded-pill fw-bold shadow-sm">
                PERIODE: {{ date('d/m/Y', strtotime($tgl_awal)) }} - {{ date('d/m/Y', strtotime($tgl_akhir)) }}
            </span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="tableRekap" class="table table-hover align-middle mb-0 text-nowrap">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>Satuan Kerja</th>
                            <th class="text-center">Total</th>
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
                            <td class="text-center text-muted fw-bold small">{{ $loop->iteration }}</td>
                            <td class="fw-bold text-dark">{{ $row->satker }}</td>

                            {{-- Kolom Total --}}
                            <td class="text-center">
                                @if($row->total_perkara > 0)
                                <a href="{{ route('laporan.banding.detail', ['satker' => $row->satker_key, 'jenis' => 'total', 'tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]) }}" class="badge-total">
                                    {{ number_format($row->total_perkara) }}
                                </a>
                                @else
                                <span class="text-zero">0</span>
                                @endif
                            </td>

                            {{-- Kolom E-Court --}}
                            <td class="text-center">
                                @if($row->jumlah_ecourt > 0)
                                <a href="{{ route('laporan.banding.detail', ['satker' => $row->satker_key, 'jenis' => 'ecourt', 'tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]) }}" class="badge-ecourt">
                                    {{ number_format($row->jumlah_ecourt) }}
                                </a>
                                @else
                                <span class="text-zero">0</span>
                                @endif
                            </td>

                            {{-- Kolom Manual --}}
                            <td class="text-center">
                                @if($row->jumlah_manual > 0)
                                <a href="{{ route('laporan.banding.detail', ['satker' => $row->satker_key, 'jenis' => 'manual', 'tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]) }}" class="badge-manual">
                                    {{ number_format($row->jumlah_manual) }}
                                </a>
                                @else
                                <span class="text-zero">0</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="tfoot-grand-total">
                        <tr>
                            <td colspan="2" class="text-center">TOTAL</td>
                            <td class="text-center">{{ number_format($gTotal) }}</td>
                            <td class="text-center">{{ number_format($gEcourt) }}</td>
                            <td class="text-center">{{ number_format($gManual) }}</td>
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
        $('#tableRekap').DataTable({
            "pageLength": 30,
            "ordering": false,
            "dom": '<"p-3 d-flex justify-content-between align-items-center"f>rtip',
            "language": {
                "search": "Cari Satker:",
                "searchPlaceholder": "Ketik nama..."
            }
        });
    });
</script>
@endsection