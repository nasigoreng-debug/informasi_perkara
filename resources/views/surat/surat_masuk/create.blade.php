@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 fade-in">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card modern-card border-0">
                <div class="card-header header-gradient py-3 d-flex justify-content-between align-items-center">
                    <h5 class="m-0 font-weight-bold text-white"><i class="fas fa-plus-circle mr-2"></i> Tambah Arsip Surat Masuk</h5>
                    <a href="{{ route('surat.index') }}" class="btn btn-light btn-sm rounded-pill px-3 text-primary font-weight-bold shadow-sm">Kembali</a>
                </div>
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('surat.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 border-right px-4">
                                <h6 class="text-primary font-weight-bold mb-4 border-bottom pb-2">Identitas Surat</h6>
                                <div class="form-group mb-3">
                                    <label class="small font-weight-bold text-uppercase">Nomor Indeks</label>
                                    <input type="number" name="no_indeks" class="form-control modern-input" value="{{ $nextIndeks }}" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="small font-weight-bold text-uppercase">Nomor Surat</label>
                                    <input type="text" name="no_surat" class="form-control modern-input" placeholder="No. Surat Resmi" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="small font-weight-bold text-uppercase">Tanggal Surat</label>
                                    <input type="date" name="tgl_surat" class="form-control modern-input" value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="small font-weight-bold text-uppercase">Asal Surat</label>
                                    <input type="text" name="asal_surat" class="form-control modern-input" placeholder="Nama Instansi Pengirim" required>
                                </div>
                            </div>

                            <div class="col-md-4 border-right px-4">
                                <h6 class="text-info font-weight-bold mb-4 border-bottom pb-2">Timeline & Disposisi</h6>
                                <div class="form-group mb-3">
                                    <label class="small font-weight-bold text-uppercase">Tanggal Masuk Umum</label>
                                    <input type="date" name="tgl_masuk_umum" class="form-control modern-input" value="{{ date('Y-m-d') }}">
                                </div>
                                <div class="form-group mb-3">
                                    <label class="small font-weight-bold text-uppercase">Tanggal Masuk Panmud</label>
                                    <input type="date" name="tgl_masuk_pan" class="form-control modern-input">
                                </div>
                                <div class="form-group mb-3">
                                    <label class="small font-weight-bold text-uppercase">Disposisi Ke</label>
                                    <input type="text" name="disposisi" class="form-control modern-input" placeholder="Contoh: Panitera / Ketua">
                                </div>
                            </div>

                            <div class="col-md-4 px-4">
                                <h6 class="text-success font-weight-bold mb-4 border-bottom pb-2">Isi & Lampiran</h6>
                                <div class="form-group mb-3">
                                    <label class="small font-weight-bold text-uppercase">Perihal</label>
                                    <textarea name="perihal" class="form-control modern-input" rows="2" placeholder="Ringkasan isi surat..." required></textarea>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="small font-weight-bold text-uppercase">Keterangan</label>
                                    <textarea name="keterangan" class="form-control modern-input" rows="2" placeholder="Catatan tambahan..."></textarea>
                                </div>
                                <div class="form-group mb-0">
                                    <label class="small font-weight-bold text-uppercase">File Lampiran</label>
                                    <div id="drop-area" class="drop-zone py-3 bg-light rounded-lg border-soft d-flex flex-column align-items-center">
                                        <i class="fas fa-cloud-upload-alt text-primary mb-1"></i>
                                        <span class="small font-weight-bold" id="file-name">Klik/Tarik File</span>
                                        <input type="file" name="lampiran" id="file-input" class="d-none">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="my-4">
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm font-weight-bold">Simpan Arsip Sultan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('surat.surat_masuk.styles_js')
@endsection