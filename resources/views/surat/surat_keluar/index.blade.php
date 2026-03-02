@extends('layouts.app')

@section('content')
<div class="container py-4 fade-in">
    <div class="card modern-card border-0">
        <div class="card-header header-gradient py-3 d-flex justify-content-between align-items-center border-0">
            <h5 class="m-0 font-weight-bold text-white tracking-wide">
                <i class="fas fa-paper-plane me-2 opacity-75"></i> Arsip Surat Keluar
            </h5>
            <a href="{{ route('surat.keluar.create') }}" class="btn btn-light btn-sm rounded-pill px-4 text-primary font-weight-bold shadow-sm hover-elevate">
                <i class="fas fa-plus me-1"></i> Tambah Baru
            </a>
        </div>

        <div class="card-body p-4 p-md-5">
            <form action="{{ route('surat.keluar.index') }}" method="GET" class="mb-4 bg-soft-light p-3 rounded-lg border-soft shadow-sm">
                <div class="row align-items-end g-3">
                    <div class="col-md-3">
                        <label class="text-xs font-weight-bold text-uppercase text-muted mb-1">Pencarian</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-search"></i></span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="No. Surat / Tujuan..." value="{{ request('search') }}">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <label class="text-xs font-weight-bold text-uppercase text-muted mb-1">Dari</label>
                        <input type="date" name="from_date" class="form-control form-control-sm modern-input" value="{{ request('from_date') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="text-xs font-weight-bold text-uppercase text-muted mb-1">Sampai</label>
                        <input type="date" name="to_date" class="form-control form-control-sm modern-input" value="{{ request('to_date') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="text-xs font-weight-bold text-uppercase text-muted mb-1">Entri</label>
                        <select name="per_page" class="form-select form-select-sm modern-input fw-bold text-primary" onchange="this.form.submit()">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <div class="btn-group w-100 shadow-sm rounded-pill overflow-hidden border">
                            <button type="submit" class="btn btn-primary btn-sm font-weight-bold" title="Terapkan Filter">
                                <i class="fas fa-filter"></i>
                            </button>
                            <a href="{{ route('surat.keluar.exportExcel', request()->query()) }}" target="_blank" class="btn btn-success btn-sm font-weight-bold" title="Export Excel">
                                <i class="fas fa-file-excel"></i>
                            </a>
                            <a href="{{ route('surat.keluar.dashboard') }}" class="btn btn-info btn-sm font-weight-bold text-white" title="Dashboard">
                                <i class="fas fa-tachometer-alt"></i>
                            </a>
                            <a href="{{ route('surat.keluar.index') }}" class="btn btn-light btn-sm text-muted font-weight-bold" title="Reset">
                                <i class="fas fa-sync-alt"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>

            <div class="table-responsive px-1">
                <table class="table modern-table align-middle">
                    <thead>
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th width="25%">Informasi Surat</th>
                            <th width="30%">Tujuan & Perihal</th>
                            <th width="20%" class="text-center">Dokumen</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $item)
                        <tr class="hover-elevate-row">
                            <td class="text-center text-muted fw-bold small">
                                {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                            </td>
                            <td>
                                <div class="font-weight-bold text-dark mb-1 small">{{ $item->no_surat }}</div>
                                <div class="text-xs text-muted small"><i class="far fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($item->tgl_surat)->format('d M Y') }}</div>
                            </td>
                            <td>
                                <div class="font-weight-bold text-primary mb-1 small text-uppercase">{{ $item->tujuan_surat }}</div>
                                <div class="text-truncate text-muted text-xs" style="max-width: 250px;" title="{{ $item->perihal }}">{{ $item->perihal }}</div>
                            </td>
                            <td class="text-center">
                                <div class="d-flex flex-column gap-1 align-items-center">
                                    @if($item->surat_pta)
                                    <a href="{{ route('surat.keluar.download', [$item->id, 'resmi']) }}" class="attachment-pill shadow-sm bg-soft-danger text-danger border-danger" target="_blank">
                                        <i class="fas fa-file-pdf me-1"></i> PDF Resmi
                                    </a>
                                    @endif

                                    @if($item->konsep_surat)
                                    <a href="{{ route('surat.keluar.download', [$item->id, 'konsep']) }}" class="attachment-pill shadow-sm bg-soft-primary text-primary border-primary" target="_blank">
                                        <i class="fas fa-file-word me-1"></i> Konsep Word
                                    </a>
                                    @endif

                                    @if(!$item->surat_pta && !$item->konsep_surat)
                                    <span class="badge bg-soft-secondary text-muted rounded-pill px-3 py-2 border-soft" style="font-size: 0.65rem;">
                                        <i class="fas fa-exclamation-circle me-1"></i> Belum Upload
                                    </span>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="btn-group shadow-sm rounded-pill overflow-hidden border bg-white">
                                    <button type="button" class="btn btn-light text-primary btn-sm px-2 border-end" onclick="openModal('detailKeluar{{ $item->id }}')" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="{{ route('surat.keluar.edit', $item->id) }}" class="btn btn-light text-warning btn-sm px-2 border-end" title="Edit">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <button type="button" class="btn btn-light text-danger btn-sm px-2" onclick="confirmDelete('{{ $item->id }}')" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <form id="delete-form-{{ $item->id }}" action="{{ route('surat.keluar.destroy', $item->id) }}" method="POST" style="display: none;">
                                    @csrf @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="text-muted small">
                                    <i class="fas fa-folder-open fa-3x mb-3 opacity-25"></i><br>
                                    Data tidak ditemukan dalam periode ini.
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white border-top py-3 px-4">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <p class="text-sm text-muted m-0 small fw-bold text-uppercase">
                        Menampilkan <span class="text-primary">{{ $data->firstItem() ?? 0 }}</span> - <span class="text-primary">{{ $data->lastItem() ?? 0 }}</span> dari <span class="text-primary">{{ $data->total() }}</span> Data
                    </p>
                </div>
                <div class="col-md-6 d-flex justify-content-center justify-content-md-end">
                    {{ $data->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL DETAIL --}}
@foreach($data as $item)
<div class="modal fade" id="detailKeluar{{ $item->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content modern-modal border-0 shadow-lg">
            <div class="modal-header header-gradient text-white border-0">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-file-alt me-2"></i> Rincian Arsip Surat Keluar
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-0 text-dark">
                <div class="p-4 bg-soft-light border-bottom">
                    <div class="row text-center g-3">
                        <div class="col-6 border-end">
                            <p class="text-xs text-muted mb-2 text-uppercase fw-bold">Status PDF Resmi</p>
                            @if($item->surat_pta)
                            <span class="badge rounded-pill bg-soft-success text-success px-3 py-2 border border-success">
                                <i class="fas fa-check-circle me-1"></i> Tersedia
                            </span>
                            @else
                            <span class="badge rounded-pill bg-soft-danger text-danger px-3 py-2 border border-danger">
                                <i class="fas fa-times-circle me-1"></i> Belum Ada
                            </span>
                            @endif
                        </div>
                        <div class="col-6">
                            <p class="text-xs text-muted mb-2 text-uppercase fw-bold">Status Konsep Word</p>
                            @if($item->konsep_surat)
                            <span class="badge rounded-pill bg-soft-primary text-primary px-3 py-2 border border-primary">
                                <i class="fas fa-file-word me-1"></i> Tersedia
                            </span>
                            @else
                            <span class="badge rounded-pill bg-soft-secondary text-muted px-3 py-2 border border-secondary">
                                <i class="fas fa-minus-circle me-1"></i> Kosong
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-xs text-muted text-uppercase fw-bold">Nomor Surat</label>
                                <div class="fw-bold text-dark border-bottom pb-1">{{ $item->no_surat }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="text-xs text-muted text-uppercase fw-bold">Tanggal Kirim</label>
                                <div class="text-dark border-bottom pb-1">
                                    <i class="far fa-calendar-check me-1 text-primary"></i>
                                    {{ \Carbon\Carbon::parse($item->tgl_surat)->translatedFormat('d F Y') }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-xs text-muted text-uppercase fw-bold">Instansi Tujuan</label>
                                <div class="fw-bold text-primary border-bottom pb-1 text-uppercase">
                                    <i class="fas fa-university me-1"></i> {{ $item->tujuan_surat }}
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="text-xs text-muted text-uppercase fw-bold">Perihal</label>
                                <div class="text-dark border-bottom pb-1">{{ $item->perihal }}</div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-3 rounded bg-light border-start border-4 border-info">
                                <label class="text-xs text-muted text-uppercase fw-bold d-block mb-1">Keterangan Tambahan / Catatan</label>
                                <p class="small m-0 text-muted fst-italic">
                                    {{ $item->keterangan ?? 'Tidak ada catatan tambahan untuk surat ini.' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light border-0 py-3 text-end">
                <button type="button" class="btn btn-secondary btn-sm rounded-pill px-4 shadow-sm" data-bs-dismiss="modal">Tutup Detail</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@push('scripts')
<script>
    function openModal(id) {
        var modal = new bootstrap.Modal(document.getElementById(id));
        modal.show();
    }
</script>
<style>
    .header-gradient {
        background: linear-gradient(135deg, #0f2027 0%, #2c5364 100%);
    }

    .bg-soft-light {
        background-color: #f8fafc;
    }

    .bg-soft-danger {
        background-color: #fff1f2;
    }

    .bg-soft-primary {
        background-color: #eff6ff;
    }

    .bg-soft-success {
        background-color: #f0fdf4;
    }

    .border-soft {
        border: 1px dashed #cbd5e1;
    }

    .attachment-pill {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 50rem;
        font-size: 0.7rem;
        font-weight: 700;
        text-decoration: none !important;
        border: 1px solid;
    }

    .hover-elevate-row:hover {
        background-color: #f8fafc;
        transform: scale(1.001);
        border-left: 4px solid #2c5364;
        transition: 0.2s ease-in-out;
    }

    .modern-modal {
        border-radius: 1rem;
        overflow: hidden;
    }

    .btn-close-white {
        filter: brightness(0) invert(1);
    }
</style>
@endpush
@endsection