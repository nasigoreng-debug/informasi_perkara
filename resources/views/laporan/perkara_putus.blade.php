@extends('layouts.app')

@section('content')
<style>
    .table-rk2 { font-size: 8.5px; border: 1px solid #000; font-family: Arial, sans-serif; }
    .table-rk2 th, .table-rk2 td { border: 1px solid #000 !important; padding: 2px !important; vertical-align: middle; text-align: center; }
    .v-text { writing-mode: vertical-rl; transform: rotate(180deg); white-space: nowrap; height: 180px; font-weight: bold; line-height: 1; }
    .header-gray { background: #e0e0e0; font-weight: bold; }
    .sticky-header { position: sticky; top: 0; background: white; z-index: 100; }
</style>

<div class="container-fluid py-3">
    {{-- BARIS FILTER --}}
    <div class="card mb-3 border-0 shadow-sm bg-light">
        <div class="card-body py-2">
            <form action="{{ route('laporan.diputus.index') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="small fw-bold">TAHUN</label>
                    <select name="tahun" class="form-select form-select-sm">
                        @for($t=date('Y'); $t>=2020; $t--)
                            <option value="{{$t}}" {{$year == $t ? 'selected' : ''}}>{{$t}}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="small fw-bold">BULAN</label>
                    <select name="bulan" class="form-select form-select-sm">
                        @foreach([1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'] as $n => $b)
                            <option value="{{$n}}" {{$month == $n ? 'selected' : ''}}>{{$b}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-dark btn-sm w-100">FILTER DATA</button>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-rk2">
            <thead class="sticky-header">
                <tr class="header-gray">
                    <th rowspan="2" class="v-text">NOMOR URUT</th>
                    <th rowspan="2" style="min-width: 140px;">PENGADILAN AGAMA</th>
                    <th rowspan="2" class="v-text">Sisa Bulan Lalu</th>
                    <th rowspan="2" class="v-text">Perkara yang Diterima</th>
                    <th rowspan="2" class="v-text">JUMLAH</th>
                    <th rowspan="2" class="v-text">Dicabut</th>
                    <th colspan="{{ count($jenisPerkara) }}">DIKABULKAN</th>
                    <th rowspan="2" class="v-text">Ditolak/Membatalkan</th>
                    <th rowspan="2" class="v-text">Tidak Diterima</th>
                    <th rowspan="2" class="v-text">Gugur/Memperbaiki</th>
                    <th rowspan="2" class="v-text">Dicoret dari Register</th>
                    <th rowspan="2" class="v-text bg-warning">Jumlah</th>
                    <th rowspan="2" class="v-text bg-warning">Sisa Akhir Bulan</th>
                    <th rowspan="2">Keterangan</th>
                </tr>
                <tr class="header-gray">
                    @foreach($jenisPerkara as $alias => $label)
                        <th class="v-text">{{ $label }}</th>
                    @endforeach
                </tr>
                <tr class="header-gray">
                    @for($i=1; $i<= (count($jenisPerkara) + 13); $i++) <td style="font-size: 8px;">{{ $i }}</td> @endfor
                </tr>
            </thead>
            <tbody>
                @foreach($laporan as $row)
                <tr>
                    <td>{{ $row->no_urut }}</td>
                    <td class="text-start fw-bold px-2">{{ $row->satker }}</td>
                    <td>{{ number_format($row->sisa_tahun_lalu) }}</td>
                    <td>{{ number_format($row->diterima) }}</td>
                    <td class="fw-bold bg-light">{{ number_format($row->beban) }}</td>
                    <td>{{ number_format($row->dicabut) }}</td>
                    @foreach($jenisPerkara as $key => $label)
                        <td>{{ $row->$key ?? 0 }}</td>
                    @endforeach
                    <td>{{ number_format($row->ditolak) }}</td>
                    <td>{{ number_format($row->tidak_diterima) }}</td>
                    <td>{{ number_format($row->gugur) }}</td>
                    <td>{{ number_format($row->dicoret) }}</td>
                    <td class="fw-bold bg-warning bg-opacity-10">{{ number_format($row->jml) }}</td>
                    <td class="fw-bold bg-warning bg-opacity-10">{{ number_format($row->sisa) }}</td>
                    <td></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection