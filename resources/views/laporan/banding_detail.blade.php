@extends('layouts.app')

@section('styles')
<style>
    .card-luxury {
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        border: none;
        overflow: hidden;
    }

    .table-detail thead {
        background-color: #f8fafc;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        color: #64748b;
    }

    .badge-ecourt {
        background: #dcfce7;
        color: #166534;
        padding: 5px 12px;
        border-radius: 6px;
        font-weight: bold;
        font-size: 0.8rem;
    }

    .badge-manual {
        background: #fee2e2;
        color: #991b1b;
        padding: 5px 12px;
        border-radius: 6px;
        font-weight: bold;
        font-size: 0.8rem;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="card card-luxury shadow border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0 fw-bold">
                <i class="fas fa-info-circle me-2"></i>Detail Perkara: {{ $request->satker }} ({{ strtoupper($request->jenis) }})
            </h5>
            <a href="javascript:history.back()" class="btn btn-sm btn-light fw-bold px-3">
                <i class="fas fa-arrow-left me-1"></i> KEMBALI
            </a>
        </div>
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
                            <th class="text-center">Pendaftaran</th>
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
                            <td class="text-center small">{{ date('d-m-Y', strtotime($d->tgl_register)) }}</td>
                            <td class="text-center">
                                <span class="{{ $d->jenis == 'E-Court' ? 'badge-ecourt' : 'badge-manual' }}">
                                    {{ $d->jenis }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-folder-open d-block mb-2 fa-2x"></i>
                                Tidak ada data detail untuk periode ini.
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