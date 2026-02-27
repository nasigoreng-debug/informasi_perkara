@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0 text-primary">
                <i class="fas fa-users-cog me-2"></i> Manajemen Pengguna
            </h4>
            <p class="text-muted small mb-0">Kelola hak akses dan identitas pegawai di wilayah hukum PTA Bandung</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="fas fa-user-plus me-2"></i> Tambah User Baru
        </a>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
    <div class="alert alert-success border-0 shadow-sm rounded-4 animate__animated animate__fadeIn">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
    </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase small fw-bold text-muted">Nama & Username</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted">Satker / Wilayah</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted">Hak Akses (Role)</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle bg-primary bg-opacity-10 text-primary fw-bold me-3">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $user->name }}</div>
                                    <div class="text-muted small">@ {{ $user->username }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="text-secondary small fw-bold">
                                <i class="fas fa-map-marker-alt me-1 text-danger"></i>
                                {{-- Pengaman Satker --}}
                                {{ $user->satker->nama ?? 'PTA BANDUNG (PUSAT)' }}
                            </span>
                        </td>
                        <td>
                            @php
                            // Pewarnaan Badge berdasarkan Role ID
                            $badgeClass = [
                            1 => 'bg-danger', // Administrator
                            2 => 'bg-warning text-dark', // Manager
                            3 => 'bg-primary', // User/Member
                            4 => 'bg-secondary' // Viewer
                            ][$user->role_id] ?? 'bg-light text-dark';
                            @endphp

                            <span class="badge {{ $badgeClass }} rounded-pill px-3 py-2">
                                <i class="fas fa-user-shield me-1 small"></i>
                                {{-- Pengaman Role: Menggunakan optional agar tidak error property on null --}}
                                {{ optional($user->role)->nama_role ?? 'Tanpa Role' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-outline-warning rounded-pill px-3">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </a>

                                {{-- Cegah hapus diri sendiri --}}
                                @if($user->id !== auth()->id())
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus user ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                        <i class="fas fa-trash me-1"></i> Hapus
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .table thead th {
        border: none;
    }

    .table tbody td {
        border-color: #f8f9fa;
    }
</style>
@endsection