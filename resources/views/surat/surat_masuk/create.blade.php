@extends('layouts.app')

@section('content')
<div class="container py-5 fade-in">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="card border-0 shadow-lg rounded-xl overflow-hidden">
                <div class="card-header bg-gradient-primary py-4 px-5 d-flex justify-content-between align-items-center border-0">
                    <div>
                        <h4 class="m-0 font-weight-bold text-white tracking-tight">
                            <i class="fas fa-file-import mr-2 opacity-75"></i> Registrasi Surat Masuk
                        </h4>
                        <p class="text-white-50 small mb-0 mt-1">Pastikan seluruh data identitas surat terisi dengan benar.</p>
                    </div>
                    <a href="{{ route('surat.masuk.index') }}" class="btn btn-glass btn-sm rounded-pill px-4 fw-bold">
                        <i class="fas fa-chevron-left mr-1"></i> Kembali
                    </a>
                </div>

                <div class="card-body p-4 p-md-5 bg-white">
                    <form action="{{ route('surat.masuk.store') }}" method="POST" enctype="multipart/form-data" id="formSuratMasuk">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-4 border-right-dashed px-4">
                                <div class="section-title mb-4">
                                    <span class="badge badge-soft-primary mb-2">Langkah 1</span>
                                    <h6 class="font-weight-bold text-dark text-uppercase letter-spacing-1">Identitas Surat</h6>
                                </div>
                                
                                <div class="form-group mb-4">
                                    <label class="label-modern">Nomor Indeks</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light border-right-0"><i class="fas fa-hashtag text-muted"></i></span>
                                        </div>
                                        <input type="number" name="no_indeks" class="form-control modern-input" value="{{ $nextIndeks }}" required>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="label-modern">Nomor Surat Resmi</label>
                                    <input type="text" name="no_surat" class="form-control modern-input shadow-none" placeholder="Contoh: W10-A/123/HK.05/I/2026" required>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="label-modern">Tanggal Surat</label>
                                    <input type="date" name="tgl_surat" class="form-control modern-input" value="{{ date('Y-m-d') }}" required>
                                </div>

                                <div class="form-group">
                                    <label class="label-modern">Asal Instansi</label>
                                    <input type="text" name="asal_surat" class="form-control modern-input" placeholder="Nama Instansi Pengirim" required>
                                </div>
                            </div>

                            <div class="col-md-4 border-right-dashed px-4">
                                <div class="section-title mb-4">
                                    <span class="badge badge-soft-info mb-2">Langkah 2</span>
                                    <h6 class="font-weight-bold text-dark text-uppercase letter-spacing-1">Arus Birokrasi</h6>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="label-modern text-info">Tgl Masuk Bag. Umum</label>
                                    <input type="date" name="tgl_masuk_umum" class="form-control modern-input border-info-soft" value="{{ date('Y-m-d') }}">
                                </div>

                                <div class="form-group mb-4">
                                    <label class="label-modern">Tgl Masuk Kepaniteraan</label>
                                    <input type="date" name="tgl_masuk_pan" class="form-control modern-input">
                                </div>

                                <div class="form-group">
                                    <label class="label-modern">Tujuan Disposisi</label>
                                    <select name="disposisi" class="form-control modern-input select-custom">
                                        <option value="">Pilih Pejabat...</option>
                                        <option value="Ketua">Ketua</option>
                                        <option value="Wakil Ketua">Wakil Ketua</option>
                                        <option value="Panitera">Panitera</option>
                                        <option value="Sekretaris">Sekretaris</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4 px-4">
                                <div class="section-title mb-4">
                                    <span class="badge badge-soft-success mb-2">Langkah 3</span>
                                    <h6 class="font-weight-bold text-dark text-uppercase letter-spacing-1">Detail Isi</h6>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="label-modern">Perihal / Ringkasan</label>
                                    <textarea name="perihal" class="form-control modern-input no-resize" rows="3" placeholder="Apa inti dari surat ini?" required></textarea>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="label-modern">Catatan / Keterangan</label>
                                    <textarea name="keterangan" class="form-control modern-input no-resize" rows="2" placeholder="Catatan tambahan jika ada..."></textarea>
                                </div>

                                <div class="form-group mb-0">
                                    <label class="label-modern">Dokumen Digital (PDF)</label>
                                    <div id="drop-area" class="upload-container d-flex flex-column align-items-center justify-content-center">
                                        <div class="upload-icon mb-2">
                                            <i class="fas fa-file-pdf fa-2x text-danger"></i>
                                        </div>
                                        <p class="small font-weight-bold mb-1" id="file-name text-dark">Tarik file ke sini</p>
                                        <p class="text-muted mb-0" style="font-size: 10px;">Atau klik untuk pilih file</p>
                                        <input type="file" name="lampiran" id="file-input" class="d-none" accept=".pdf,.jpg,.jpeg,.png">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 d-flex justify-content-between align-items-center bg-soft-light p-4 rounded-lg">
                            <div class="text-muted small italic">
                                <i class="fas fa-info-circle mr-1"></i> Data yang disimpan akan otomatis masuk ke buku register digital.
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow-lg fw-bold hover-lift">
                                <i class="fas fa-save mr-2"></i> Simpan Arsip
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        --soft-primary: #eef2ff;
        --border-color: #e3e6f0;
    }

    .rounded-xl { border-radius: 1.25rem !important; }
    .bg-gradient-primary { background: var(--primary-gradient); }
    
    .border-right-dashed {
        border-right: 1px dashed var(--border-color);
    }

    .label-modern {
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        color: #4e73df;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
        display: block;
    }

    .modern-input {
        border-radius: 0.75rem;
        border: 1.5px solid var(--border-color);
        padding: 0.6rem 1rem;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        background-color: #f8f9fc;
    }

    .modern-input:focus {
        background-color: #fff;
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.1);
    }

    .badge-soft-primary { background: #e8ebf8; color: #4e73df; }
    .badge-soft-info { background: #e0f2f1; color: #00897b; }
    .badge-soft-success { background: #e8f5e9; color: #2e7d32; }

    .upload-container {
        border: 2px dashed #d1d3e2;
        border-radius: 1rem;
        padding: 1.5rem;
        cursor: pointer;
        transition: 0.3s;
        background: #fdfdfd;
    }

    .upload-container:hover {
        background: #f1f4ff;
        border-color: #4e73df;
    }

    .btn-glass {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(5px);
    }

    .btn-glass:hover {
        background: rgba(255, 255, 255, 0.3);
        color: white;
    }

    .hover-lift:hover {
        transform: translateY(-2px);
    }

    .no-resize { resize: none; }
    
    @media (max-width: 768px) {
        .border-right-dashed { border-right: none; border-bottom: 1px dashed var(--border-color); margin-bottom: 2rem; padding-bottom: 2rem; }
    }
</style>

<script>
    document.getElementById('drop-area').onclick = function() {
        document.getElementById('file-input').click();
    };

    document.getElementById('file-input').onchange = function() {
        let name = this.files[0].name;
        document.querySelector('#drop-area p').innerText = name;
        document.getElementById('drop-area').classList.add('bg-soft-primary');
    };
</script>
@endsection