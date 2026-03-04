@extends('layouts.app')

@section('title', "Detail Akta Cerai - $satker")

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    :root {
        --dark-navy: #1e293b;
        --border-color: #e2e8f0;
        --bg-body: #f8fafc;
        --accent-blue: #2563eb;
    }

    body {
        background-color: var(--bg-body);
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: #334155;
        letter-spacing: -0.01em;
    }

    /* Container dikunci agar seimbang */
    .content-container {
        max-width: 1200px;
        margin: auto;
    }

    .btn-back {
        width: 40px; height: 40px; border-radius: 10px;
        display: inline-flex; align-items: center; justify-content: center;
        background: white; border: 1px solid var(--border-color);
        color: var(--dark-navy); text-decoration: none !important;
    }

    /* Table DNA - Grid Kotak Tipis (Classic Clean) */
    .table-container {
        background: white; border: 1px solid var(--border-color);
        border-radius: 12px; overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .table-grid { width: 100%; border-collapse: collapse; table-layout: fixed; }
    
    .table-grid thead th {
        background: #f8fafc; border: 1px solid var(--border-color);
        padding: 14px 10px; font-size: 0.75rem; font-weight: 800;
        text-transform: uppercase; color: #64748b; letter-spacing: 0.05em;
    }
    
    .table-grid tbody td {
        border: 1px solid var(--border-color);
        padding: 12px 10px; font-size: 0.82rem; vertical-align: middle;
        word-wrap: break-word;
    }

    .table-grid tr:hover td { background-color: #f1f5f9; }

    .no-perkara { color: var(--accent-blue); font-weight: 700; text-decoration: none; }

    /* Styling Badge Status Tema CC */
    .badge-cc {
        display: inline-block; padding: 5px 10px; border-radius: 6px;
        font-weight: 800; font-size: 0.65rem; text-transform: uppercase;
        border: 1px solid transparent;
    }
    .badge-tepat { background: #f0fdf4; color: #16a34a; border-color: #dcfce7; }
    .badge-lambat { background: #fffbeb; color: #d97706; border-color: #fef3c7; }
    .badge-anomali { background: #fff1f2; color: #e11d48; border-color: #ffe4e6; }

    @media print {
        .no-print { display: none !important; }
        .table-container { border: none; box-shadow: none; }
        .content-container { max-width: 100%; }
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="content-container">
        
        {{-- HEADER TEMA CC --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center">
                <a href="{{ route('akta-cerai.index', ['tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhir]) }}" class="btn-back me-3 shadow-sm no-print">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <h2 class="fw-800 mb-0" style="color: var(--dark-navy); font-size: 1.4rem;">Detail Penerbitan Akta</h2>
                    <p class="text-muted small mb-0">
                        Satker: <span class="text-primary font-weight-bold text-uppercase">{{ $satker }}</span> 
                        <span class="mx-2">|</span> 
                        Periode: <span class="fw-bold">{{ \Carbon\Carbon::parse($tglAwal)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($tglAkhir)->format('d/m/Y') }}</span>
                    </p>
                </div>
            </div>
            
            <div class="no-print">
                <div class="btn-group shadow-sm">
                    <a href="{{ route('akta-cerai.export-detail', ['satker' => $satker, 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhir, 'kategori' => $kategori]) }}"
                        target="_blank" class="btn btn-sm btn-success fw-bold px-3">
                        <i class="bi bi-file-earmark-excel me-1"></i> EXPORT
                    </a>
                    <button onclick="window.print()" class="btn btn-sm btn-dark fw-bold px-3">
                        <i class="bi bi-printer me-1"></i> CETAK
                    </button>
                </div>
            </div>
        </div>

        {{-- TABLE SECTION --}}
        <div class="table-container shadow-sm">
            <div class="table-responsive">
                <table class="table-grid text-center" id="detailTable">
                    <thead>
                        <tr>
                            <th style="width: 50px;">NO</th>
                            <th style="width: 200px;" class="text-start px-3">NOMOR PERKARA</th>
                            <th style="width: 180px;">JENIS PERKARA</th>
                            <th style="width: 110px;">TGL BHT</th>
                            <th style="width: 110px;">TGL IKRAR</th>
                            <th style="width: 110px;">TGL AKTA</th>
                            <th style="width: 90px;">SELISIH</th>
                            <th style="width: 180px;">STATUS KINERJA</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $index => $item)
                            @php
                                $isAnomali = $item->selisih_anomali < 0;
                                $isTerlambat = $item->selisih_hari > 7;
                                $fmt = fn($d) => $d ? \Carbon\Carbon::parse($d)->format('d-m-Y') : '-';
                            @endphp
                            <tr class="{{ $isAnomali ? 'bg-light font-italic' : '' }}">
                                <td class="text-muted fw-bold">{{ $index + 1 }}</td>
                                <td class="text-start px-3">
                                    <span class="no-perkara">{{ $item->nomor_perkara }}</span>
                                </td>
                                <td class="text-muted small text-uppercase fw-500">{{ $item->jenis_perkara_nama }}</td>
                                <td>{{ $fmt($item->tanggal_bht) }}</td>
                                <td>{{ $fmt($item->tgl_ikrar_talak) }}</td>
                                <td class="fw-bold">{{ $fmt($item->tgl_akta_cerai) }}</td>
                                <td class="fw-bold {{ $isTerlambat || $isAnomali ? 'text-danger' : 'text-success' }}">
                                    {{ $item->selisih_hari }} H
                                </td>
                                <td>
                                    @if($isAnomali) 
                                        <span class="badge-cc badge-anomali">AC < BHT</span>
                                    @elseif($isTerlambat) 
                                        <span class="badge-cc badge-lambat">Terlambat</span>
                                    @else 
                                        <span class="badge-cc badge-tepat">Tepat Waktu</span> 
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-5 text-muted fw-bold text-uppercase">Data tidak ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection