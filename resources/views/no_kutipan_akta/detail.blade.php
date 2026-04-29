@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <h3>Detail Data Invalid: Satker {{ strtoupper($satker) }}</h3>
    <p>Periode: {{ $tgl_awal }} s/d {{ $tgl_akhir }}</p>
    <a href="{{ route('no_kutipan.index', ['tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]) }}" class="btn btn-secondary mb-3">Kembali</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nomor Perkara</th>
                <th>Jenis Perkara</th>
                <th>Tgl Daftar</th>
                <th>Pihak 1</th>
                <th>Pihak 2</th>
                <th>Isi Kolom Akta</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $key => $d)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $d->nomor_perkara }}</td>
                <td>{{ $d->jenis_perkara_nama }}</td>
                <td>{{ $d->tanggal_pendaftaran }}</td>
                <td>{{ $d->pihak1_text }}</td>
                <td>{{ $d->pihak2_text }}</td>
                <td class="bg-warning">{{ $d->no_kutipan_akta_nikah ?? 'NULL' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada data invalid.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection