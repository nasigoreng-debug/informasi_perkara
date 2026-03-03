@extends('layouts.app')

@section('content')
<div class="container py-5 fade-in">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-lg rounded-0" style="border-top: 5px solid #8b0000 !important;">
                <div class="card-header bg-white py-4 px-5 border-bottom">
                    <h4 class="m-0 font-weight-bold text-dark"><i class="fas fa-plus-circle mr-2 text-danger"></i> REGISTRASI DOKUMEN HUKUM</h4>
                    <p class="text-muted small mb-0 mt-1">Input data regulasi baru ke dalam Database JDIH PTA Bandung.</p>
                </div>
                <div class="card-body p-5 bg-white">
                    <form action="{{ route('peraturan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-6 border-right pr-md-5">
                                <label class="jdih-label">Jenis Produk Hukum</label>
                                <select name="jenis_peraturan" class="form-control jdih-input mb-4" required>
                                    <option value="">-- Pilih Jenis --</option>
                                    <option>Undang-Undang (UU)</option>
                                    <option>Peraturan Pemerintah Pengganti Undang-undang (PERPU)</option>
                                    <option>Peraturan Pemerintah (PP)</option>
                                    <option>Instruksi Presiden (INPRES)</option>
                                    <option>Peraturan Mahkamah Agung (PERMA)</option>
                                    <option>Surat Edaran Mahkamah Agung (SEMA)</option>
                                    <option>Surat Keputusan Ketua Mahkamah Agung (SK KMA)</option>
                                    <option>Surat Keputusan Sekretaris Mahkamah Agung (SK SEKMA)</option>
                                    <option>Surat Edaran Direktur Jenderal Badan Peradilan Agama (SE Dirjen Badilag)</option>
                                    <option>Surat Keputusan Direktur Jenderal Badan Peradilan Agama (SK Dirjen Badilag)</option>
                                    <option>Surat Edaran Ketua Pengadilan Tinggi Agama Bandung (SE KPTA Bandung)</option>
                                    <option>Surat Keputusan Ketua Pengadilan Tinggi Agama Bandung (SK KPTA Bandung)</option>
                                    <option>Peraturan lainnya</option>
                                </select>

                                <label class="jdih-label">Nomor Peraturan</label>
                                <input type="text" name="no_peraturan" class="form-control jdih-input mb-4" placeholder="Contoh: 1, 05, atau 12/KMA/SK/I/2026" required>

                                <div class="row">
                                    <div class="col-6">
                                        <label class="jdih-label">Tahun</label>
                                        <input type="number" name="tahun" class="form-control jdih-input" value="{{ date('Y') }}" required>
                                    </div>
                                    <div class="col-6">
                                        <label class="jdih-label">Tgl. Ditetapkan</label>
                                        <input type="date" name="tgl_peraturan" class="form-control jdih-input">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 pl-md-5">
                                <label class="jdih-label">Tentang / Judul Peraturan</label>
                                <textarea name="tentang" class="form-control jdih-input mb-4" rows="5" placeholder="Masukkan judul atau perihal lengkap peraturan..." required></textarea>

                                <label class="jdih-label">Unggah Berkas PDF</label>
                                <div class="p-4 border rounded bg-light text-center" style="border: 2px dashed #ccc !important;">
                                    <i class="fas fa-file-pdf fa-2x text-muted mb-2"></i>
                                    <input type="file" name="dokumen" class="form-control-file d-block mx-auto" accept=".pdf">
                                    <small class="text-muted mt-2 d-block">Maksimal ukuran file: 20MB</small>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 pt-4 border-top d-flex justify-content-end align-items-center">
                            <a href="{{ route('peraturan.index') }}" class="btn btn-light rounded-pill px-4 mr-3">Batal</a>
                            <button type="submit" class="btn btn-danger rounded-pill px-5 shadow-sm fw-bold">
                                <i class="fas fa-save mr-2"></i> SIMPAN REGULASI
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .jdih-label {
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        color: #8b0000;
        letter-spacing: 1px;
        margin-bottom: 8px;
        display: block;
    }

    .jdih-input {
        border-radius: 0;
        border: 1px solid #ddd;
        padding: 12px 15px;
        background: #fdfdfd;
        font-size: 0.95rem;
    }

    .jdih-input:focus {
        border-color: #8b0000;
        box-shadow: none;
        background: #fff;
    }

    .rounded-xl {
        border-radius: 15px !important;
    }
</style>
@endsection