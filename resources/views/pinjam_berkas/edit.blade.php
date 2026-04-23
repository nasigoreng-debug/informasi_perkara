@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>{{ $title }}</h3>
                </div>
                <div class="card-body">
                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('pinjam.update', $pinjam->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nama_peminjam" class="form-label">Nama Peminjam <span class="text-danger">*</span></label>
                            <input type="text"
                                class="form-control @error('nama_peminjam') is-invalid @enderror"
                                id="nama_peminjam"
                                name="nama_peminjam"
                                value="{{ old('nama_peminjam', $pinjam->nama_peminjam) }}"
                                required>
                            @error('nama_peminjam')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="no_banding" class="form-label">Nomor Banding/Perkara <span class="text-danger">*</span></label>
                            <input type="text"
                                class="form-control @error('no_banding') is-invalid @enderror"
                                id="no_banding"
                                name="no_banding"
                                value="{{ old('no_banding', $pinjam->no_banding) }}"
                                required>
                            @error('no_banding')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tgl_pinjam" class="form-label">Tanggal Pinjam <span class="text-danger">*</span></label>
                            <input type="date"
                                class="form-control @error('tgl_pinjam') is-invalid @enderror"
                                id="tgl_pinjam"
                                name="tgl_pinjam"
                                value="{{ old('tgl_pinjam', date('Y-m-d', strtotime($pinjam->tgl_pinjam))) }}"
                                required>
                            @error('tgl_pinjam')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tgl_kembali" class="form-label">Tanggal Kembali</label>
                            <input type="date"
                                class="form-control @error('tgl_kembali') is-invalid @enderror"
                                id="tgl_kembali"
                                name="tgl_kembali"
                                value="{{ old('tgl_kembali', $pinjam->tgl_kembali ? date('Y-m-d', strtotime($pinjam->tgl_kembali)) : '') }}">
                            @error('tgl_kembali')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="bkt_pinjam" class="form-label">Bukti Pinjam</label>
                            @if($pinjam->bkt_pinjam)
                            <div class="mb-2">
                                <span class="badge bg-info">File saat ini:</span>
                                <a href="{{ asset('dokumen_pinjam/bkt_pinjam/' . $pinjam->bkt_pinjam) }}" target="_blank">
                                    {{ $pinjam->bkt_pinjam }}
                                </a>
                            </div>
                            @endif
                            <input type="file"
                                class="form-control @error('bkt_pinjam') is-invalid @enderror"
                                id="bkt_pinjam"
                                name="bkt_pinjam"
                                accept="image/jpeg,image/png">
                            <small class="text-muted">Format: JPG, PNG | Max: 1MB | Kosongkan jika tidak ingin mengubah</small>
                            @error('bkt_pinjam')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="bkt_kembali" class="form-label">Bukti Kembali</label>
                            @if($pinjam->bkt_kembali)
                            <div class="mb-2">
                                <span class="badge bg-info">File saat ini:</span>
                                <a href="{{ asset('dokumen_pinjam/bkt_kembali/' . $pinjam->bkt_kembali) }}" target="_blank">
                                    {{ $pinjam->bkt_kembali }}
                                </a>
                            </div>
                            @endif
                            <input type="file"
                                class="form-control @error('bkt_kembali') is-invalid @enderror"
                                id="bkt_kembali"
                                name="bkt_kembali"
                                accept="image/jpeg,image/png">
                            <small class="text-muted">Format: JPG, PNG | Max: 1MB | Kosongkan jika tidak ingin mengubah</small>
                            @error('bkt_kembali')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror"
                                id="keterangan"
                                name="keterangan"
                                rows="3">{{ old('keterangan', $pinjam->keterangan) }}</textarea>
                            @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('pinjam.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection