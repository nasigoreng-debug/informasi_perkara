@extends('layouts.app')

@section('content')
<div class="container py-4 fade-in">
    <div class="card modern-card border-0 shadow-lg">
        <div class="card-header header-gradient-siwas py-3 d-flex justify-content-between align-items-center border-0">
            <h5 class="m-0 font-weight-bold text-white tracking-wide">
                <i class="fas fa-database me-2 opacity-75"></i> Data Register Pengaduan
            </h5>
            <a href="{{ route('pengaduan.create') }}" class="btn btn-light btn-sm rounded-pill px-4 text-danger fw-bold shadow-sm hover-elevate">
                <i class="fas fa-plus me-1"></i> Tambah Baru
            </a>
        </div>

        <div class="card-body p-4 p-md-5">
            <form action="{{ route('pengaduan.index') }}" method="GET" class="mb-4 bg-soft-light p-3 rounded-lg border-soft">
                <div class="row align-items-end g-2">
                    <div class="col-md-4">
                        <label class="text-xs font-weight-bold text-uppercase text-muted mb-1 text-dark">Cari Data</label>
                        <input type="text" name="search" class="form-control form-control-sm modern-input" placeholder="No PGD / Nama Pihak..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="text-xs font-weight-bold text-uppercase text-muted mb-1 text-dark">Dari</label>
                        <input type="date" name="from_date" class="form-control form-control-sm modern-input" value="{{ request('from_date', $startDate ?? date('Y-01-01')) }}">
                    </div>
                    <div class="col-md-2">
                        <label class="text-xs font-weight-bold text-uppercase text-muted mb-1 text-dark">Sampai</label>
                        <input type="date" name="to_date" class="form-control form-control-sm modern-input" value="{{ request('to_date', $endDate ?? date('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="btn-group w-100 shadow-sm rounded-pill overflow-hidden border">
                            <button type="submit" class="btn btn-danger btn-sm font-weight-bold">Filter</button>

                            <a href="{{ route('pengaduan.export_excel', request()->all()) }}" class="btn btn-success btn-sm font-weight-bold text-white" target="_blank">
                                <i class="fas fa-file-excel me-1"></i> Excel
                            </a>

                            <a href="{{ route('pengaduan.dashboard') }}" class="btn btn-dark btn-sm text-white font-weight-bold">Dashboard</a>
                            <a href="{{ route('pengaduan.index') }}" class="btn btn-light btn-sm text-muted font-weight-bold"><i class="fas fa-sync-alt"></i></a>
                        </div>
                    </div>
                </div>
            </form>

            <div class="table-responsive px-1">
                <table class="table modern-table align-middle">
                    <thead class="bg-light">
                        <tr class="text-muted text-uppercase small">
                            <th width="5%" class="text-center">No</th>
                            <th width="20%">No. PGD / Tanggal</th>
                            <th width="20%">Pihak (P/T)</th>
                            <th width="25%" class="text-center">Progres / Tracking Berkas</th>
                            <th width="15%" class="text-center">Status</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $item)
                        <tr class="hover-elevate-row" onclick="window.location='{{ route('pengaduan.detail', $item->id) }}'" style="cursor: pointer;">
                            <td class="text-center text-muted fw-bold small">
                                {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                            </td>
                            <td>
                                <div class="font-weight-bold text-dark small mb-0">{{ $item->no_pgd }}</div>
                                <div class="text-xxs text-muted fw-bold">
                                    <i class="far fa-calendar-alt me-1 text-danger"></i>
                                    {{ $item->tgl_terima_pgd ? \Carbon\Carbon::parse($item->tgl_terima_pgd)->format('d/m/Y') : '-' }}
                                </div>
                            </td>
                            <td>
                                <div class="text-xs font-weight-bold text-primary mb-1">P: {{ Str::limit($item->pelapor, 20) }}</div>
                                <div class="text-xs font-weight-bold text-danger">T: PA {{ Str::limit($item->terlapor, 20) }}</div>
                            </td>
                            <td class="text-center">
                                @php
                                $progress = 10; // Start diterima
                                if($item->dis_pm_hk) $progress = 30;
                                if($item->dis_kpta) $progress = 50;
                                if($item->dis_wkpta) $progress = 70;
                                if($item->dis_hatiwasda) $progress = 90;
                                if($item->tgl_selesai_pgd) $progress = 100;

                                $barColor = ($progress == 100) ? 'bg-success' : 'bg-primary';
                                @endphp
                                <div class="px-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-xxs fw-bold text-muted">{{ $progress }}%</span>
                                        <span class="text-xxs fw-bold text-uppercase italic text-primary" style="font-size: 8px;">
                                            <i class="fas fa-map-marker-alt me-1"></i> {{ $item->status_berkas ?? 'PTSP' }}
                                        </span>
                                    </div>
                                    <div class="progress shadow-sm" style="height: 6px; border-radius: 10px; background: #eaecf4;">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated {{ $barColor }}"
                                            role="progressbar" style="width: {{ $progress }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                @if($item->tgl_selesai_pgd)
                                <span class="badge badge-soft-success px-3 py-2 rounded-pill shadow-xs border">
                                    <i class="fas fa-check-circle me-1"></i> SELESAI
                                </span>
                                @else
                                <span class="badge badge-soft-warning px-3 py-2 rounded-pill shadow-xs border">
                                    <i class="fas fa-spinner fa-spin me-1"></i> PROSES
                                </span>
                                @endif
                            </td>
                            <td class="text-center" onclick="event.stopPropagation();">
                                <div class="btn-group shadow-sm rounded-pill overflow-hidden border bg-white">
                                    <a href="{{ route('pengaduan.detail', $item->id) }}" class="btn btn-light btn-sm text-primary border-end"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('pengaduan.edit', $item->id) }}" class="btn btn-light btn-sm text-warning border-end"><i class="fas fa-pencil-alt"></i></a>
                                    <button type="button" class="btn btn-light btn-sm text-danger" onclick="confirmDelete('{{ $item->id }}')"><i class="fas fa-trash"></i></button>
                                </div>
                                <form id="delete-form-{{ $item->id }}" action="{{ route('pengaduan.destroy', $item->id) }}" method="POST" style="display: none;">@csrf @method('DELETE')</form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted small italic">Kagak ada data di database nih Bos Admin.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 d-flex justify-content-center">
                {!! $data->appends(request()->all())->links('pagination::bootstrap-4') !!}
            </div>
        </div>
    </div>
</div>

<style>
    .header-gradient-siwas {
        background: linear-gradient(135deg, #8b0000 0%, #000000 100%);
    }

    .hover-elevate-row:hover {
        background-color: #f8f9fc;
        border-left: 4px solid #8b0000;
        transition: 0.2s;
    }

    .badge-soft-success {
        background-color: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0 !important;
    }

    .badge-soft-warning {
        background-color: #fffbeb;
        color: #d97706;
        border: 1px solid #fde68a !important;
    }

    .text-xxs {
        font-size: 0.65rem;
    }

    .modern-input {
        border-radius: 0.5rem;
        font-size: 0.85rem;
        border: 1px solid #ddd;
    }

    .italic {
        font-style: italic;
    }

    .progress-bar-animated {
        animation: 2s linear infinite progress-bar-stripes;
    }
</style>

<script>
    function confirmDelete(id) {
        if (confirm('Yakin mau hapus data ini secara permanen, Bos?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endsection