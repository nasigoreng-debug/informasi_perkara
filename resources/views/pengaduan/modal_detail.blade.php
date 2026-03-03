<div class="modal-header header-gradient-siwas py-3 border-0">
    <h5 class="modal-title font-weight-bold text-white tracking-wide small">
        <i class="fas fa-route me-2 opacity-75"></i> Detail & Tracking Alur SOP Pengaduan
    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body p-4 p-md-5 bg-white">
    <div class="row">
        <div class="col-md-5 border-end">
            <h6 class="text-danger fw-bold mb-4 d-flex align-items-center small">
                <span class="icon-circle bg-soft-danger me-2"><i class="fas fa-file-invoice"></i></span>
                Resume Pengaduan
            </h6>

            <div class="bg-soft-light p-4 rounded-lg border-soft mb-4">
                <div class="mb-3">
                    <label class="text-xxs font-weight-bold text-uppercase text-muted d-block mb-1">Nomor Pengaduan</label>
                    <span class="fw-bold text-dark small">{{ $pgd->no_pgd }}</span>
                </div>
                <div class="mb-3">
                    <label class="text-xxs font-weight-bold text-uppercase text-muted d-block mb-1">Identitas Pelapor</label>
                    <span class="fw-bold text-primary small">{{ $pgd->pelapor }}</span>
                </div>
                <div class="mb-3">
                    <label class="text-xxs font-weight-bold text-uppercase text-muted d-block mb-1">Identitas Terlapor</label>
                    <span class="fw-bold text-danger small">{{ $pgd->terlapor }}</span>
                </div>
                <div class="mb-0">
                    <label class="text-xxs font-weight-bold text-uppercase text-muted d-block mb-1">Perihal / Uraian</label>
                    <p class="small text-muted mb-0 italic" style="font-size: 0.75rem;">"{{ $pgd->uraian_pgd }}"</p>
                </div>
            </div>

            <h6 class="text-success fw-bold mb-3 small d-flex align-items-center">
                <i class="fas fa-paperclip me-2"></i> Dokumen Pendukung
            </h6>
            <div class="list-group list-group-flush border rounded overflow-hidden shadow-xs">
                @if($pgd->surat_pgd)
                <a href="{{ route('pengaduan.download', [$pgd->id, 'surat']) }}" class="list-group-item list-group-item-action small py-3 border-bottom" target="_blank">
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
            <h6 class="text-dark fw-bold mb-4 d-flex align-items-center small">
                <span class="icon-circle bg-dark text-white me-2"><i class="fas fa-history"></i></span>
                Progres Histori Berkas (SOP)
            </h6>

            <div class="vertical-tracking">
                @php
                // Logika penentuan titik biru (Active)
                $activePos = 'DITERIMA';
                if($pgd->dis_pm_hk) $activePos = 'PM-HK';
                if($pgd->dis_kpta) $activePos = 'KPTA';
                if($pgd->dis_wkpta) $activePos = 'WKPTA';
                if($pgd->dis_hatiwasda) $activePos = 'HATIWASDA';
                if($pgd->tgl_tindak_lanjut) $activePos = 'TINDAK-LANJUT';
                if($pgd->tgl_selesai_pgd) $activePos = 'SELESAI';
                @endphp

                @if($pgd->tgl_selesai_pgd)
                <div class="tracking-item {{ $activePos == 'SELESAI' ? 'current' : '' }}">
                    <div class="tracking-date">{{ \Carbon\Carbon::parse($pgd->tgl_selesai_pgd)->format('d M Y') }}<span>LHP</span></div>
                    <div class="tracking-dot"></div>
                    <div class="tracking-content">
                        <h6 class="fw-bold text-success mb-1 small">Pemeriksaan & Pembuatan LHP</h6>
                        <p class="text-muted text-xxs">Tim Pemeriksa melakukan pemeriksaan, membuat LHP, dan menyerahkan kepada Pimpinan.</p>
                    </div>
                </div>
                @endif

                @if($pgd->dis_hatiwasda)
                <div class="tracking-item {{ $activePos == 'HATIWASDA' ? 'current' : '' }}">
                    <div class="tracking-date">{{ \Carbon\Carbon::parse($pgd->dis_hatiwasda)->format('d M Y') }}<span>HATIWASDA</span></div>
                    <div class="tracking-dot"></div>
                    <div class="tracking-content">
                        <h6 class="fw-bold mb-1 small">Penelaahan oleh HATIWASDA</h6>
                        <p class="text-muted text-xxs">Hakim Tinggi Pengawas melakukan penelaahan dan melaporkan hasil kepada Wakil Ketua.</p>
                    </div>
                </div>
                @endif

                @if($pgd->dis_wkpta)
                <div class="tracking-item {{ $activePos == 'WKPTA' ? 'current' : '' }}">
                    <div class="tracking-date">{{ \Carbon\Carbon::parse($pgd->dis_wkpta)->format('d M Y') }}<span>WKPTA</span></div>
                    <div class="tracking-dot"></div>
                    <div class="tracking-content">
                        <h6 class="fw-bold mb-1 small">Disposisi Wakil Ketua</h6>
                        <p class="text-muted text-xxs">Wakil Ketua menelaah kelayakan pengaduan dan menunjuk Tim Pemeriksa.</p>
                    </div>
                </div>
                @endif

                @if($pgd->dis_kpta)
                <div class="tracking-item {{ $activePos == 'KPTA' ? 'current' : '' }}">
                    <div class="tracking-date">{{ \Carbon\Carbon::parse($pgd->dis_kpta)->format('d M Y') }}<span>KETUA</span></div>
                    <div class="tracking-dot"></div>
                    <div class="tracking-content">
                        <h6 class="fw-bold mb-1 small">Disposisi Ketua PTA</h6>
                        <p class="text-muted text-xxs">Ketua mempelajari berkas dan mendisposisikan kepada Wakil Ketua.</p>
                    </div>
                </div>
                @endif

                @if($pgd->dis_pm_hk)
                <div class="tracking-item {{ $activePos == 'PM-HK' ? 'current' : '' }}">
                    <div class="tracking-date">{{ \Carbon\Carbon::parse($pgd->dis_pm_hk)->format('d M Y') }}<span>REGISTER</span></div>
                    <div class="tracking-dot"></div>
                    <div class="tracking-content">
                        <h6 class="fw-bold mb-1 small">Registrasi & Aplikasi Siwas</h6>
                        <p class="text-muted text-xxs">Panitera Muda Hukum mencatat pada Buku Register dan menginput ke Siwas.</p>
                    </div>
                </div>
                @endif

                <div class="tracking-item {{ $activePos == 'DITERIMA' ? 'current' : '' }}">
                    <div class="tracking-date">{{ \Carbon\Carbon::parse($pgd->tgl_terima_pgd)->format('d M Y') }}<span>START</span></div>
                    <div class="tracking-dot"></div>
                    <div class="tracking-content">
                        <h6 class="fw-bold text-dark mb-1 small">Menerima Pengaduan</h6>
                        <p class="text-muted text-xxs">Penerimaan berkas pengaduan masyarakat atau delegasi Bawas MARI.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .header-gradient-siwas {
        background: linear-gradient(135deg, #8b0000 0%, #000000 100%);
    }

    .bg-soft-danger {
        background-color: rgba(139, 0, 0, 0.1);
        color: #8b0000;
    }

    .icon-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
    }

    .rounded-lg {
        border-radius: 1rem;
    }

    .border-soft {
        border: 1px dashed #cbd5e1;
    }

    .text-xxs {
        font-size: 0.65rem;
        letter-spacing: 0.05em;
    }

    .shadow-xs {
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    /* Timeline Vertikal Pak Admin */
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
        margin-bottom: 30px;
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
        padding-top: 0;
    }
</style>