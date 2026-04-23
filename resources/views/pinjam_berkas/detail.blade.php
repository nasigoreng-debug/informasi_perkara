@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>{{ $title }}</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Nama Peminjam</th>
                            <td>{{ $pinjam->nama_peminjam }}</td>
                        </tr>
                        <tr>
                            <th>Nomor Perkara</th>
                            <td>{{ $pinjam->no_banding }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Pinjam</th>
                            <td>{{ \Carbon\Carbon::parse($pinjam->tgl_pinjam)->format('d/m/Y H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Kembali</th>
                            <td>
                                @if($pinjam->tgl_kembali)
                                {{ \Carbon\Carbon::parse($pinjam->tgl_kembali)->format('d/m/Y H:i:s') }}
                                @else
                                <span class="badge bg-warning">Belum Kembali</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Lama Pinjam</th>
                            <td>
                                @php
                                $tglPinjam = \Carbon\Carbon::parse($pinjam->tgl_pinjam);
                                if($pinjam->tgl_kembali) {
                                $tglKembali = \Carbon\Carbon::parse($pinjam->tgl_kembali);
                                $lama = $tglPinjam->diffInDays($tglKembali);
                                echo "{$lama} Hari";
                                } else {
                                $lama = $tglPinjam->diffInDays(\Carbon\Carbon::now());
                                echo "{$lama} Hari (Masih Dipinjam)";
                                }
                                @endphp
                            </td>
                        </tr>
                        <tr>
                            <th>Bukti Pinjam</th>
                            <td>
                                @if($pinjam->bkt_pinjam)
                                <a href="{{ asset('dokumen_pinjam/bkt_pinjam/' . $pinjam->bkt_pinjam) }}" target="_blank" class="btn btn-sm btn-info">
                                    <i class="fas fa-file-image"></i> Lihat Bukti Pinjam
                                </a>
                                @else
                                -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Bukti Kembali</th>
                            <td>
                                @if($pinjam->bkt_kembali)
                                <a href="{{ asset('dokumen_pinjam/bkt_kembali/' . $pinjam->bkt_kembali) }}" target="_blank" class="btn btn-sm btn-info">
                                    <i class="fas fa-file-image"></i> Lihat Bukti Kembali
                                </a>
                                @else
                                -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Keterangan</th>
                            <td>{{ $pinjam->keterangan ?? '-' }}</td>
                        </tr>
                    </table>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('pinjam.index') }}" class="btn btn-secondary">Kembali</a>
                        <a href="{{ route('pinjam.edit', $pinjam->id) }}" class="btn btn-primary">Edit Data</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection