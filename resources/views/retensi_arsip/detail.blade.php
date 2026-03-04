@extends('layouts.app')

@section('content')
<div class="container py-4 px-md-5 fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <div class="d-flex align-items-center">
            <a href="{{ route('retensi-arsip.index') }}" class="btn btn-outline-danger rounded-circle me-3">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h4 class="fw-bold text-dark mb-0 text-uppercase">Detail Arsip Perkara</h4>
                <p class="text-muted small mb-0">ID Arsip: #{{ $arsip->id }} | Terdaftar pada: {{ $arsip->created_at->format('d/m/Y') }}</p>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('retensi-arsip.edit', $arsip->id) }}" class="btn btn-warning rounded-pill px-4 fw-bold">
                <i class="fas fa-edit me-2"></i> EDIT DATA
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-danger border-bottom pb-2 mb-4 text-uppercase">
                        <i class="fas fa-file-invoice me-2"></i> Informasi Detail Perkara
                    </h6>

                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold text-muted text-uppercase small">Nomor Banding</div>
                        <div class="col-sm-8 fw-bold text-danger fs-5">{{ $arsip->no_banding ?? '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold text-muted text-uppercase small">Nomor Perkara PA</div>
                        <div class="col-sm-8 fw-bold text-dark">{{ $arsip->no_pa ?? '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold text-muted text-uppercase small">PA Pengaju</div>
                        <div class="col-sm-8 text-dark">{{ $arsip->pa_pengaju ?? '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold text-muted text-uppercase small">Jenis Perkara</div>
                        <div class="col-sm-8"><span class="badge bg-info-subtle text-info border border-info-subtle px-3">{{ $arsip->jenis_perkara ?? '-' }}</span></div>
                    </div>

                    <hr class="my-4 opacity-25">

                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold text-muted text-uppercase small">Pembanding</div>
                        <div class="col-sm-8 fw-normal text-uppercase text-dark">{{ $arsip->pembanding ?? '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold text-muted text-uppercase small">Terbanding</div>
                        <div class="col-sm-8 fw-normal text-uppercase text-dark">{{ $arsip->terbanding ?? '-' }}</div>
                    </div>

                    <hr class="my-4 opacity-25">

                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold text-muted text-uppercase small">Dokumen Putusan</div>
                        <div class="col-sm-8 fw-normal text-uppercase text-dark">{{ $arsip->putusan ?? 'BELUM ADA DOKUMEN PUTUSAN' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3 text-uppercase text-start">Status & Lokasi</h6>

                    <div class="mb-3">
                        <label class="small fw-bold text-muted d-block text-uppercase">Status Putusan</label>
                        <span class="badge bg-danger rounded-pill px-3">{{ $arsip->status_put ?? 'Banding' }}</span>
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold text-muted d-block text-uppercase">Tahun</label>
                        <span class="fw-bold fs-4 text-dark">{{ $arsip->tahun ?? '-' }}</span>
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold text-muted d-block text-uppercase">Lokasi Buku/Box</label>
                        <span class="fw-bold text-dark"><i class="fas fa-box me-1 text-warning"></i> {{ $arsip->buku ?? 'Belum Ditentukan' }}</span>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 text-center">
                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3 text-uppercase text-start">Berkas Digital</h6>
                    @if(!empty($arsip->putusan) && $arsip->putusan != 'null')
                    <div class="py-3">
                        <i class="fas fa-file-pdf fa-4x text-danger mb-3"></i>
                        <p class="small fw-bold text-dark mb-1 text-uppercase">Dokumen Digital Tersedia</p>
                        <small class="text-muted d-block mb-3" style="font-size: 0.7rem;">{{ $arsip->putusan }}</small>

                        <a href="{{ asset('storage/app/public/retensi_arsip_perkara/' . $arsip->putusan) }}" target="_blank" class="btn btn-danger w-100 rounded-pill shadow-sm fw-bold">
                            <i class="fas fa-download me-2"></i> LIHAT / DOWNLOAD PDF
                        </a>
                    </div>
                    @else
                    <div class="py-3">
                        <div class="opacity-50">
                            <i class="fas fa-file-circle-xmark fa-4x text-muted mb-3"></i>
                            <p class="small fw-bold text-muted text-uppercase mb-3">Belum Ada Scan PDF</p>
                        </div>
                        <a href="{{ route('retensi-arsip.edit', $arsip->id) }}" class="btn btn-outline-secondary btn-sm rounded-pill px-4">
                            <i class="fas fa-upload me-1"></i> Upload Sekarang
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-info-subtle {
        background-color: rgba(13, 202, 240, 0.1);
    }

    .text-uppercase {
        text-transform: uppercase;
    }

    .card {
        transition: 0.3s;
    }
</style>
@endsection