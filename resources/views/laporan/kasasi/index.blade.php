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
        font-size: 1.15rem;
        font-weight: 850;
        color: #0f172a;
        letter-spacing: -0.025em;
    }

    .pta-number {
        color: #4f46e5;
        font-weight: 700;
        font-family: 'JetBrains Mono', monospace;
    }

    .kasasi-number {
        color: #10b981;
        font-weight: 700;
        font-family: 'JetBrains Mono', monospace;
    }

    .label-accent {
        font-size: 0.65rem;
        text-transform: uppercase;
        font-weight: 800;
        color: #64748b;
        letter-spacing: 0.05em;
        margin-bottom: 2px;
    }

    .badge-soft {
        background-color: #f1f5f9;
        color: #475569;
        font-size: 0.65rem;
        font-weight: 700;
        border: 1px solid #e2e8f0;
    }
</style>

<div class="container-fluid px-4 py-4">
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
                    {{-- Dropdown Bulan --}}
                    <select name="bulan" class="form-select border-0 fw-bold text-primary shadow-sm rounded-pill w-auto" onchange="this.form.submit()">
                        <option value="">Semua Bulan</option>
                        @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                        @endforeach
                    </select>
                    {{-- Dropdown Tahun --}}
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

    @php $currentSatker = null; @endphp
    <div id="caseList">
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
                    {{-- 1. FOKUS HAKIM & JENIS --}}
                    <div class="col-lg-3 border-end">
                        <div class="label-accent">Ketua Majelis Hakim</div>
                        <div class="judge-name mb-1 text-truncate" title="{{ $item->kmh }}">
                            <i class="fas fa-user-tie text-muted me-2 opacity-50"></i>{{ $item->kmh }}
                        </div>
                        <span class="badge badge-soft rounded-pill px-2 py-1">
                            <i class="fas fa-folder-open me-1"></i>{{ $item->jenis_perkara }}
                        </span>
                    </div>

                    {{-- 2. FOKUS NOMOR PTA --}}
                    <div class="col-lg-3 px-lg-4 border-end my-3 my-lg-0">
                        <div class="label-accent">No. Perkara PTA & PA</div>
                        <div class="pta-number fs-6 mb-1">{{ $item->no_pta }}</div>
                        <div class="small text-muted"><i class="fas fa-link me-1"></i>Asal PA: {{ $item->no_pa }}</div>
                    </div>

                    {{-- 3. FOKUS NOMOR KASASI & STATUS --}}
                    <div class="col-lg-2 px-lg-4 border-end my-3 my-lg-0">
                        <div class="label-accent">No. Kasasi</div>
                        <div class="kasasi-number small mb-1">{{ $item->no_kasasi }}</div>
                        <span class="badge bg-{{ $item->status_color }} bg-opacity-10 text-{{ $item->status_color }} rounded-pill px-2 py-1 fw-bold" style="font-size: 0.6rem;">
                            {{ $item->status_label }}
                        </span>
                    </div>

                    {{-- 4. FOKUS KETERANGAN TANGGAL --}}
                    <div class="col-lg-2 ps-lg-4 my-3 my-lg-0">
                        <div class="label-accent">Keterangan</div>

                        {{-- Info Tanggal Putus --}}
                        <div class="mb-1">
                            @if($item->tgl_putusan)
                            <div class="text-success fw-bold" style="font-size: 0.75rem;">
                                <i class="fas fa-check-circle me-1"></i>Putus: {{ \Carbon\Carbon::parse($item->tgl_putusan)->translatedFormat('d M Y') }}
                            </div>
                            @else
                            <div class="text-muted small italic"><i class="far fa-clock me-1"></i>Proses MA</div>
                            @endif
                        </div>

                        {{-- Info Tanggal Registrasi --}}
                        <div class="text-muted" style="font-size: 0.7rem;">
                            <span class="fw-bold">Reg:</span>
                            @if($item->tgl_reg_kasasi)
                            {{ \Carbon\Carbon::parse($item->tgl_reg_kasasi)->format('d/m/y') }}
                            @else
                            <span class="text-danger fw-bold">Not Found</span>
                            @endif
                        </div>
                    </div>

                    {{-- 5. AKSI --}}
                    <div class="col-lg-2 text-lg-end">
                        <button class="btn btn-primary rounded-pill py-2 px-4 shadow-sm w-100 fw-bold" data-bs-toggle="modal" data-bs-target="#modal{{ $item->unique_id }}">
                            <i class="fas fa-eye me-1"></i> Amar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL DETAIL --}}
        <div class="modal fade" id="modal{{ $item->unique_id }}" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered text-start">
                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="modal-header bg-dark text-white border-0 p-4">
                        <h5 class="modal-title fw-bold"><i class="fas fa-balance-scale me-2"></i> Detail Amar Kasasi</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row g-3 mb-4 bg-light rounded-3 p-3 border mx-0">
                            <div class="col-md-4 border-end">
                                <div class="label-accent">Ketua Majelis</div>
                                <div class="fw-bold text-dark small text-truncate">{{ $item->kmh }}</div>
                            </div>
                            <div class="col-md-4 border-end ps-md-3">
                                <div class="label-accent">No. PTA / KASASI</div>
                                <div class="fw-bold text-primary small">{{ $item->no_pta }}</div>
                                <div class="fw-bold text-success small">{{ $item->no_kasasi }}</div>
                            </div>
                            <div class="col-md-4 ps-md-3">
                                <div class="label-accent">Tanggal Putusan MA</div>
                                <div class="fw-bold {{ $item->tgl_putusan ? 'text-success' : 'text-muted' }} small">
                                    {{ $item->tgl_putusan ? \Carbon\Carbon::parse($item->tgl_putusan)->translatedFormat('d F Y') : 'Belum Putus' }}
                                </div>
                            </div>
                        </div>
                        <div class="label-accent mb-2">Salinan Amar Lengkap:</div>
                        <div class="p-4 bg-white border rounded-3" style="text-align: justify; line-height: 1.8; font-size: 1rem; color: #333; max-height: 400px; overflow-y: auto;">
                            {!! nl2br(e($item->amar_full)) !!}
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 bg-light">
                        <button class="btn btn-primary w-100 py-3 rounded-4 fw-bold shadow" onclick="window.print()"><i class="fas fa-print me-2"></i> Cetak Dokumen</button>
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
    document.getElementById('liveSearch').addEventListener('keyup', function() {
        let val = this.value.toLowerCase();
        document.querySelectorAll('.searchable-item').forEach(el => {
            el.style.display = el.textContent.toLowerCase().includes(val) ? '' : 'none';
        });
    });
</script>
@endpush
@endsection