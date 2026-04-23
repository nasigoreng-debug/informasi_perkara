@extends('layouts.app')

@section('content')
<style>
    /* Custom Styling untuk Kesan Mewah */
    .card-custom {
        border-radius: 15px;
        border: none;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        transition: 0.3s;
    }

    .card-custom:hover {
        transform: translateY(-5px);
    }

    .bg-gradient-pta {
        background: linear-gradient(135deg, #1a4d2e 0%, #34a853 100%) !important;
    }

    .bg-gradient-gold {
        background: linear-gradient(135deg, #b8860b 0%, #f4c430 100%) !important;
    }

    .bg-gradient-sky {
        background: linear-gradient(135deg, #0f4c75 0%, #3282b8 100%) !important;
    }

    .filter-wrapper {
        background: #fff;
        border-radius: 50px;
        padding: 10px 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .btn-pta {
        border-radius: 30px;
        font-weight: 600;
        padding: 8px 20px;
    }

    .table-pta thead th {
        background: #f8fbf9;
        color: #1a4d2e;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        border: none;
    }

    .tfoot-total {
        background: #f1f8f4;
        font-weight: 800;
        color: #1a4d2e;
    }

    .progress-w {
        height: 7px;
        border-radius: 10px;
        background-color: #eee;
        overflow: hidden;
    }
</style>

<div class="container-fluid py-4">
    <div class="row align-items-center mb-4">
        <div class="col-md-5 text-md-left text-center">
            <h3 class="font-weight-bold mb-0 text-dark">✨ Monitoring Perkara Prodeo</h3>
            <p class="text-muted small mb-0">PTA Bandung - Data Realtime SIPP Satker</p>
        </div>
        <div class="col-md-7 mt-3 mt-md-0 text-md-right text-center">
            <div class="d-flex justify-content-md-end justify-content-center flex-wrap align-items-center">
                <form action="{{ route('prodeo.index') }}" method="GET" class="filter-wrapper d-flex align-items-center mb-2 mr-2">
                    <input type="date" name="tgl_awal" class="form-control form-control-sm border-0 bg-transparent text-primary font-weight-bold" value="{{ $tglAwal }}">
                    <span class="mx-2 text-muted">sampai</span>
                    <input type="date" name="tgl_akhir" class="form-control form-control-sm border-0 bg-transparent text-primary font-weight-bold" value="{{ $tglAkhir }}">
                    <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm ml-3">🔍 Filter</button>
                    <a href="{{ route('prodeo.index', ['reset' => 1]) }}" class="btn btn-light btn-sm rounded-pill ml-1">Reset</a>
                </form>

                <a href="{{ route('prodeo.export', ['tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhir]) }}"
                    class="btn btn-success btn-pta shadow-sm mb-2">
                    <i class="fas fa-file-excel mr-2"></i> Export Excel
                </a>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card card-custom bg-gradient-pta text-white">
                <div class="card-body p-4 text-center">
                    <i class="fas fa-folder-open fa-2x mb-2 opacity-5"></i>
                    <h6 class="text-uppercase small mb-1 opacity-8">Total Penerimaan</h6>
                    <h2 class="font-weight-bold mb-0">{{ number_format($summary->total_masuk, 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card card-custom bg-gradient-gold text-white">
                <div class="card-body p-4 text-center">
                    <i class="fas fa-user-shield fa-2x mb-2 opacity-5"></i>
                    <h6 class="text-uppercase small mb-1 opacity-8">Total Perkara Prodeo</h6>
                    <h2 class="font-weight-bold mb-0">{{ number_format($summary->total_prodeo, 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card card-custom bg-gradient-sky text-white">
                <div class="card-body p-4 text-center">
                    <i class="fas fa-chart-line fa-2x mb-2 opacity-5"></i>
                    <h6 class="text-uppercase small mb-1 opacity-8">Rata-rata Rasio Wilayah</h6>
                    <h2 class="font-weight-bold mb-0">{{ $summary->rasio }}%</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom overflow-hidden border-0 shadow-lg">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 table-pta" id="tableProdeo">
                <thead class="text-center">
                    <tr>
                        <th class="text-left px-4">Satuan Kerja</th>
                        <th>Masuk</th>
                        <th>Prodeo</th>
                        <th width="20%">Rasio Penggunaan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach($data as $row)
                    <tr>
                        <td class="text-left px-4 font-weight-bold text-dark">{{ $row->satker }}</td>
                        <td>{{ number_format($row->total_masuk, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge badge-pill px-3 py-2" style="background: #e8f5e9; color: #2e7d32;">
                                {{ number_format($row->jumlah_prodeo, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-3">
                            <div class="d-flex align-items-center justify-content-center">
                                <span class="mr-3 font-weight-bold small">{{ $row->persentase }}%</span>
                                <div class="progress-w flex-grow-1 d-none d-md-flex">
                                    <div class="progress-bar {{ $row->persentase > 15 ? 'bg-warning' : 'bg-success' }}"
                                        role="progressbar" style="width: {{ $row->persentase }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('prodeo.detail', ['satker' => $row->satker, 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhir]) }}"
                                class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm">
                                <i class="fas fa-search-plus"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="tfoot-total text-center">
                    <tr>
                        <td class="text-left px-4 font-weight-bold">GRAND TOTAL SE-WILAYAH</td>
                        <td class="font-weight-bold">{{ number_format($summary->total_masuk, 0, ',', '.') }}</td>
                        <td class="font-weight-bold">{{ number_format($summary->total_prodeo, 0, ',', '.') }}</td>
                        <td class="font-weight-bold">{{ $summary->rasio }}%</td>
                        <td>-</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#tableProdeo').DataTable({
            pageLength: 26,
            responsive: true,
            order: [
                [3, 'desc']
            ], // Rasio tertinggi di atas
            dom: 'frtip',
            language: {
                search: "",
                searchPlaceholder: "🔍 Cari Satker..."
            }
        });

        // Mempercantik input search DataTables
        $('.dataTables_filter input').addClass('form-control-sm border-0 shadow-none px-3').css('background', '#f1f3f4').css('border-radius', '20px');
    });
</script>
@endpush