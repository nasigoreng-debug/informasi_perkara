@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between">
                <h5 class="mb-0">Detail Perkara: PA {{ strtoupper($satker) }}</h5>
                <a href="{{ route('ekonomi-syariah.index', ['tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]) }}"
                    class="btn btn-sm btn-light">Kembali</a>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead class="bg-light small text-center">
                        <tr>
                            <th>No</th>
                            <th>Nomor Perkara</th>
                            <th>Jenis Perkara</th> {{-- Kolom Baru --}}
                            <th>Tgl Daftar</th>
                            <th>Para Pihak</th>
                            <th>Majelis Hakim</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($perkara as $p)
                            <tr class="small">
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="fw-bold">{{ $p->nomor_perkara }}</td>
                                <td>{{ $p->jenis_perkara_nama }}</td> 
                                <td class="text-center">{{ date('d-m-Y', strtotime($p->tanggal_pendaftaran)) }}</td>
                                <td>
                                    <strong>P:</strong> {{ $p->pihak1_text }}<br>
                                    <strong>T:</strong> {{ $p->pihak2_text }}
                                </td>
                                <td>
                                    @if (empty($p->hakim_anggota_1) && empty($p->hakim_anggota_2))
                                        {{ $p->hakim_ketua }} <span class="text-muted">(Hakim Tunggal)</span>
                                    @else
                                        1. {{ $p->hakim_ketua }} (K)<br>
                                        2. {{ $p->hakim_anggota_1 }} (A1)<br>
                                        3. {{ $p->hakim_anggota_2 }} (A2)
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
