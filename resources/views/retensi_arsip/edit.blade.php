@extends('layouts.app')

@section('content')
<div class="container py-4 px-md-5">
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <div class="d-flex align-items-center">
            <a href="{{ route('retensi-arsip.index') }}" class="btn btn-outline-danger rounded-circle me-3">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h4 class="fw-bold text-dark mb-0">EDIT ARSIP PERKARA</h4>
                <p class="text-muted small mb-0">Perbarui data digitalisasi arsip No: <span class="text-danger fw-bold">{{ $arsip->no_banding }}</span></p>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <form action="{{ route('retensi-arsip.update', $arsip->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-12 text-uppercase">
                        <h6 class="fw-bold text-danger border-bottom pb-2 mb-3"><i class="fas fa-id-card me-2"></i> Identitas Perkara</h6>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted text-uppercase">PA Pengaju (Satker)</label>
                        <select name="pa_pengaju" class="form-select" required>
                            <option value="">-- Pilih Satker --</option>
                            <option value="Bandung" {{ (old('pa_pengaju', $arsip->pa_pengaju) == 'Bandung') ? 'selected' : '' }}>Bandung</option>
                            <option value="Bekasi" {{ (old('pa_pengaju', $arsip->pa_pengaju) == 'Bekasi') ? 'selected' : '' }}>Bekasi</option>
                            <option value="Bogor" {{ (old('pa_pengaju', $arsip->pa_pengaju) == 'Bogor') ? 'selected' : '' }}>Bogor</option>
                            <option value="Ciamis" {{ (old('pa_pengaju', $arsip->pa_pengaju) == 'Ciamis') ? 'selected' : '' }}>Ciamis</option>
                            <option value="Cianjur" {{ (old('pa_pengaju', $arsip->pa_pengaju) == 'Cianjur') ? 'selected' : '' }}>Cianjur</option>
                            <option value="Cibadak" {{ (old('pa_pengaju', $arsip->pa_pengaju) == 'Cibadak') ? 'selected' : '' }}>Cibadak</option>
                            <option value="Cibinong" {{ (old('pa_pengaju', $arsip->pa_pengaju) == 'Cibinong') ? 'selected' : '' }}>Cibinong</option>
                            <option value="Cikarang" {{ (old('pa_pengaju', $arsip->pa_pengaju) == 'Cikarang') ? 'selected' : '' }}>Cikarang</option>
                            <option value="Cimahi" {{ (old('pa_pengaju', $arsip->pa_pengaju) == 'Cimahi') ? 'selected' : '' }}>Cimahi</option>
                            <option value="Cirebon" {{ (old('pa_pengaju', $arsip->pa_pengaju) == 'Cirebon') ? 'selected' : '' }}>Cirebon</option>
                            <option value="Depok" {{ (old('pa_pengaju', $arsip->pa_pengaju) == 'Depok') ? 'selected' : '' }}>Depok</option>
                            <option value="Garut" {{ (old('pa_pengaju', $arsip->pa_pengaju) == 'Garut') ? 'selected' : '' }}>Garut</option>
                            <option value="Indramayu" {{ (old('pa_pengaju', $arsip->pa_pengaju) == 'Indramayu') ? 'selected' : '' }}>Indramayu</option>
                            <option value="Instansi Lain" {{ (old('pa_pengaju', $arsip->pa_pengaju) == 'Instansi Lain') ? 'selected' : '' }}>Instansi Lain</option>
                            <option value="Karawang" {{ (old('pa_pengaju', $arsip->pa_pengaju) == 'Karawang') ? 'selected' : '' }}>Karawang</option>
                            <option value="Kota Banjar" {{ (old('pa_pengaju', $arsip->pa_pengaju) == 'Kota Banjar') ? 'selected' : '' }}>Kota Banjar</option>
                            <option value="Kota Tasikmalaya" {{ (old('pa_pengaju', $arsip->pa_pengaju) == 'Kota Tasikmalaya') ? 'selected' : '' }}>Kota Tasikmalaya</option>
                            <option value="Kuningan" {{ (old('pa_pengaju', $arsip->pa_pengaju) == 'Kuningan') ? 'selected' : '' }}>Kuningan</option>
                            <option value="Majalengka" {{ (old('pa_pengaju', $arsip->pa_pengaju) == 'Majalengka') ? 'selected' : '' }}>Majalengka</option>
                            <option value="Ngamprah" {{ (old('pa_pengaju', $arsip->pa_pengaju) == 'Ngamprah') ? 'selected' : '' }}>Ngamprah</option>
                            <option value="Prinsipal" {{ (old('pa_pengaju', $arsip->pa_pengaju) == 'Prinsipal') ? 'selected' : '' }}>Prinsipal</option>
                            <option value="Purwakarta" {{ (old('pa_pengaju', $arsip->pa_pengaju) == 'Purwakarta') ? 'selected' : '' }}>Purwakarta</option>
                            <option value="Soreang" {{ (old('pa_pengaju', $arsip->pa_pengaju) == 'Soreang') ? 'selected' : '' }}>Soreang</option>
                            <option value="Subang" {{ (old('pa_pengaju', $arsip->pa_pengaju) == 'Subang') ? 'selected' : '' }}>Subang</option>
                            <option value="Sukabumi" {{ (old('pa_pengaju', $arsip->pa_pengaju) == 'Sukabumi') ? 'selected' : '' }}>Sukabumi</option>
                            <option value="Sumber" {{ (old('pa_pengaju', $arsip->pa_pengaju) == 'Sumber') ? 'selected' : '' }}>Sumber</option>
                            <option value="Sumedang" {{ (old('pa_pengaju', $arsip->pa_pengaju) == 'Sumedang') ? 'selected' : '' }}>Sumedang</option>
                            <option value="Tasikmalaya" {{ (old('pa_pengaju', $arsip->pa_pengaju) == 'Tasikmalaya') ? 'selected' : '' }}>Tasikmalaya</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted text-uppercase">Jenis Perkara</label>
                        <select name="jenis_perkara" class="form-select">
                            <option value="">-- Pilih Jenis --</option>
                            @php
                            $jenis_list = ["Asal Usul Anak", "Cerai Gugat", "Cerai Talak", "Dispensasi Kawin", "Ekonomi Syariah", "Ganti Rugi terhadap Wali", "Hak-hak Bekas Isteri", "Harta Bersama", "Hibah", "Isbath Nikah", "Izin Kawin", "Izin Poligami", "Kelalaian Kewajiban Suami/Isteri", "Kewarisan", "Lain-lain", "Nafkah Anak oleh Ibu", "P3HP/Penetapan Ahli Waris", "Pembatalan Perkawinan", "Pencabutan Kekuasaan Orang Tua", "Pencabutan Kekuasaan Wali", "Pencegahan Perkawinan", "Pengesahan Anak/Pengangkatan Anak", "Penguasaan Anak/Hadlonah", "Penolakan Kawin Campuran", "Penolakan Perkawinan oleh PPN", "Penunjukan Orang Lain Sbg Wali", "Perwalian", "Wakaf", "Wali Adhol", "Wasiat", "Zakat/Infaq/Shodaqoh"];
                            @endphp
                            @foreach($jenis_list as $j)
                            <option value="{{ $j }}" {{ (old('jenis_perkara', $arsip->jenis_perkara) == $j) ? 'selected' : '' }}>{{ $j }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted text-uppercase">Tahun</label>
                        <input type="number" name="tahun" value="{{ old('tahun', $arsip->tahun) }}" class="form-control" required>
                    </div>

                    <div class="col-12 mt-4 text-uppercase">
                        <h6 class="fw-bold text-danger border-bottom pb-2 mb-3"><i class="fas fa-list-ol me-2"></i> Nomor Perkara & Tingkat</h6>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">No. Banding</label>
                        <input type="text" name="no_banding" value="{{ old('no_banding', $arsip->no_banding) }}" class="form-control" placeholder=".../Pdt.G/2024/PTA.Bdg">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">No. Perkara PA</label>
                        <input type="text" name="no_pa" value="{{ old('no_pa', $arsip->no_pa) }}" class="form-control" placeholder=".../Pdt.G/2024/PA.xxx">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">No. Kasasi</label>
                        <input type="text" name="no_kasasi" value="{{ old('no_kasasi', $arsip->no_kasasi) }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">No. PK</label>
                        <input type="text" name="no_pk" value="{{ old('no_pk', $arsip->no_pk) }}" class="form-control">
                    </div>

                    <div class="col-12 mt-4 text-uppercase">
                        <h6 class="fw-bold text-danger border-bottom pb-2 mb-3"><i class="fas fa-users me-2"></i> Pihak & Amar Putusan</h6>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted text-uppercase">Pembanding / Pemohon</label>
                        <input type="text" name="pembanding" value="{{ old('pembanding', $arsip->pembanding) }}" class="form-control text-uppercase" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted text-uppercase">Terbanding / Termohon</label>
                        <input type="text" name="terbanding" value="{{ old('terbanding', $arsip->terbanding) }}" class="form-control text-uppercase" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted text-uppercase">Status Putus</label>
                        <select name="status_put" class="form-select">
                            <option value="">-- Pilih Status Putus --</option>
                            <option value="Menguatkan" {{ old('status_put', $arsip->status_put) == 'Menguatkan' ? 'selected' : '' }}>Menguatkan</option>
                            <option value="Mengabulkan" {{ old('status_put', $arsip->status_put) == 'Mengabulkan' ? 'selected' : '' }}>Mengabulkan</option>
                            <option value="Membatalkan" {{ old('status_put', $arsip->status_put) == 'Membatalkan' ? 'selected' : '' }}>Membatalkan</option>
                            <option value="Memperbaiki" {{ old('status_put', $arsip->status_put) == 'Memperbaiki' ? 'selected' : '' }}>Memperbaiki</option>
                            <option value="Tidak Dapat Diterima" {{ old('status_put', $arsip->status_put) == 'Tidak Dapat Diterima' ? 'selected' : '' }}>Tidak Dapat Diterima</option>
                            <option value="Dicabut" {{ old('status_put', $arsip->status_put) == 'Dicabut' ? 'selected' : '' }}>Dicabut</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted text-uppercase">Tingkat Arsip</label>
                        <select name="tingkat" class="form-select">
                            <option value="Banding" {{ old('tingkat', $arsip->tingkat) == 'Banding' ? 'selected' : '' }}>Banding</option>
                            <option value="Kasasi" {{ old('tingkat', $arsip->tingkat) == 'Kasasi' ? 'selected' : '' }}>Kasasi</option>
                            <option value="PK" {{ old('tingkat', $arsip->tingkat) == 'PK' ? 'selected' : '' }}>PK</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted text-uppercase">Lokasi Buku / Box</label>
                        <input type="text" name="buku" value="{{ old('buku', $arsip->buku) }}" class="form-control">
                    </div>

                    <div class="col-12 mt-4 text-uppercase">
                        <h6 class="fw-bold text-danger border-bottom pb-2 mb-3"><i class="fas fa-file-pdf me-2"></i> Digitalisasi Dokumen Fisik</h6>
                        <div class="bg-light p-4 rounded-4 border border-dashed text-center">
                            <label class="form-label fw-bold text-dark d-block">PILIH FILE PDF PUTUSAN (Kosongkan jika tidak ingin mengubah)</label>
                            <input type="file" name="file_pdf" class="form-control shadow-sm mx-auto" style="max-width: 400px;" accept=".pdf">
                            <p class="text-muted small mt-2 mb-0">* Maksimal ukuran file: 10MB. Format file harus PDF.</p>

                            @if($arsip->file_pdf) {{-- Sesuaikan dengan nama kolom penyimpanan file PDF di database --}}
                            <div class="mt-3">
                                <span class="small text-muted">File saat ini:</span>
                                <a href="{{ asset('storage/arsip_perkara/'.$arsip->file_pdf) }}" target="_blank" class="btn btn-sm btn-outline-danger rounded-pill px-3 ms-2">
                                    <i class="fas fa-file-pdf me-1"></i> Lihat Dokumen
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 mt-4">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('retensi-arsip.index') }}" class="btn btn-light rounded-pill px-4">Batal</a>
                            <button type="submit" class="btn btn-danger rounded-pill px-5 fw-bold shadow-sm">SIMPAN PERUBAHAN</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .form-control:focus,
    .form-select:focus {
        border-color: #8b0000;
        box-shadow: 0 0 0 0.25rem rgba(139, 0, 0, 0.1);
    }

    .text-uppercase {
        text-transform: uppercase;
    }

    .border-dashed {
        border-style: dashed !important;
        border-width: 2px !important;
    }
</style>
@endsection