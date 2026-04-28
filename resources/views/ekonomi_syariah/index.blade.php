@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800 fw-bold">Monitoring Perkara Ekonomi Syariah</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('monitoring') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Ekonomi Syariah</li>
                </ol>
            </nav>
        </div>

        <div class="row mb-4">
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2 border-0"
                    style="border-left: 5px solid #4e73df !important;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Perkara Se-Jawa
                                    Barat</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($grandTotal, 0, ',', '.') }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-gavel fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2 border-0"
                    style="border-left: 5px solid #1cc88a !important;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Periode Monitoring
                                </div>
                                <div class="small mb-0 font-weight-bold text-gray-800">
                                    {{ date('d M Y', strtotime($tgl_awal)) }} - {{ date('d M Y', strtotime($tgl_akhir)) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2 border-0"
                    style="border-left: 5px solid #36b9cc !important;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Jumlah Satker Terdata
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ count($data) }} Satker</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-university fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-filter mr-2"></i>Parameter Pencarian</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('ekonomi-syariah.index') }}" method="GET" class="row align-items-end">
                    <div class="col-md-3 mb-3">
                        <label class="form-label small fw-bold text-muted">Tanggal Mulai</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-right-0"><i
                                    class="fas fa-calendar-alt text-primary"></i></span>
                            <input type="date" name="tgl_awal" class="form-control" value="{{ $tgl_awal }}">
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label small fw-bold text-muted">Tanggal Sampai</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-right-0"><i
                                    class="fas fa-calendar-alt text-primary"></i></span>
                            <input type="date" name="tgl_akhir" class="form-control" value="{{ $tgl_akhir }}">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="btn-group w-100 shadow-sm">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fa fa-search mr-2"></i> Tampilkan Data
                            </button>
                            <a href="{{ route('ekonomi-syariah.index', ['reset' => 1]) }}" class="btn btn-light border">
                                <i class="fa fa-sync mr-2"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>

                <hr class="my-4">

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="text-uppercase small font-weight-bold" style="background-color: #f8f9fc;">
                            <tr>
                                <th class="text-center py-3" style="width: 80px;">No</th>
                                <th class="py-3">Satuan Kerja</th>
                                <th class="text-center py-3" style="width: 250px;">Total Perkara</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td class="text-center font-weight-bold text-muted">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mr-3"
                                                style="width: 35px; height: 35px;">
                                                <i class="fas fa-landmark text-primary small"></i>
                                            </div>
                                            <span class="font-weight-bold text-gray-800">{{ $item->nama_satker }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if ($item->total_perkara > 0)
                                            <a href="{{ route('ekonomi-syariah.detail', ['satker' => strtolower($item->nama_satker), 'tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]) }}"
                                                class="btn btn-sm btn-primary-soft rounded-pill px-4 font-weight-bold"
                                                style="background-color: #eef2ff; color: #4e73df; border: none;">
                                                {{ number_format($item->total_perkara, 0, ',', '.') }} Perkara
                                            </a>
                                        @else
                                            <span class="badge badge-light p-2 text-muted px-3">Nihil</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-primary text-white shadow-sm">
                                <td colspan="2" class="text-center font-weight-bold py-3 text-uppercase">Total
                                    Keseluruhan Wilayah PTA Bandung</td>
                                <td class="text-center py-3">
                                    <span
                                        class="h5 mb-0 font-weight-bold">{{ number_format($grandTotal, 0, ',', '.') }}</span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Tambahan CSS untuk mempercantik */
        .btn-primary-soft:hover {
            background-color: #4e73df !important;
            color: white !important;
            transition: all 0.3s;
        }

        .table thead th {
            border-top: none;
            color: #4e73df;
            letter-spacing: 0.05em;
        }

        .card {
            border-radius: 12px;
        }

        .input-group-text {
            border-radius: 8px 0 0 8px;
        }

        .form-control {
            border-radius: 0 8px 8px 0;
        }

        .btn {
            border-radius: 8px;
        }
    </style>
@endsection
