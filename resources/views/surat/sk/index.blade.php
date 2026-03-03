@extends('layouts.app')

@section('content')
<div class="container py-4 fade-in">
    <div class="card modern-card border-0">
        <div class="card-header header-gradient py-3 d-flex justify-content-between align-items-center border-0">
            <div class="d-flex align-items-center">
                <h5 class="m-0 font-weight-bold text-white tracking-wide">
                    <i class="fas fa-file-signature me-2 opacity-75"></i> Arsip Surat Keputusan (SK)
                </h5>
            </div>
            <a href="{{ route('sk.create') }}" class="btn btn-light btn-sm rounded-pill px-4 text-primary font-weight-bold shadow-sm hover-elevate">
                <i class="fas fa-plus me-1"></i> Tambah Baru
            </a>
        </div>

        <div class="card-body p-4 p-md-5">
            <form action="{{ route('sk.index') }}" method="GET" class="mb-4 bg-soft-light p-3 rounded-lg border-soft">
                <div class="row align-items-end">
                    <div class="col-md-4 mb-2 mb-md-0">
                        <label class="text-xs font-weight-bold text-uppercase text-muted mb-1">Pencarian</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-search"></i></span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="No. SK / Tentang SK..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2 mb-2 mb-md-0">
                        <label class="text-xs font-weight-bold text-uppercase text-muted mb-1">Dari</label>
                        <input type="date" name="from_date" class="form-control form-control-sm modern-input shadow-sm" value="{{ $startDate }}">
                    </div>
                    <div class="col-md-2 mb-2 mb-md-0">
                        <label class="text-xs font-weight-bold text-uppercase text-muted mb-1">Sampai</label>
                        <input type="date" name="to_date" class="form-control form-control-sm modern-input shadow-sm" value="{{ $endDate }}">
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="btn-group w-100 shadow-sm rounded-pill overflow-hidden border">
                            <button type="submit" class="btn btn-primary btn-sm font-weight-bold">
                                <i class="fas fa-filter"></i> <span class="d-none d-lg-inline ms-1">Filter</span>
                            </button>

                            <a href="{{ route('sk.exportExcel', request()->query()) }}" target="_blank" class="btn btn-success btn-sm font-weight-bold">
                                <i class="fas fa-file-excel"></i> <span class="d-none d-lg-inline ms-1">Excel</span>
                            </a>

                            <a href="{{ route('sk.dashboard') }}" class="btn btn-info btn-sm font-weight-bold text-white">
                                <i class="fas fa-tachometer-alt"></i> <span class="d-none d-lg-inline ms-1">Dashboard</span>
                            </a>
                            <a href="{{ route('sk.index') }}" class="btn btn-light btn-sm text-muted font-weight-bold">
                                <i class="fas fa-sync-alt"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>

            @if(isset($isDefault) && $isDefault)
            <div class="alert bg-soft-primary border-0 shadow-sm rounded-pill px-4 py-2 mb-4 d-flex justify-content-between align-items-center">
                <span class="small font-weight-bold text-primary">
                    <i class="fas fa-bolt me-2 text-warning"></i> Menampilkan arsip SK tahun berjalan ({{ date('Y') }}).
                </span>
                <form action="{{ route('sk.index') }}" method="GET" class="m-0">
                    <input type="hidden" name="from_date" value="1900-01-01">
                    <input type="hidden" name="to_date" value="{{ date('Y-m-d') }}">
                    <button type="submit" class="btn btn-xs btn-primary rounded-pill px-3 font-weight-bold shadow-sm" style="font-size: 0.7rem">Lihat Semua Data</button>
                </form>
            </div>
            @endif

            <div class="table-responsive px-1">
                <table class="table modern-table align-middle">
                    <thead>
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th width="20%">Nomor SK</th>
                            <th width="30%">Tentang / Perihal</th>
                            <th width="12%">Tanggal SK</th>
                            <th width="18%" class="text-center">Berkas SK</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $item)
                        <tr class="hover-elevate-row">
                            <td class="text-center text-muted font-weight-bold small">{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="icon-circle bg-soft-primary text-primary me-3 shadow-sm"><i class="fas fa-file-alt"></i></div>
                                    <div>
                                        <div class="font-weight-bold text-dark mb-0 small">{{ $item->no_sk }}</div>
                                        <span class="badge badge-soft-primary rounded-pill px-2 mt-1" style="font-size: 0.65rem">Tahun {{ $item->tahun }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-dark small font-weight-bold lh-base" style="max-width: 300px;">{{ Str::limit($item->tentang, 100) }}</div>
                            </td>
                            <td>
                                <div class="text-xs text-muted fw-bold"><i class="far fa-calendar-alt me-1 text-primary"></i> {{ \Carbon\Carbon::parse($item->tgl_sk)->format('d/m/Y') }}</div>
                            </td>
                            <td class="text-center">
                                <div class="d-flex flex-column align-items-center gap-1">
                                    @if($item->dokumen)
                                    <a href="{{ route('sk.download', [$item->id, 'resmi']) }}" class="attachment-pill resmi shadow-sm" target="_blank">
                                        <i class="fas fa-file-pdf me-1"></i> Resmi <i class="fas fa-download ms-1"></i>
                                    </a>
                                    @endif
                                    @if($item->konsep_sk)
                                    <a href="{{ route('sk.download', [$item->id, 'konsep']) }}" class="attachment-pill konsep shadow-sm" target="_blank">
                                        <i class="fas fa-file-word me-1"></i> Konsep <i class="fas fa-download ms-1"></i>
                                    </a>
                                    @endif
                                    @if(!$item->dokumen && !$item->konsep_sk)
                                    <span class="text-xs text-muted font-italic small">Tidak ada berkas</span>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="btn-group shadow-sm rounded-pill overflow-hidden border">
                                    <button type="button" class="btn btn-light text-primary btn-sm border-end hover-elevate"
                                        onclick="openModal('detail{{ $item->id }}')" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="{{ route('sk.edit', $item->id) }}" class="btn btn-light text-warning btn-sm border-end hover-elevate" title="Edit">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <button type="button" class="btn btn-light text-danger btn-sm hover-elevate"
                                        onclick="confirmDelete('{{ $item->id }}')" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <form id="delete-form-{{ $item->id }}" action="{{ route('sk.destroy', $item->id) }}" method="POST" style="display: none;">
                                    @csrf @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted small"><i class="fas fa-folder-open fa-3x mb-3 text-light"></i><br>Data SK tidak ditemukan.</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-top py-3 px-4">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0 small text-muted">
                    Menampilkan <span class="fw-bold text-primary">{{ $data->firstItem() ?? 0 }}</span> - <span class="fw-bold text-primary">{{ $data->lastItem() ?? 0 }}</span> dari <span class="fw-bold text-primary">{{ $data->total() }}</span> entri
                </div>
                <div class="col-md-6 d-flex justify-content-center justify-content-md-end">
                    {{ $data->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>

@foreach($data as $item)
<div class="modal fade" id="detail{{ $item->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content modern-modal border-0 shadow-lg">
            <div class="modal-header header-gradient text-white border-0">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-info-circle me-2"></i> Detail Arsip SK</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="p-4 bg-soft-light border-bottom text-center">
                    <div class="row g-0">
                        <div class="col-6 border-end">
                            <p class="text-xs text-muted mb-1 text-uppercase fw-bold">Nomor SK</p>
                            <h6 class="font-weight-bold text-primary mb-0 small">{{ $item->no_sk }}</h6>
                        </div>
                        <div class="col-6">
                            <p class="text-xs text-muted mb-1 text-uppercase fw-bold">Tanggal SK</p>
                            <h6 class="font-weight-bold text-dark mb-0 small">{{ \Carbon\Carbon::parse($item->tgl_sk)->format('d F Y') }}</h6>
                        </div>
                    </div>
                </div>
                <div class="p-4 text-dark">
                    <div class="mb-3">
                        <label class="text-xs font-weight-bold text-uppercase text-muted mb-1">Tentang / Perihal SK</label>
                        <p class="small text-dark fw-bold border-bottom pb-2 text-justify">{{ $item->tentang }}</p>
                    </div>
                    <div class="row">
                        <div class="col-md-6 border-end text-center">
                            <label class="text-xs font-weight-bold text-uppercase text-muted mb-1 d-block">SK Resmi (PDF)</label>
                            <div class="mt-2">
                                @if($item->dokumen)
                                <a href="{{ route('sk.download', [$item->id, 'resmi']) }}" class="attachment-pill resmi px-3 py-2 shadow-sm" target="_blank">
                                    <i class="fas fa-file-pdf me-1"></i> Download PDF
                                </a>
                                @else
                                <span class="text-xs text-muted font-italic"><i class="fas fa-times-circle me-1"></i> Tidak tersedia</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6 text-center">
                            <label class="text-xs font-weight-bold text-uppercase text-muted mb-1 d-block">Konsep (DOCX)</label>
                            <div class="mt-2">
                                @if($item->konsep_sk)
                                <a href="{{ route('sk.download', [$item->id, 'konsep']) }}" class="attachment-pill konsep px-3 py-2 shadow-sm" target="_blank">
                                    <i class="fas fa-file-word me-1"></i> Download Word
                                </a>
                                @else
                                <span class="text-xs text-muted font-italic"><i class="fas fa-times-circle me-1"></i> Tidak tersedia</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-secondary btn-sm rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach

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
        border-left: 4px solid #4e73df;
    }

    .icon-circle {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .bg-soft-primary {
        background-color: #e0e7ff;
        color: #4e73df;
    }

    .badge-soft-primary {
        background-color: #e0e7ff;
        color: #4e73df;
    }

    .attachment-pill {
        display: inline-flex;
        align-items: center;
        padding: 0.2rem 0.8rem;
        border-radius: 50rem;
        font-size: 0.65rem;
        font-weight: 700;
        text-decoration: none !important;
        border: 1px solid;
        transition: 0.2s;
        width: 100%;
        max-width: 120px;
        justify-content: center;
    }

    .attachment-pill.resmi {
        background: #fee2e2;
        color: #dc2626;
        border-color: #fecaca;
    }

    .attachment-pill.konsep {
        background: #dbeafe;
        color: #2563eb;
        border-color: #bfdbfe;
    }

    .attachment-pill:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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

<script>
    function openModal(id) {
        new bootstrap.Modal(document.getElementById(id)).show();
    }

    function confirmDelete(id) {
        if (confirm('Apakah Bapak yakin ingin menghapus arsip SK ini secara permanen?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endsection