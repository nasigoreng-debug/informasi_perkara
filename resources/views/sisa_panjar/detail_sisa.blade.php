@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center border-bottom">
            <div>
                <h5 class="fw-bold mb-0 text-primary">RINCIAN PERKARA SISA PANJAR</h5>
                <small class="text-muted text-uppercase fw-bold">PA {{ $satker }} | TINGKAT {{ $jenis }}</small>
            </div>
            <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm rounded-pill px-3 shadow-sm">
                <i class="fas fa-arrow-left me-1"></i> KEMBALI
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-center text-uppercase" style="font-size: 0.75rem;">
                    <tr class="text-muted fw-bold">
                        <th class="py-3">No</th>
                        <th class="text-start px-4">Nomor Perkara</th>
                        <th>Tgl Putus</th>
                        <th>Durasi (Bulan)</th>
                        <th class="text-end px-4">Sisa Saldo</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach($data as $row)
                    <tr>
                        <td class="small text-muted">{{ $loop->iteration }}</td>
                        <td class="text-start px-4 fw-bold text-primary">{{ $row->nomor_perkara }}</td>
                        <td>{{ date('d-m-Y', strtotime($row->tgl_putusan)) }}</td>
                        <td><span class="badge bg-danger rounded-pill px-3">{{ $row->selisih_bulan }} Bln</span></td>
                        <td class="text-end px-4 fw-bold text-danger">Rp {{ number_format($row->sisa, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection