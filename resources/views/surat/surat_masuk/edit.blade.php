@extends('layouts.app')

@section('content')
<div class="container py-4 fade-in">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card modern-card border-0">
                <div class="card-header header-gradient-warning py-3 d-flex justify-content-between align-items-center">
                    <h5 class="m-0 font-weight-bold text-white"><i class="fas fa-edit mr-2"></i> Perbarui Arsip Surat</h5>
                    <a href="{{ route('surat.index') }}" class="btn btn-light btn-sm rounded-pill px-3 text-warning font-weight-bold shadow-sm">Kembali</a>
                </div>
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('surat.update', $surat->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="row">
                            <div class="col-md-4 border-right px-4">
                                <h6 class="text-warning font-weight-bold mb-4 border-bottom pb-2">Identitas Surat</h6>
                                <div class="form-group mb-3">
                                    <label class="small font-weight-bold text-uppercase">Nomor Indeks</label>
                                    <input type="number" name="no_indeks" class="form-control modern-input" value="{{ old('no_indeks', $surat->no_indeks) }}" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="small font-weight-bold text-uppercase">Nomor Surat</label>
                                    <input type="text" name="no_surat" class="form-control modern-input" value="{{ old('no_surat', $surat->no_surat) }}" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="small font-weight-bold text-uppercase">Tanggal Surat</label>
                                    <input type="date" name="tgl_surat" class="form-control modern-input" value="{{ $surat->tgl_surat }}" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="small font-weight-bold text-uppercase">Asal Surat</label>
                                    <input type="text" name="asal_surat" class="form-control modern-input" value="{{ old('asal_surat', $surat->asal_surat) }}" required>
                                </div>
                            </div>

                            <div class="col-md-4 border-right px-4">
                                <h6 class="text-info font-weight-bold mb-4 border-bottom pb-2">Timeline & Disposisi</h6>
                                <div class="form-group mb-3">
                                    <label class="small font-weight-bold text-uppercase">Tanggal Masuk Umum</label>
                                    <input type="date" name="tgl_masuk_umum" class="form-control modern-input" value="{{ $surat->tgl_masuk_umum }}">
                                </div>
                                <div class="form-group mb-3">
                                    <label class="small font-weight-bold text-uppercase">Tanggal Masuk Panmud</label>
                                    <input type="date" name="tgl_masuk_pan" class="form-control modern-input" value="{{ $surat->tgl_masuk_pan }}">
                                </div>
                                <div class="form-group mb-3">
                                    <label class="small font-weight-bold text-uppercase">Disposisi Ke</label>
                                    <input type="text" name="disposisi" class="form-control modern-input" value="{{ old('disposisi', $surat->disposisi) }}">
                                </div>
                            </div>

                            <div class="col-md-4 px-4">
                                <h6 class="text-success font-weight-bold mb-4 border-bottom pb-2">Isi & Lampiran</h6>
                                <div class="form-group mb-3">
                                    <label class="small font-weight-bold text-uppercase">Perihal</label>
                                    <textarea name="perihal" class="form-control modern-input" rows="2" required>{{ old('perihal', $surat->perihal) }}</textarea>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="small font-weight-bold text-uppercase">Keterangan</label>
                                    <textarea name="keterangan" class="form-control modern-input" rows="2">{{ old('keterangan', $surat->keterangan) }}</textarea>
                                </div>
                                <div class="form-group mb-0">
                                    <label class="small font-weight-bold text-uppercase">Ganti File (Opsional)</label>
                                    <div id="drop-area" class="drop-zone py-3 bg-light rounded-lg border-soft d-flex flex-column align-items-center">
                                        <i class="fas fa-file-pdf {{ $surat->lampiran ? 'text-success' : 'text-primary' }} mb-1"></i>
                                        <span class="small font-weight-bold" id="file-name">{{ $surat->lampiran ? 'File: '.Str::limit($surat->lampiran, 15) : 'Klik/Tarik File' }}</span>
                                        <input type="file" name="lampiran" id="file-input" class="d-none">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="my-4">
                        <div class="text-right">
                            <button type="submit" class="btn btn-warning rounded-pill px-5 shadow-sm font-weight-bold text-white">Update Arsip Sultan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('surat.surat_masuk.styles_js')
@endsection