@extends('layouts.app')

@section('title', 'Tambah Surat Keluar | PTA Bandung')

@section('content')
<div class="container py-4 fade-in">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card modern-card border-0">
                <div class="card-header header-gradient py-3 d-flex justify-content-between align-items-center border-0">
                    <div class="d-flex align-items-center">
                        <h5 class="m-0 font-weight-bold text-white tracking-wide">
                            <i class="fas fa-paper-plane me-2 opacity-75"></i> Tambah Surat Keluar
                        </h5>
                    </div>
                    <a href="{{ route('surat.keluar.index') }}" class="btn btn-light btn-sm rounded-pill px-4 text-primary font-weight-bold shadow-sm hover-elevate">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>

                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('surat.keluar.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-5">
                            <h6 class="text-primary fw-bold mb-3 d-flex align-items-center">
                                <span class="icon-circle bg-soft-primary me-2"><i class="fas fa-info-circle"></i></span>
                                Informasi Utama Surat
                            </h6>
                            <div class="bg-soft-light p-4 rounded-lg border-soft">
                                <div class="row g-3">
                                    <div class="col-md-7">
                                        <label class="text-xs font-weight-bold text-uppercase text-muted mb-1">Nomor Surat</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-hashtag text-muted"></i></span>
                                            <input type="text" name="no_surat" class="form-control modern-input border-start-0 ps-0" placeholder="W11-A/..." required>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="text-xs font-weight-bold text-uppercase text-muted mb-1">Tanggal Surat</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-calendar-alt text-muted"></i></span>
                                            <input type="date" name="tgl_surat" class="form-control modern-input border-start-0 ps-0" value="{{ date('Y-m-d') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="text-xs font-weight-bold text-uppercase text-muted mb-1">Tujuan Surat (Satker Tujuan)</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-building text-muted"></i></span>
                                            <select name="tujuan_surat" class="form-select modern-input border-start-0 ps-0" required>
                                                <option value="" disabled selected>-- Pilih Pengadilan Agama Tujuan --</option>
                                                <option value="Bandung">Bandung</option>
                                                <option value="Indramayu">Indramayu</option>
                                                <option value="Majalengka">Majalengka</option>
                                                <option value="Sumber">Sumber</option>
                                                <option value="Ciamis">Ciamis</option>
                                                <option value="Tasikmalaya">Tasikmalaya</option>
                                                <option value="Karawang">Karawang</option>
                                                <option value="Cimahi">Cimahi</option>
                                                <option value="Subang">Subang</option>
                                                <option value="Sumedang">Sumedang</option>
                                                <option value="Purwakarta">Purwakarta</option>
                                                <option value="Sukabumi">Sukabumi</option>
                                                <option value="Cianjur">Cianjur</option>
                                                <option value="Kuningan">Kuningan</option>
                                                <option value="Cibadak">Cibadak</option>
                                                <option value="Cirebon">Cirebon</option>
                                                <option value="Garut">Garut</option>
                                                <option value="Bogor">Bogor</option>
                                                <option value="Bekasi">Bekasi</option>
                                                <option value="Cibinong">Cibinong</option>
                                                <option value="Cikarang">Cikarang</option>
                                                <option value="Depok">Depok</option>
                                                <option value="Tasikmalaya Kota">Kota Tasikmalaya</option>
                                                <option value="Banjar">Banjar</option>
                                                <option value="Soreang">Soreang</option>
                                                <option value="Ngamprah">Ngamprah</option>
                                                <option value="Lain-lain / Luar Wilayah">Lain-lain / Luar Wilayah</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="text-xs font-weight-bold text-uppercase text-muted mb-1">Perihal</label>
                                        <textarea name="perihal" class="form-control modern-input" rows="3" placeholder="Uraikan perihal surat..." required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-primary fw-bold mb-3 d-flex align-items-center">
                                <span class="icon-circle bg-soft-primary me-2"><i class="fas fa-file-upload"></i></span>
                                Berkas Digital
                            </h6>
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="p-3 border rounded-lg bg-white shadow-sm border-start border-danger border-4">
                                        <label class="text-xs font-weight-bold text-uppercase text-danger mb-2 d-block">Surat Resmi (PDF)</label>
                                        <input type="file" name="surat_pta" class="form-control form-control-sm" accept=".pdf">
                                        <small class="text-muted mt-2 d-block small">File hasil scan/tanda tangan (Maks 10MB)</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 border rounded-lg bg-white shadow-sm border-start border-primary border-4">
                                        <label class="text-xs font-weight-bold text-uppercase text-primary mb-2 d-block">Konsep Surat (Word/RTF)</label>
                                        <input type="file" name="konsep_surat" class="form-control form-control-sm" accept=".docx,.doc,.rtf">
                                        <small class="text-muted mt-2 d-block small">File konsep untuk arsip edit (Maks 10MB)</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="text-xs font-weight-bold text-uppercase text-muted mb-1">Keterangan Tambahan (Opsional)</label>
                            <textarea name="keterangan" class="form-control modern-input" rows="2" placeholder="Catatan internal..."></textarea>
                        </div>

                        <div class="text-end border-top pt-4">
                            <button type="reset" class="btn btn-light rounded-pill px-4 me-2 fw-bold text-muted">Reset</button>
                            <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-bold shadow-sm hover-elevate">
                                <i class="fas fa-save me-2"></i> Simpan Arsip Keluar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* CSS Tambahan khusus untuk mempercantik form */
    .border-soft {
        border: 1px dashed #cbd5e1;
    }

    .bg-soft-light {
        background-color: #f8fafc;
    }

    .rounded-lg {
        border-radius: 1rem;
    }

    .icon-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
    }

    .modern-input:focus {
        box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.1);
        border-color: #4e73df;
    }
</style>
@endsection