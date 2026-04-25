@extends('layouts.app')

@section('content')
<div class="container py-5 fade-in">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="card border-0 shadow-lg rounded-xl overflow-hidden">
                <div class="card-header bg-gradient-warning py-4 px-5 d-flex justify-content-between align-items-center border-0">
                    <div>
                        <h4 class="m-0 font-weight-bold text-white tracking-tight">
                            <i class="fas fa-edit mr-2 opacity-75"></i> Edit Surat Masuk
                        </h4>
                        <p class="text-white-50 small mb-0 mt-1">Perbaharui data arsip surat yang sudah tersimpan.</p>
                    </div>
                    <a href="{{ route('surat.masuk.index') }}" class="btn btn-glass btn-sm rounded-pill px-4 fw-bold">
                        <i class="fas fa-chevron-left mr-1"></i> Kembali
                    </a>
                </div>

                <div class="card-body p-4 p-md-5 bg-white">
                    <form action="{{ route('surat.masuk.update', $surat->id) }}" method="POST" enctype="multipart/form-data" id="formSuratMasuk">
                        @csrf
                        @method('PUT')
                        <div class="row g-4">
                            <div class="col-md-4 border-right-dashed px-4">
                                <div class="section-title mb-4">
                                    <span class="badge badge-soft-primary mb-2">Identitas</span>
                                    <h6 class="font-weight-bold text-dark text-uppercase letter-spacing-1">Identitas Surat</h6>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="label-modern">Nomor Indeks <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light border-right-0"><i class="fas fa-hashtag text-muted"></i></span>
                                        </div>
                                        <input type="number" name="no_indeks" class="form-control modern-input @error('no_indeks') is-invalid @enderror" value="{{ old('no_indeks', $surat->no_indeks) }}" required>
                                    </div>
                                    <small class="text-muted form-text">*Indeks bebas, bisa diisi sesuai kebutuhan</small>
                                    @error('no_indeks')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-4">
                                    <label class="label-modern">Nomor Surat Resmi <span class="text-danger">*</span></label>
                                    <input type="text" name="no_surat" class="form-control modern-input @error('no_surat') is-invalid @enderror" value="{{ old('no_surat', $surat->no_surat) }}" placeholder="Contoh: W10-A/123/HK.05/I/2026" required>
                                    @error('no_surat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-4">
                                    <label class="label-modern">Tanggal Surat <span class="text-danger">*</span></label>
                                    <input type="date" name="tgl_surat" class="form-control modern-input @error('tgl_surat') is-invalid @enderror" value="{{ old('tgl_surat', $surat->tgl_surat) }}" required>
                                    @error('tgl_surat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="label-modern">Asal Instansi <span class="text-danger">*</span></label>
                                    <select name="asal_surat" class="form-control modern-input @error('asal_surat') is-invalid @enderror" required>
                                        <option value="">--Pilih Instansi Asal--</option>
                                        <option value="Advokat" {{ old('asal_surat', $surat->asal_surat) == 'Advokat' ? 'selected' : '' }}>Advokat</option>
                                        <option value="Badan Pengawasan MA RI" {{ old('asal_surat', $surat->asal_surat) == 'Badan Pengawasan MA RI' ? 'selected' : '' }}>Badan Pengawasan MA RI</option>
                                        <option value="Pengadilan Agama Bandung" {{ old('asal_surat', $surat->asal_surat) == 'Pengadilan Agama Bandung' ? 'selected' : '' }}>Pengadilan Agama Bandung</option>
                                        <option value="Pengadilan Agama Bekasi" {{ old('asal_surat', $surat->asal_surat) == 'Pengadilan Agama Bekasi' ? 'selected' : '' }}>Pengadilan Agama Bekasi</option>
                                        <option value="Pengadilan Agama Bogor" {{ old('asal_surat', $surat->asal_surat) == 'Pengadilan Agama Bogor' ? 'selected' : '' }}>Pengadilan Agama Bogor</option>
                                        <option value="Pengadilan Agama Ciamis" {{ old('asal_surat', $surat->asal_surat) == 'Pengadilan Agama Ciamis' ? 'selected' : '' }}>Pengadilan Agama Ciamis</option>
                                        <option value="Pengadilan Agama Cianjur" {{ old('asal_surat', $surat->asal_surat) == 'Pengadilan Agama Cianjur' ? 'selected' : '' }}>Pengadilan Agama Cianjur</option>
                                        <option value="Pengadilan Agama Cibadak" {{ old('asal_surat', $surat->asal_surat) == 'Pengadilan Agama Cibadak' ? 'selected' : '' }}>Pengadilan Agama Cibadak</option>
                                        <option value="Pengadilan Agama Cibinong" {{ old('asal_surat', $surat->asal_surat) == 'Pengadilan Agama Cibinong' ? 'selected' : '' }}>Pengadilan Agama Cibinong</option>
                                        <option value="Pengadilan Agama Cikarang" {{ old('asal_surat', $surat->asal_surat) == 'Pengadilan Agama Cikarang' ? 'selected' : '' }}>Pengadilan Agama Cikarang</option>
                                        <option value="Pengadilan Agama Cimahi" {{ old('asal_surat', $surat->asal_surat) == 'Pengadilan Agama Cimahi' ? 'selected' : '' }}>Pengadilan Agama Cimahi</option>
                                        <option value="Pengadilan Agama Cirebon" {{ old('asal_surat', $surat->asal_surat) == 'Pengadilan Agama Cirebon' ? 'selected' : '' }}>Pengadilan Agama Cirebon</option>
                                        <option value="Pengadilan Agama Depok" {{ old('asal_surat', $surat->asal_surat) == 'Pengadilan Agama Depok' ? 'selected' : '' }}>Pengadilan Agama Depok</option>
                                        <option value="Direktorat Jenderal Badan Peradilan Agama" {{ old('asal_surat', $surat->asal_surat) == 'Direktorat Jenderal Badan Peradilan Agama' ? 'selected' : '' }}>Direktorat Jenderal Badan Peradilan Agama</option>
                                        <option value="Pengadilan Agama Garut" {{ old('asal_surat', $surat->asal_surat) == 'Pengadilan Agama Garut' ? 'selected' : '' }}>Pengadilan Agama Garut</option>
                                        <option value="Pengadilan Agama Indramayu" {{ old('asal_surat', $surat->asal_surat) == 'Pengadilan Agama Indramayu' ? 'selected' : '' }}>Pengadilan Agama Indramayu</option>
                                        <option value="Instansi Lain" {{ old('asal_surat', $surat->asal_surat) == 'Instansi Lain' ? 'selected' : '' }}>Instansi Lain</option>
                                        <option value="Pengadilan Agama Karawang" {{ old('asal_surat', $surat->asal_surat) == 'Pengadilan Agama Karawang' ? 'selected' : '' }}>Pengadilan Agama Karawang</option>
                                        <option value="Pengadilan Agama Kota Banjar" {{ old('asal_surat', $surat->asal_surat) == 'Pengadilan Agama Kota Banjar' ? 'selected' : '' }}>Pengadilan Agama Kota Banjar</option>
                                        <option value="Pengadilan Agama Kota Tasikmalaya" {{ old('asal_surat', $surat->asal_surat) == 'Pengadilan Agama Kota Tasikmalaya' ? 'selected' : '' }}>Pengadilan Agama Kota Tasikmalaya</option>
                                        <option value="Pengadilan Agama Kuningan" {{ old('asal_surat', $surat->asal_surat) == 'Pengadilan Agama Kuningan' ? 'selected' : '' }}>Pengadilan Agama Kuningan</option>
                                        <option value="Lain-lain" {{ old('asal_surat', $surat->asal_surat) == 'Lain-lain' ? 'selected' : '' }}>Lain-lain</option>
                                        <option value="Mahkamah Agung Republik Indonesia" {{ old('asal_surat', $surat->asal_surat) == 'Mahkamah Agung Republik Indonesia' ? 'selected' : '' }}>Mahkamah Agung Republik Indonesia</option>
                                        <option value="Kepaniteraan Mahkamah Agung Republik Indonesia" {{ old('asal_surat', $surat->asal_surat) == 'Kepaniteraan Mahkamah Agung Republik Indonesia' ? 'selected' : '' }}>Kepaniteraan Mahkamah Agung Republik Indonesia</option>
                                        <option value="Pengadilan Agama Majalengka" {{ old('asal_surat', $surat->asal_surat) == 'Pengadilan Agama Majalengka' ? 'selected' : '' }}>Pengadilan Agama Majalengka</option>
                                        <option value="Pengadilan Agama Ngamprah" {{ old('asal_surat', $surat->asal_surat) == 'Pengadilan Agama Ngamprah' ? 'selected' : '' }}>Pengadilan Agama Ngamprah</option>
                                        <option value="Prinsipal" {{ old('asal_surat', $surat->asal_surat) == 'Prinsipal' ? 'selected' : '' }}>Prinsipal</option>
                                        <option value="Pengadilan Agama Purwakarta" {{ old('asal_surat', $surat->asal_surat) == 'Pengadilan Agama Purwakarta' ? 'selected' : '' }}>Pengadilan Agama Purwakarta</option>
                                        <option value="Pengadilan Agama Soreang" {{ old('asal_surat', $surat->asal_surat) == 'Pengadilan Agama Soreang' ? 'selected' : '' }}>Pengadilan Agama Soreang</option>
                                        <option value="Pengadilan Agama Subang" {{ old('asal_surat', $surat->asal_surat) == 'Pengadilan Agama Subang' ? 'selected' : '' }}>Pengadilan Agama Subang</option>
                                        <option value="Pengadilan Agama Sukabumi" {{ old('asal_surat', $surat->asal_surat) == 'Pengadilan Agama Sukabumi' ? 'selected' : '' }}>Pengadilan Agama Sukabumi</option>
                                        <option value="Pengadilan Agama Sumber" {{ old('asal_surat', $surat->asal_surat) == 'Pengadilan Agama Sumber' ? 'selected' : '' }}>Pengadilan Agama Sumber</option>
                                        <option value="Pengadilan Agama Sumedang" {{ old('asal_surat', $surat->asal_surat) == 'Pengadilan Agama Sumedang' ? 'selected' : '' }}>Pengadilan Agama Sumedang</option>
                                        <option value="Pengadilan Agama Tasikmalaya" {{ old('asal_surat', $surat->asal_surat) == 'Pengadilan Agama Tasikmalaya' ? 'selected' : '' }}>Pengadilan Agama Tasikmalaya</option>
                                    </select>
                                    @error('asal_surat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4 border-right-dashed px-4">
                                <div class="section-title mb-4">
                                    <span class="badge badge-soft-info mb-2">Arsip</span>
                                    <h6 class="font-weight-bold text-dark text-uppercase letter-spacing-1">Arus Birokrasi</h6>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="label-modern text-info">Tgl Masuk Bag. Umum <span class="text-danger">*</span></label>
                                    <input type="date" name="tgl_masuk_umum" class="form-control modern-input @error('tgl_masuk_umum') is-invalid @enderror" value="{{ old('tgl_masuk_umum', $surat->tgl_masuk_umum) }}" required>
                                    @error('tgl_masuk_umum')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-4">
                                    <label class="label-modern">Tgl Masuk Kepaniteraan <span class="text-danger">*</span></label>
                                    <input type="date" name="tgl_masuk_pan" class="form-control modern-input @error('tgl_masuk_pan') is-invalid @enderror" value="{{ old('tgl_masuk_pan', $surat->tgl_masuk_pan) }}" required>
                                    @error('tgl_masuk_pan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="label-modern">Tujuan Disposisi <span class="text-danger">*</span></label>
                                    <select name="disposisi" class="form-control modern-input @error('disposisi') is-invalid @enderror" required>
                                        <option value="">--Pilih--</option>
                                        @foreach ($users as $user)
                                        <option value="{{ $user->name }}" {{ old('disposisi', $surat->disposisi) == $user->name ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('disposisi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4 px-4">
                                <div class="section-title mb-4">
                                    <span class="badge badge-soft-success mb-2">Konten</span>
                                    <h6 class="font-weight-bold text-dark text-uppercase letter-spacing-1">Detail Isi</h6>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="label-modern">Perihal / Ringkasan <span class="text-danger">*</span></label>
                                    <textarea name="perihal" class="form-control modern-input no-resize @error('perihal') is-invalid @enderror" rows="3" placeholder="Apa inti dari surat ini?" required>{{ old('perihal', $surat->perihal) }}</textarea>
                                    @error('perihal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-4">
                                    <label class="label-modern">Catatan / Keterangan <span class="text-danger">*</span></label>
                                    <textarea name="keterangan" class="form-control modern-input no-resize @error('keterangan') is-invalid @enderror" rows="2" placeholder="Catatan tambahan jika ada..." required>{{ old('keterangan', $surat->keterangan) }}</textarea>
                                    @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-0">
                                    <label class="label-modern">Dokumen Digital <span class="text-muted">(Kosongkan jika tidak diubah)</span></label>

                                    @if($surat->lampiran)
                                    <div class="alert alert-light border rounded-lg mb-3 p-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="fas fa-paperclip text-primary mr-2"></i>
                                                <span class="small font-weight-bold">{{ $surat->lampiran }}</span>
                                            </div>
                                            <a href="{{ route('surat.masuk.download', $surat->id) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                    @endif

                                    <div id="drop-area" class="upload-container d-flex flex-column align-items-center justify-content-center">
                                        <div class="upload-icon mb-2">
                                            <i class="fas fa-cloud-upload-alt fa-2x text-primary"></i>
                                        </div>
                                        <p class="small font-weight-bold mb-1 text-dark" id="file-name-text">
                                            @if($surat->lampiran)
                                            Klik atau taruh file baru untuk mengganti
                                            @else
                                            Tarik file ke sini
                                            @endif
                                        </p>
                                        <p class="text-muted mb-0" style="font-size: 10px;">Atau klik untuk pilih file</p>
                                        <input type="file" name="lampiran" id="file-input" class="d-none" accept=".pdf,.jpg,.jpeg,.png">
                                    </div>
                                    <small class="text-muted form-text">Max 10MB (PDF, JPG, PNG). File lama akan terganti.</small>
                                    @error('lampiran')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 d-flex justify-content-between align-items-center bg-soft-light p-4 rounded-lg">
                            <div class="text-muted small italic">
                                <i class="fas fa-info-circle mr-1"></i> Perubahan akan langsung tercatat di activity log sistem.
                            </div>
                            <div>
                                <a href="{{ route('surat.masuk.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-4 mr-2">
                                    <i class="fas fa-times mr-2"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-warning btn-lg rounded-pill px-5 shadow-lg fw-bold hover-lift">
                                    <i class="fas fa-save mr-2"></i> Update Data
                                </button>
                            </div>
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
        --warning-gradient: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%);
        --soft-primary: #eef2ff;
        --border-color: #e3e6f0;
    }

    .rounded-xl {
        border-radius: 1.25rem !important;
    }

    .bg-gradient-warning {
        background: var(--warning-gradient);
    }

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

    .modern-input.is-invalid {
        border-color: #e74a3b;
    }

    .badge-soft-primary {
        background: #e8ebf8;
        color: #4e73df;
    }

    .badge-soft-info {
        background: #e0f2f1;
        color: #00897b;
    }

    .badge-soft-success {
        background: #e8f5e9;
        color: #2e7d32;
    }

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

    .upload-container.has-file {
        background: #e8f5e9;
        border-color: #2e7d32;
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

    .no-resize {
        resize: none;
    }

    .bg-soft-light {
        background: #f8f9fc;
    }

    @media (max-width: 768px) {
        .border-right-dashed {
            border-right: none;
            border-bottom: 1px dashed var(--border-color);
            margin-bottom: 2rem;
            padding-bottom: 2rem;
        }
    }
</style>

<script>
    // Drag & Drop file upload
    const dropArea = document.getElementById('drop-area');
    const fileInput = document.getElementById('file-input');
    const fileNameText = document.getElementById('file-name-text');

    dropArea.onclick = function() {
        fileInput.click();
    };

    fileInput.onchange = function() {
        if (this.files && this.files[0]) {
            let fileName = this.files[0].name;
            fileNameText.innerText = fileName;
            dropArea.classList.add('has-file');
        } else {
            fileNameText.innerText = 'Tarik file ke sini';
            dropArea.classList.remove('has-file');
        }
    };

    // Drag & Drop events
    dropArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropArea.style.background = '#f1f4ff';
        dropArea.style.borderColor = '#4e73df';
    });

    dropArea.addEventListener('dragleave', (e) => {
        e.preventDefault();
        dropArea.style.background = '#fdfdfd';
        dropArea.style.borderColor = '#d1d3e2';
    });

    dropArea.addEventListener('drop', (e) => {
        e.preventDefault();
        dropArea.style.background = '#fdfdfd';
        dropArea.style.borderColor = '#d1d3e2';

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            let fileName = files[0].name;
            fileNameText.innerText = fileName;
            dropArea.classList.add('has-file');
        }
    });
</script>
@endsection