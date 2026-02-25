@extends('layouts.app')

@section('styles')
<style>
    .card-luxury {
        border-radius: 16px;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .table-detail thead {
        background-color: #f8fafc;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        color: #64748b;
    }

    .badge-status {
        padding: 5px 12px;
        border-radius: 6px;
        font-weight: bold;
        font-size: 0.8rem;
    }

    .bg-info-soft {
        background-color: #e0f2fe;
        color: #0369a1;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Rincian Keadaan Perkara</h4>
            <p class="text-muted mb-0">
                Satker: <strong>{{ strtoupper($request->satker) }}</strong> |
                Kategori: <span class="badge bg-info-soft">{{ strtoupper($request->jenis) }}</span>
            </p>
        </div>
        <a href="javascript:history.back()" class="btn btn-outline-secondary fw-bold px-4 shadow-sm bg-white">
            <i class="fas fa-arrow-left me-2"></i> KEMBALI
        </a>
    </div>

    <div class="card card-luxury">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle table-detail">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>Nomor Perkara Banding</th>
                            <th>Nomor Perkara PA</th>
                            <th>Jenis Perkara</th>
                            <th class="text-center">Tgl Register</th>
                            <th class="text-center">Tgl Putus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($details as $i => $d)
                        <tr>
                            <td class="text-center text-muted small">{{ $i + 1 }}</td>
                            <td class="fw-bold text-primary">{{ $d->nomor_perkara_banding }}</td>
                            <td class="fw-semibold text-dark">{{ $d->nomor_perkara_pa }}</td>
                            <td>
                                <span class="text-secondary small fw-bold text-uppercase">{{ $d->jenis_perkara }}</span>
                            </td>
                            <td class="text-center small">{{ $d->tgl_register ? date('d-m-Y', strtotime($d->tgl_register)) : '-' }}</td>
                            <td class="text-center">
                                @if($d->tgl_putusan)
                                <span class="badge bg-success small">{{ date('d-m-Y', strtotime($d->tgl_putusan)) }}</span>
                                @else
                                <span class="badge bg-light text-muted border small">Belum Putus</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-folder-open d-block mb-2 fa-2x"></i>
                                Tidak ada rincian data untuk kategori ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection