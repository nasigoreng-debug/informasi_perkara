@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header {{ $status == 'tepat' ? 'bg-success' : 'bg-danger' }} text-white">
            <h3 class="card-title">
                Detail Perkara {{ $status == 'tepat' ? 'Tepat Waktu' : 'Terlambat' }} - {{ $satker }}
            </h3>
            <div class="card-tools float-right">
                <a href="{{ route('perkara.tepat_waktu', ['tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhir, 'batas_hari' => $batasHari]) }}"
                    class="btn btn-light btn-sm">
                    Kembali
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="alert alert-info">
                Periode Putusan: <strong>{{ date('d-m-Y', strtotime($tglAwal)) }}</strong>
                s/d <strong>{{ date('d-m-Y', strtotime($tglAkhir)) }}</strong>
                (Batas Waktu: {{ $batasHari }} Hari)
            </div>

            <table class="table table-bordered table-hover">
                <thead class="bg-light">
                    <tr>
                        <th>No</th>
                        <th>Nomor Perkara</th>
                        <th>Tgl Pendaftaran</th>
                        <th>Tgl Putusan</th>
                        <th class="text-center">Durasi (Hari)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($details as $index => $row)
                    @php
                    $tglDaftar = \Carbon\Carbon::parse($row->tanggal_pendaftaran);
                    $tglPutus = \Carbon\Carbon::parse($row->tanggal_putusan);
                    $durasi = $tglDaftar->diffInDays($tglPutus);
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $row->nomor_perkara }}</td>
                        <td>{{ $tglDaftar->format('d/m/Y') }}</td>
                        <td>{{ $tglPutus->format('d/m/Y') }}</td>
                        <td class="text-center">
                            <span class="badge text-dark {{ $durasi <= $batasHari ? 'badge-success' : 'badge-danger' }}">
                                {{ $durasi }} Hari
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Data tidak ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection