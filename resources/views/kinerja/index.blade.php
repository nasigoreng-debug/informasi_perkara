@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<style>
    .content-wrapper {
        padding: 30px 20px;
    }

    .filter-wrapper {
        background: #fff;
        border-radius: 50px;
        padding: 8px 20px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        border: 1px solid #e3e6f0;
    }

    .premium-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
        overflow: hidden;
    }

    .table-modern thead th {
        background-color: #4e73df !important;
        color: #ffffff !important;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.5px;
        border: none;
        padding: 12px;
    }

    .table-modern tbody tr {
        transition: all 0.2s;
    }

    .table-modern tbody tr:hover {
        background-color: rgba(78, 115, 223, 0.05) !important;
    }

    .nama-personil {
        text-transform: capitalize;
        font-weight: 400;
        color: #3a3b45;
    }

    .badge-kode {
        background: #f8f9fc;
        color: #4e73df;
        font-weight: 800;
        padding: 4px 8px;
        border-radius: 6px;
        border: 1px solid #e3e6f0;
    }

    .badge-hari {
        min-width: 65px;
        font-weight: 700;
        font-size: 12px;
        padding: 6px;
        border-radius: 50px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .hari-hijau {
        background-color: #1cc88a !important;
        color: #fff;
    }

    .hari-kuning {
        background-color: #f6c23e !important;
        color: #fff;
    }

    .hari-merah {
        background-color: #e74a3b !important;
        color: #fff;
    }

    .progress {
        height: 8px;
        border-radius: 10px;
        background: #eaecf4;
    }

    .sticky-top {
        top: -1px;
        z-index: 10;
    }

    /* ============================================================
       FIX CETAK: CSS KHUSUS UNTUK PRINT
       ============================================================ */
    @media print {

        /* Sembunyikan tombol filter, sidebar, navbar, dan search box saat diprint */
        .filter-wrapper,
        .btn,
        #searchHakim,
        #searchPP,
        .sidebar,
        .navbar,
        footer,
        .reset-btn {
            display: none !important;
        }

        /* Hilangkan scrollbar dan batasan tinggi agar semua data keluar */
        .table-responsive {
            max-height: none !important;
            overflow: visible !important;
        }

        /* Hilangkan shadow dan border radius agar cetakan lebih bersih */
        .premium-card {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
        }

        /* Pastikan tabel memenuhi lebar kertas */
        .container-fluid,
        .content-wrapper {
            padding: 0 !important;
            margin: 0 !important;
        }

        /* Atur warna agar tetap muncul saat diprint (opsional) */
        .table-dark th {
            background-color: #4e73df !important;
            color: white !important;
            -webkit-print-color-adjust: exact;
        }

        .badge,
        .progress-bar {
            -webkit-print-color-adjust: exact;
        }
    }
</style>

<div class="container-fluid content-wrapper animate__animated animate__fadeIn">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800 fw-bold">Statistik Kinerja</h1>
        <div class="d-flex gap-2">
            <form action="{{ route('kinerja.index') }}" method="GET" class="filter-wrapper d-flex gap-2 align-items-center animate__animated animate__fadeInRight">
                <div class="d-flex align-items-center px-2">
                    <i class="far fa-calendar-alt text-primary me-2"></i>
                    <input type="date" name="tgl_awal" class="form-control form-control-sm border-0 bg-transparent" value="{{ $tgl_awal }}">
                </div>
                <div class="text-muted small fw-bold text-uppercase">S.D</div>
                <div class="d-flex align-items-center px-2">
                    <input type="date" name="tgl_akhir" class="form-control form-control-sm border-0 bg-transparent" value="{{ $tgl_akhir }}">
                </div>
                <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm">Filter</button>
            </form>
            <button onclick="window.print()" class="btn btn-sm btn-dark rounded-pill px-3 shadow-sm">
                <i class="fas fa-print me-1"></i> Cetak
            </button>
        </div>
    </div>

    <div class="alert alert-warning py-3 border-0 shadow-sm mb-4 animate__animated animate__fadeInUp no-print">
        <i class="fas fa-info-circle me-2 text-primary"></i>
        Beban dihitung dari <strong>Perkara Masuk</strong> (periode ini) + <strong>Sisa Perkara</strong> (dari periode lalu).
    </div>

    <div class="row">
        <div class="col-xl-6 mb-4">
            <div class="card premium-card animate__animated animate__zoomIn">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-gavel me-2"></i>KINERJA HAKIM TINGGI</h6>
                    <input type="text" id="searchHakim" class="form-control form-control-sm w-50 rounded-pill no-print" placeholder="Cari...">
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 600px;">
                        <table class="table table-modern table-hover align-middle mb-0" id="tableHakim" style="font-size: 13px;">
                            <thead class="sticky-top">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Nama</th>
                                    <th class="text-center">Kode</th>
                                    <th class="text-center">Beban</th>
                                    <th class="text-center">Putus</th>
                                    <th class="text-center">Sisa</th>
                                    <th width="20%">%</th>
                                    <th class="text-center">Rata²</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kinerjaHakim as $index => $h)
                                <tr>
                                    <td class="text-center text-muted small">{{ $index + 1 }}</td>
                                    <td class="nama-personil">{{ strtolower($h->nama) }}</td>
                                    <td class="text-center"><span class="badge-kode">{{ $h->kode ?: '-' }}</span></td>
                                    <td class="text-center fw-bold">{{ $h->beban }}</td>
                                    <td class="text-center text-success fw-bold">{{ $h->putus }}</td>
                                    <td class="text-center text-danger fw-bold">{{ $h->sisa }}</td>
                                    <td data-sort="{{ $h->persen }}">
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2">
                                                <div class="progress-bar bg-success shadow-sm" style="width: {{ $h->persen }}%"></div>
                                            </div>
                                            <span class="fw-bold">{{ round($h->persen) }}%</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @php
                                        $warna = 'hari-merah';
                                        if($h->rata_hari <= 30) $warna='hari-hijau' ;
                                            elseif($h->rata_hari <= 60) $warna='hari-kuning' ;
                                                @endphp
                                                <span class="badge badge-hari {{ $warna }}">
                                                {{ $h->rata_hari }} Hari
                                                </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 mb-4">
            <div class="card premium-card animate__animated animate__zoomIn" style="animation-delay: 0.1s;">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-info"><i class="fas fa-user-tie me-2"></i>KINERJA PANITERA PENGGANTI</h6>
                    <input type="text" id="searchPP" class="form-control form-control-sm w-50 rounded-pill no-print" placeholder="Cari...">
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 600px;">
                        <table class="table table-modern table-hover align-middle mb-0" id="tablePP" style="font-size: 13px;">
                            <thead class="sticky-top">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Nama</th>
                                    <th class="text-center">Kode</th>
                                    <th class="text-center">Beban</th>
                                    <th class="text-center">Minut</th>
                                    <th class="text-center">Sisa</th>
                                    <th width="20%">%</th>
                                    <th class="text-center">Rata²</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kinerjaPP as $index => $p)
                                <tr>
                                    <td class="text-center text-muted small">{{ $index + 1 }}</td>
                                    <td class="nama-personil">{{ strtolower($p->nama) }}</td>
                                    <td class="text-center"><span class="badge-kode" style="color:#36b9cc">D</span></td>
                                    <td class="text-center fw-bold">{{ $p->beban }}</td>
                                    <td class="text-center text-info fw-bold">{{ $p->minutasi }}</td>
                                    <td class="text-center text-danger fw-bold">{{ $p->sisa }}</td>
                                    <td data-sort="{{ $p->persen_minutasi }}">
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2">
                                                <div class="progress-bar bg-info shadow-sm" style="width: {{ $p->persen_minutasi }}%"></div>
                                            </div>
                                            <span class="fw-bold">{{ round($p->persen_minutasi) }}%</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @php
                                        $warnaPP = 'hari-merah';
                                        if($p->rata_serah <= 30) $warnaPP='hari-hijau' ;
                                            elseif($p->rata_serah <= 60) $warnaPP='hari-kuning' ;
                                                @endphp
                                                <span class="badge badge-hari {{ $warnaPP }}">
                                                {{ $p->rata_serah }} Hari
                                                </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        jQuery.extend(jQuery.fn.dataTableExt.oSort, {
            "natural-asc": function(a, b) {
                return a.localeCompare(b, undefined, {
                    numeric: true,
                    sensitivity: 'base'
                });
            },
            "natural-desc": function(a, b) {
                return b.localeCompare(a, undefined, {
                    numeric: true,
                    sensitivity: 'base'
                });
            }
        });

        const config = {
            "paging": false,
            "info": false,
            "dom": 't',
            "order": [],
            "columnDefs": [{
                "type": "natural",
                "targets": 2
            }]
        };
        const tHakim = $('#tableHakim').DataTable(config);
        const tPP = $('#tablePP').DataTable(config);
        $('#searchHakim').on('keyup', function() {
            tHakim.search(this.value).draw();
        });
        $('#searchPP').on('keyup', function() {
            tPP.search(this.value).draw();
        });
    });
</script>
@endsection