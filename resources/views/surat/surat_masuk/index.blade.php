@extends('layouts.app')

@section('content')
<div class="container py-4 fade-in">
    <div class="card modern-card border-0">
        <div class="card-header header-gradient py-3 d-flex justify-content-between align-items-center border-0">
            <div class="d-flex align-items-center">
                <h5 class="m-0 font-weight-bold text-white tracking-wide">
                    <i class="fas fa-envelope-open-text me-2 opacity-75"></i> Arsip Surat Masuk
                </h5>
            </div>
            <a href="{{ route('surat.masuk.create') }}" class="btn btn-light btn-sm rounded-pill px-4 text-primary font-weight-bold shadow-sm hover-elevate">
                <i class="fas fa-plus me-1"></i> Tambah Baru
            </a>
        </div>

        <div class="card-body p-4 p-md-5">
            <form action="{{ route('surat.masuk.index') }}" method="GET" class="mb-4 bg-soft-light p-3 rounded-lg border-soft">
                <div class="row align-items-end">
                    <div class="col-md-4 mb-2 mb-md-0">
                        <label class="text-xs font-weight-bold text-uppercase text-muted mb-1">Pencarian</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-search"></i></span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="No. Surat / Perihal..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2 mb-2 mb-md-0">
                        <label class="text-xs font-weight-bold text-uppercase text-muted mb-1">Dari</label>
                        <input type="date" name="from_date" class="form-control form-control-sm modern-input shadow-sm" value="{{ request('from_date') }}">
                    </div>
                    <div class="col-md-2 mb-2 mb-md-0">
                        <label class="text-xs font-weight-bold text-uppercase text-muted mb-1">Sampai</label>
                        <input type="date" name="to_date" class="form-control form-control-sm modern-input shadow-sm" value="{{ request('to_date') }}">
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="btn-group w-100 shadow-sm rounded-pill overflow-hidden border">
                            <button type="submit" class="btn btn-primary btn-sm font-weight-bold">
                                <i class="fas fa-filter"></i> <span class="d-none d-lg-inline ms-1">Filter</span>
                            </button>
                            <a href="{{ route('surat.masuk.exportExcel', request()->query()) }}" target="_blank" class="btn btn-success btn-sm font-weight-bold">
                                <i class="fas fa-file-excel"></i> <span class="d-none d-lg-inline ms-1">Excel</span>
                            </a>
                            <a href="{{ route('surat.masuk.dashboard') }}" class="btn btn-info btn-sm font-weight-bold text-white">
                                <i class="fas fa-tachometer-alt"></i> <span class="d-none d-lg-inline ms-1">Dashboard</span>
                            </a>
                            <a href="{{ route('surat.masuk.index') }}" class="btn btn-light btn-sm text-muted font-weight-bold">
                                <i class="fas fa-sync-alt"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>

            @if(isset($isDefault) && $isDefault)
            <div class="alert bg-soft-primary border-0 shadow-sm rounded-pill px-4 py-2 mb-4 d-flex justify-content-between align-items-center">
                <span class="small font-weight-bold text-primary">
                    <i class="fas fa-bolt me-2 text-warning"></i> Menampilkan arsip tahun berjalan ({{ date('Y') }}).
                </span>
                <a href="{{ route('surat.masuk.index', ['all' => 'true']) }}" class="btn btn-xs btn-primary rounded-pill px-3 font-weight-bold shadow-sm" style="font-size: 0.7rem">Lihat Semua Data</a>
            </div>
            @endif

            <div class="table-responsive px-1">
                <table class="table modern-table align-middle">
                    <thead>
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th width="12%">Indeks</th>
                            <th width="25%">Informasi Surat</th>
                            <th width="25%">Asal & Perihal</th>
                            <th width="18%">Disposisi & File</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data_surat as $item)
                        <tr class="hover-elevate-row">
                            <td class="text-center text-muted font-weight-bold small">{{ ($data_surat->currentPage() - 1) * $data_surat->perPage() + $loop->iteration }}</td>
                            <td><span class="badge badge-soft-primary px-3 py-2 rounded-pill shadow-sm">#{{ $item->no_indeks }}</span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="icon-circle bg-soft-primary text-primary me-3 shadow-sm"><i class="fas fa-envelope"></i></div>
                                    <div>
                                        <div class="font-weight-bold text-dark mb-1 small">{{ $item->no_surat }}</div>
                                        <div class="text-xs text-muted"><i class="far fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($item->tgl_surat)->format('d M Y') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="font-weight-bold text-primary mb-1 small">{{ $item->asal_surat }}</div>
                                <div class="text-truncate text-muted text-xs" style="max-width: 200px;" title="{{ $item->perihal }}">{{ $item->perihal }}</div>
                            </td>
                            <td>
                                @if($item->disposisi)
                                <span class="badge badge-soft-danger px-2 py-1 mb-2 d-inline-block rounded shadow-xs text-xs"><i class="fas fa-share me-1"></i> {{ $item->disposisi }}</span>
                                @endif
                                <div class="mt-1">
                                    @if($item->lampiran)
                                    <a href="{{ route('surat.masuk.download', $item->id) }}" class="attachment-pill shadow-sm" target="_blank">
                                        <i class="fas fa-file-pdf me-1"></i> File <i class="fas fa-download ms-1"></i>
                                    </a>
                                    @else
                                    <span class="text-xs text-muted font-italic">Tidak ada file</span>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-light text-primary btn-sm rounded-circle shadow-sm mx-1 hover-elevate"
                                        onclick="openModal('detail{{ $item->id }}')" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <a href="{{ route('surat.masuk.edit', $item->id) }}" class="btn btn-light text-warning btn-sm rounded-circle shadow-sm mx-1 hover-elevate" title="Edit">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>

                                    <button type="button" class="btn btn-light text-danger btn-sm rounded-circle shadow-sm mx-1 hover-elevate"
                                        onclick="confirmDelete('{{ $item->id }}')" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>

                                <form id="delete-form-{{ $item->id }}" action="{{ route('surat.masuk.destroy', $item->id) }}" method="POST" style="display: none;">
                                    @csrf @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted small"><i class="fas fa-folder-open fa-3x mb-3 text-light"></i><br>Data tidak ditemukan.</div>
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
                    <p class="text-sm text-muted m-0 small">
                        Menampilkan <span class="font-weight-bold text-primary">{{ $data_surat->firstItem() ?? 0 }}</span> - <span class="font-weight-bold text-primary">{{ $data_surat->lastItem() ?? 0 }}</span> dari <span class="font-weight-bold text-primary">{{ $data_surat->total() }}</span> entri
                    </p>
                </div>
                <div class="col-md-6 d-flex justify-content-center justify-content-md-end">
                    {{ $data_surat->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>

@foreach($data_surat as $item)
<div class="modal fade" id="detail{{ $item->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content modern-modal border-0 shadow-lg">
            <div class="modal-header header-gradient text-white border-0">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-info-circle me-2"></i> Detail Arsip Surat
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="p-4 bg-soft-light border-bottom">
                    <div class="row text-center g-0">
                        <div class="col-3 border-end">
                            <p class="text-xs text-muted mb-1 text-uppercase">Indeks</p>
                            <h6 class="font-weight-bold text-primary mb-0">#{{ $item->no_indeks }}</h6>
                        </div>
                        <div class="col-3 border-end text-dark">
                            <p class="text-xs text-muted mb-1 text-uppercase">Tgl Surat</p>
                            <h6 class="font-weight-bold mb-0 small">{{ \Carbon\Carbon::parse($item->tgl_surat)->format('d/m/Y') }}</h6>
                        </div>
                        <div class="col-3 border-end">
                            <p class="text-xs text-muted mb-1 text-uppercase">Masuk Umum</p>
                            <h6 class="font-weight-bold mb-0 small text-dark">{{ $item->tgl_masuk_umum ?? '-' }}</h6>
                        </div>
                        <div class="col-3">
                            <p class="text-xs text-muted mb-1 text-uppercase">Lampiran</p>
                            <span class="badge bg-{{ $item->lampiran ? 'success' : 'secondary' }} rounded-pill small px-2">
                                {{ $item->lampiran ? 'Ada' : 'Kosong' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="p-4 text-dark">
                    <div class="row mb-3 pb-2 border-bottom">
                        <div class="col-4 fw-bold text-muted text-uppercase small">No. Surat</div>
                        <div class="col-8 fw-bold">{{ $item->no_surat }}</div>
                    </div>
                    <div class="row mb-3 pb-2 border-bottom">
                        <div class="col-4 fw-bold text-muted text-uppercase small">Asal Surat</div>
                        <div class="col-8">{{ $item->asal_surat }}</div>
                    </div>
                    <div class="row mb-3 pb-2 border-bottom">
                        <div class="col-4 fw-bold text-danger text-uppercase small">Disposisi</div>
                        <div class="col-8 fw-bold text-danger">{{ $item->disposisi ?? 'Belum ada' }}</div>
                    </div>
                    <div class="row mb-3 pb-2 border-bottom">
                        <div class="col-4 fw-bold text-muted text-uppercase small">Perihal</div>
                        <div class="col-8">{{ $item->perihal }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4 fw-bold text-muted text-uppercase small">Keterangan</div>
                        <div class="col-8 text-muted fst-italic small">{{ $item->keterangan ?? '-' }}</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                @if($item->lampiran)
                <a href="{{ route('surat.masuk.download', $item->id) }}" class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm me-auto" target="_blank">
                    <i class="fas fa-download me-1"></i> Download File
                </a>
                @endif
                <button type="button" class="btn btn-secondary btn-sm rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@push('scripts')
<script>
    function openModal(modalId) {
        const modalElement = document.getElementById(modalId);
        if (modalElement) {
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        }
    }
</script>

<style>
    .fade-in {
        animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modern-card {
        border-radius: 1rem;
        box-shadow: 0 0.5rem 2rem rgba(0, 0, 0, 0.08);
    }

    .header-gradient {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    }

    .bg-soft-light {
        background-color: #f8fafc;
    }

    .border-soft {
        border: 1px dashed #cbd5e1;
    }

    .modern-input {
        border-radius: 0.5rem;
        border: 1px solid #e2e8f0;
    }

    .modern-table th {
        background-color: #f8f9fc;
        color: #5a5c69;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.7rem;
        padding: 1rem;
        border-bottom: 2px solid #e3e6f0;
    }

    .hover-elevate-row:hover {
        background-color: #fdfdfe;
        transform: scale(1.001);
        border-left: 3px solid #4e73df;
    }

    .icon-circle {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .bg-soft-primary,
    .badge-soft-primary {
        background-color: #e0e7ff;
        color: #4e73df;
    }

    .badge-soft-danger {
        background-color: #fee2e2;
        color: #dc2626;
    }

    .attachment-pill {
        display: inline-flex;
        align-items: center;
        padding: 0.2rem 0.8rem;
        background: #ecfdf5;
        color: #059669;
        border-radius: 50rem;
        font-size: 0.7rem;
        font-weight: 700;
        text-decoration: none !important;
        border: 1px solid #a7f3d0;
    }

    .modern-modal {
        border-radius: 1rem;
        overflow: hidden;
    }

    .text-xs {
        font-size: 0.75rem;
    }

    .hover-elevate:hover {
        transform: translateY(-2px);
        transition: 0.2s;
    }

    .btn-close-white {
        filter: brightness(0) invert(1);
    }

    .border-end {
        border-right: 1px solid #dee2e6;
    }
</style>
@endpush
@endsection