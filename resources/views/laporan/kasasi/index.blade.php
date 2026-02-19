@extends('layouts.app')

@section('title', 'Monitor Kasasi PTA')

@section('content')
@php \Carbon\Carbon::setLocale('id'); @endphp

<style>
    body { background: #f8fafc; color: #1e293b; text-align: left; }
    .filter-bar { background: #fff; border-radius: 20px; padding: 20px; border: 1px solid #e2e8f0; }
    .judge-card { background: #fff; border-radius: 20px; border: none; transition: all 0.3s ease; margin-bottom: 1.2rem; position: relative; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); }
    .judge-card:hover { transform: translateY(-3px); box-shadow: 0 15px 25px -5px rgba(0, 0, 0, 0.08); }
    .status-indicator { position: absolute; left: 0; top: 0; bottom: 0; width: 6px; }
    .judge-name { font-size: 1.1rem; font-weight: 800; color: #0f172a; letter-spacing: -0.025em; }
    .pta-number, .kasasi-number { font-weight: 700; font-family: 'JetBrains Mono', monospace; font-size: 0.85rem; }
    .pta-number { color: #4f46e5; }
    .kasasi-number { color: #10b981; }
    .label-accent { font-size: 0.65rem; text-transform: uppercase; font-weight: 800; color: #64748b; letter-spacing: 0.05em; margin-bottom: 4px; }
    .badge-soft { background-color: #f1f5f9; color: #475569; font-size: 0.65rem; font-weight: 700; border: 1px solid #e2e8f0; }
    
    .btn-circle { width: 38px; height: 38px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; padding: 0; transition: all 0.2s; border: 1px solid transparent; }
    .btn-circle:hover { transform: scale(1.1); }
</style>

<div class="container-fluid px-4 py-4">
    {{-- Notifikasi --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- Header & Filter Section --}}
    <div class="filter-bar mb-4 shadow-sm">
        <form action="{{ route('kasasi.index') }}" method="GET" id="filterForm">
            <div class="row align-items-center g-3">
                <div class="col-md-3">
                    <h4 class="fw-bold text-dark mb-1">Monitoring Kasasi</h4>
                    <p class="text-muted small mb-0">Total: <span class="fw-bold text-primary">{{ number_format($grandTotal) }}</span> Perkara</p>
                </div>
                <div class="col-md-4">
                    <div class="input-group bg-light rounded-pill px-3 py-1">
                        <span class="input-group-text bg-transparent border-0 text-muted"><i class="fas fa-search"></i></span>
                        <input type="text" id="liveSearch" class="form-control bg-transparent border-0 shadow-none" placeholder="Cari Nama Hakim, No. PTA, atau No. Kasasi...">
                    </div>
                </div>
                <div class="col-md-5 d-flex gap-2 justify-content-md-end">
                    <select name="bulan" class="form-select border-0 fw-bold text-primary shadow-sm rounded-pill w-auto" onchange="this.form.submit()">
                        <option value="">Semua Bulan</option>
                        @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                        @endforeach
                    </select>
                    <select name="tahun" class="form-select border-0 fw-bold text-primary shadow-sm rounded-pill w-auto" onchange="this.form.submit()">
                        <option value="">Semua Tahun</option>
                        @foreach($years as $year)
                        <option value="{{ $year }}" {{ $tahun == $year ? 'selected' : '' }}>Tahun {{ $year }}</option>
                        @endforeach
                    </select>
                    <a href="{{ route('kasasi.index') }}" class="btn btn-light rounded-pill border shadow-sm px-3"><i class="fas fa-undo-alt"></i></a>
                </div>
            </div>
        </form>
    </div>

    <div id="caseList">
        @php $currentSatker = null; @endphp
        @forelse($data as $item)
        @if($currentSatker != $item->pengadilan_agama)
        <div class="d-flex align-items-center mt-5 mb-3">
            <span class="badge bg-dark rounded-pill px-3 py-2 fw-bold shadow-sm">
                <i class="fas fa-university me-2"></i>{{ $item->pengadilan_agama }}
            </span>
            <div class="flex-grow-1 border-top ms-3 opacity-25"></div>
        </div>
        @php $currentSatker = $item->pengadilan_agama; @endphp
        @endif

        <div class="card judge-card searchable-item">
            <div class="status-indicator bg-{{ $item->status_color }}"></div>
            <div class="card-body p-4">
                <div class="row align-items-center">
                    {{-- 1. Hakim --}}
                    <div class="col-lg-3 border-end">
                        <div class="label-accent">Ketua Majelis Hakim</div>
                        <div class="judge-name mb-1 text-truncate" title="{{ $item->kmh }}">
                            <i class="fas fa-user-tie text-muted me-2 opacity-50"></i>{{ $item->kmh }}
                        </div>
                        <span class="badge badge-soft rounded-pill px-2 py-1">
                            <i class="fas fa-folder-open me-1"></i>{{ $item->jenis_perkara }}
                        </span>
                    </div>

                    {{-- 2. No PTA --}}
                    <div class="col-lg-2 px-lg-3 border-end my-3 my-lg-0">
                        <div class="label-accent">No. PTA & PA</div>
                        <div class="pta-number mb-1 text-truncate">{{ $item->no_pta }}</div>
                        <div class="small text-muted" style="font-size: 0.65rem;">{{ $item->no_pa }}</div>
                    </div>

                    {{-- 3. No Kasasi --}}
                    <div class="col-lg-2 px-lg-3 border-end my-3 my-lg-0">
                        <div class="label-accent">No. Kasasi</div>
                        <div class="kasasi-number mb-1 text-truncate">{{ $item->no_kasasi }}</div>
                        <span class="badge bg-{{ $item->status_color }} bg-opacity-10 text-{{ $item->status_color }} rounded-pill px-2 py-1 fw-bold" style="font-size: 0.6rem;">
                            {{ $item->status_label }}
                        </span>
                    </div>

                    {{-- 4. Status Putusan --}}
                    <div class="col-lg-2 px-lg-3 border-end my-3 my-lg-0">
                        <div class="label-accent">Putusan MA</div>
                        <div class="mb-1">
                            @if($item->tgl_putusan)
                            <div class="text-success fw-bold" style="font-size: 0.75rem;">
                                <i class="fas fa-check-circle me-1"></i>{{ \Carbon\Carbon::parse($item->tgl_putusan)->translatedFormat('d/m/y') }}
                            </div>
                            @else
                            <div class="text-muted small">Proses MA</div>
                            @endif
                        </div>
                        <div class="text-muted" style="font-size: 0.65rem;">
                            Reg: {{ $item->tgl_reg_kasasi ? \Carbon\Carbon::parse($item->tgl_reg_kasasi)->format('d/m/y') : '-' }}
                        </div>
                    </div>

                    {{-- 5. Tombol Amar --}}
                    <div class="col-lg-1 text-center border-end my-3 my-lg-0">
                        <div class="label-accent">Amar</div>
                        <button class="btn btn-outline-primary btn-circle shadow-sm" data-bs-toggle="modal" data-bs-target="#modalAmar{{ $item->unique_id }}" title="Lihat Isi Amar">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>

                    {{-- 6. Dokumen PDF --}}
                    <div class="col-lg-2 ps-lg-4 text-center text-lg-end">
                        <div class="label-accent mb-2">Dokumen</div>
                        <div class="d-flex justify-content-lg-end justify-content-center align-items-center gap-2">
                            @if($item->file_pdf)
                                <div class="text-success me-1 d-none d-xl-block" style="font-size: 0.6rem; font-weight: 800;">Tersedia</div>
                                <a href="/public/storage/{{ trim($item->file_pdf) }}" target="_blank" class="btn btn-success btn-circle shadow-sm" title="Download PDF">
                                    <i class="fas fa-file-download"></i>
                                </a>
                                <button class="btn btn-outline-success btn-circle shadow-sm" data-bs-toggle="modal" data-bs-target="#uploadModal{{ $item->unique_id }}" title="Edit/Ganti File">
                                    <i class="fas fa-edit"></i>
                                </button>
                            @else
                                <div class="text-danger me-1 d-none d-xl-block" style="font-size: 0.6rem; font-weight: 800;">Kosong</div>
                                <button class="btn btn-outline-danger btn-circle shadow-sm" data-bs-toggle="modal" data-bs-target="#uploadModal{{ $item->unique_id }}" title="Upload PDF">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL UPLOAD --}}
        <div class="modal fade" id="uploadModal{{ $item->unique_id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="modal-header bg-success text-white border-0 p-4">
                        <h5 class="modal-title fw-bold"><i class="fas fa-file-pdf me-2"></i> Upload Putusan</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('kasasi.upload', $item->perkara_id) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="modal-body p-4 text-start">
                            <input type="hidden" name="nama_db" value="{{ $item->nama_db }}">
                            <div class="alert alert-info py-2 small border-0 mb-3 text-dark">
                                No. Kasasi: <strong>{{ $item->no_kasasi }}</strong>
                            </div>
                            <div class="mb-3 text-dark">
                                <label class="form-label fw-bold">Pilih Dokumen (PDF)</label>
                                <input type="file" name="file_pdf" class="form-control" accept=".pdf" required>
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
        <div class="modal fade" id="modalAmar{{ $item->unique_id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4 text-dark text-start">
                    <div class="modal-header bg-dark text-white border-0 p-4">
                        <h5 class="modal-title fw-bold"><i class="fas fa-balance-scale me-2"></i> Isi Amar Kasasi</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="bg-light p-3 border rounded-3 mb-3">
                            <div class="small fw-bold text-primary">No. Kasasi: {{ $item->no_kasasi }}</div>
                            <div class="small text-muted">KMH: {{ $item->kmh }}</div>
                        </div>
                        
                        <div id="printArea{{ $item->unique_id }}" class="p-4 bg-white border rounded-3 shadow-sm" style="text-align: justify; line-height: 1.8; max-height: 400px; overflow-y: auto;">
                            <div class="d-none print-only-header">
                                <h2 style="text-align: center; text-decoration: underline;">SALINAN AMAR PUTUSAN</h2>
                                <table style="width: 100%; margin-bottom: 20px; font-size: 14px;">
                                    <tr><td style="width: 120px;">Nomor Kasasi</td><td>: {{ $item->no_kasasi }}</td></tr>
                                    <tr><td>Ketua Majelis</td><td>: {{ $item->kmh }}</td></tr>
                                </table>
                                <hr style="border: 1px solid #000;">
                            </div>
                            
                            {{-- Content Amar --}}
                            <div class="amar-text-content">
                                {!! nl2br(e($item->amar_full)) !!}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 bg-light">
                        <button class="btn btn-primary w-100 py-3 rounded-4 fw-bold shadow" 
                                onclick="printJustAmar('printArea{{ $item->unique_id }}')">
                            <i class="fas fa-print me-2"></i> Cetak Isi Amar Saja
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="alert bg-white shadow-sm rounded-4 p-5 text-center">Data tidak ditemukan.</div>
        @endforelse
    </div>
</div>

@push('scripts')
<script>
    // 1. Live Search
    document.getElementById('liveSearch').addEventListener('keyup', function() {
        let val = this.value.toLowerCase();
        document.querySelectorAll('.searchable-item').forEach(el => {
            el.style.display = el.textContent.toLowerCase().includes(val) ? '' : 'none';
        });
    });

    // 2. Fungsi Cetak Perbaikan (Support <br> dan Line Breaks)
    function printJustAmar(divId) {
        var content = document.getElementById(divId).innerHTML;
        var printWindow = window.open('', '', 'height=700,width=900');
        
        printWindow.document.write('<html><head><title>Cetak Amar</title>');
        printWindow.document.write('<style>');
        // CSS Penting: pre-line memastikan baris baru dan <br> berfungsi
        printWindow.document.write('body { font-family: "Times New Roman", serif; padding: 50px; line-height: 1.8; font-size: 12pt; color: #000; }');
        printWindow.document.write('.amar-text-content { white-space: pre-line; text-align: justify; display: block; }');
        printWindow.document.write('.print-only-header { display: block !important; margin-bottom: 30px; }');
        printWindow.document.write('h2 { margin-bottom: 10px; }');
        printWindow.document.write('hr { margin-bottom: 20px; border: 0; border-top: 2px solid #000; }');
        printWindow.document.write('table { margin-bottom: 20px; border-collapse: collapse; }');
        printWindow.document.write('</style></head><body>');
        printWindow.document.write(content);
        printWindow.document.write('</body></html>');

        printWindow.document.close();
        
        setTimeout(function() {
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        }, 500);
    }
</script>
@endpush
@endsection