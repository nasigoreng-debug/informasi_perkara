@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center border-bottom">
            <div>
                <h5 class="fw-bold mb-0 text-primary">RINCIAN PERKARA SISA PANJAR (> 6 BULAN)</h5>
                <small class="text-muted text-uppercase fw-bold">PA {{ strtoupper($satker) }} | TINGKAT {{ strtoupper($jenis) }}</small>
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
                        <th>Tgl Putusan</th>
                        <th class="bg-warning bg-opacity-10 text-dark">Tgl Pemberitahuan</th> <th>Durasi</th>
                        <th class="text-end px-4">Sisa Saldo</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach($data as $row)
                    <tr>
                        <td class="small text-muted">{{ $loop->iteration }}</td>
                        <td class="text-start px-4 fw-bold text-primary">{{ $row->nomor_perkara }}</td>
                        <td class="small">{{ $row->tgl_putusan ? date('d-m-Y', strtotime($row->tgl_putusan)) : '-' }}</td>
                        
                        <td class="fw-bold text-dark bg-warning bg-opacity-10">
                            {{ $row->tgl_notif ? date('d-m-Y', strtotime($row->tgl_notif)) : '-' }}
                        </td>

                        <td>
                            <span class="badge bg-danger rounded-pill px-3 shadow-sm">
                                {{ $row->selisih_bulan }} Bulan
                            </span>
                        </td>
                        <td class="text-end px-4 fw-bold text-danger">
                            Rp {{ number_format($row->sisa, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($data->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
            <h5 class="text-muted">Tidak ada tunggakan sisa panjar di satker ini.</h5>
        </div>
        @endif
    </div>
</div>
@endsection