@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-3 text-right">
        <a href="{{ route('non-mediasi-gugatan.index', ['tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]) }}" class="btn btn-sm btn-secondary px-4 shadow-sm">KEMBALI</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-danger text-white font-weight-bold d-flex justify-content-between">
            <span>DETAIL TIDAK MEDIASI: {{ $satker }}</span>
            <span>{{ count($perkaraDetail) }} PERKARA</span>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped mb-0 text-center small">
                <thead class="bg-light font-weight-bold">
                    <tr>
                        <th width="50">NO</th>
                        <th width="180">NOMOR PERKARA</th>
                        <th>TGL DAFTAR</th>
                        <th>TGL PUTUSAN</th>
                        <th class="text-left">PIHAK (P1 / P2)</th>
                        <th>STATUS PUTUSAN</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($perkaraDetail as $p)
                    <tr>
                        <td class="align-middle">{{ $loop->iteration }}</td>
                        <td class="font-weight-bold align-middle">{{ $p->nomor_perkara }}</td>
                        <td class="align-middle">{{ $p->tanggal_pendaftaran ? \Carbon\Carbon::parse($p->tanggal_pendaftaran)->format('d-m-Y') : '-' }}</td>
                        <td class="text-primary font-weight-bold align-middle">
                            {{ $p->tanggal_putusan ? \Carbon\Carbon::parse($p->tanggal_putusan)->format('d-m-Y') : '-' }}
                        </td>
                        <td class="text-left">
                            <small class="d-block"><b>P1:</b> {{ $p->pihak1_text }}</small>
                            <small class="d-block text-muted"><b>P2:</b> {{ $p->pihak2_text }}</small>
                        </td>
                        <td class="align-middle">
                            <span class="badge badge-warning font-weight-normal px-2 text-dark">
                                {{ $p->status_putusan }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-5 text-muted font-italic">Data Tidak Ditemukan atau Semua Perkara Sudah Mediasi/Cabut.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection