@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg rounded-4 animate__animated animate__fadeInUp">
                <div class="card-header bg-warning p-4 rounded-top-4 border-0">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-user-edit me-2"></i> Edit Data Pengguna</h5>
                </div>
                <div class="card-body p-5">
                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="row g-4">
                            <div class="col-md-12">
                                <label class="form-label fw-bold text-muted small text-uppercase">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ $user->name }}" class="form-control rounded-pill px-4" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">Username</label>
                                <input type="text" name="username" value="{{ $user->username }}" class="form-control rounded-pill px-4" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">Ganti Password (Kosongkan jika tidak diubah)</label>
                                <input type="password" name="password" class="form-control rounded-pill px-4" placeholder="Isi hanya jika ingin ganti">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">Satuan Kerja</label>
                                <select name="satker_id" class="form-select rounded-pill px-4" required>
                                    @foreach($satkers as $s)
                                    <option value="{{ $s->id }}" {{ $user->satker_id == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">Hak Akses (Role)</label>
                                <select name="role_id" class="form-select rounded-pill px-4" required>
                                    @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>{{ $role->nama_role }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mt-5 d-flex gap-2">
                            <button type="submit" class="btn btn-warning rounded-pill px-5 fw-bold shadow-sm">UPDATE DATA</button>
                            <a href="{{ route('users.index') }}" class="btn btn-light rounded-pill px-4">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection