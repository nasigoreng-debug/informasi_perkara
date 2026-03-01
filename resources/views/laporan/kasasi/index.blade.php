@extends('layouts.app')

@section('title', 'Monitor Kasasi PTA')

@section('content')
@php \Carbon\Carbon::setLocale('id'); @endphp

<style>
    body {
        background: #f8fafc;
        color: #1e293b;
        text-align: left;
    }

    .filter-bar {
        background: #fff;
        border-radius: 20px;
        padding: 20px;
        border: 1px solid #e2e8f0;
    }

    .judge-card {
        background: #fff;
        border-radius: 20px;
        border: none;
        transition: all 0.3s ease;
        margin-bottom: 1.2rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .judge-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 25px -5px rgba(0, 0, 0, 0.08);
    }

    .status-indicator {
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 6px;
    }

    .judge-name {
        font-size: 1.1rem;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.025em;
    }

    .pta-number,
    .kasasi-number {
        font-weight: 700;
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.85rem;
    }

    .pta-number {
        color: #4f46e5;
    }

    .kasasi-number {
        color: #10b981;
    }

    .label-accent {
        font-size: 0.65rem;
        text-transform: uppercase;
        font-weight: 800;
        color: #64748b;
        letter-spacing: 0.05em;
        margin-bottom: 4px;
    }

    .badge-soft {
        background-color: #f1f5f9;
        color: #475569;
        font-size: 0.65rem;
        font-weight: 700;
        border: 1px solid #e2e8f0;
    }

    .btn-circle {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        transition: all 0.2s;
        border: 1px solid transparent;
    }

    .btn-circle:hover {
        transform: scale(1.1);
    }

    @media print {
        .no-print {
            display: none !important;
        }

        .print-only {
            display: block !important;
        }
    }

    .print-only {
        display: none;
    }
</style>

<div class="container px-4 py-4">
    {{-- Notifikasi --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- Header & Filter Section --}}
    <div class="filter-bar mb-4 shadow-sm">
        <form action="{{ route('kasasi.index') }}" method="GET" id="filterForm">
            <div class="row align-items-center g-3">
                <div class="col-md-3">
                    <h4 class="fw-bold text-dark mb-1">Monitoring Kasasi</h4>
                    <p class="text-muted small mb-0">Total: <span class="fw-bold text-primary">{{ number_format($grandTotal ?? 0) }}</span> Perkara</p>
                </div>

                <div class="col-md-4">
                    <div class="input-group bg-light rounded-pill px-3 py-1 border border-primary border-opacity-25">
                        <span class="input-group-text bg-transparent border-0 text-muted">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" id="liveSearch" class="form-control bg-transparent border-0 shadow-none"
                            placeholder="Cari Satker, No. Perkara, Nama Hakim, atau Status...">
                    </div>
                </div>

                <div class="col-md-5 d-flex gap-2 justify-content-md-end">
                    <select name="bulan" class="form-select border-0 fw-bold text-primary shadow-sm rounded-pill w-auto"
                        onchange="document.getElementById('filterForm').submit()">
                        <option value="">Semua Bulan</option>
                        @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ ($bulan ?? '') == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                        @endforeach
                    </select>

                    <select name="tahun" class="form-select border-0 fw-bold text-primary shadow-sm rounded-pill w-auto"
                        onchange="document.getElementById('filterForm').submit()">
                        <option value="">Semua Tahun</option>
                        @foreach($years ?? [] as $year)
                        <option value="{{ $year }}" {{ ($tahun ?? '') == $year ? 'selected' : '' }}>Tahun {{ $year }}</option>
                        @endforeach
                    </select>

                    <a href="{{ route('kasasi.index') }}" class="btn btn-light rounded-pill border shadow-sm px-3" title="Reset filter">
                        <i class="fas fa-undo-alt"></i>
                    </a>
                    <a href="{{ route('kasasi.export', ['bulan' => $bulan, 'tahun' => $tahun]) }}"
                        class="btn btn-success rounded-pill shadow-sm px-3 fw-bold" target="_blank">
                        <i class="fas fa-file-excel me-1"></i> EXCEL
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Daftar Perkara --}}
    <div id="caseList">
        @php $currentSatker = null; @endphp
        @forelse($data ?? [] as $item)
        @if($currentSatker != ($item->pengadilan_agama ?? ''))
        <div class="d-flex align-items-center mt-5 mb-3 satker-header">
            <span class="badge bg-dark rounded-pill px-3 py-2 fw-bold shadow-sm">
                <i class="fas fa-university me-2"></i>{{ $item->pengadilan_agama ?? 'Unknown' }}
            </span>
            <div class="flex-grow-1 border-top ms-3 opacity-25"></div>
        </div>
        @php $currentSatker = $item->pengadilan_agama; @endphp
        @endif

        {{-- Kartu Perkara --}}
        <div class="card judge-card searchable-item"
            data-search="{{ strtolower(
                ($item->nama_db ?? '') . ' ' .
                ($item->nomor_urut ?? '') . ' ' .
                ($item->pengadilan_agama ?? '') . ' ' .
                ($item->no_pa ?? '') . ' ' .
                ($item->no_pta ?? '') . ' ' .
                ($item->no_kasasi ?? '') . ' ' .
                ($item->jenis_perkara ?? '') . ' ' .
                ($item->tgl_reg_kasasi ?? '') . ' ' .
                ($item->tgl_putusan ?? '') . ' ' .
                ($item->status_putusan_kasasi_text ?? '') . ' ' .
                ($item->status_label ?? '') . ' ' .
                ($item->kmh ?? '')
            ) }}">
            <div class="status-indicator bg-{{ $item->status_color ?? 'secondary' }}"></div>
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-lg-3 border-end">
                        <div class="label-accent">Ketua Majelis Hakim</div>
                        <div class="judge-name mb-1 text-truncate" title="{{ $item->kmh ?? '' }}">
                            <i class="fas fa-user-tie text-muted me-2 opacity-50"></i>{{ $item->kmh ?? '-' }}
                        </div>
                        <span class="badge badge-soft rounded-pill px-2 py-1">
                            <i class="fas fa-folder-open me-1"></i>{{ $item->jenis_perkara ?? '-' }}
                        </span>
                    </div>

                    <div class="col-lg-2 px-lg-3 border-end my-3 my-lg-0">
                        <div class="label-accent">No. PTA & PA</div>
                        <div class="pta-number mb-1 text-truncate">{{ $item->no_pta ?? '-' }}</div>
                        <div class="small text-muted" style="font-size: 0.65rem;">{{ $item->no_pa ?? '-' }}</div>
                    </div>

                    <div class="col-lg-2 px-lg-3 border-end my-3 my-lg-0">
                        <div class="label-accent">No. Kasasi</div>
                        <div class="kasasi-number mb-1 text-truncate">{{ $item->no_kasasi ?? '-' }}</div>
                        <div class="small fw-bold text-primary mb-1" style="font-size: 0.65rem;">{{ $item->status_putusan_kasasi_text ?? '-' }}</div>
                        <span class="badge bg-{{ $item->status_color ?? 'secondary' }} bg-opacity-10 text-{{ $item->status_color ?? 'secondary' }} rounded-pill px-2 py-1 fw-bold" style="font-size: 0.6rem;">
                            {{ $item->status_label ?? 'Unknown' }}
                        </span>
                    </div>

                    <div class="col-lg-2 px-lg-3 border-end my-3 my-lg-0">
                        <div class="label-accent">Putusan MA</div>
                        <div class="mb-1">
                            @if(!empty($item->tgl_putusan))
                            <div class="text-success fw-bold" style="font-size: 0.75rem;">
                                <i class="fas fa-check-circle me-1"></i>{{ \Carbon\Carbon::parse($item->tgl_putusan)->translatedFormat('d/m/y') }}
                            </div>
                            @else
                            <div class="text-muted small">Proses MA</div>
                            @endif
                        </div>
                        <div class="text-muted" style="font-size: 0.65rem;">
                            Reg: {{ !empty($item->tgl_reg_kasasi) ? \Carbon\Carbon::parse($item->tgl_reg_kasasi)->format('d/m/y') : '-' }}
                        </div>
                    </div>

                    <div class="col-lg-1 text-center border-end my-3 my-lg-0">
                        <div class="label-accent">Amar</div>
                        <button class="btn btn-outline-primary btn-circle shadow-sm" data-bs-toggle="modal" data-bs-target="#modalAmar{{ $item->unique_id ?? $loop->index }}" title="Lihat Amar" {{ empty($item->amar_full) ? 'disabled' : '' }}>
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>

                    <div class="col-lg-2 ps-lg-4 text-center text-lg-end">
                        <div class="label-accent mb-2">Dokumen</div>
                        <div class="d-flex justify-content-lg-end justify-content-center align-items-center gap-2">
                            @if(!empty($item->file_pdf))
                            <a href="{{ asset('public/storage/' . ltrim($item->file_pdf, '/')) }}" target="_blank" class="btn btn-success btn-circle shadow-sm" title="Download"><i class="fas fa-file-download"></i></a>
                            <button class="btn btn-outline-success btn-circle shadow-sm" data-bs-toggle="modal" data-bs-target="#uploadModal{{ $item->unique_id ?? $loop->index }}" title="Edit"><i class="fas fa-edit"></i></button>
                            @else
                            <button class="btn btn-outline-danger btn-circle shadow-sm" data-bs-toggle="modal" data-bs-target="#uploadModal{{ $item->unique_id ?? $loop->index }}" title="Upload"><i class="fas fa-cloud-upload-alt"></i></button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL UPLOAD --}}
        <div class="modal fade" id="uploadModal{{ $item->unique_id ?? $loop->index }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="modal-header bg-success text-white border-0 p-4">
                        <h5 class="modal-title fw-bold"><i class="fas fa-file-pdf me-2"></i>{{ !empty($item->file_pdf) ? 'Ganti Dokumen' : 'Upload Dokumen' }}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('kasasi.upload', $item->perkara_id ?? 0) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="modal-body p-4 text-start">
                            <input type="hidden" name="nama_db" value="{{ $item->nama_db ?? '' }}">
                            <div class="alert alert-info py-2 small border-0 mb-3">No. Kasasi: <strong>{{ $item->no_kasasi ?? '-' }}</strong></div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Pilih Dokumen (PDF)</label>
                                <input type="file" name="file_pdf" class="form-control" accept=".pdf" required onchange="validateFile(this)">
                                <small class="text-muted">Maksimal 10MB</small>
                            </div>
                        </div>
                        <div class="modal-footer border-0 p-4 bg-light">
                            <button type="submit" class="btn btn-success rounded-pill w-100 fw-bold shadow">Simpan Dokumen</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODAL AMAR --}}
        @if(!empty($item->amar_full))
        <div class="modal fade" id="modalAmar{{ $item->unique_id ?? $loop->index }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header bg-dark text-white border-0 p-4">
                        <h5 class="modal-title fw-bold"><i class="fas fa-balance-scale me-2"></i> Isi Amar Kasasi</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div id="printArea{{ $item->unique_id ?? $loop->index }}" class="p-4 bg-white border rounded-3">
                            <div class="print-only text-center mb-4">
                                <h4 class="fw-bold mb-3">SALINAN AMAR PUTUSAN</h4>
                                <table class="table table-sm table-borderless text-start">
                                    <tr>
                                        <td style="width: 120px;">Nomor Kasasi</td>
                                        <td>: {{ $item->no_kasasi ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Ketua Majelis</td>
                                        <td>: {{ $item->kmh ?? '-' }}</td>
                                    </tr>
                                </table>
                                <hr class="my-3">
                            </div>
                            <div class="amar-content" style="text-align: justify; line-height: 1.8;">{!! nl2br(e($item->amar_full)) !!}</div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 bg-light">
                        <button class="btn btn-primary w-100 py-3 rounded-4 fw-bold shadow" onclick="printAmar('printArea{{ $item->unique_id ?? $loop->index }}')"><i class="fas fa-print me-2"></i> Cetak Isi Amar</button>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @empty
        <div class="alert bg-white shadow-sm rounded-4 p-5 text-center">
            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
            <h5>Tidak Ada Data</h5>
        </div>
        @endforelse
    </div>
</div>

@push('scripts')
<script>
    // Validasi file upload
    function validateFile(input) {
        const file = input.files[0];
        if (file && (file.type !== 'application/pdf' || file.size > 10 * 1024 * 1024)) {
            alert('File harus PDF dan maksimal 10MB!');
            input.value = '';
        }
    }

    // Live Search Super Komplit
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('liveSearch');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const term = this.value.toLowerCase().trim();
                const items = document.querySelectorAll('.searchable-item');

                items.forEach(item => {
                    const searchText = item.getAttribute('data-search');
                    const matches = term === '' || searchText.includes(term);
                    item.style.display = matches ? '' : 'none';
                });

                // Perbarui visibilitas Header Satker
                refreshSatkerHeaders();
            });
        }
    });

    function refreshSatkerHeaders() {
        const headers = document.querySelectorAll('.satker-header');
        headers.forEach(header => {
            let hasVisibleCards = false;
            let nextEl = header.nextElementSibling;

            // Cek elemen setelah header sampai ketemu header satker berikutnya
            while (nextEl && !nextEl.classList.contains('satker-header')) {
                if (nextEl.classList.contains('searchable-item') && nextEl.style.display !== 'none') {
                    hasVisibleCards = true;
                    break;
                }
                nextEl = nextEl.nextElementSibling;
            }
            header.style.setProperty('display', hasVisibleCards ? 'flex' : 'none', 'important');
        });
    }

    function printAmar(divId) {
        const content = document.getElementById(divId).innerHTML;
        const printWindow = window.open('', '_blank', 'width=900,height=700');
        printWindow.document.write('<html><head><title>Cetak Amar</title><style>body{font-family:serif;padding:50px;line-height:1.8;text-align:justify;}.print-only{display:block !important;}</style></head><body>' + content + '</body></html>');
        printWindow.document.close();
        setTimeout(() => {
            printWindow.focus();
            printWindow.print();
        }, 500);
    }

    // Auto-hide alert
    setTimeout(() => {
        document.querySelectorAll('.alert-dismissible').forEach(a => {
            new bootstrap.Alert(a).close();
        });
    }, 5000);
</script>
@endpush
@endsection