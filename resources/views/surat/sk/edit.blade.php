@extends('layouts.app')

@section('title', 'Edit Surat Keputusan | PTA Bandung')

@section('content')
<div class="container py-4 fade-in">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card modern-card border-0 shadow-lg">
                <div class="card-header header-gradient py-3 d-flex justify-content-between align-items-center border-0">
                    <div class="d-flex align-items-center">
                        <h5 class="m-0 font-weight-bold text-white tracking-wide">
                            <i class="fas fa-edit me-2 opacity-75"></i> Edit Surat Keputusan
                        </h5>
                    </div>
                    <a href="{{ route('sk.index') }}" class="btn btn-light btn-sm rounded-pill px-4 text-primary font-weight-bold shadow-sm hover-elevate">
                        <i class="fas fa-arrow-left me-1"></i> Batal
                    </a>
                </div>

                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('sk.update', $sk->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-5">
                            <h6 class="text-primary fw-bold mb-3 d-flex align-items-center">
                                <span class="icon-circle bg-soft-primary me-2"><i class="fas fa-info-circle"></i></span>
                                Perbarui Informasi SK
                            </h6>
                            <div class="bg-soft-light p-4 rounded-lg border-soft">
                                <div class="row g-3">
                                    <div class="col-md-7">
                                        <label class="text-xs font-weight-bold text-uppercase text-muted mb-1">Nomor SK</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-hashtag text-muted"></i></span>
                                            <input type="text" name="no_sk" class="form-control modern-input border-start-0 ps-0" value="{{ $sk->no_sk }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="text-xs font-weight-bold text-uppercase text-muted mb-1">Tanggal SK</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-calendar-alt text-muted"></i></span>
                                            <input type="date" name="tgl_sk" class="form-control modern-input border-start-0 ps-0" value="{{ $sk->tgl_sk }}" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="text-xs font-weight-bold text-uppercase text-muted mb-1">Tentang / Perihal SK</label>
                                        <textarea name="tentang" class="form-control modern-input" rows="4" required>{{ $sk->tentang }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-5">
                            <h6 class="text-primary fw-bold mb-3 d-flex align-items-center">
                                <span class="icon-circle bg-soft-primary me-2"><i class="fas fa-file-upload"></i></span>
                                Perbarui Berkas (Kosongkan jika tidak diubah)
                            </h6>
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="p-3 border rounded-lg bg-white shadow-sm border-start border-danger border-4">
                                        <label class="text-xs font-weight-bold text-uppercase text-danger mb-2 d-block">SK Resmi (PDF)</label>
                                        @if($sk->dokumen)
                                        <div class="mb-2 small text-muted font-italic"><i class="fas fa-paperclip me-1"></i> {{ Str::limit($sk->dokumen, 30) }}</div>
                                        @endif
                                        <input type="file" name="dokumen" class="form-control form-control-sm" accept=".pdf">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 border rounded-lg bg-white shadow-sm border-start border-primary border-4">
                                        <label class="text-xs font-weight-bold text-uppercase text-primary mb-2 d-block">Konsep SK (Word/DOCX)</label>
                                        @if($sk->konsep_sk)
                                        <div class="mb-2 small text-muted font-italic"><i class="fas fa-paperclip me-1"></i> {{ Str::limit($sk->konsep_sk, 30) }}</div>
                                        @endif
                                        <input type="file" name="konsep_sk" class="form-control form-control-sm" accept=".docx,.doc">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end border-top pt-4">
                            <button type="submit" class="btn btn-warning rounded-pill px-5 py-2 fw-bold shadow-sm hover-elevate">
                                <i class="fas fa-sync-alt me-2"></i> Perbarui Arsip SK
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection