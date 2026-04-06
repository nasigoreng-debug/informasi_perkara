@extends('layouts.app')

@section('content')
<div class="container py-5" style="font-family: 'Inter', sans-serif;">
    <div class="mb-4">
        <a href="{{ route('input.index', ['tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhir]) }}"
            class="btn btn-outline-dark btn-sm rounded-pill px-4 font-weight-bold shadow-sm">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Rekap
        </a>
    </div>

    <div class="card shadow-lg border-0 rounded-xl overflow-hidden">
        <div class="card-header bg-dark p-4 text-white" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3 class="mb-0 font-weight-bold text-white uppercase tracking-tight">{{ $label }}</h3>
                    <p class="mb-0 text-warning font-weight-bold small uppercase tracking-widest">
                        Periode: {{ \Carbon\Carbon::parse($tglAwal)->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::parse($tglAkhir)->translatedFormat('d F Y') }}
                    </p>
                </div>
                <div class="col-md-4 text-md-right mt-3 mt-md-0">
                    <div class="bg-white text-dark p-2 rounded shadow-sm d-inline-block">
                        <h4 class="mb-0 font-weight-bold">{{ $list->count() }} <small class="text-muted">Perkara</small></h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body bg-white p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light text-dark font-weight-bold small uppercase">
                        <tr>
                            <th class="text-center py-3">No</th>
                            <th class="py-3">Nomor Perkara</th>
                            <th class="py-3 text-center">Tanggal Daftar</th>
                            <th class="py-3">Pihak Kesatu</th>
                            <th class="py-3">Jejak Akun Admin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($list as $item)
                        <tr>
                            <td class="text-center align-middle text-muted font-weight-bold">{{ $loop->iteration }}</td>
                            <td class="align-middle py-3">
                                <span class="d-block font-weight-bold text-primary" style="font-size: 1rem;">{{ $item->nomor_perkara }}</span>
                            </td>
                            <td class="text-center align-middle font-weight-bold" style="color:#000;">
                                {{ \Carbon\Carbon::parse($item->tanggal_pendaftaran)->translatedFormat('d/m/Y') }}
                            </td>
                            <td class="align-middle small font-weight-bold text-uppercase" style="color:#000; max-width:300px;">
                                {{ $item->pihak1_text ?? '-' }}
                            </td>
                            <td class="align-middle">
                                <div class="d-flex flex-column gap-1">
                                    <span class="badge badge-danger text-dark text-left px-2 py-1 mb-1">
                                        <i class="fas fa-fingerprint mr-1"></i> INPUT: {{ strtoupper($item->diinput_oleh) }}
                                    </span>
                                    @if($item->diperbaharui_oleh)
                                    <span class="badge badge-warning text-dark text-left px-2 py-1">
                                        <i class="fas fa-edit mr-1"></i> UPDATE: {{ strtoupper($item->diperbaharui_oleh) }}
                                    </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 font-weight-bold text-muted italic">Data tidak ditemukan dalam database.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .font-weight-bold {
        font-weight: 700 !important;
    }

    .table tbody td {
        color: #000000 !important;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .rounded-xl {
        border-radius: 1rem !important;
    }

    .tracking-widest {
        letter-spacing: 0.1em;
    }

    .bg-dark {
        background-color: #0f172a !important;
    }
</style>
@endsection