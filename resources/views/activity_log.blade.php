@extends('layouts.app')

@section('title', 'Log Aktivitas Sistem | PTA Bandung')

@section('content')
<div class="container py-4">
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0 text-dark">
                <i class="fas fa-history me-2 text-primary"></i> Jejak Aktivitas Pengguna
            </h5>
            <small class="text-muted">Klik baris untuk lihat detail lengkap</small>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary small text-uppercase">
                    <tr>
                        <th class="ps-4">Waktu</th>
                        <th>User</th>
                        <th>Aksi</th>
                        <th>Model</th>
                        <th class="text-end pe-4">Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $logs = \App\Models\ActivityLog::with('user')->latest()->paginate(10);
                    @endphp

                    @forelse($logs as $log)
                    <tr data-bs-toggle="collapse" data-bs-target="#detail-{{ $log->id }}" style="cursor: pointer;">
                        <td class="ps-4">
                            <div class="fw-bold text-dark small mb-0">{{ $log->created_at->translatedFormat('d M Y') }}</div>
                            <div class="text-muted font-monospace" style="font-size: 0.7rem;">{{ $log->created_at->format('H:i') }}</div>
                        </td>
                        <td>
                            <span class="fw-semibold small text-dark">{{ optional($log->user)->name ?? 'System' }}</span>
                        </td>
                        <td>
                            @php
                                $act = strtolower($log->activity);
                                $color = match(true) {
                                    str_contains($act, 'create'), str_contains($act, 'tambah') => 'success',
                                    str_contains($act, 'update'), str_contains($act, 'edit') => 'warning',
                                    str_contains($act, 'delete'), str_contains($act, 'hapus') => 'danger',
                                    default => 'primary'
                                };
                            @endphp
                            <span class="badge rounded-pill bg-{{ $color }} bg-opacity-10 text-{{ $color }} px-3 border border-{{ $color }} border-opacity-25">
                                {{ strtoupper($log->activity) }}
                            </span>
                        </td>
                        <td class="small text-muted">{{ $log->model ?? '-' }}</td>
                        <td class="text-end pe-4">
                            <i class="fas fa-chevron-down text-muted small"></i>
                        </td>
                    </tr>
                    <tr class="collapse bg-light" id="detail-{{ $log->id }}">
                        <td colspan="5" class="p-4">
                            <div class="card card-body border-0 shadow-sm rounded-3">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h6 class="fw-bold text-primary mb-2 small"><i class="fas fa-info-circle me-1"></i> Deskripsi Lengkap:</h6>
                                        <p class="text-dark mb-0 bg-white p-3 rounded border shadow-sm" style="white-space: pre-wrap; font-size: 0.85rem; line-height: 1.6;">
                                            {{ $log->description ?: 'Tidak ada detail deskripsi.' }}
                                        </p>
                                    </div>
                                    <div class="col-md-4 mt-3 mt-md-0 border-start">
                                        <h6 class="fw-bold text-secondary mb-2 small"><i class="fas fa-laptop me-1"></i> Info Teknis:</h6>
                                        <ul class="list-unstyled mb-0" style="font-size: 0.75rem;">
                                            <li><strong>IP Address:</strong> {{ $log->ip_address }}</li>
                                            <li class="mt-2 text-truncate"><strong>User Agent:</strong> <br><span class="text-muted">{{ $log->user_agent }}</span></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">Belum ada data.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-white py-3 border-top-0 d-flex justify-content-center">
            {{ $logs->links() }}
        </div>
    </div>
</div>

<style>
    /* Transisi halus saat buka detail */
    .collapse { transition: all 0.3s ease; }
    tr[aria-expanded="true"] { background-color: rgba(13, 110, 253, 0.03); }
    tr[aria-expanded="true"] .fa-chevron-down { transform: rotate(180deg); transition: 0.3s; }
</style>
@endsection