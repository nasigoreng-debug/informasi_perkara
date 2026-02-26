@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    body { 
        font-family: 'Plus Jakarta Sans', sans-serif; 
        background-color: #f4f7fa; 
    }
    
    .page-heading { padding: 2rem 0; }
    
    /* Card Luxury DNA */
    .card-luxury {
        background: #ffffff;
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.02);
        overflow: hidden;
    }

    /* Tabel Detail Presisi */
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

    /* Soft Badge DNA */
    .badge-soft-success {
        background: #f0fdf4;
        color: #16a34a;
        border: 1px solid #dcfce7;
        font-weight: 700;
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        font-size: 0.75rem;
    }

    .badge-soft-info {
        background: #eef2ff;
        color: #4f46e5;
        border: 1px solid #e0e7ff;
        font-weight: 700;
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        font-size: 0.75rem;
    }

    .badge-soft-light {
        background: #f8fafc;
        color: #94a3b8;
        border: 1px solid #e2e8f0;
        font-weight: 600;
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        font-size: 0.75rem;
    }

    /* Hover effect baris */
    .table-luxury tbody tr:hover { background-color: #f8fafc; transition: 0.2s; }
</style>

<div class="container px-4">
    <div class="page-heading d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <a href="javascript:history.back()" class="btn-back me-3 shadow-sm" title="Kembali">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="h3 fw-800 mb-1" style="color: #1e293b;">Rincian Perkara</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                        <li class="breadcrumb-item"><a href="{{ url('laporan-utama') }}" class="text-decoration-none text-muted">Panel</a></li>
                        <li class="breadcrumb-item text-muted text-uppercase fw-bold">{{ $request->satker }}</li>
                        <li class="breadcrumb-item active fw-bold" style="color: #4f46e5;">{{ strtoupper($request->jenis) }}</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="text-end">
            <div class="small fw-bold text-muted text-uppercase mb-1">Total Rincian</div>
            <span class="badge bg-white border text-primary py-2 px-3 rounded-pill fw-800 shadow-sm">
                {{ count($details) }} PERKARA
            </span>
        </div>
    </div>

    <div class="card card-luxury border-0">
        <div class="table-responsive">
            <table class="table table-luxury align-middle mb-0">
                <thead>
                    <tr>
                        <th class="text-center" width="60">No</th>
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
                        <td class="text-center fw-bold text-muted">{{ $i + 1 }}</td>
                        <td>
                            <div class="fw-800" style="color: #4f46e5;">{{ $d->nomor_perkara_banding }}</div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $d->nomor_perkara_pa }}</div>
                        </td>
                        <td>
                            <span class="badge-soft-info text-uppercase">{{ $d->jenis_perkara }}</span>
                        </td>
                        <td class="text-center">
                            <span class="text-muted fw-medium">{{ $d->tgl_register ? date('d-m-Y', strtotime($d->tgl_register)) : '-' }}</span>
                        </td>
                        <td class="text-center">
                            @if($d->tgl_putusan)
                                <span class="badge-soft-success">
                                    <i class="fas fa-gavel me-1"></i> {{ date('d-m-Y', strtotime($d->tgl_putusan)) }}
                                </span>
                            @else
                                <span class="badge-soft-light">
                                    <i class="fas fa-clock me-1"></i> PROSES
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="py-4 opacity-25">
                                <i class="fas fa-folder-open fa-4x mb-3"></i>
                                <h5 class="fw-bold">Data rincian tidak tersedia</h5>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white border-0 py-3 text-center text-muted small">
            Monitoring Real-time SIAPPTA - {{ date('d/m/Y H:i') }}
        </div>
    </div>
</div>
@endsection