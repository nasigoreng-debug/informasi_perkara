@extends('layouts.app')

@section('content')
<div class="container-fluid pt-4">
    <div class="row mb-3 align-items-center">
        <div class="col-md-8">
            <h4 class="font-weight-bold text-navy">
                <i class="fas fa-folder-open text-info mr-2"></i>Detail Tunggakan PA {{ strtoupper($satker) }}
                @if($jenis) <span class="badge badge-warning font-weight-normal ml-2">{{ $jenis }}</span> @endif
            </h4>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('bht.no.akta.index', ['tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]) }}" class="btn btn-secondary shadow-sm rounded-pill px-4 btn-sm">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Rekap
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-navy text-white d-flex justify-content-between align-items-center py-3">
            <h3 class="card-title m-0 font-weight-bold"><i class="fas fa-list-ul mr-2 small"></i>Daftar Perkara Belum Terbit Akta Cerai</h3>
            <span class="badge badge-light px-3 py-2 text-navy">{{ count($data) }} Perkara</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="bg-light text-center small text-uppercase font-weight-bold">
                        <tr>
                            <th width="40">No</th>
                            <th class="text-left">Nomor Perkara</th>
                            <th class="text-left">Jenis Perkara</th>
                            <th>Putusan</th>
                            <th>Minutasi</th>
                            <th>BHT / Ikrar</th>
                            <th class="bg-danger-soft text-danger">Belum Terbit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $perkara)
                        <tr class="text-center">
                            <td class="text-muted align-middle">{{ $loop->iteration }}</td>
                            <td class="text-left font-weight-bold text-navy align-middle">
                                {{ $perkara->nomor_perkara }}
                            </td>
                            <td class="text-left align-middle">
                                @if(str_contains($perkara->jenis_perkara_nama, 'Gugat'))
                                <span class="badge badge-outline-info font-weight-normal"><i class="fas fa-female mr-1"></i> {{ $perkara->jenis_perkara_nama }}</span>
                                @else
                                <span class="badge badge-outline-purple font-weight-normal"><i class="fas fa-male mr-1"></i> {{ $perkara->jenis_perkara_nama }}</span>
                                @endif
                            </td>
                            <td class="align-middle text-sm">{{ $perkara->tanggal_putusan ? date('d/m/Y', strtotime($perkara->tanggal_putusan)) : '-' }}</td>
                            <td class="align-middle text-sm text-muted">{{ $perkara->tanggal_minutasi ? date('d/m/Y', strtotime($perkara->tanggal_minutasi)) : '-' }}</td>
                            <td class="align-middle">
                                @if(str_contains($perkara->jenis_perkara_nama, 'Talak'))
                                <span class="text-purple font-weight-bold">{{ $perkara->tgl_ikrar_talak ? date('d/m/Y', strtotime($perkara->tgl_ikrar_talak)) : '-' }}</span>
                                <br><small class="badge badge-warning py-0 text-danger" style="font-size: 10px; opacity: 0.8;">IKRAR</small>
                                @else
                                <span class="text-info font-weight-bold">{{ $perkara->tanggal_bht ? date('d/m/Y', strtotime($perkara->tanggal_bht)) : '-' }}</span>
                                <br><small class="badge badge-info py-0 text-danger" style="font-size: 10px; opacity: 0.8;">BHT</small>
                                @endif
                            </td>
                            <td class="align-middle">
                                <span class="h5 font-weight-bold text-danger mb-0">{{ $perkara->selisih_hari }}</span>
                                <small class="text-muted d-block" style="font-size: 10px;">HARI</small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-5 text-center text-muted font-italic">
                                <i class="fas fa-check-circle text-success fa-2x mb-2 d-block"></i>
                                Tidak ada tunggakan ditemukan untuk satker ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white text-muted small py-2">
            <i class="fas fa-info-circle mr-1"></i> Data dihitung berdasarkan Tgl. BHT untuk Cerai Gugat dan Tgl. Ikrar untuk Cerai Talak.
        </div>
    </div>
</div>

<style>
    .bg-navy {
        background-color: #001f3f !important;
    }

    .text-navy {
        color: #001f3f;
    }

    .text-purple {
        color: #6f42c1;
    }

    .bg-danger-soft {
        background-color: rgba(220, 53, 69, 0.05);
    }

    .badge-outline-info {
        color: #17a2b8;
        border: 1px solid #17a2b8;
        background: transparent;
    }

    .badge-outline-purple {
        color: #6f42c1;
        border: 1px solid #6f42c1;
        background: transparent;
    }

    .table td {
        vertical-align: middle !important;
    }
</style>
@endsection