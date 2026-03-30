@extends('layouts.app')

@section('content')
<style>
    .clickable-card { transition: all 0.3s ease; cursor: pointer; }
    .clickable-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important; }
    .active-filter-sudah { border: 2px solid #28a745 !important; background-color: #f8fff9; }
    .active-filter-belum { border: 2px solid #dc3545 !important; background-color: #fff8f8; }
    .sticky-sidebar { position: sticky; top: 90px; z-index: 1000; height: fit-content; }
    .bg-soft-primary { background-color: rgba(78, 115, 223, 0.1); }
    
    /* Perbaikan Pagination - Mencegah Tumpuk */
    .pagination-wrapper {
        margin-top: 1.5rem;
        margin-bottom: 0.5rem;
    }
    .pagination-wrapper nav {
        display: flex;
        justify-content: center;
    }
    .pagination-wrapper .pagination {
        flex-wrap: wrap;
        justify-content: center;
        gap: 0.25rem;
        margin-bottom: 0;
    }
    .pagination-wrapper .page-item {
        margin: 0;
        display: inline-block;
    }
    .pagination-wrapper .page-link {
        border-radius: 0.375rem !important;
        padding: 0.5rem 0.875rem;
        font-size: 0.875rem;
    }
    
    /* Responsif untuk Mobile */
    @media (max-width: 768px) {
        .pagination-wrapper .page-link {
            padding: 0.375rem 0.625rem;
            font-size: 0.75rem;
        }
        .table-responsive-custom {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
    }
    
    /* Perbaikan Tabel di Mobile */
    @media (max-width: 768px) {
        .table thead th,
        .table tbody td {
            white-space: nowrap;
        }
    }
</style>

<div class="container-fluid py-4 px-md-5">
    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 border-bottom pb-3">
        <div class="d-flex align-items-center mb-3 mb-md-0">
            <i class="fas fa-university fa-3x me-3 text-primary"></i>
            <div>
                <h3 class="fw-bold text-dark mb-0 text-uppercase">Bank Putusan Digital</h3>
                <p class="text-primary fw-bold small mb-0 uppercase">DATABASE DOKUMEN PUTUSAN & ANONIMASI PTA BANDUNG</p>
            </div>
        </div>
        <div class="bg-white p-2 rounded-pill shadow-sm border px-3">
            <form action="{{ route('bank.index') }}" method="GET" class="d-flex align-items-center gap-2 flex-wrap flex-sm-nowrap">
                <input type="date" name="tgl_awal" class="form-control form-control-sm border-0 bg-light rounded-pill" value="{{ $tgl_awal }}">
                <span class="text-muted small">s.d</span>
                <input type="date" name="tgl_akhir" class="form-control form-control-sm border-0 bg-light rounded-pill" value="{{ $tgl_akhir }}">
                <button type="submit" class="btn btn-sm btn-primary px-3 rounded-pill shadow-sm">Filter</button>
            </form>
        </div>
    </div>

    {{-- Statistik Cards --}}
    <div class="row g-3 mb-4 text-uppercase">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm p-4 rounded-4 clickable-card {{ request('status_upload') == 'sudah' ? 'active-filter-sudah' : '' }}"
                onclick="window.location.href='{{ route('bank.index', array_merge(request()->query(), ['status_upload' => 'sudah'])) }}'"
                style="border-left: 5px solid #28a745 !important;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <small class="text-muted fw-bold d-block mb-1 small">Sudah Upload</small>
                        <h2 class="mb-0 fw-bold text-success">{{ number_format($stats['sudah']) }} <span class="fs-6 fw-normal text-muted">Perkara</span></h2>
                        <div class="mt-3">
                            <span class="small fw-bold text-success">{{ $stats['persen_sudah'] }}% Selesai</span>
                            <div class="progress mt-1" style="height: 8px;">
                                <div class="progress-bar bg-success" style="width: {{ $stats['persen_sudah'] }}%"></div>
                            </div>
                        </div>
                    </div>
                    <i class="fas fa-cloud-arrow-up fa-3x text-success opacity-25"></i>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm p-4 rounded-4 clickable-card {{ request('status_upload') == 'belum' ? 'active-filter-belum' : '' }}"
                onclick="window.location.href='{{ route('bank.index', array_merge(request()->query(), ['status_upload' => 'belum'])) }}'"
                style="border-left: 5px solid #dc3545 !important;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <small class="text-muted fw-bold d-block mb-1 small">Belum Upload</small>
                        <h2 class="mb-0 fw-bold text-danger">{{ number_format($stats['belum']) }} <span class="fs-6 fw-normal text-muted">Perkara</span></h2>
                        <div class="mt-3">
                            <span class="small fw-bold text-danger">{{ $stats['persen_belum'] }}% Antrean</span>
                            <div class="progress mt-1" style="height: 8px;">
                                <div class="progress-bar bg-danger" style="width: {{ $stats['persen_belum'] }}%"></div>
                            </div>
                        </div>
                    </div>
                    <i class="fas fa-cloud-showers-heavy fa-3x text-danger opacity-25"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Sidebar --}}
        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm sticky-sidebar rounded-4 overflow-hidden">
                <div class="card-header bg-dark text-white py-3 border-0 text-uppercase text-center">
                    <h6 class="m-0 fw-bold small">Pencarian Spesifik</h6>
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
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control border-0 shadow-sm" placeholder="No Perkara/Satker...">
                        </div>

                        <div class="mb-3">
                            <label class="text-uppercase text-muted fw-bold mb-1 small">Tahun Register</label>
                            <select name="tahun" class="form-select border-0 shadow-sm">
                                <option value="">-- Semua Tahun --</option>
                                @php
                                    $currentYear = date('Y');
                                    for($y = $currentYear; $y >= 2015; $y--) {
                                        $selected = request('tahun') == $y ? 'selected' : '';
                                        echo '<option value="'.$y.'" '.$selected.'>'.$y.'</option>';
                                    }
                                @endphp
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold rounded-pill shadow-sm">CARI DATA</button>
                        <a href="{{ route('bank.index') }}" class="btn btn-link btn-sm w-100 mt-2 text-decoration-none text-muted small text-center">Reset Filter</a>
                    </form>
                </div>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="table-responsive-custom">
                    <table class="table table-hover align-middle mb-0 bg-white">
                        <thead class="bg-dark text-white">
                            <tr>
                                <th class="py-3 px-4">SATKER & NO PERKARA</th>
                                <th class="py-3">PUTUSAN</th>
                                <th class="py-3 text-center">FILE LOKAL</th>
                                <th class="py-3 text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($perkaras as $p)
                                @php 
                                    $file = $files[$p->nomor_perkara_banding] ?? null; 
                                    $modalId = 'modal' . md5($p->nomor_perkara_banding); 
                                @endphp
                                <tr>
                                    <td class="px-4">
                                        <div class="fw-bold text-primary">{{ $p->nomor_perkara_banding }}</div>
                                        <small class="text-muted d-block small">{{ $p->nama_satker }}</small>
                                        <small class="text-muted fst-italic" style="font-size: 0.7rem;">Reg: {{ $p->tgl_register }}</small>
                                    </td>
                                    <td>
                                        <div class="small fw-bold text-dark">{{ $p->tgl_putusan }}</div>
                                        <span class="badge bg-soft-primary text-primary border-0 fw-bold" style="font-size: 0.65rem;">{{ $p->durasi_hari }} HARI</span>
                                    </td>
                                    <td class="text-center">
                                        @if($file)
                                            <div class="d-flex justify-content-center gap-2">
                                                @if($file->file_putusan) 
                                                    <a href="{{ asset('storage/'.$file->file_putusan) }}" target="_blank" class="btn btn-sm btn-outline-success border-0" title="File Putusan">
                                                        <i class="fas fa-file-word fa-lg"></i>
                                                    </a> 
                                                @endif
                                                @if($file->file_anonimasi) 
                                                    <a href="{{ asset('storage/'.$file->file_anonimasi) }}" target="_blank" class="btn btn-sm btn-outline-info border-0" title="File Anonimasi">
                                                        <i class="fas fa-user-secret fa-lg"></i>
                                                    </a> 
                                                @endif
                                            </div>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger border border-danger px-3 shadow-sm" style="font-size: 0.65rem;">BELUM UPLOAD</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#{{ $modalId }}">
                                            UPLOAD
                                        </button>
                                    </td>
                                </tr>

                                {{-- Modal Upload --}}
                                <div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <form action="{{ route('bank.upload') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="nomor_perkara_banding" value="{{ $p->nomor_perkara_banding }}">
                                            <div class="modal-content border-0 shadow rounded-4">
                                                <div class="modal-header border-0 pt-4 px-4">
                                                    <h5 class="fw-bold text-dark">Upload Dokumen Perkara</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body px-4 py-0">
                                                    <div class="alert bg-soft-primary text-primary small border-0 mb-3">
                                                        {{ $p->nomor_perkara_banding }}
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="small fw-bold mb-1 text-dark">File Putusan Asli (DOCX/RTF)</label>
                                                        <input type="file" name="file_putusan" class="form-control rounded-3" accept=".docx,.rtf">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="small fw-bold mb-1 text-dark">File Anonimasi (DOCX/RTF)</label>
                                                        <input type="file" name="file_anonimasi" class="form-control rounded-3" accept=".docx,.rtf">
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-0 pb-4 px-4">
                                                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold shadow-sm">
                                                        SIMPAN KE DATABASE LOKAL
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted fst-italic">
                                        Data tidak ditemukan...
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            {{-- Pagination dengan Wrapper untuk Mencegah Tumpuk --}}
            @if($perkaras->hasPages())
                <div class="pagination-wrapper">
                    {{ $perkaras->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection