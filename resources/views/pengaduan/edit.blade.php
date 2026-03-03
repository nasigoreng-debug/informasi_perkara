@extends('layouts.app')

@section('content')
<div class="container py-4 fade-in">
    <div class="card modern-card border-0 shadow-lg">
        <div class="card-header header-gradient-pengaduan py-3 d-flex justify-content-between align-items-center border-0">
            <h5 class="m-0 font-weight-bold text-white tracking-wide">
                <i class="fas fa-edit me-2 opacity-75"></i> Ubah Data Pengaduan
            </h5>
            <a href="{{ route('pengaduan.index') }}" class="btn btn-light btn-sm rounded-pill px-4 text-danger font-weight-bold shadow-sm hover-elevate">
                <i class="fas fa-arrow-left me-1"></i> Batal
            </a>
        </div>

        <div class="card-body p-4 p-md-5">
            <form action="{{ route('pengaduan.update', $pgd->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 border-end">
                        <h6 class="text-danger fw-bold mb-4 d-flex align-items-center">
                            <span class="icon-circle bg-soft-danger me-2"><i class="fas fa-info-circle"></i></span>
                            Update Informasi Pelaporan
                        </h6>
                        <div class="bg-soft-light p-4 rounded-lg border-soft mb-4">
                            <div class="form-group mb-3">
                                <label class="text-xs font-weight-bold text-muted text-uppercase mb-1">Tanggal Terima</label>
                                <input type="date" name="tgl_terima_pgd" class="form-control modern-input @error('tgl_terima_pgd') is-invalid @enderror" value="{{ old('tgl_terima_pgd', $pgd->tgl_terima_pgd) }}" required>
                                @error('tgl_terima_pgd') <div class="invalid-feedback fw-bold small">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label class="text-xs font-weight-bold text-muted text-uppercase mb-1">Nomor Pengaduan</label>
                                <input type="text" name="no_pgd" class="form-control modern-input @error('no_pgd') is-invalid @enderror" value="{{ old('no_pgd', $pgd->no_pgd) }}" required>
                                @error('no_pgd') <div class="invalid-feedback fw-bold small">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label class="text-xs font-weight-bold text-muted text-uppercase mb-1">Nama Pelapor</label>
                                <input type="text" name="pelapor" class="form-control modern-input @error('pelapor') is-invalid @enderror" value="{{ old('pelapor', $pgd->pelapor) }}" required>
                                @error('pelapor') <div class="invalid-feedback fw-bold small">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label class="text-xs font-weight-bold text-muted text-uppercase mb-1">Terlapor (Satker)</label>
                                <select name="terlapor" class="form-select modern-input @error('terlapor') is-invalid @enderror">
                                    @php $satkers = ['Bandung', 'Bekasi', 'Bogor', 'Ciamis', 'Cianjur', 'Cibadak', 'Cibinong', 'Cikarang', 'Cimahi', 'Cirebon', 'Depok', 'Garut', 'Indramayu', 'Karawang', 'Kota Banjar', 'Kota Tasikmalaya', 'Kuningan', 'Majalengka', 'Ngamprah', 'Purwakarta', 'Soreang', 'Subang', 'Sukabumi', 'Sumber', 'Sumedang', 'Tasikmalaya']; @endphp
                                    @foreach($satkers as $satker)
                                    <option value="{{ $satker }}" {{ old('terlapor', $pgd->terlapor) == $satker ? 'selected' : '' }}>{{ $satker }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label class="text-xs font-weight-bold text-muted text-uppercase mb-1">Uraian Pengaduan</label>
                                <textarea name="uraian_pgd" class="form-control modern-input @error('uraian_pgd') is-invalid @enderror" rows="3">{{ old('uraian_pgd', $pgd->uraian_pgd) }}</textarea>
                            </div>

                            <div class="form-group mb-0">
                                <label class="text-xs font-weight-bold text-muted text-uppercase mb-1">Ditangani Oleh</label>
                                <select name="ditangani_oleh" class="form-select modern-input">
                                    <option value="Badan Pengawas MARI" {{ old('ditangani_oleh', $pgd->ditangani_oleh) == 'Badan Pengawas MARI' ? 'selected' : '' }}>Badan Pengawas MARI</option>
                                    <option value="Pengadilan Tingkat Banding" {{ old('ditangani_oleh', $pgd->ditangani_oleh) == 'Pengadilan Tingkat Banding' ? 'selected' : '' }}>Pengadilan Tingkat Banding</option>
                                    <option value="Pengadilan Tingkat Pertama" {{ old('ditangani_oleh', $pgd->ditangani_oleh) == 'Pengadilan Tingkat Pertama' ? 'selected' : '' }}>Pengadilan Tingkat Pertama</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 ps-md-4">
                        <h6 class="text-primary fw-bold mb-4 d-flex align-items-center">
                            <span class="icon-circle bg-soft-primary me-2"><i class="fas fa-truck-loading"></i></span>
                            Update Tracking & File
                        </h6>
                        <div class="bg-soft-light p-4 rounded-lg border-soft mb-4">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="text-xxs font-weight-bold text-muted text-uppercase mb-1">Disposisi Panmud HK</label>
                                    <input type="date" name="dis_pm_hk" class="form-control modern-input shadow-xs" value="{{ old('dis_pm_hk', $pgd->dis_pm_hk) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-xxs font-weight-bold text-muted text-uppercase mb-1">Disposisi Ketua</label>
                                    <input type="date" name="dis_kpta" class="form-control modern-input shadow-xs" value="{{ old('dis_kpta', $pgd->dis_kpta) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-xxs font-weight-bold text-muted text-uppercase mb-1">Disposisi Wakil</label>
                                    <input type="date" name="dis_wkpta" class="form-control modern-input shadow-xs" value="{{ old('dis_wkpta', $pgd->dis_wkpta) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-xxs font-weight-bold text-muted text-uppercase mb-1">Disposisi Hatiwasda</label>
                                    <input type="date" name="dis_hatiwasda" class="form-control modern-input shadow-xs" value="{{ old('dis_hatiwasda', $pgd->dis_hatiwasda) }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="text-xxs font-weight-bold text-muted text-uppercase mb-1">Tindak Lanjut</label>
                                    <input type="date" name="tgl_tindak_lanjut" class="form-control modern-input shadow-xs" value="{{ old('tgl_tindak_lanjut', $pgd->tgl_tindak_lanjut) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-xxs font-weight-bold text-muted text-uppercase mb-1">Tgl Selesai / LHP</label>
                                    <input type="date" name="tgl_selesai_pgd" class="form-control modern-input shadow-xs" value="{{ old('tgl_selesai_pgd', $pgd->tgl_selesai_pgd) }}">
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="text-xs font-weight-bold text-muted text-uppercase mb-1">Status Pengaduan</label>
                                <select name="status_pgd" class="form-select modern-input shadow-xs">
                                    @foreach(['Disposisi', 'Klarifikasi', 'Telaah Berkas', 'Pemeriksaan oleh TIM', 'Selesai', 'Diarsipkan', 'Tidak dapat ditindaklanjuti'] as $st)
                                    <option value="{{ $st }}" {{ old('status_pgd', $pgd->status_pgd) == $st ? 'selected' : '' }}>{{ $st }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label class="text-xs font-weight-bold text-muted text-uppercase mb-1">Status Posisi Berkas</label>
                                <select name="status_berkas" class="form-select modern-input shadow-xs">
                                    @foreach(['Ketua', 'Wakil Ketua', 'Hakim Tinggi Pengawas', 'Panitera', 'Panitera Muda Hukum', 'Petugas Pengaduan', 'Pengadilan Agama Terlapor'] as $sb)
                                    <option value="{{ $sb }}" {{ old('status_berkas', $pgd->status_berkas) == $sb ? 'selected' : '' }}>{{ $sb }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <h6 class="text-success fw-bold mb-3 small"><i class="fas fa-file-export me-2"></i>Update Berkas Digital</h6>
                        <div class="p-3 border rounded-lg bg-soft-light shadow-xs border-soft">
                            <div class="mb-3">
                                <label class="text-xxs font-weight-bold text-uppercase text-danger mb-1 d-block">Ganti Surat Pengaduan (PDF)</label>
                                <input type="file" name="surat_pgd" class="form-control form-control-sm">
                                <div class="text-xxs text-muted mt-1">File saat ini: <span class="fw-bold">{{ $pgd->surat_pgd ?? '-' }}</span></div>
                            </div>
                            <div class="mb-0">
                                <label class="text-xxs font-weight-bold text-uppercase text-primary mb-1 d-block">Ganti Lampiran / LHP (PDF)</label>
                                <input type="file" name="lampiran" class="form-control form-control-sm">
                                <div class="text-xxs text-muted mt-1">File saat ini: <span class="fw-bold">{{ $pgd->lampiran ?? '-' }}</span></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-5 pt-4 border-top">
                    <a href="{{ route('pengaduan.index') }}" class="btn btn-light rounded-pill px-5 fw-bold text-muted shadow-sm me-2">Batal</a>
                    <button type="submit" class="btn btn-warning rounded-pill px-5 fw-bold shadow-lg hover-elevate">
                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .header-gradient-pengaduan {
        background: linear-gradient(135deg, #8b0000 0%, #000000 100%);
    }

    .bg-soft-danger {
        background-color: rgba(139, 0, 0, 0.1);
        color: #8b0000;
    }

    .bg-soft-primary {
        background-color: rgba(78, 115, 223, 0.1);
        color: #4e73df;
    }

    .icon-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .modern-input {
        border-radius: 0.5rem;
        font-size: 0.85rem;
        border: 1px solid #e2e8f0;
    }

    .modern-input:focus {
        border-color: #8b0000;
        box-shadow: 0 0 0 0.2rem rgba(139, 0, 0, 0.1);
    }

    .border-soft {
        border: 1px dashed #cbd5e1;
    }

    .shadow-xs {
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .text-xxs {
        font-size: 0.65rem;
    }

    .hover-elevate:hover {
        transform: translateY(-3px);
        transition: 0.3s;
    }
</style>
@endsection