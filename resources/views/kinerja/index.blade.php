@extends('layouts.app')

@section('content')
{{-- Stylesheets --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

{{-- Custom CSS --}}
<style>
    :root {
        --navy: #001f3f;
        --gold: #d4af37;
        --gold-soft: rgba(212, 175, 55, 0.1);
        --white-glass: rgba(255, 255, 255, 0.9);
    }

    .content-wrapper {
        padding: 30px 20px;
        background-color: #f8f9fc;
    }

    /* Info Boxes */
    .kriteria-box {
        background: var(--navy);
        color: #fff;
        border-radius: 15px;
        border-left: 6px solid var(--gold);
        box-shadow: 0 10px 30px rgba(0, 31, 63, 0.2);
    }

    .kotretan-box {
        background: #fff;
        border-radius: 12px;
        border: 1px dashed var(--gold);
        padding: 15px;
        font-family: 'Courier New', Courier, monospace;
        color: #333;
    }

    .premium-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 0.5rem 2rem 0 rgba(0, 0, 0, 0.1);
        overflow: hidden;
        background: var(--white-glass);
    }

    /* Table Design */
    .table-modern thead th {
        background-color: var(--navy) !important;
        color: var(--gold) !important;
        text-transform: uppercase;
        font-size: 10px;
        letter-spacing: 1.5px;
        border: none;
        padding: 18px 10px;
    }

    .table-modern tbody tr {
        transition: all 0.2s;
        border-bottom: 1px solid #f1f1f1;
    }

    .table-modern tbody tr:hover {
        background-color: var(--gold-soft) !important;
    }

    .table-modern tbody tr:first-child {
        background-color: rgba(212, 175, 55, 0.07) !important;
        border-left: 5px solid var(--gold);
    }

    .nama-personil {
        text-transform: uppercase;
        font-weight: 700;
        color: var(--navy);
        font-size: 11px;
    }

    /* Score Badge */
    .skor-final {
        font-weight: 800;
        color: var(--navy);
        background: linear-gradient(135deg, #fff 0%, var(--gold-soft) 100%);
        padding: 6px 14px;
        border-radius: 10px;
        border: 1px solid var(--gold);
        display: inline-block;
    }

    .badge-hari {
        min-width: 65px;
        font-weight: 700;
        font-size: 11px;
        padding: 6px;
        border-radius: 50px;
    }

    .hari-hijau {
        background-color: #1cc88a;
        color: #fff;
    }

    .hari-kuning {
        background-color: #f6c23e;
        color: #fff;
    }

    .hari-merah {
        background-color: #e74a3b;
        color: #fff;
    }

    .progress {
        height: 6px;
        border-radius: 10px;
        background: #eaecf4;
    }

    .btn-primary {
        background-color: var(--navy);
        border-color: var(--navy);
    }

    @media print {

        .filter-wrapper,
        .btn,
        .no-print,
        .sidebar,
        .navbar,
        footer {
            display: none !important;
        }

        .premium-card {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
        }

        .kriteria-box,
        .kotretan-box {
            background-color: #f8f9fc !important;
            color: #000 !important;
            border: 1px solid #000 !important;
        }
    }
</style>

{{-- Main Content --}}
<div class="container-fluid content-wrapper animate__animated animate__fadeIn">
    {{-- Header --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800 fw-bold">Monitoring Kinerja Pegawai</h1>
            <p class="text-muted small mb-0">
                <i class="fas fa-shield-alt text-gold me-1"></i> Dashboard penyelsaian perkara
            </p>
        </div>
        <div class="d-flex gap-2">
            <form action="{{ route('kinerja.index') }}" method="GET" class="filter-wrapper d-flex gap-2 align-items-center no-print">
                <input type="date" name="tgl_awal" class="form-control form-control-sm border-0 bg-transparent" value="{{ $tgl_awal }}">
                <div class="text-muted small fw-bold">S.D</div>
                <input type="date" name="tgl_akhir" class="form-control form-control-sm border-0 bg-transparent" value="{{ $tgl_akhir }}">
                <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm">Filter</button>
            </form>
            <button onclick="window.print()" class="btn btn-sm btn-dark rounded-pill px-3 shadow-sm">
                <i class="fas fa-print me-1"></i> Cetak
            </button>
        </div>
    </div>

    {{-- Info Boxes --}}
    <div class="row mb-4">
        <div class="col-md-7">
            <div class="kriteria-box p-4 h-100 shadow-sm">
                <h5 class="fw-bold mb-3" style="color: var(--gold);">
                    <i class="fas fa-balance-scale me-2"></i>Parameter Penilaian
                </h5>
                <ul class="list-unstyled small">
                    <li class="mb-2"><i class="fas fa-caret-right text-gold me-2"></i><strong>(60%) Persentase Penyelesaian:</strong> Fokus pada hasil putusan/minutasi.</li>
                    <li class="mb-2"><i class="fas fa-caret-right text-gold me-2"></i><strong>(40%) Volume Beban Kerja:</strong> Fokus pada kontribusi kuantitas perkara.</li>
                    <li class="mb-2"><i class="fas fa-caret-right text-gold me-2"></i><strong>Indeks Beban:</strong> Beban Pegawai dibanding Beban Tertinggi.</li>
                    <li><i class="fas fa-caret-right text-gold me-2"></i><strong>Catatan:</strong> Jika skor sama, Beban terbanyak & Rata-rata hari tercepat menang.</li>
                </ul>
            </div>
        </div>
        <div class="col-md-5">
            <div class="kotretan-box h-100 shadow-sm">
                <h6 class="fw-bold mb-2 text-navy"><i class="fas fa-edit me-2"></i> Rumus:</h6>
                <div class="small bg-light p-2 rounded">
                    <div class="mb-2 pb-2" style="border-bottom: 2px dotted var(--gold);">
                        <code>Skor Akhir = (% Hasil × 0.6) + ((Beban/Max) × 100 × 0.4)</code>
                    </div>
                    <code>
                        Misal Max Beban Kantor = 10<br>
                        Pegawai A: Beban 10, Selesai 70%<br>
                        Skor = (70 × 0.6) + (10/10 × 100 × 0.4) = 42 + 40 = <strong>82.0</strong><br><br>
                        Pegawai B: Beban 2, Selesai 100%<br>
                        Skor = (100 × 0.6) + (2/10 × 100 × 0.4) = 60 + 8 = <strong>68.0</strong>
                    </code>
                </div>
                <div class="mt-2 small text-muted"><em>*Pegawai A menang karena volume kerja jauh lebih berat.</em></div>
            </div>
        </div>
    </div>

    {{-- Tables Row --}}
    <div class="row">
        {{-- Hakim Table --}}
        <div class="col-xl-6 mb-4">
            <div class="card premium-card animate__animated animate__fadeInLeft">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-gavel me-2"></i>KINERJA HAKIM TINGGI</h6>
                    <input type="text" id="searchHakim" class="form-control form-control-sm w-50 rounded-pill no-print" placeholder="Cari...">
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 700px;">
                        <table class="table table-modern table-hover align-middle mb-0" id="tableHakim" style="font-size: 12px;">
                            <thead class="sticky-top">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Identitas</th>
                                    <th class="text-center">Beban</th>
                                    <th class="text-center">Putus</th>
                                    <th class="text-center">Sisa</th>
                                    <th width="15%">%</th>
                                    <th class="text-center">Rata²</th>
                                    <th class="text-center">Skor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kinerjaHakim as $index => $h)
                                <tr>
                                    <td class="text-center text-muted">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="nama-personil">{{ strtolower($h->nama) }}</div>
                                        <span class="small" style="font-size: 9px;">{{ $h->kode ?: '-' }}</span>
                                    </td>
                                    <td class="text-center fw-bold">{{ $h->beban }}</td>
                                    <td class="text-center text-success fw-bold">{{ $h->putus }}</td>
                                    <td class="text-center text-danger">{{ $h->sisa }}</td>
                                    <td data-sort="{{ $h->persen }}">
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2">
                                                <div class="progress-bar bg-success" style="width: {{ $h->persen }}%"></div>
                                            </div>
                                            <span class="small fw-bold">{{ round($h->persen) }}%</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @php
                                        $warna = $h->rata_hari <= 30 ? 'hari-hijau' : ($h->rata_hari <= 60 ? 'hari-kuning' : 'hari-merah' );
                                                @endphp
                                                <span class="badge badge-hari {{ $warna }}">{{ $h->rata_hari }} H</span>
                                    </td>
                                    <td class="text-center"><span class="skor-final">{{ $h->skor_final }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Panitera Pengganti Table --}}
        <div class="col-xl-6 mb-4">
            <div class="card premium-card animate__animated animate__fadeInRight">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-info"><i class="fas fa-user-tie me-2"></i>KINERJA PANITERA PENGGANTI</h6>
                    <input type="text" id="searchPP" class="form-control form-control-sm w-50 rounded-pill no-print" placeholder="Cari...">
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 700px;">
                        <table class="table table-modern table-hover align-middle mb-0" id="tablePP" style="font-size: 12px;">
                            <thead class="sticky-top">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Identitas</th>
                                    <th class="text-center">Beban</th>
                                    <th class="text-center">Minut</th>
                                    <th class="text-center">Sisa</th>
                                    <th width="15%">%</th>
                                    <th class="text-center">Rata²</th>
                                    <th class="text-center">Skor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kinerjaPP as $index => $p)
                                <tr>
                                    <td class="text-center text-muted">{{ $index + 1 }}</td>
                                    <td class="nama-personil">{{ strtolower($p->nama) }}</td>
                                    <td class="text-center fw-bold">{{ $p->beban }}</td>
                                    <td class="text-center text-info fw-bold">{{ $p->minutasi }}</td>
                                    <td class="text-center text-danger">{{ $p->sisa }}</td>
                                    <td data-sort="{{ $p->persen_minutasi }}">
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2">
                                                <div class="progress-bar bg-info" style="width: {{ $p->persen_minutasi }}%"></div>
                                            </div>
                                            <span class="small fw-bold">{{ round($p->persen_minutasi) }}%</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @php
                                        $warnaPP = $p->rata_serah <= 30 ? 'hari-hijau' : ($p->rata_serah <= 60 ? 'hari-kuning' : 'hari-merah' );
                                                @endphp
                                                <span class="badge badge-hari {{ $warnaPP }}">{{ $p->rata_serah }} H</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="skor-final" style="color: #15a2b8; background: rgba(54, 185, 204, 0.1); border-color: #36b9cc;">{{ $p->skor_final }}</span>
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

{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        const config = {
            paging: false,
            info: false,
            dom: 't',
            order: []
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