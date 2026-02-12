@extends('layouts.app')

@section('title', 'Monitor Perkara Kasasi')

@section('content')

<div class="progress fixed-top" style="height: 3px; z-index: 2000;">
    <div id="refreshBar" class="progress-bar bg-warning" role="progressbar" style="width: 100%"></div>
</div>

<div class="container-fluid px-4">

    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h5 class="text-uppercase text-muted small fw-bold ls-1 mb-1">Monitoring Perkara</h5>
            <h2 class="fw-bold text-dark mb-0"> Permohonan Kasasi Tahun {{ $tahun }}</h2>
        </div>
        <div class="d-flex gap-2">
            <form action="{{ route('kasasi.index') }}" method="GET">
                <select name="tahun" class="form-select border-0 shadow-sm bg-white text-primary fw-bold" style="cursor: pointer;" onchange="this.form.submit()">
                    @foreach($years as $year)
                    <option value="{{ $year }}" {{ $year == $tahun ? 'selected' : '' }}>Tahun {{ $year }}</option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="p-3 bg-white rounded-3 shadow-sm d-flex align-items-center justify-content-between border-start border-4 border-primary">
                <div>
                    <div class="text-muted small fw-bold text-uppercase">Total Perkara</div>
                    <div class="fs-3 fw-bold text-dark">{{ number_format($grandTotal, 0, ',', '.') }}</div>
                </div>
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3">
                    <i class="fas fa-file-contract fa-lg"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 bg-white rounded-3 shadow-sm d-flex align-items-center justify-content-between border-start border-4 border-success">
                <div>
                    <div class="text-muted small fw-bold text-uppercase">Satuan Kerja</div>
                    <div class="fs-3 fw-bold text-dark">26</div>
                </div>
                <div class="bg-success bg-opacity-10 text-success rounded-circle p-3">
                    <i class="fas fa-building fa-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover w-100 mb-0" id="dataTable">
                    <thead class="bg-light">
                        <tr style="border-bottom: 2px solid #e9ecef;">
                            <th class="py-3 ps-4 text-secondary text-uppercase font-size-xs fw-bold" width="5%">No</th>
                            <th class="py-3 text-secondary text-uppercase font-size-xs fw-bold" width="20%">Satuan Kerja / Jenis Perkara</th>
                            <th class="py-3 text-center text-secondary text-uppercase font-size-xs fw-bold">No. PA</th>
                            <th class="py-3 text-center text-secondary text-uppercase font-size-xs fw-bold">No. PTA</th>
                            <th class="py-3 text-center text-secondary text-uppercase font-size-xs fw-bold">No. Kasasi</th>
                            <th class="py-3 text-center text-secondary text-uppercase font-size-xs fw-bold">Tgl. Reg</th>
                            <th class="py-3 text-secondary text-uppercase font-size-xs fw-bold">Ketua Majelis</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $currentSatker = null;
                        $rowNumber = 1;
                        @endphp

                        @forelse($data as $item)

                        {{-- GROUP HEADERS (SATKER) --}}
                        @if($currentSatker != $item->pengadilan_agama)
                        <tr class="bg-body-tertiary group-row">
                            <td colspan="7" class="py-3 ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-dark text-white rounded-circle d-flex justify-content-center align-items-center fw-bold me-3 shadow-sm" style="width: 28px; height: 28px; font-size: 0.8rem;">
                                        {{ $item->nomor_urut }}
                                    </div>
                                    <span class="fw-bold text-dark fs-6">{{ $item->pengadilan_agama }}</span>

                                    @php $totalSatker = $totals->firstWhere('pengadilan_agama', $item->pengadilan_agama); @endphp
                                    <span class="badge bg-white text-secondary border ms-3 rounded-pill px-3">
                                        {{ $totalSatker->total ?? 0 }} Perkara
                                    </span>
                                </div>
                            </td>
                        </tr>
                        @php $currentSatker = $item->pengadilan_agama; @endphp
                        @endif

                        {{-- DATA ROW --}}
                        <tr class="align-middle border-bottom-light">
                            <td class="text-center text-muted fw-light">{{ $rowNumber++ }}</td>

                            <td>
                                @php
                                $jp = strtolower($item->jenis_perkara);
                                // Warna Soft (Pastel)
                                $bgClass = 'bg-secondary bg-opacity-10 text-secondary';
                                if(str_contains($jp, 'cerai gugat')) $bgClass = 'bg-warning bg-opacity-10 text-warning-dark';
                                elseif(str_contains($jp, 'cerai talak')) $bgClass = 'bg-danger bg-opacity-10 text-danger';
                                elseif(str_contains($jp, 'waris')) $bgClass = 'bg-success bg-opacity-10 text-success';
                                elseif(str_contains($jp, 'ekonomi')) $bgClass = 'bg-info bg-opacity-10 text-info-dark';
                                elseif(str_contains($jp, 'itsbat')) $bgClass = 'bg-primary bg-opacity-10 text-primary';
                                @endphp
                                <span class="badge {{ $bgClass }} px-3 py-2 rounded-2 fw-medium border border-0">
                                    {{ $item->jenis_perkara }}
                                </span>
                            </td>

                            <td class="text-center">
                                <span class="font-mono text-dark small">{{ $item->no_pa }}</span>
                            </td>
                            <td class="text-center">
                                <span class="font-mono text-muted small">{{ $item->no_pta }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success bg-opacity-10 text-success font-mono px-2 py-1 border border-success border-opacity-25">
                                    {{ $item->no_kasasi }}
                                </span>
                            </td>

                            <td class="text-center">
                                @if($item->tgl_reg_kasasi != '-')
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-dark small">{{ \Carbon\Carbon::parse($item->tgl_reg_kasasi)->format('d/m/Y') }}</span>
                                    <span class="text-muted" style="font-size: 0.7rem;">{{ \Carbon\Carbon::parse($item->tgl_reg_kasasi)->diffForHumans() }}</span>
                                </div>
                                @else
                                -
                                @endif
                            </td>

                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-placeholder me-2 bg-light text-muted rounded-circle d-flex justify-content-center align-items-center" style="width: 30px; height: 30px;">
                                        <i class="fas fa-gavel fa-xs"></i>
                                    </div>
                                    <span class="text-dark small fw-medium">{{ $item->kmh }}</span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted opacity-50">
                                    <i class="fas fa-folder-open fa-3x mb-3"></i>
                                    <p>Tidak ada data ditemukan</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white py-3 border-top">
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted small fst-italic">Menampilkan data real-time dari SIPP Satker</span>
                <div class="fw-bold fs-5">
                    Total: <span class="text-primary">{{ number_format($grandTotal, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* 1. Custom Font Utilities */
    .ls-1 {
        letter-spacing: 1px;
    }

    .font-size-xs {
        font-size: 0.75rem;
    }

    /* 2. Monospace untuk Nomor Perkara (Agar angka sejajar) */
    .font-mono {
        font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
        letter-spacing: -0.5px;
    }

    /* 3. Warna Text Khusus untuk Soft Badge */
    .text-warning-dark {
        color: #d97706 !important;
    }

    /* Warna oranye gelap agar terbaca di bg kuning */
    .text-info-dark {
        color: #0891b2 !important;
    }

    /* 4. Table Tweaks */
    .table> :not(caption)>*>* {
        padding: 1rem 0.75rem;
        /* Padding lebih lega */
        background-color: transparent;
        /* Hilangkan bg default */
        box-shadow: none;
    }

    .border-bottom-light {
        border-bottom: 1px solid #f3f4f6;
        /* Garis pemisah sangat tipis */
    }

    /* Hilangkan border samping */
    .table td,
    .table th {
        border-right: none !important;
        border-left: none !important;
    }

    /* Group Row Styling */
    .group-row td {
        background-color: #f9fafb !important;
        /* Abu-abu sangat muda */
        border-top: 1px solid #e5e7eb;
        border-bottom: 1px solid #e5e7eb;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            paging: false, // Matikan pagination agar scroll semua (cocok untuk monitor)
            searching: true, // Tetap aktifkan pencarian
            ordering: false, // Matikan sorting agar grouping satker tidak acak
            info: false, // Matikan text "Showing 1 to 10"
            dom: '<"p-3"f>rt', // Layout simple: hanya search dan table
            language: {
                search: "",
                searchPlaceholder: "Cari nomor perkara atau hakim..."
            }
        });

        // Styling Search Box DataTables
        $('.dataTables_filter input').addClass('form-control form-control-sm border-0 bg-light shadow-sm').css('width', '300px');

        // Logic Auto Refresh (Sama seperti sebelumnya)
        const refreshTime = 60;
        let timeLeft = refreshTime;
        const progressBar = document.getElementById('refreshBar');

        setInterval(function() {
            timeLeft--;
            const percentage = (timeLeft / refreshTime) * 100;
            progressBar.style.width = percentage + "%";
            if (timeLeft <= 0) window.location.reload();
        }, 1000);
    });
</script>
@endpush