@extends('layouts.app')

@section('content')
<div class="container py-4 fade-in">
    <div class="card modern-card border-0">
        <div class="card-header header-gradient py-3 d-flex justify-content-between align-items-center border-0">
            <div class="d-flex align-items-center">
                <h5 class="m-0 font-weight-bold text-white tracking-wide">
                    <i class="fas fa-envelope-open-text me-2 opacity-75"></i> Arsip Surat Masuk
                </h5>
                <span class="badge bg-white text-primary ms-3 rounded-pill px-3">{{ $data_surat->total() }} Total</span>
            </div>
            <a href="{{ route('surat.masuk.create') }}" class="btn btn-light btn-sm rounded-pill px-4 text-primary font-weight-bold shadow-sm hover-elevate">
                <i class="fas fa-plus me-1"></i> <span class="d-none d-lg-inline">Tambah Baru</span>
            </a>
        </div>

        <div class="card-body p-4 p-md-5">
            <!-- Filter Form -->
            <form action="{{ route('surat.masuk.index') }}" method="GET" class="mb-4 bg-soft-light p-3 rounded-lg border-soft">
                <div class="row align-items-end g-2">
                    <div class="col-md-3 mb-2 mb-md-0">
                        <label class="text-xs font-weight-bold text-uppercase text-muted mb-1">
                            <i class="fas fa-search me-1"></i> Pencarian
                        </label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-search"></i></span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0 modern-input" placeholder="No. Surat / Perihal / Asal..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2 mb-2 mb-md-0">
                        <label class="text-xs font-weight-bold text-uppercase text-muted mb-1">
                            <i class="fas fa-calendar-alt me-1"></i> Dari Tanggal
                        </label>
                        <input type="date" name="from_date" class="form-control form-control-sm modern-input shadow-sm" value="{{ request('from_date') }}">
                    </div>
                    <div class="col-md-2 mb-2 mb-md-0">
                        <label class="text-xs font-weight-bold text-uppercase text-muted mb-1">
                            <i class="fas fa-calendar-alt me-1"></i> Sampai Tanggal
                        </label>
                        <input type="date" name="to_date" class="form-control form-control-sm modern-input shadow-sm" value="{{ request('to_date') }}">
                    </div>
                    <div class="col-md-5">
                        <div class="d-flex gap-2 justify-content-md-end">
                            <button type="submit" class="btn btn-primary btn-sm font-weight-bold rounded-pill px-4 shadow-sm">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <a href="{{ route('surat.masuk.exportExcel', request()->query()) }}" class="btn btn-success btn-sm font-weight-bold rounded-pill px-3 shadow-sm">
                                <i class="fas fa-file-excel me-1"></i> Excel
                            </a>
                            <a href="{{ route('surat.masuk.dashboard') }}" class="btn btn-info btn-sm font-weight-bold text-white rounded-pill px-3 shadow-sm">
                                <i class="fas fa-chart-line me-1"></i> Dashboard
                            </a>
                            <a href="{{ route('surat.masuk.index') }}" class="btn btn-secondary btn-sm rounded-pill px-3 shadow-sm" title="Reset Filter">
                                <i class="fas fa-sync-alt"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>

            @if(isset($isDefault) && $isDefault)
            <div class="alert bg-soft-primary border-0 shadow-sm rounded-pill px-4 py-2 mb-4 d-flex justify-content-between align-items-center flex-wrap">
                <div class="small font-weight-bold text-primary">
                    <i class="fas fa-bolt me-2 text-warning"></i> Menampilkan arsip tahun berjalan ({{ date('Y') }}).
                </div>
                <a href="{{ route('surat.masuk.index', ['all' => 'true'] + request()->except('all')) }}" class="btn btn-xs btn-primary rounded-pill px-3 font-weight-bold shadow-sm mt-2 mt-sm-0" style="font-size: 0.7rem">
                    <i class="fas fa-database me-1"></i> Lihat Semua Data
                </a>
            </div>
            @endif

            <div class="table-responsive px-1">
                <table class="table modern-table align-middle">
                    <thead>
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th width="10%">Indeks</th>
                            <th width="20%">Nomor Surat</th>
                            <th width="15%">Tanggal Surat</th>
                            <th width="20%">Asal Instansi</th>
                            <th width="15%">Perihal</th>
                            <th width="10%">Disposisi</th>
                            <th width="5%" class="text-center">File</th>
                            <th width="10%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data_surat as $item)
                        <tr class="hover-elevate-row">
                            <td class="text-center text-muted font-weight-bold small">
                                {{ ($data_surat->currentPage() - 1) * $data_surat->perPage() + $loop->iteration }}
                            </td>
                            <td>
                                <span class="badge badge-soft-primary px-3 py-2 rounded-pill shadow-sm fw-bold">
                                    #{{ $item->no_indeks }}
                                </span>
                            </td>
                            <td>
                                <div class="font-weight-bold text-dark mb-1 small">{{ $item->no_surat }}</div>
                            </td>
                            <td>
                                <div class="text-sm">
                                    <i class="far fa-calendar-alt text-muted me-1"></i>
                                    {{ \Carbon\Carbon::parse($item->tgl_surat)->translatedFormat('d M Y') }}
                                </div>
                                <div class="text-xs text-muted mt-1">
                                    <i class="fas fa-inbox me-1"></i> Masuk: {{ \Carbon\Carbon::parse($item->tgl_masuk_pan)->translatedFormat('d/m/Y') }}
                                </div>
                            </td>
                            <td>
                                <div class="font-weight-bold text-primary mb-1 small">{{ $item->asal_surat }}</div>
                            </td>
                            <td>
                                <div class="text-truncate text-muted text-sm" style="max-width: 180px;" title="{{ $item->perihal }}">
                                    {{ Str::limit($item->perihal, 40) }}
                                </div>
                            </td>
                            <td>
                                @if($item->disposisi)
                                <span class="badge badge-soft-danger px-2 py-1 rounded-pill shadow-xs text-xs">
                                    <i class="fas fa-share me-1"></i> {{ Str::limit($item->disposisi, 15) }}
                                </span>
                                @else
                                <span class="text-muted text-xs">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($item->lampiran)
                                <a href="{{ route('surat.masuk.download', $item->id) }}" class="attachment-pill shadow-sm" target="_blank" title="Download File">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-light text-primary btn-sm rounded-circle shadow-sm mx-1 hover-elevate"
                                        onclick="openModal('detail{{ $item->id }}')" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <a href="{{ route('surat.masuk.edit', $item->id) }}" class="btn btn-light text-warning btn-sm rounded-circle shadow-sm mx-1 hover-elevate" title="Edit">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>

                                    <button type="button" class="btn btn-light text-danger btn-sm rounded-circle shadow-sm mx-1 hover-elevate"
                                        onclick="confirmDelete('{{ $item->id }}', '{{ $item->no_surat }}')" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>

                                <form id="delete-form-{{ $item->id }}" action="{{ route('surat.masuk.destroy', $item->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-folder-open fa-3x mb-3 text-light"></i>
                                    <p class="mb-0">Data tidak ditemukan.</p>
                                    <a href="{{ route('surat.masuk.create') }}" class="btn btn-sm btn-primary rounded-pill mt-3">
                                        <i class="fas fa-plus me-1"></i> Tambah Surat
                                    </a>
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
                    <p class="text-sm text-muted m-0 small">
                        <i class="fas fa-chart-line me-1"></i>
                        Menampilkan <span class="font-weight-bold text-primary">{{ $data_surat->firstItem() ?? 0 }}</span>
                        - <span class="font-weight-bold text-primary">{{ $data_surat->lastItem() ?? 0 }}</span>
                        dari <span class="font-weight-bold text-primary">{{ $data_surat->total() }}</span> entri
                    </p>
                </div>
                <div class="col-md-6 d-flex justify-content-center justify-content-md-end">
                    {{ $data_surat->appends(request()->query())->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail -->
@foreach($data_surat as $item)
<div class="modal fade" id="detail{{ $item->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content modern-modal border-0 shadow-lg">
            <div class="modal-header header-gradient text-white border-0 py-3">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-white bg-opacity-25 p-2 me-3">
                        <i class="fas fa-envelope-open-text fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-bold mb-0">Detail Arsip Surat Masuk</h5>
                        <small class="text-white-50">{{ $item->no_surat }}</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-0">
                <!-- Badge Status -->
                <div class="p-4 bg-gradient-soft border-bottom">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div class="mb-2 mb-md-0">
                            <span class="badge badge-soft-primary rounded-pill px-3 py-2 me-2">
                                <i class="fas fa-hashtag me-1"></i> Indeks: #{{ $item->no_indeks }}
                            </span>
                            @if($item->lampiran)
                            <span class="badge badge-soft-success rounded-pill px-3 py-2 me-2">
                                <i class="fas fa-paperclip me-1"></i> Memiliki Lampiran
                            </span>
                            @else
                            <span class="badge badge-soft-secondary rounded-pill px-3 py-2 me-2">
                                <i class="fas fa-ban me-1"></i> Tanpa Lampiran
                            </span>
                            @endif
                            @if($item->created_at != $item->updated_at)
                            <span class="badge badge-soft-info rounded-pill px-3 py-2">
                                <i class="fas fa-edit me-1"></i> Telah Diupdate
                            </span>
                            @endif
                        </div>
                        <div class="text-muted small">
                            <i class="fas fa-user-circle me-1"></i>
                            Dibuat oleh: <strong>{{ $item->creator ? $item->creator->name : 'System' }}</strong>
                            <br class="d-md-none">
                            <i class="fas fa-clock ms-md-2 me-1"></i>
                            {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y H:i') }}
                            @if($item->created_at != $item->updated_at)
                            <br class="d-md-none">
                            <i class="fas fa-edit ms-md-2 me-1"></i>
                            Update: {{ \Carbon\Carbon::parse($item->updated_at)->translatedFormat('d F Y H:i') }}
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Grid Info Cards -->
                <div class="p-4 bg-light border-bottom">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="info-card text-center p-3 rounded-3">
                                <div class="info-icon bg-primary-soft rounded-circle mx-auto mb-2">
                                    <i class="fas fa-calendar-alt text-primary"></i>
                                </div>
                                <span class="text-muted text-xs text-uppercase d-block">Tanggal Surat</span>
                                <strong class="text-dark">{{ \Carbon\Carbon::parse($item->tgl_surat)->translatedFormat('d F Y') }}</strong>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-card text-center p-3 rounded-3">
                                <div class="info-icon bg-info-soft rounded-circle mx-auto mb-2">
                                    <i class="fas fa-inbox text-info"></i>
                                </div>
                                <span class="text-muted text-xs text-uppercase d-block">Masuk Bag. Umum</span>
                                <strong class="text-dark">{{ \Carbon\Carbon::parse($item->tgl_masuk_umum)->translatedFormat('d F Y') }}</strong>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-card text-center p-3 rounded-3">
                                <div class="info-icon bg-success-soft rounded-circle mx-auto mb-2">
                                    <i class="fas fa-archive text-success"></i>
                                </div>
                                <span class="text-muted text-xs text-uppercase d-block">Masuk Kepaniteraan</span>
                                <strong class="text-dark">{{ \Carbon\Carbon::parse($item->tgl_masuk_pan)->translatedFormat('d F Y') }}</strong>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-card text-center p-3 rounded-3">
                                <div class="info-icon bg-secondary-soft rounded-circle mx-auto mb-2">
                                    <i class="fas fa-edit text-secondary"></i>
                                </div>
                                <span class="text-muted text-xs text-uppercase d-block">Terakhir Update</span>
                                <strong class="text-dark">{{ $item->updater ? $item->updater->name : 'System' }}</strong>
                                <small class="text-muted d-block mt-1">
                                    <i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($item->updated_at)->translatedFormat('d/m/Y H:i') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail Content -->
                <div class="p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="detail-section">
                                <h6 class="section-title mb-3">
                                    <i class="fas fa-file-alt text-primary me-2"></i> Informasi Surat
                                </h6>
                                <div class="detail-item">
                                    <label class="detail-label">Nomor Surat</label>
                                    <div class="detail-value fw-bold">{{ $item->no_surat }}</div>
                                </div>
                                <div class="detail-item">
                                    <label class="detail-label">Asal Instansi</label>
                                    <div class="detail-value">{{ $item->asal_surat }}</div>
                                </div>
                                <div class="detail-item">
                                    <label class="detail-label">Perihal</label>
                                    <div class="detail-value p-3 bg-light rounded-3">
                                        {{ $item->perihal }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="detail-section">
                                <h6 class="section-title mb-3">
                                    <i class="fas fa-share-alt text-danger me-2"></i> Disposisi & Keterangan
                                </h6>
                                <div class="detail-item">
                                    <label class="detail-label">Tujuan Disposisi</label>
                                    <div class="detail-value">
                                        <span class="badge badge-soft-danger rounded-pill px-3 py-2">
                                            <i class="fas fa-user-check me-1"></i> {{ $item->disposisi ?? 'Belum ada disposisi' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="detail-item">
                                    <label class="detail-label">Keterangan</label>
                                    <div class="detail-value text-muted fst-italic">
                                        {{ $item->keterangan ?? '-' }}
                                    </div>
                                </div>
                                @if($item->lampiran)
                                <div class="detail-item">
                                    <label class="detail-label">Lampiran File</label>
                                    <div class="detail-value">
                                        <div class="file-info p-3 bg-soft-primary rounded-3">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div>
                                                    <i class="fas fa-file-pdf fa-2x text-danger me-3"></i>
                                                    <span class="font-weight-bold">{{ $item->lampiran }}</span>
                                                </div>
                                                <a href="{{ route('surat.masuk.download', $item->id) }}" class="btn btn-sm btn-primary rounded-pill px-3" target="_blank">
                                                    <i class="fas fa-download me-1"></i> Download
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light border-0 py-3">
                <div class="d-flex gap-2 w-100 justify-content-end">
                    <button type="button" class="btn btn-secondary btn-sm rounded-pill px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Tutup
                    </button>
                    <a href="{{ route('surat.masuk.edit', $item->id) }}" class="btn btn-warning btn-sm rounded-pill px-4">
                        <i class="fas fa-edit me-1"></i> Edit Surat
                    </a>
                    @if($item->lampiran)
                    <a href="{{ route('surat.masuk.download', $item->id) }}" class="btn btn-primary btn-sm rounded-pill px-4" target="_blank">
                        <i class="fas fa-download me-1"></i> Download Lampiran
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function openModal(modalId) {
        const modalElement = document.getElementById(modalId);
        if (modalElement) {
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        }
    }

    function confirmDelete(id, noSurat) {
        Swal.fire({
            title: 'Hapus Arsip Surat?',
            html: `Anda akan menghapus surat <strong>${noSurat}</strong><br>Data yang dihapus tidak dapat dikembalikan!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash me-1"></i> Ya, Hapus!',
            cancelButtonText: '<i class="fas fa-times me-1"></i> Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${id}`).submit();
            }
        });
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
        overflow: hidden;
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
        transition: all 0.2s;
    }

    .modern-input:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.1);
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

    .modern-table td {
        vertical-align: middle;
    }

    .hover-elevate-row {
        transition: all 0.2s ease;
    }

    .hover-elevate-row:hover {
        background-color: #fdfdfe;
        transform: scale(1.001);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .hover-elevate-row:hover td:first-child {
        border-left: 3px solid #4e73df;
    }

    .attachment-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        background: #ecfdf5;
        color: #059669;
        border-radius: 50%;
        font-size: 0.9rem;
        font-weight: 700;
        text-decoration: none !important;
        transition: all 0.2s;
    }

    .attachment-pill:hover {
        background: #059669;
        color: white;
        transform: translateY(-2px);
    }

    .modern-modal {
        border-radius: 1rem;
        overflow: hidden;
    }

    .text-xs {
        font-size: 0.7rem;
    }

    .text-sm {
        font-size: 0.8rem;
    }

    .hover-elevate:hover {
        transform: translateY(-2px);
        transition: 0.2s;
    }

    .btn-close-white {
        filter: brightness(0) invert(1);
    }

    /* Modal Detail Styles */
    .info-card {
        background: white;
        border: 1px solid #e3e6f0;
        transition: all 0.3s ease;
    }

    .info-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    }

    .info-icon {
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .bg-primary-soft {
        background: rgba(78, 115, 223, 0.1);
    }

    .bg-info-soft {
        background: rgba(0, 136, 123, 0.1);
    }

    .bg-success-soft {
        background: rgba(46, 125, 50, 0.1);
    }

    .bg-warning-soft {
        background: rgba(246, 194, 62, 0.1);
    }

    .bg-secondary-soft {
        background: rgba(108, 117, 125, 0.1);
    }

    .bg-gradient-soft {
        background: linear-gradient(135deg, #f8f9fc 0%, #f1f4f9 100%);
    }

    .detail-section {
        margin-bottom: 1.5rem;
    }

    .section-title {
        font-size: 0.85rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #4e73df;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e3e6f0;
    }

    .detail-item {
        margin-bottom: 1rem;
    }

    .detail-label {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #858796;
        display: block;
        margin-bottom: 0.25rem;
    }

    .detail-value {
        font-size: 0.9rem;
        color: #2c3e50;
    }

    .bg-soft-primary {
        background-color: #e8ebf8;
    }

    .badge-soft-primary {
        background-color: #e0e7ff;
        color: #4e73df;
    }

    .badge-soft-danger {
        background-color: #fee2e2;
        color: #dc2626;
    }

    .badge-soft-success {
        background-color: #d1fae5;
        color: #059669;
    }

    .badge-soft-secondary {
        background-color: #e2e3e5;
        color: #6c757d;
    }

    .badge-soft-info {
        background-color: #cff4fc;
        color: #0dcaf0;
    }

    .file-info {
        background: #f8f9fc;
        border: 1px solid #e3e6f0;
        transition: all 0.2s;
    }

    .file-info:hover {
        background: #e8ebf8;
        border-color: #4e73df;
    }

    .gap-2 {
        gap: 0.5rem;
    }

    .rounded-3 {
        border-radius: 0.75rem !important;
    }

    @media (max-width: 768px) {
        .modal-dialog {
            margin: 1rem;
        }

        .info-card {
            margin-bottom: 0.5rem;
        }

        .detail-item {
            margin-bottom: 0.75rem;
        }
    }
</style>
@endpush
@endsection