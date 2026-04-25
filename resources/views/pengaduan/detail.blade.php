@extends('layouts.app')

@section('content')
<div class="container py-4 fade-in">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="card modern-card border-0 shadow-lg">
                <div class="card-header header-gradient-siwas py-3 d-flex justify-content-between align-items-center border-0">
                    <h5 class="m-0 font-weight-bold text-white tracking-wide">
                        <i class="fas fa-route me-2 opacity-75"></i> Detail & Tracking Alur SOP Pengaduan
                    </h5>
                    <a href="{{ route('pengaduan.index') }}" class="btn btn-light btn-sm rounded-pill px-4 text-danger font-weight-bold shadow-sm hover-elevate">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>

                <div class="card-body p-4 p-md-5">
                    <div class="row">
                        <div class="col-md-5 border-end">
                            <h6 class="text-danger fw-bold mb-4 d-flex align-items-center">
                                <span class="icon-circle bg-soft-danger me-2"><i class="fas fa-file-invoice"></i></span>
                                Resume Pengaduan
                            </h6>

                            <div class="bg-soft-light p-4 rounded-lg border-soft mb-4">
                                <div class="mb-3">
                                    <label class="text-xxs font-weight-bold text-uppercase text-muted d-block">Nomor Register Pengaduan</label>
                                    <span class="fw-bold text-dark">{{ $pgd->no_pgd }}</span>
                                </div>
                                <div class="mb-3">
                                    <label class="text-xxs font-weight-bold text-uppercase text-muted d-block">Nomor Surat Pengaduan</label>
                                    <span class="fw-bold text-dark">{{ $pgd->no_surat_pgd }}</span>
                                </div> {{-- PERBAIKAN: tutup div mb-3 yang kurang --}}
                                <div class="mb-3">
                                    <label class="text-xxs font-weight-bold text-uppercase text-muted d-block">Identitas Pelapor</label>
                                    <span class="fw-bold text-primary">{{ $pgd->pelapor }}</span>
                                </div>
                                <div class="mb-3">
                                    <label class="text-xxs font-weight-bold text-uppercase text-muted d-block">Identitas Terlapor</label>
                                    <span class="fw-bold text-danger">{{ $pgd->terlapor }}</span>
                                </div>
                                <div class="mb-0">
                                    <label class="text-xxs font-weight-bold text-uppercase text-muted d-block">Perihal / Uraian</label>
                                    <p class="small text-muted mb-0 italic">"{{ $pgd->uraian_pgd }}"</p>
                                </div>
                            </div>

                            <h6 class="text-success fw-bold mb-3 small d-flex align-items-center">
                                <i class="fas fa-paperclip me-2"></i> Dokumen Pendukung
                            </h6>
                            <div class="list-group list-group-flush border rounded overflow-hidden">
                                @if($pgd->surat_pgd)
                                <a href="{{ route('pengaduan.download', [$pgd->id, 'surat']) }}" class="list-group-item list-group-item-action small py-3" target="_blank">
                                    <i class="fas fa-file-pdf text-danger me-2"></i> Surat Pengaduan / Delegasi
                                </a>
                                @endif
                                @if($pgd->lampiran)
                                <a href="{{ route('pengaduan.download', [$pgd->id, 'lampiran']) }}" class="list-group-item list-group-item-action small py-3" target="_blank">
                                    <i class="fas fa-file-pdf text-danger me-2"></i> Dokumen LHP / SPT
                                </a>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-7 ps-md-5 mt-4 mt-md-0">
                            <h6 class="text-dark fw-bold mb-4 d-flex align-items-center">
                                <span class="icon-circle bg-dark text-white me-2"><i class="fas fa-history"></i></span>
                                Progres Histori Berkas (SOP)
                            </h6>

                            <div class="vertical-tracking">
                                @php
                                // PERBAIKAN: Logika Active dari tahap AKHIR ke AWAL dengan elseif
                                $activePos = 'DITERIMA';
                                if($pgd->tgl_selesai_pgd) {
                                $activePos = 'SELESAI';
                                } elseif($pgd->tgl_tindak_lanjut) {
                                $activePos = 'TINDAK-LANJUT';
                                } elseif($pgd->dis_hatiwasda) {
                                $activePos = 'HATIWASDA';
                                } elseif($pgd->dis_wkpta) {
                                $activePos = 'WKPTA';
                                } elseif($pgd->dis_kpta) {
                                $activePos = 'KPTA';
                                } elseif($pgd->dis_pm_hk) {
                                $activePos = 'PM-HK';
                                }
                                @endphp

                                @if($pgd->tgl_selesai_pgd)
                                <div class="tracking-item {{ $activePos == 'SELESAI' ? 'current' : '' }}">
                                    <div class="tracking-date">{{ \Carbon\Carbon::parse($pgd->tgl_selesai_pgd)->format('d M Y') }}<span>FINAL</span></div>
                                    <div class="tracking-dot"></div>
                                    <div class="tracking-content">
                                        <h6 class="fw-bold text-success">Laporan LHP ke Bawas MARI</h6>
                                        <p class="text-muted small">Ketua memerintahkan Panmud Hukum melaporkan hasil pemeriksaan ke Bawas MARI.</p>
                                    </div>
                                </div>
                                @endif

                                @if($pgd->tgl_tindak_lanjut)
                                <div class="tracking-item {{ $activePos == 'TINDAK-LANJUT' ? 'current' : '' }}">
                                    <div class="tracking-date">{{ \Carbon\Carbon::parse($pgd->tgl_tindak_lanjut)->format('d M Y') }}<span>LHP</span></div>
                                    <div class="tracking-dot"></div>
                                    <div class="tracking-content">
                                        <h6 class="fw-bold">Pemeriksaan & Pembuatan LHP</h6>
                                        <p class="text-muted small">Tim Pemeriksa melakukan pemeriksaan, membuat LHP, dan menyerahkan kepada Pimpinan.</p>
                                    </div>
                                </div>
                                @endif

                                @if($pgd->dis_hatiwasda)
                                <div class="tracking-item {{ $activePos == 'HATIWASDA' ? 'current' : '' }}">
                                    <div class="tracking-date">{{ \Carbon\Carbon::parse($pgd->dis_hatiwasda)->format('d M Y') }}<span>HATIWASDA</span></div>
                                    <div class="tracking-dot"></div>
                                    <div class="tracking-content">
                                        <h6 class="fw-bold">Penelaahan oleh HATIWASDA</h6>
                                        <p class="text-muted small">Hakim Tinggi Pengawas melakukan penelaahan dan melaporkan hasil kepada Wakil Ketua.</p>
                                    </div>
                                </div>
                                @endif

                                @if($pgd->dis_wkpta)
                                <div class="tracking-item {{ $activePos == 'WKPTA' ? 'current' : '' }}">
                                    <div class="tracking-date">{{ \Carbon\Carbon::parse($pgd->dis_wkpta)->format('d M Y') }}<span>WKPTA</span></div>
                                    <div class="tracking-dot"></div>
                                    <div class="tracking-content">
                                        <h6 class="fw-bold">Disposisi Wakil Ketua</h6>
                                        <p class="text-muted small">Wakil Ketua menelaah kelayakan pengaduan dan menunjuk Tim Pemeriksa.</p>
                                    </div>
                                </div>
                                @endif

                                @if($pgd->dis_kpta)
                                <div class="tracking-item {{ $activePos == 'KPTA' ? 'current' : '' }}">
                                    <div class="tracking-date">{{ \Carbon\Carbon::parse($pgd->dis_kpta)->format('d M Y') }}<span>KETUA</span></div>
                                    <div class="tracking-dot"></div>
                                    <div class="tracking-content text-muted">
                                        <h6 class="fw-bold text-dark">Disposisi Ketua PTA</h6>
                                        <p class="text-muted small">Ketua mempelajari berkas dan mendisposisikan kepada Wakil Ketua.</p>
                                    </div>
                                </div>
                                @endif

                                @if($pgd->dis_pm_hk)
                                <div class="tracking-item {{ $activePos == 'PM-HK' ? 'current' : '' }}">
                                    <div class="tracking-date">{{ \Carbon\Carbon::parse($pgd->dis_pm_hk)->format('d M Y') }}<span>REGISTER</span></div>
                                    <div class="tracking-dot"></div>
                                    <div class="tracking-content">
                                        <h6 class="fw-bold text-dark">Registrasi</h6>
                                        <p class="text-muted small">Panitera Muda Hukum mencatat pada Buku Register.</p>
                                    </div>
                                </div>
                                @endif

                                <div class="tracking-item {{ $activePos == 'DITERIMA' ? 'current' : '' }}">
                                    <div class="tracking-date">{{ \Carbon\Carbon::parse($pgd->tgl_terima_pgd)->format('d M Y') }}<span>DITERIMA</span></div>
                                    <div class="tracking-dot"></div>
                                    <div class="tracking-content">
                                        <h6 class="fw-bold text-dark">Menerima Pengaduan</h6>
                                        <p class="text-muted small">Penerimaan berkas pengaduan masyarakat atau delegasi Bawas MARI.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .vertical-tracking {
        position: relative;
        padding: 10px 0;
    }

    .vertical-tracking::before {
        content: '';
        position: absolute;
        left: 107px;
        top: 0;
        height: 100%;
        width: 2px;
        background: #e3e6f0;
    }

    .tracking-item {
        position: relative;
        display: flex;
        align-items: flex-start;
        margin-bottom: 35px;
    }

    .tracking-date {
        width: 90px;
        text-align: right;
        font-size: 0.7rem;
        font-weight: 800;
        color: #5a5c69;
        line-height: 1.2;
    }

    .tracking-date span {
        display: block;
        font-size: 0.6rem;
        color: #b7b9cc;
        text-transform: uppercase;
    }

    .tracking-dot {
        position: relative;
        width: 14px;
        height: 14px;
        background: #fff;
        border: 3px solid #d1d3e2;
        border-radius: 50%;
        margin: 0 15px;
        z-index: 2;
        margin-top: 2px;
    }

    .tracking-item.current .tracking-dot {
        background: #4e73df;
        border-color: #fff;
        box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.3);
    }

    .tracking-content {
        flex: 1;
    }

    .header-gradient-siwas {
        background: linear-gradient(135deg, #8b0000 0%, #000000 100%);
    }

    .text-xxs {
        font-size: 0.65rem;
        letter-spacing: 0.05em;
    }

    .border-soft {
        border: 1px dashed #cbd5e1;
    }
</style>
@endsection