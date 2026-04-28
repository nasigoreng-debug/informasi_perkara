@extends('layouts.app')

@section('title', 'Log Aktivitas Sistem | PTA Bandung')

@section('content')
<div class="container py-4">
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-history me-2 text-primary"></i> Jejak Aktivitas Pengguna</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Waktu</th>
                        <th>User</th>
                        <th>Aktivitas</th>
                        <th>Detail</th>
                        <th>Alamat IP</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(\App\Models\ActivityLog::with('user')->latest()->get() as $log)
                    <tr>
                        <td class="ps-4 small">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                        <td class="fw-bold">{{ optional($log->user)->name }}</td>
                        <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ $log->activity }}</span></td>
                        <td class="small text-muted">{{ $log->description }}</td>
                        <td class="small font-monospace">{{ $log->ip_address }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection