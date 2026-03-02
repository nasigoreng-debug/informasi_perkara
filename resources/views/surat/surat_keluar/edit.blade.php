@extends('layouts.app')

@section('title', 'Edit Surat Keluar | PTA Bandung')

@section('content')
<div class="container py-4 fade-in">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card modern-card border-0">
                <div class="card-header bg-warning py-3 d-flex justify-content-between align-items-center border-0">
                    <div class="d-flex align-items-center">
                        <h5 class="m-0 font-weight-bold text-white tracking-wide">
                            <i class="fas fa-edit me-2 opacity-75"></i> Edit Arsip Surat Keluar
                        </h5>
                    </div>
                    <a href="{{ route('surat.keluar.index') }}" class="btn btn-light btn-sm rounded-pill px-4 text-warning font-weight-bold shadow-sm">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                </div>

                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('surat.keluar.update', $surat->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-5">
                            <h6 class="text-warning fw-bold mb-3 d-flex align-items-center">
                                <span class="icon-circle bg-soft-warning me-2"><i class="fas fa-info-circle"></i></span>
                                Perbarui Informasi Utama
                            </h6>
                            <div class="bg-soft-light p-4 rounded-lg border-soft">
                                <div class="row g-3">
                                    <div class="col-md-7">
                                        <label class="text-xs font-weight-bold text-uppercase text-muted mb-1">Nomor Surat</label>
                                        <input type="text" name="no_surat" class="form-control modern-input" value="{{ $surat->no_surat }}" required>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="text-xs font-weight-bold text-uppercase text-muted mb-1">Tanggal Surat</label>
                                        <input type="date" name="tgl_surat" class="form-control modern-input" value="{{ $surat->tgl_surat }}" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="text-xs font-weight-bold text-uppercase text-muted mb-1">Tujuan Surat</label>
                                        @php
                                        $satkers = [
                                        'Bandung','Indramayu','Majalengka','Sumber','Ciamis','Tasikmalaya','Karawang','Cimahi','Subang','Sumedang','Purwakarta','Sukabumi','Cianjur','Kuningan','Cibadak','Cirebon','Garut','Bogor','Bekasi','Cibinong','Cikarang','Depok','Tasikmalaya Kota','Banjar','Soreang','Ngamprah'
                                        ];
                                        @endphp
                                        <select name="tujuan_surat" class="form-select modern-input" required>
                                            @foreach($satkers as $satker)
                                            <option value="{{ $satker }}" {{ $surat->tujuan_surat == $satker ? 'selected' : '' }}>{{ $satker }}</option>
                                            @endforeach
                                            <option value="Lain-lain / Luar Wilayah" {{ !in_array($surat->tujuan_surat, $satkers) ? 'selected' : '' }}>Lain-lain / Luar Wilayah</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="text-xs font-weight-bold text-uppercase text-muted mb-1">Perihal</label>
                                        <textarea name="perihal" class="form-control modern-input" rows="3" required>{{ $surat->perihal }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-5">
                            <h6 class="text-warning fw-bold mb-3 d-flex align-items-center">
                                <span class="icon-circle bg-soft-warning me-2"><i class="fas fa-file-upload"></i></span>
                                Perbarui Berkas Digital
                            </h6>
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="p-4 border rounded-lg bg-white shadow-sm border-start border-danger border-4">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-file-pdf text-danger fs-4 me-2"></i>
                                            <label class="text-xs font-weight-bold text-uppercase text-danger m-0">Surat Resmi (PDF)</label>
                                        </div>
                                        <input type="file" name="surat_pta" class="form-control form-control-sm modern-input" accept=".pdf">
                                        @if($surat->surat_pta)
                                        <div class="mt-2 text-xs text-success fw-bold"><i class="fas fa-check-circle"></i> File PDF sudah tersedia</div>
                                        @else
                                        <div class="mt-2 text-xs text-muted italic">Belum ada file PDF</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-4 border rounded-lg bg-white shadow-sm border-start border-primary border-4">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-file-word text-primary fs-4 me-2"></i>
                                            <label class="text-xs font-weight-bold text-uppercase text-primary m-0">Konsep (Docx/RTF)</label>
                                        </div>
                                        <input type="file" name="konsep_surat" class="form-control form-control-sm modern-input" accept=".docx,.doc,.rtf">
                                        @if($surat->konsep_surat)
                                        <div class="mt-2 text-xs text-primary fw-bold"><i class="fas fa-check-circle"></i> File Word sudah tersedia</div>
                                        @else
                                        <div class="mt-2 text-xs text-muted italic">Belum ada file konsep</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="text-xs font-weight-bold text-uppercase text-muted mb-1">Keterangan Tambahan</label>
                            <textarea name="keterangan" class="form-control modern-input" rows="2">{{ $surat->keterangan }}</textarea>
                        </div>

                        <div class="text-end border-top pt-4">
                            <button type="submit" class="btn btn-warning rounded-pill px-5 py-2 fw-bold text-white shadow-sm hover-elevate">
                                <i class="fas fa-sync me-2"></i> Perbarui Arsip
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .icon-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
    }

    .bg-soft-warning {
        background-color: #fff3cd;
        color: #856404;
    }

    .bg-soft-light {
        background-color: #f8fafc;
    }

    .border-soft {
        border: 1px dashed #cbd5e1;
    }

    .rounded-lg {
        border-radius: 1rem;
    }

    .modern-input {
        border-radius: 0.5rem;
    }
</style>
@endsection