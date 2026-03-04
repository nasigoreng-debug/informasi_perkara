@extends('layouts.app')

@section('content')
<style>
    .clickable-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .clickable-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }

    .active-filter-sudah {
        border: 2px solid #28a745 !important;
        background-color: #f8fff9;
    }

    .active-filter-belum {
        border: 2px solid #dc3545 !important;
        background-color: #fff8f8;
    }

    .sticky-sidebar {
        position: sticky;
        top: 90px;
        z-index: 1000;
        height: fit-content;
    }
</style>

<div class="container-fluid py-4 px-md-5">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 border-bottom pb-3">
        <div class="d-flex align-items-center">
            <img src="{{ asset('storage/logo-pta.png') }}" alt="Logo" style="width: 55px;" class="me-3">
            <div>
                <h3 class="fw-bold text-dark mb-0">RETENSI ARSIP PERKARA</h3>
                <p class="text-danger fw-bold small mb-0">DATABASE DIGITALISASI ARSIP LAMA PTA BANDUNG</p>
            </div>
        </div>
        <a href="{{ route('retensi-arsip.create') }}" class="btn btn-sm btn-danger rounded-pill px-4 fw-bold shadow-sm">TAMBAH ARSIP</a>
    </div>

    {{-- Statistik Cards --}}
    <div class="row g-3 mb-3 text-uppercase">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-dark text-white p-3 rounded-4">
                <small class="fw-bold opacity-75 d-block small">Total Arsip</small>
                <h3 class="mb-0 fw-bold">{{ number_format($stats['total']) }}</h3>
            </div>
        </div>
        @foreach(['banding' => 'danger', 'kasasi' => 'primary', 'pk' => 'warning'] as $key => $color)
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-{{ $color }} {{ $key == 'pk' ? 'text-dark' : 'text-white' }} p-3 rounded-4">
                <small class="fw-bold opacity-75 d-block small">{{ strtoupper($key) }}</small>
                <h3 class="mb-0 fw-bold">{{ number_format($stats[$key]) }}</h3>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Filter Digitalisasi --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm p-4 rounded-4 clickable-card {{ request('status_digital') == 'sudah' ? 'active-filter-sudah' : '' }}"
                onclick="window.location.href='{{ route('retensi-arsip.index', array_merge(request()->query(), ['status_digital' => 'sudah'])) }}'"
                style="border-left: 5px solid #28a745 !important;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1">Sudah Digitalisasi</small>
                        <h2 class="mb-0 fw-bold text-success">{{ number_format($stats['sudah_upload']) }} <span class="fs-6 fw-normal text-muted">Berkas</span></h2>
                        <div class="mt-3">
                            <span class="small fw-bold text-success">{{ $stats['persen_sudah'] }}% Terupload</span>
                            <div class="progress mt-1" style="height: 8px;">
                                <div class="progress-bar bg-success" style="width: {{ $stats['persen_sudah'] }}%"></div>
                            </div>
                        </div>
                    </div>
                    <i class="fas fa-file-circle-check fa-3x text-success opacity-25"></i>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm p-4 rounded-4 clickable-card {{ request('status_digital') == 'belum' ? 'active-filter-belum' : '' }}"
                onclick="window.location.href='{{ route('retensi-arsip.index', array_merge(request()->query(), ['status_digital' => 'belum'])) }}'"
                style="border-left: 5px solid #dc3545 !important;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1">Belum Digitalisasi</small>
                        <h2 class="mb-0 fw-bold text-danger">{{ number_format($stats['belum_upload']) }} <span class="fs-6 fw-normal text-muted">Berkas</span></h2>
                        <div class="mt-3">
                            <span class="small fw-bold text-danger">{{ $stats['persen_belum'] }}% Tersisa</span>
                            <div class="progress mt-1" style="height: 8px;">
                                <div class="progress-bar bg-danger" style="width: {{ $stats['persen_belum'] }}%"></div>
                            </div>
                        </div>
                    </div>
                    <i class="fas fa-file-circle-exclamation fa-3x text-danger opacity-25"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Sidebar Pencarian --}}
        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm sticky-sidebar rounded-4 overflow-hidden">
                <div class="card-header bg-dark text-white py-3 border-0 text-uppercase">
                    <h6 class="m-0 fw-bold small">Pencarian Spesifik</h6>
                </div>
                <div class="card-body p-4 bg-light">
                    <form action="{{ route('retensi-arsip.index') }}" method="GET">
                        @if(request('status_digital'))
                        <input type="hidden" name="status_digital" value="{{ request('status_digital') }}">
                        @endif

                        <div class="mb-3">
                            <label class="text-uppercase text-muted fw-bold mb-1 small">Keyword</label>
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control border-0 shadow-sm" placeholder="No Perkara/Nama...">
                        </div>

                        <div class="mb-3">
                            <label class="text-uppercase text-muted fw-bold mb-1 small">Satker</label>
                            <select name="pa_pengaju" class="form-select border-0 shadow-sm">
                                <option value="">-- Semua Satker --</option>
                                @foreach(["Bandung", "Bekasi", "Bogor", "Cirebon", "Indramayu", "Karawang", "Sukabumi", "Sumedang", "Tasikmalaya"] as $pa)
                                <option value="{{ $pa }}" {{ request('pa_pengaju') == $pa ? 'selected' : '' }}>{{ $pa }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="text-uppercase text-muted fw-bold mb-1 small">Tahun Perkara</label>
                            <select name="tahun" class="form-select border-0 shadow-sm">
                                <option value="">-- Semua Tahun --</option>
                                @php
                                $currentYear = date('Y');
                                for($y = $currentYear; $y >= 1986; $y--) {
                                echo '<option value="'.$y.'" '.(request('tahun')==$y ? 'selected' : '' ).'>'.$y.'</option>';
                                }
                                @endphp
                            </select>
                        </div>

                        <button type="submit" class="btn btn-danger w-100 fw-bold rounded-pill shadow-sm mt-2">
                            <i class="fas fa-search me-1"></i> CARI DATA
                        </button>

                        <a href="{{ route('retensi-arsip.index') }}" class="btn btn-link btn-sm w-100 mt-2 text-decoration-none text-muted small">
                            Reset Filter
                        </a>
                    </form>
                </div>
            </div>
        </div>

        {{-- Tabel Data --}}
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <table class="table table-hover align-middle mb-0 bg-white">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th class="py-3 px-4">NOMOR PERKARA</th>
                            <th class="py-3">PIHAK-PIHAK</th>
                            <th class="py-3 text-center">STATUS</th>
                            <th class="py-3 text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $item)
                        <tr>
                            <td class="px-4">
                                <div class="fw-bold text-danger">{{ $item->no_banding }}</div>
                                <small class="text-muted">{{ $item->pa_pengaju }} ({{ $item->no_pa }})</small>
                            </td>
                            <td>
                                <div class="fw-normal text-uppercase small">{{ $item->pembanding }}</div>
                                <div class="text-danger fw-bold" style="font-size:0.65rem">VS</div>
                                <div class="fw-normal text-uppercase small">{{ $item->terbanding }}</div>
                            </td>
                            <td class="text-center">
                                @if(!empty($item->putusan))
                                <span class="badge bg-success-subtle text-success border border-success px-3 shadow-sm">
                                    <i class="fas fa-check-circle me-1"></i> DIGITAL
                                </span>
                                <small class="d-block text-muted mt-1" style="font-size: 0.65rem;">(PDF Tersedia)</small>
                                @else
                                <span class="badge bg-danger-subtle text-danger border border-danger px-3 shadow-sm">
                                    <i class="fas fa-file-invoice me-1"></i> FISIK
                                </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group shadow-sm rounded-pill overflow-hidden border">
                                    <a href="{{ route('retensi-arsip.show', $item->id) }}" class="btn btn-white btn-sm px-3 border-end" title="Detail">
                                        <i class="fas fa-eye text-primary"></i>
                                    </a>

                                    <a href="{{ route('retensi-arsip.edit', $item->id) }}" class="btn btn-white btn-sm px-3 border-end" title="Edit">
                                        <i class="fas fa-edit text-warning"></i>
                                    </a>

                                    {{-- Tombol Hapus dengan Data Attributes --}}
                                    <button type="button"
                                        class="btn btn-white btn-sm px-3 text-danger btn-delete"
                                        title="Hapus"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteModal"
                                        data-id="{{ $item->id }}"
                                        data-nobanding="{{ $item->no_banding }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">Data tidak ditemukan...</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $data->links() }}</div>
        </div>
    </div>
</div>

{{-- Modal Delete --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-dark" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4">
                <div class="text-center mb-3">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning"></i>
                </div>
                <p class="text-center text-muted mb-0">Apakah Anda yakin ingin menghapus data arsip perkara:</p>
                <p class="text-center fw-bold text-danger fs-5 mt-1" id="deleteNoBanding"></p>
                <div class="alert alert-danger rounded-3 py-2 small">
                    <i class="fas fa-info-circle me-1"></i> Tindakan ini tidak dapat dibatalkan dan file PDF yang terkait akan dihapus secara permanen dari server.
                </div>
            </div>
            <div class="modal-footer border-0 pb-4 px-4">
                <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">BATAL</button>
                
                {{-- Form Delete dengan ID dinamis --}}
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">
                        <i class="fas fa-trash-alt me-1"></i> HAPUS
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tangkap semua tombol delete
    const deleteButtons = document.querySelectorAll('.btn-delete');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Ambil data dari attribute tombol
            const id = this.getAttribute('data-id');
            const noBanding = this.getAttribute('data-nobanding');
            
            // Set teks nomor perkara di modal
            document.getElementById('deleteNoBanding').innerText = noBanding;
            
            // Update form action dengan ID yang benar
            const form = document.getElementById('deleteForm');
            form.action = '{{ url("retensi-arsip-perkara/delete") }}/' + id;
            // atau gunakan route name:
            // form.action = '{{ route("retensi-arsip.destroy", "") }}/' + id;
        });
    });
});
</script>

@endsection