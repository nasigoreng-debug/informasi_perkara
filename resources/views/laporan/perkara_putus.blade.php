@extends('layouts.app')

@section('content')
<style>
    .table-rk4 {
        font-size: 8px;
        border: 1px solid #000;
        font-family: 'Arial Narrow', Arial, sans-serif;
    }

    .table-rk4 th,
    .table-rk4 td {
        border: 1px solid #000 !important;
        padding: 2px 1px !important;
        vertical-align: middle;
        text-align: center;
    }

    .v-text {
        writing-mode: vertical-rl;
        transform: rotate(180deg);
        white-space: nowrap;
        height: 150px;
        font-weight: bold;
        line-height: 1;
        padding: 5px 0 !important;
    }

    .header-gray {
        background: #f2f2f2;
        font-weight: bold;
    }

    .sticky-header {
        position: sticky;
        top: 0;
        background: white;
        z-index: 100;
    }

    .bg-kuning {
        background: #fffde7 !important;
    }

    .text-start-important {
        text-align: left !important;
        padding-left: 5px !important;
    }
</style>

<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="fw-bold mb-0">LAPORAN KEADAAN PERKARA YANG DIPUTUS (RK4)</h5>
            <small class="text-muted">Periode: {{ \Carbon\Carbon::parse($start)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($end)->format('d/m/Y') }}</small>
        </div>
        <div class="badge bg-primary px-3 py-2">PTA BANDUNG</div>
    </div>

    <div class="card mb-3 border-0 shadow-sm">
        <div class="card-body py-2 bg-light">
            <form action="{{ route('laporan.diputus.index') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="small fw-bold">TANGGAL MULAI</label>
                    <input type="date" name="start" class="form-select form-select-sm" value="{{ $start }}">
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold">TANGGAL SELESAI</label>
                    <input type="date" name="end" class="form-select form-select-sm" value="{{ $end }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-dark btn-sm w-100">
                        <i class="fas fa-filter me-1"></i> FILTER
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('laporan.diputus.export', ['start'=>$start, 'end'=>$end]) }}" class="btn btn-success btn-sm w-100">
                        <i class="fas fa-file-excel me-1"></i> EXCEL
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive shadow-sm" style="max-height: 80vh;">
        <table class="table table-rk4">
            <thead class="sticky-header">
                <tr class="header-gray">
                    <th rowspan="2" style="width: 20px;">NO</th>
                    <th rowspan="2" style="min-width: 150px;">NAMA PENGADILAN</th>
                    <th rowspan="2" class="v-text">SISA BULAN LALU</th>
                    <th rowspan="2" class="v-text">DITERIMA BULAN INI</th>
                    <th rowspan="2" class="v-text bg-kuning">JUMLAH (BEBAN)</th>
                    <th rowspan="2" class="v-text">DICABUT</th>
                    <th colspan="{{ count($jenisPerkara) }}">DIKABULKAN (PER JENIS PERKARA)</th>
                    <th rowspan="2" class="v-text">DITOLAK</th>
                    <th rowspan="2" class="v-text">TIDAK DITERIMA (NO)</th>
                    <th rowspan="2" class="v-text">GUGUR</th>
                    <th rowspan="2" class="v-text">DICORET DARI REGISTER</th>
                    <th rowspan="2" class="v-text bg-primary text-white">JUMLAH PUTUS</th>
                    <th rowspan="2" class="v-text bg-danger text-white">SISA AKHIR</th>
                </tr>
                <tr class="header-gray">
                    @foreach($jenisPerkara as $alias => $label)
                    <th class="v-text">{{ $label }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @php
                $t_sisa_lalu = 0; $t_terima = 0; $t_beban = 0; $t_cabut = 0;
                $t_kabul = array_fill_keys(array_keys($jenisPerkara), 0);
                $t_tolak = 0; $t_no = 0; $t_gugur = 0; $t_coret = 0; $t_putus = 0; $t_akhir = 0;
                @endphp

                @foreach($laporan as $row)
                <tr>
                    <td>{{ $row->no_urut }}</td>
                    <td class="text-start-important fw-bold">{{ $row->satker }}</td>
                    <td>{{ number_format($row->sisa_tahun_lalu) }}</td>
                    <td>{{ number_format($row->diterima) }}</td>
                    <td class="bg-kuning">{{ number_format($row->beban) }}</td>
                    <td>{{ number_format($row->dicabut) }}</td>

                    @foreach($jenisPerkara as $key => $label)
                    @php $t_kabul[$key] += $row->$key; @endphp
                    <td>{{ $row->$key }}</td>
                    @endforeach

                    <td>{{ number_format($row->ditolak) }}</td>
                    <td>{{ number_format($row->tidak_diterima) }}</td>
                    <td>{{ number_format($row->gugur) }}</td>
                    <td>{{ number_format($row->dicoret) }}</td>
                    <td class="bg-primary bg-opacity-10 fw-bold">{{ number_format($row->jml) }}</td>
                    <td class="bg-danger bg-opacity-10 fw-bold">{{ number_format($row->sisa) }}</td>
                </tr>
                @php
                $t_sisa_lalu += $row->sisa_tahun_lalu; $t_terima += $row->diterima; $t_beban += $row->beban;
                $t_cabut += $row->dicabut; $t_tolak += $row->ditolak; $t_no += $row->tidak_diterima;
                $t_gugur += $row->gugur; $t_coret += $row->dicoret; $t_putus += $row->jml; $t_akhir += $row->sisa;
                @endphp
                @endforeach
            </tbody>
            <tfoot class="header-gray sticky-bottom">
                <tr class="fw-bold">
                    <td colspan="2">TOTAL SE-WILAYAH JAWA BARAT</td>
                    <td>{{ number_format($t_sisa_lalu) }}</td>
                    <td>{{ number_format($t_terima) }}</td>
                    <td class="bg-kuning">{{ number_format($t_beban) }}</td>
                    <td>{{ number_format($t_cabut) }}</td>
                    @foreach($jenisPerkara as $key => $label)
                    <td>{{ number_format($t_kabul[$key]) }}</td>
                    @endforeach
                    <td>{{ number_format($t_tolak) }}</td>
                    <td>{{ number_format($t_no) }}</td>
                    <td>{{ number_format($t_gugur) }}</td>
                    <td>{{ number_format($t_coret) }}</td>
                    <td class="bg-primary text-white">{{ number_format($t_putus) }}</td>
                    <td class="bg-danger text-white">{{ number_format($t_akhir) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection