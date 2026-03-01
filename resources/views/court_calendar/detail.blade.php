@extends('layouts.app')

@section('title', 'Detail Court Calendar | ' . $satker)

@section('content')
<div class="container py-4 px-4">
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden animate__animated animate__fadeIn">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold mb-0 text-primary"><i class="fas fa-search me-2"></i>DETAIL TUNGGAKAN COURT CALENDAR</h5>
                <small class="text-muted text-uppercase">SATKER: {{ $satker }}</small>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('court-calendar.export-detail', ['satker' => $satker, 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhir]) }}" class="btn btn-success btn-sm rounded-pill px-3 no-loader">
                    <i class="fas fa-file-excel me-1"></i> Export Excel
                </a>
                <a href="{{ route('court-calendar', ['tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhir]) }}" class="btn btn-secondary btn-sm rounded-pill px-3">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="small fw-bold text-muted text-uppercase">
                        <th class="ps-4 py-3">No</th>
                        <th>Nomor Perkara</th>
                        <th class="text-center">Tgl Daftar</th>
                        <th class="text-center">Tgl Putus</th>
                        <th>Status Terakhir</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $row)
                    <tr>
                        <td class="ps-4 text-muted">{{ $index + 1 }}</td>
                        <td><span class="fw-bold text-primary">{{ $row->nomor_perkara }}</span></td>
                        <td class="text-center">{{ date('d-m-Y', strtotime($row->tanggal_pendaftaran)) }}</td>
                        <td class="text-center fw-bold">{{ date('d-m-Y', strtotime($row->tanggal_putusan)) }}</td>
                        <td><span class="badge bg-warning bg-opacity-10 text-warning px-3 rounded-pill">{{ $row->proses_terakhir_text }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-5 italic text-muted">Data tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection