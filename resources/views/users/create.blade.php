@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg rounded-4 animate__animated animate__fadeInUp">
                <div class="card-header bg-primary text-white p-4 rounded-top-4 border-0">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-user-plus me-2"></i> Daftarkan Pengguna Baru</h5>
                </div>
                <div class="card-body p-5">
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-12">
                                <label class="form-label fw-bold text-muted small text-uppercase">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control rounded-pill px-4" placeholder="Contoh: Ahmad Sulaiman, S.H." required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">Username</label>
                                <input type="text" name="username" class="form-control rounded-pill px-4" placeholder="Untuk login..." required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">Password</label>
                                <input type="password" name="password" class="form-control rounded-pill px-4" placeholder="Minimal 6 karakter" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">Satuan Kerja (Wilayah)</label>
                                <select name="satker_id" class="form-select rounded-pill px-4" required>
                                    <option value="">-- Pilih Satker --</option>
                                    @foreach($satkers as $s)
                                        <option value="{{ $s->id }}">{{ $s->nama }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">Hak Akses (Role)</label>
                                <select name="role_id" class="form-select rounded-pill px-4" required>
                                    <option value="">-- Pilih Role --</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->nama_role }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mt-5 d-flex gap-2">
                            <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">SIMPAN USER</button>
                            <a href="{{ route('users.index') }}" class="btn btn-light rounded-pill px-4">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection