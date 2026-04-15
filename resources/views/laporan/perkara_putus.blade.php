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

    .sync-pill {
        display: inline-flex;
        align-items: center;
        background: #ffffff;
        border: 1px solid #dee2e6;
        padding: 3px 12px;
        border-radius: 50px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }
</style>

<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="fw-bold mb-0 text-uppercase">LAPORAN KEADAAN PERKARA YANG DIPUTUS (RK4)</h5>
            <div class="d-flex align-items-center mt-1">
                <small class="text-muted fw-bold me-3">
                    <i class="fas fa-calendar-alt me-1"></i>
                    Periode: {{ \Carbon\Carbon::parse($start)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($end)->format('d/m/Y') }}
                </small>

                @php
                $lastSync = \DB::table('sync_logs')
                ->where('modul', 'like', '%rk4%')
                ->latest('updated_at')
                ->first();
                @endphp

                @if($lastSync)
                <div class="sync-pill">
                    <i class="fas fa-sync-alt fa-spin text-success me-2" style="font-size: 10px;"></i>
                    <small class="text-muted" style="font-size: 11px;">
                        Terakhir Sinkron: <strong class="text-dark">{{ \Carbon\Carbon::parse($lastSync->updated_at)->format('d/m/Y H:i') }}</strong>
                    </small>
                </div>
                @endif
            </div>
        </div>
        <div class="badge bg-primary px-3 py-2 shadow-sm rounded-pill">PTA BANDUNG</div>
    </div>

    <div class="card mb-3 border-0 shadow-sm rounded border">
        <div class="card-body py-2 bg-light">
            <form action="{{ route('laporan.diputus.index') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="small fw-bold text-muted text-uppercase" style="font-size: 10px;">Tanggal Mulai</label>
                    <input type="date" name="start" class="form-control form-control-sm" value="{{ $start }}">
                </div>
                <div class="col-md-2">
                    <label class="small fw-bold text-muted text-uppercase" style="font-size: 10px;">Tanggal Selesai</label>
                    <input type="date" name="end" class="form-control form-control-sm" value="{{ $end }}">
                </div>
                <div class="col-md-6 d-flex gap-2">
                    <button type="submit" class="btn btn-dark btn-sm px-4 shadow-sm">
                        <i class="fas fa-filter me-1"></i> FILTER
                    </button>
                    <a href="{{ route('laporan.diputus.index') }}" class="btn btn-outline-secondary btn-sm px-3 shadow-sm bg-white">
                        <i class="fas fa-undo me-1"></i> RESET
                    </a>
                    <button type="button" onclick="window.print()" class="btn btn-outline-danger btn-sm px-3 shadow-sm bg-white">
                        <i class="fas fa-print me-1"></i> CETAK
                    </button>
                </div>
                <div class="col-md-2 text-end">
                    <a href="{{ route('laporan.diputus.export', ['start'=>$start, 'end'=>$end]) }}" class="btn btn-success btn-sm w-100 shadow-sm" target="_blank">
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
                    <th rowspan="2" class="v-text">DISMISSAL</th>
                    <th colspan="{{ count($jenisPerkara) }}">DIKABULKAN (PER JENIS PERKARA)</th>
                    <th rowspan="2" class="v-text">DITOLAK</th>
                    <th rowspan="2" class="v-text">TIDAK DITERIMA (NO)</th>
                    <th rowspan="2" class="v-text">GUGUR</th>
                    <th rowspan="2" class="v-text">DIGUGURKAN</th>
                    <th rowspan="2" class="v-text">DICORET DARI REGISTER</th>
                    <th rowspan="2" class="v-text">PERDAMAIAN</th>
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
                $t_sisa_lalu = 0; $t_terima = 0; $t_beban = 0; $t_cabut = 0; $t_dismissal = 0;
                $t_kabul = array_fill_keys(array_keys($jenisPerkara), 0);
                $t_tolak = 0; $t_no = 0; $t_gugur = 0; $t_digugurkan = 0; $t_coret = 0; $t_damai = 0; $t_putus = 0; $t_akhir = 0;
                @endphp

                @foreach($laporan as $row)
                <tr>
                    <td class="bg-light">{{ $row->no_urut }}</td>
                    <td class="text-start-important fw-bold text-uppercase">{{ $row->satker }}</td>
                    <td>{{ $row->sisa_tahun_lalu > 0 ? number_format($row->sisa_tahun_lalu) : '-' }}</td>
                    <td>{{ $row->diterima > 0 ? number_format($row->diterima) : '-' }}</td>
                    <td class="bg-kuning fw-bold text-dark">{{ $row->beban > 0 ? number_format($row->beban) : '-' }}</td>

                    {{-- Status Putus --}}
                    <td>{{ $row->dicabut > 0 ? number_format($row->dicabut) : '-' }}</td>
                    <td>{{ $row->dismissal > 0 ? number_format($row->dismissal) : '-' }}</td>

                    @foreach($jenisPerkara as $key => $label)
                    @php $t_kabul[$key] += ($row->$key ?? 0); @endphp
                    <td>{{ ($row->$key ?? 0) > 0 ? number_format($row->$key) : '-' }}</td>
                    @endforeach

                    <td>{{ $row->ditolak > 0 ? number_format($row->ditolak) : '-' }}</td>
                    <td>{{ $row->tidak_diterima > 0 ? number_format($row->tidak_diterima) : '-' }}</td>
                    <td>{{ $row->gugur > 0 ? number_format($row->gugur) : '-' }}</td>
                    <td>{{ $row->digugurkan > 0 ? number_format($row->digugurkan) : '-' }}</td>
                    <td>{{ $row->dicoret > 0 ? number_format($row->dicoret) : '-' }}</td>
                    <td>{{ $row->perdamaian > 0 ? number_format($row->perdamaian) : '-' }}</td>

                    <td class="bg-primary bg-opacity-10 fw-bold">{{ number_format($row->jml) }}</td>
                    <td class="bg-danger bg-opacity-10 fw-bold">{{ number_format($row->sisa) }}</td>
                </tr>
                @php
                $t_sisa_lalu += $row->sisa_tahun_lalu;
                $t_terima += $row->diterima;
                $t_beban += $row->beban;
                $t_cabut += $row->dicabut;
                $t_dismissal += $row->dismissal;
                $t_tolak += $row->ditolak;
                $t_no += $row->tidak_diterima;
                $t_gugur += $row->gugur;
                $t_digugurkan += $row->digugurkan;
                $t_coret += $row->dicoret;
                $t_damai += $row->perdamaian;
                $t_putus += $row->jml;
                $t_akhir += $row->sisa;
                @endphp
                @endforeach
            </tbody>
            <tfoot class="header-gray sticky-bottom border-top-2 fw-bold">
                <tr>
                    <td colspan="2">TOTAL SE-WILAYAH JAWA BARAT</td>
                    <td>{{ number_format($t_sisa_lalu) }}</td>
                    <td>{{ number_format($t_terima) }}</td>
                    <td class="bg-kuning text-dark">{{ number_format($t_beban) }}</td>
                    <td>{{ number_format($t_cabut) }}</td>
                    <td>{{ number_format($t_dismissal) }}</td>
                    @foreach($jenisPerkara as $key => $label)
                    <td>{{ number_format($t_kabul[$key]) }}</td>
                    @endforeach
                    <td>{{ number_format($t_tolak) }}</td>
                    <td>{{ number_format($t_no) }}</td>
                    <td>{{ number_format($t_gugur) }}</td>
                    <td>{{ number_format($t_digugurkan) }}</td>
                    <td>{{ number_format($t_coret) }}</td>
                    <td>{{ number_format($t_damai) }}</td>
                    <td class="bg-primary text-white">{{ number_format($t_putus) }}</td>
                    <td class="bg-danger text-white">{{ number_format($t_akhir) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection