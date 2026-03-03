@extends('layouts.app')

@section('content')
<div class="container py-4 fade-in">
    <div class="row g-4 mb-4">
    </div>

    <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom border-danger-subtle border-4">
            <h6 class="m-0 font-weight-bold text-danger"><i class="fas fa-bell me-2"></i> Daftar Pengaduan dalam Proses Penyelesaian</h6>
            <a href="{{ route('pengaduan.index') }}" class="btn btn-danger btn-sm rounded-pill px-4 fw-bold shadow-sm">Kelola Data<i class="fas fa-arrow-right ms-1 small"></i></a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0 custom-table">
                    <thead class="bg-light text-xs text-uppercase fw-bold text-muted">
                        <tr>
                            <th class="ps-4 py-3">No. Pengaduan / Tgl Terima</th>
                            <th>Pelapor & Terlapor</th>
                            <th class="text-center">Aksi</th>
                            <th class="text-center pe-4">Lama Proses</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($notif_deadline as $notif)
                        <tr class="hover-soft">
                            <td class="ps-4">
                                <div class="font-weight-bold text-dark small">{{ $notif->no_pgd }}</div>
                                <div class="text-xs text-muted">{{ \Carbon\Carbon::parse($notif->tgl_terima_pgd)->format('d/m/Y') }}</div>
                            </td>
                            <td>
                                <div class="text-xs font-weight-bold text-primary">P: {{ $notif->pelapor }}</div>
                                <div class="text-xs font-weight-bold text-danger">T: {{ $notif->terlapor }}</div>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-light btn-sm text-primary rounded-pill border shadow-sm px-3"
                                    onclick="showTrackingModal('{{ $notif->id }}')">
                                    <i class="fas fa-eye me-1"></i> Tracking Berkas
                                </button>
                            </td>
                            <td class="text-center pe-4">
                                @php $days = \Carbon\Carbon::parse($notif->tgl_terima_pgd)->diffInDays(now()); @endphp
                                <span class="badge {{ $days > 14 ? 'bg-soft-danger text-danger' : 'bg-soft-primary text-primary' }} rounded-pill px-3 py-1 fw-bold border">
                                    {{ $days }} Hari
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted small italic">Tidak ada pengaduan aktif.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTracking" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-lg overflow-hidden">
            <div id="modalContentTracking">
                <div class="text-center py-5">
                    <div class="spinner-border text-danger" role="status"></div>
                    <p class="mt-2 text-muted small">Memuat data birokrasi...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showTrackingModal(id) {
        $('#modalTracking').modal('show');
        $('#modalContentTracking').html('<div class="text-center py-5"><div class="spinner-border text-danger" role="status"></div><p class="mt-2 text-muted small">Memuat alur SOP...</p></div>');

        // Memanggil route detail khusus modal
        $.get("{{ url('/pengaduan/modal-detail') }}/" + id, function(data) {
            $('#modalContentTracking').html(data);
        });
    }
</script>

<style>
    /* Tambahkan CSS yang Bapak punya di sini */
    .vertical-tracking::before {
        content: '';
        position: absolute;
        left: 107px;
        top: 0;
        height: 100%;
        width: 2px;
        background: #e3e6f0;
    }

    .tracking-item {
        position: relative;
        display: flex;
        align-items: flex-start;
        margin-bottom: 35px;
    }

    .tracking-dot {
        position: relative;
        width: 14px;
        height: 14px;
        background: #fff;
        border: 3px solid #d1d3e2;
        border-radius: 50%;
        margin: 0 15px;
        z-index: 2;
        margin-top: 2px;
    }

    .tracking-item.current .tracking-dot {
        background: #4e73df;
        border-color: #fff;
        box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.3);
    }

    /* ... (CSS Sultan lainnya) ... */

    /* Perbaikan Modal Agar Tidak Berantakan */
    .vertical-tracking-modal {
        position: relative;
        padding: 10px 0;
        margin-left: 20px;
    }

    .vertical-tracking-modal::before {
        content: '';
        position: absolute;
        left: 87px;
        top: 0;
        height: 100%;
        width: 2px;
        background: #e3e6f0;
    }

    .tracking-item {
        position: relative;
        display: flex;
        align-items: flex-start;
        margin-bottom: 30px;
        z-index: 1;
    }

    .tracking-date {
        width: 80px;
        text-align: right;
        font-size: 0.6rem;
        font-weight: 800;
        color: #5a5c69;
        line-height: 1.2;
    }

    .tracking-date span {
        display: block;
        font-size: 0.5rem;
        color: #b7b9cc;
        text-transform: uppercase;
    }

    .tracking-dot {
        position: relative;
        width: 12px;
        height: 12px;
        background: #fff;
        border: 2px solid #d1d3e2;
        border-radius: 50%;
        margin: 0 10px;
        z-index: 2;
        margin-top: 2px;
    }

    .tracking-item.current .tracking-dot {
        background: #4e73df;
        border-color: #fff;
        box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.2);
    }

    .tracking-content {
        flex: 1;
    }

    .icon-circle {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endsection