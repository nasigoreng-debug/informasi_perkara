@extends('layouts.app')

@section('content')
<style>
    .content-container {
        max-width: 1050px;
        margin: auto;
    }

    .table thead th {
        background-color: #f8f9fc;
        text-transform: uppercase;
        font-size: 0.8rem !important;
        font-weight: 700 !important;
        color: #4e73df;
        padding: 12px;
        border-top: none;
    }

    .table tbody td {
        font-size: 0.85rem !important;
        vertical-align: middle !important;
        color: #2d3748;
        padding: 10px;
    }

    .badge-status {
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        font-weight: 700;
        font-size: 0.7rem;
        display: inline-block;
    }
</style>

<div class="container py-4">
    <div class="content-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="font-weight-bold text-gray-800 mb-0">Detail Penerbitan Akta</h3>
                <p class="text-muted small mb-0">Satker: <span class="text-primary font-weight-bold text-uppercase">{{ $satker }}</span> | Periode: {{ \Carbon\Carbon::parse($tglAwal)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($tglAkhir)->format('d/m/Y') }}</p>
            </div>
            <div class="btn-group shadow-sm">
                <a href="{{ route('akta-cerai.export-detail', ['satker' => $satker, 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhir, 'kategori' => $kategori]) }}"
                    target="_blank"
                    class="btn btn-sm btn-success font-weight-bold">
                    <i class="fas fa-file-excel mr-1"></i> EXPORT
                </a>
                <a href="{{ route('akta-cerai.index', ['tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhir]) }}" class="btn btn-sm btn-white border px-3 font-weight-bold text-dark">
                    <i class="fas fa-arrow-left mr-1"></i> KEMBALI
                </a>
            </div>
        </div>

        <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
            <div class="card-body p-0 text-dark">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="text-center text-uppercase">
                            <tr>
                                <th>NO</th>
                                <th class="text-left">NOMOR PERKARA</th>
                                <th>JENIS</th>
                                <th>TGL BHT</th>
                                <th>TGL IKRAR</th>
                                <th>TGL AKTA</th>
                                <th>SELISIH</th>
                                <th>STATUS</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @forelse($data as $index => $item)
                            @php
                            $isAnomali = $item->selisih_anomali < 0;
                                $isTerlambat=$item->selisih_hari > 7;
                                $fmt = fn($d) => $d ? \Carbon\Carbon::parse($d)->format('d-m-Y') : '-';
                                @endphp
                                <tr class="{{ $isAnomali ? 'bg-light font-italic' : '' }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td class="text-left font-weight-bold text-primary">{{ $item->nomor_perkara }}</td>
                                    <td class="text-muted small text-uppercase">{{ $item->jenis_perkara_nama }}</td>
                                    <td>{{ $fmt($item->tanggal_bht) }}</td>
                                    <td>{{ $fmt($item->tgl_ikrar_talak) }}</td>
                                    <td class="font-weight-bold">{{ $fmt($item->tgl_akta_cerai) }}</td>
                                    <td class="font-weight-bold {{ $isTerlambat || $isAnomali ? 'text-danger' : 'text-success' }}">
                                        {{ $item->selisih_hari }} Hari
                                    </td>
                                    <td>
                                        @if($isAnomali) <span class="badge-status bg-danger text-white">AC MENDAHULUI BHT</span>
                                                @elseif($isTerlambat) <span class="badge-status bg-warning text-dark border-warning" style="background:#fff3cd">TERLAMBAT</span>
                                                @else <span class="badge-status bg-success text-white">TEPAT</span> @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="py-5 text-muted">Data tidak ditemukan.</td>
                                </tr>
                                @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection