@extends('layouts.app')

@push('styles')
<style>
    .clickable-card {
        transition: all 0.3s ease;
        cursor: pointer;
        border-bottom: 3px solid transparent;
    }

    .clickable-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }

    .active-filter-sudah {
        border: 2px solid #28a745 !important;
        background-color: #f8fff9;
    }

    .active-filter-belum {
        border: 2px solid #dc3545 !important;
        background-color: #fff8f8;
    }

    .sticky-sidebar {
        position: sticky;
        top: 90px;
        z-index: 100;
        height: fit-content;
    }

    .bg-soft-primary {
        background-color: rgba(78, 115, 223, 0.1);
    }

    /* Badge Custom untuk Jenis Perkara */
    .badge-perkara {
        background-color: #e9ecef;
        color: #495057;
        font-weight: 600;
        font-size: 0.7rem;
        text-transform: uppercase;
    }

    /* Pagination Fix */
    .pagination-wrapper {
        margin-top: 1.5rem;
        display: flex;
        justify-content: center;
    }

    .pagination-wrapper .pagination {
        flex-wrap: wrap;
        gap: 4px;
    }

    .table-responsive-custom {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        border-radius: 15px;
    }

    @media (max-width: 768px) {
        .table-responsive-custom table {
            white-space: nowrap;
        }

        .sticky-sidebar {
            position: static;
            margin-bottom: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4 px-md-5">
    {{-- Header Section --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 border-bottom pb-3">
        <div class="d-flex align-items-center mb-3 mb-md-0">
            <i class="fas fa-university fa-3x me-3 text-primary"></i>
            <div>
                <h3 class="fw-bold text-dark mb-0 text-uppercase">Bank Putusan Digital</h3>
                <p class="text-primary fw-bold small mb-0">DATABASE DOKUMEN PUTUSAN & ANONIMASI PTA BANDUNG</p>
            </div>
        </div>
        <div class="bg-white p-2 rounded-pill shadow-sm border px-3">
            <form action="{{ route('bank.index') }}" method="GET" class="d-flex align-items-center gap-2">
                <input type="date" name="tgl_awal" class="form-control form-control-sm border-0 bg-light rounded-pill" value="{{ $tgl_awal }}">
                <span class="text-muted small">s.d</span>
                <input type="date" name="tgl_akhir" class="form-control form-control-sm border-0 bg-light rounded-pill" value="{{ $tgl_akhir }}">
                <button type="submit" class="btn btn-sm btn-primary px-3 rounded-pill">Filter</button>
            </form>
        </div>
    </div>

    {{-- Statistik Cards --}}
    <div class="row g-3 mb-4">
        @foreach (['sudah' => 'success', 'belum' => 'danger'] as $status => $color)
        <div class="col-md-6">
            <div class="card border-0 shadow-sm p-4 rounded-4 clickable-card {{ request('status_upload') == $status ? 'active-filter-'.$status : '' }}"
                onclick="window.location.href='{{ route('bank.index', array_merge(request()->query(), ['status_upload' => $status])) }}'"
                style="border-left: 5px solid var(--bs-{{ $color }}) !important;">
                <div class="d-flex justify-content-between align-items-start text-uppercase">
                    <div>
                        <small class="text-muted fw-bold d-block mb-1">{{ $status }} Upload</small>
                        <h2 class="mb-0 fw-bold text-{{ $color }}">{{ number_format($stats[$status]) }}
                            <span class="fs-6 fw-normal text-muted">Perkara</span>
                        </h2>
                        <div class="mt-3" style="width: 200px;">
                            <span class="small fw-bold text-{{ $color }}">{{ $stats['persen_'.$status] }}% {{ $status == 'sudah' ? 'Selesai' : 'Antrean' }}</span>
                            <div class="progress mt-1" style="height: 6px;">
                                <div class="progress-bar bg-{{ $color }}" style="width: {{ $stats['persen_'.$status] }}%"></div>
                            </div>
                        </div>
                    </div>
                    <i class="fas {{ $status == 'sudah' ? 'fa-cloud-arrow-up' : 'fa-cloud-showers-heavy' }} fa-3x text-{{ $color }} opacity-25"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row">
        {{-- Sidebar Search --}}
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm sticky-sidebar rounded-4 overflow-hidden">
                <div class="card-header bg-dark text-white py-3 border-0 text-center">
                    <h6 class="m-0 fw-bold small text-uppercase">Pencarian Spesifik</h6>
                </div>
                <div class="card-body p-4 bg-light">
                    <form action="{{ route('bank.index') }}" method="GET">
                        <input type="hidden" name="tgl_awal" value="{{ $tgl_awal }}">
                        <input type="hidden" name="tgl_akhir" value="{{ $tgl_akhir }}">
                        @if(request('status_upload'))
                        <input type="hidden" name="status_upload" value="{{ request('status_upload') }}">
                        @endif

                        <div class="mb-3">
                            <label class="text-uppercase text-muted fw-bold mb-1 small">Keyword</label>
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control border-0 shadow-sm" placeholder="No Perkara/Satker/Jenis...">
                        </div>

                        <div class="mb-3">
                            <label class="text-uppercase text-muted fw-bold mb-1 small">Tahun Register</label>
                            <select name="tahun" class="form-select border-0 shadow-sm">
                                <option value="">-- Semua Tahun --</option>
                                @for($y = date('Y'); $y >= 2015; $y--)
                                <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold rounded-pill mb-2">CARI DATA</button>
                        <a href="{{ route('bank.index') }}" class="btn btn-outline-secondary btn-sm w-100 rounded-pill">Reset Filter</a>
                    </form>
                </div>
            </div>
        </div>

        {{-- Table Content --}}
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="table-responsive-custom">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-dark text-white">
                            <tr>
                                <th class="py-3 px-4">SATKER & NO PERKARA</th>
                                <th class="py-3">KLASIFIKASI</th> {{-- Kolom Baru --}}
                                <th class="py-3">PUTUSAN</th>
                                <th class="py-3 text-center">FILE LOKAL</th>
                                <th class="py-3 text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($perkaras as $p)
                            @php
                            $file = $files[$p->nomor_perkara_banding] ?? null;
                            $modalId = 'modal' . str_replace(['/', '.', ' '], '_', $p->nomor_perkara_banding);
                            @endphp
                            <tr>
                                <td class="px-4">
                                    <div class="fw-bold text-primary">{{ $p->nomor_perkara_banding }}</div>
                                    <small class="text-dark fw-bold d-block">{{ $p->nama_satker }}</small>
                                    <small class="text-muted fst-italic" style="font-size: 0.75rem;">Reg: {{ \Carbon\Carbon::parse($p->tgl_register)->format('d/m/Y') }}</small>
                                </td>
                                <td>
                                    <span class="badge badge-perkara border mb-1">{{ $p->jenis_perkara }}</span>
                                    <div class="small text-muted">{{ $p->jenis_putus_text ?? '-' }}</div>
                                </td>
                                <td>
                                    <div class="small fw-bold text-dark">{{ \Carbon\Carbon::parse($p->tgl_putusan)->format('d M Y') }}</div>
                                    <span class="badge bg-soft-primary text-primary border-0">{{ $p->durasi_hari }} HARI</span>
                                </td>
                                <td class="text-center">
                                    @if($file)
                                    <div class="d-flex justify-content-center gap-2">
                                        @if($file->file_putusan)
                                        <a href="{{ route('bank.download', [$p->nomor_perkara_banding, 'putusan']) }}" class="btn btn-sm btn-light text-success shadow-sm border" title="Download Putusan" target="_blank">
                                            <i class="fas fa-file-word fa-lg"></i>
                                        </a>
                                        @endif
                                        @if($file->file_anonimasi)
                                        <a href="{{ route('bank.download', [$p->nomor_perkara_banding, 'anonimasi']) }}" class="btn btn-sm btn-light text-info shadow-sm border" title="Download Anonimasi">
                                            <i class="fas fa-user-secret fa-lg"></i>
                                        </a>
                                        @endif
                                    </div>
                                    @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger px-3">BELUM</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-primary btn-sm rounded-pill px-3 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#{{ $modalId }}">
                                        <i class="fas fa-upload me-1"></i> UPLOAD
                                    </button>
                                </td>
                            </tr>

                            {{-- Modal Upload --}}
                            {{-- Modal Upload --}}
                            <div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <form action="{{ route('bank.upload') }}" method="POST" enctype="multipart/form-data" class="w-100">
                                        @csrf
                                        <input type="hidden" name="nomor_perkara_banding" value="{{ $p->nomor_perkara_banding }}">
                                        <div class="modal-content border-0 shadow rounded-4">
                                            <div class="modal-header border-0 pt-4 px-4">
                                                <h5 class="fw-bold mb-0">Upload Dokumen</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body px-4">
                                                <div class="alert bg-soft-primary text-primary small border-0 mb-3 fw-bold">
                                                    {{ $p->nomor_perkara_banding }}<br>
                                                    <span class="text-muted fw-normal">{{ $p->nama_satker }}</span>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="small fw-bold mb-1">File Putusan Asli (DOCX/RTF)</label>
                                                    <input type="file" name="file_putusan" class="form-control" accept=".docx,.rtf">
                                                    @if($file && $file->file_putusan)
                                                    <div class="mt-1 small text-success"><i class="fas fa-check-circle"></i> File sudah ada. Upload lagi untuk mengganti.</div>
                                                    @endif
                                                </div>
                                                <div class="mb-3">
                                                    <label class="small fw-bold mb-1">File Anonimasi (DOCX/RTF)</label>
                                                    <input type="file" name="file_anonimasi" class="form-control" accept=".docx,.rtf">
                                                    @if($file && $file->file_anonimasi)
                                                    <div class="mt-1 small text-success"><i class="fas fa-check-circle"></i> File sudah ada. Upload lagi untuk mengganti.</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 pb-4 px-4">
                                                <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold shadow">SIMPAN PERUBAHAN</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted fst-italic">
                                    <i class="fas fa-folder-open fa-3x d-block mb-3 opacity-25"></i>
                                    Data tidak ditemukan...
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="pagination-wrapper">
                {{ $perkaras->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection