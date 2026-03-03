@extends('layouts.app')

@section('content')
<div class="container py-5 fade-in">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-lg rounded-0" style="border-top: 5px solid #2c3e50 !important;">
                <div class="card-header bg-white py-4 px-5 border-bottom">
                    <h4 class="m-0 font-weight-bold text-dark"><i class="fas fa-edit mr-2 text-primary"></i> PERBARUI DOKUMEN HUKUM</h4>
                    <p class="text-muted small mb-0 mt-1">Ubah informasi pada data regulasi ID: {{ $item->id }}</p>
                </div>
                <div class="card-body p-5 bg-white">
                    <form action="{{ route('peraturan.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row g-4">
                            <div class="col-md-6 border-right pr-md-5">
                                <label class="jdih-label">Jenis Produk Hukum</label>
                                <select name="jenis_peraturan" class="form-control jdih-input mb-4" required>
                                    @php
                                    $list_jenis = ["Undang-Undang (UU)", "Peraturan Pemerintah Pengganti Undang-undang (PERPU)", "Peraturan Pemerintah (PP)", "Instruksi Presiden (INPRES)", "Peraturan Mahkamah Agung (PERMA)", "Surat Edaran Mahkamah Agung (SEMA)", "Surat Keputusan Ketua Mahkamah Agung (SK KMA)", "Surat Keputusan Sekretaris Mahkamah Agung (SK SEKMA)", "Surat Edaran Direktur Jenderal Badan Peradilan Agama (SE Dirjen Badilag)", "Surat Keputusan Direktur Jenderal Badan Peradilan Agama (SK Dirjen Badilag)", "Surat Edaran Ketua Pengadilan Tinggi Agama Bandung (SE KPTA Bandung)", "Surat Keputusan Ketua Pengadilan Tinggi Agama Bandung (SK KPTA Bandung)", "Peraturan lainnya"];
                                    @endphp
                                    @foreach($list_jenis as $jenis)
                                    <option value="{{ $jenis }}" @selected($item->jenis_peraturan == $jenis)>{{ $jenis }}</option>
                                    @endforeach
                                </select>

                                <label class="jdih-label">Nomor Peraturan</label>
                                <input type="text" name="no_peraturan" class="form-control jdih-input mb-4" value="{{ $item->no_peraturan }}" required>

                                <div class="row">
                                    <div class="col-6">
                                        <label class="jdih-label">Tahun</label>
                                        <input type="number" name="tahun" class="form-control jdih-input" value="{{ $item->tahun }}" required>
                                    </div>
                                    <div class="col-6">
                                        <label class="jdih-label">Tgl. Ditetapkan</label>
                                        <input type="date" name="tgl_peraturan" class="form-control jdih-input" value="{{ $item->tgl_peraturan }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 pl-md-5">
                                <label class="jdih-label">Tentang / Judul Peraturan</label>
                                <textarea name="tentang" class="form-control jdih-input mb-4" rows="5" required>{{ $item->tentang }}</textarea>

                                <label class="jdih-label">Update Dokumen PDF</label>
                                <div class="p-3 border rounded mb-2 bg-light d-flex align-items-center">
                                    <i class="fas fa-file-pdf text-danger mr-3 fa-lg"></i>
                                    <small class="text-dark font-weight-bold">{{ $item->dokumen ?? 'Belum ada file' }}</small>
                                </div>
                                <input type="file" name="dokumen" class="form-control-file" accept=".pdf">
                                <small class="text-muted d-block mt-1 italic">*Kosongkan jika tidak ingin mengganti file.</small>
                            </div>
                        </div>

                        <div class="mt-5 pt-4 border-top d-flex justify-content-end">
                            <a href="{{ route('peraturan.index') }}" class="btn btn-light rounded-pill px-4 mr-3">Batal</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm fw-bold">SIMPAN PERUBAHAN</button>
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
        color: #2c3e50;
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
</style>
@endsection