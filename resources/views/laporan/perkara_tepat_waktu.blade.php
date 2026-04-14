@extends('layouts.app')

@section('content')
<style>
    .table-header-custom {
        background-color: #f8f9fa;
        text-transform: uppercase;
        font-size: 0.85rem;
        vertical-align: middle !important;
    }

    .link-angka {
        text-decoration: none !important;
        font-weight: bold;
        display: block;
        width: 100%;
    }

    .link-angka:hover {
        background-color: rgba(0, 0, 0, 0.1);
        border-radius: 4px;
    }

    @media print {
        .no-print {
            display: none !important;
        }
    }
</style>

<div class="container-fluid py-4">
    <div class="text-center mb-4">
        <h4 class="font-weight-bold">MONITORING KETEPATAN WAKTU PENYELESAIAN PERKARA</h4>
        <h5 class="text-uppercase">WILAYAH HUKUM PTA JAWA BARAT</h5>
        <p class="badge badge-info">Periode: {{ date('d-m-Y', strtotime($tglAwal)) }} s/d {{ date('d-m-Y', strtotime($tglAkhir)) }}</p>
    </div>

    <div class="card shadow mb-4 no-print">
        <div class="card-body">
            <form method="GET" action="{{ url()->current() }}" class="row align-items-end">
                <div class="col-md-3">
                    <label class="small font-weight-bold">TANGGAL AWAL</label>
                    <input type="date" name="tgl_awal" class="form-control" value="{{ $tglAwal }}">
                </div>
                <div class="col-md-3">
                    <label class="small font-weight-bold">TANGGAL AKHIR</label>
                    <input type="date" name="tgl_akhir" class="form-control" value="{{ $tglAkhir }}">
                </div>
                <div class="col-md-2">
                    <label class="small font-weight-bold">BATAS HARI (5 BLN)</label>
                    <input type="number" name="batas_hari" class="form-control" value="{{ $batasHari }}">
                </div>
                <div class="col-md-4">
                    <div class="btn-group w-100 shadow-sm">
                        <button type="submit" class="btn btn-primary font-weight-bold"><i class="fas fa-search"></i> PROSES</button>
                        <a href="{{ url()->current() }}" class="btn btn-dark font-weight-bold"><i class="fas fa-undo"></i> RESET</a>
                        <button type="button" onclick="window.print()" class="btn btn-secondary font-weight-bold"><i class="fas fa-print"></i> CETAK</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm text-center" width="100%">
                    <thead class="table-header-custom font-weight-bold">
                        <tr>
                            <th rowspan="2" width="40" class="align-middle">NO</th>
                            <th rowspan="2" class="align-middle text-left pl-3">PENGADILAN AGAMA</th>
                            <th rowspan="2" width="120" class="align-middle">TOTAL PERKARA DIPUTUS</th>
                            <th colspan="3">JUMLAH DISELESAIKAN</th>
                            <th rowspan="2" width="150" class="align-middle bg-light">BELUM PUTUS > 5 BULAN</th>
                        </tr>
                        <tr>
                            <th width="110">s/d 3 bulan</th>
                            <th width="110">3-{{ ceil($batasHari/30) }} bulan</th>
                            <th width="110">> {{ ceil($batasHari/30) }} bulan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $gt_total = 0; $gt_3 = 0; $gt_35 = 0; $gt_5 = 0; $gt_belum = 0;
                        @endphp
                        @foreach($results as $index => $row)
                        @php $params = ['koneksi_satker' => $row->koneksi_satker, 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhir, 'batas_hari' => $batasHari]; @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="text-left pl-3 text-uppercase font-weight-bold text-start">{{ $row->nama_satker }}</td>
                            <td><a href="{{ route('perkara.tepatwaktu.detail', array_merge($params, ['status' => 'total'])) }}" class="link-angka text-dark">{{ number_format($row->total_putus) }}</a></td>
                            <td><a href="{{ route('perkara.tepatwaktu.detail', array_merge($params, ['status' => '3_bulan'])) }}" class="link-angka text-success">{{ number_format($row->diputus_3_bulan) }}</a></td>
                            <td><a href="{{ route('perkara.tepatwaktu.detail', array_merge($params, ['status' => '3_5_bulan'])) }}" class="link-angka text-primary">{{ number_format($row->diputus_3_5_bulan) }}</a></td>
                            <td><a href="{{ route('perkara.tepatwaktu.detail', array_merge($params, ['status' => 'lebih_5_bulan'])) }}" class="link-angka text-danger">{{ number_format($row->diputus_lebih_5_bulan) }}</a></td>
                            <td class="bg-light"><a href="{{ route('perkara.tepatwaktu.detail', array_merge($params, ['status' => 'belum_putus'])) }}" class="link-angka text-danger">{{ number_format($row->belum_putus_lebih_5_bulan) }}</a></td>
                        </tr>
                        @php $gt_total += $row->total_putus; $gt_3 += $row->diputus_3_bulan; $gt_35 += $row->diputus_3_5_bulan; $gt_5 += $row->diputus_lebih_5_bulan; $gt_belum += $row->belum_putus_lebih_5_bulan; @endphp
                        @endforeach
                    </tbody>
                    <tfoot class="font-weight-bold bg-dark text-white">
                        <tr>
                            <td colspan="2">JUMLAH</td>
                            <td>{{ number_format($gt_total) }}</td>
                            <td>{{ number_format($gt_3) }}</td>
                            <td>{{ number_format($gt_35) }}</td>
                            <td>{{ number_format($gt_5) }}</td>
                            <td>{{ number_format($gt_belum) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection