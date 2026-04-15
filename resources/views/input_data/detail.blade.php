@extends('layouts.app')

@section('content')
<div class="container py-5" style="font-family: 'Inter', sans-serif;">
    <div class="mb-4">
        <a href="{{ route('input.index', ['tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhir]) }}"
            class="btn btn-outline-dark btn-sm rounded-pill px-4 font-weight-bold shadow-sm">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="card shadow-lg border-0 rounded-xl overflow-hidden">
        <div class="card-header bg-dark p-4 text-white" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3 class="mb-0 font-weight-bold text-white uppercase tracking-tight">DETAIL INPUT OLEH ADMIN: {{ $label }}</h3>
                    <p class="mb-0 text-warning font-weight-bold small uppercase tracking-widest">
                        Periode: {{ \Carbon\Carbon::parse($tglAwal)->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::parse($tglAkhir)->translatedFormat('d F Y') }}
                    </p>
                </div>
                <div class="col-md-4 text-md-right mt-3 mt-md-0">
                    <div class="bg-white text-dark p-2 rounded shadow-sm d-inline-block border-left border-danger border-lg" style="border-left-width: 5px !important;">
                        <h4 class="mb-0 font-weight-bold">{{ $list->count() }} <small class="text-muted text-uppercase">Pelanggaran</small></h4>
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
                            <th class="py-3">Bukti Input User Admin</th>
                        </tr>
                    </thead>
                    <tbody class="font-medium">
                        @forelse($list as $item)
                        <tr>
                            <td class="text-center align-middle text-muted font-weight-bold">{{ $loop->iteration }}</td>
                            <td class="align-middle py-3">
                                <span class="d-block font-weight-bold text-primary" style="font-size: 1rem;">{{ $item->nomor_perkara }}</span>
                            </td>
                            <td class="text-center align-middle font-weight-bold" style="color:#000;">
                                {{ \Carbon\Carbon::parse($item->tanggal_pendaftaran)->translatedFormat('d/m/Y') }}
                            </td>
                            <td class="align-middle small font-weight-bold text-uppercase" style="color:#444; max-width:300px;">
                                {{ $item->pihak1_text ?? '-' }}
                            </td>
                            <td class="align-middle">
                                <div class="d-flex flex-column gap-1">
                                    {{-- Highlight Utama pada INPUT --}}
                                    <span class="badge badge-danger text-white text-left px-3 py-2 mb-1 shadow-sm">
                                        <i class="fas fa-user-shield mr-2"></i> DIINPUT OLEH: {{ strtoupper($item->diinput_oleh) }}
                                    </span>

                                    @if($item->diperbaharui_oleh)
                                    <span class="badge badge-light text-muted text-left px-3 py-1 border" style="font-size: 0.75rem;">
                                        <i class="fas fa-history mr-2"></i> Perubahan Terakhir: {{ strtoupper($item->diperbaharui_oleh) }}
                                    </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 font-weight-bold text-muted italic">
                                <i class="fas fa-info-circle fa-2x mb-3 d-block"></i>
                                Tidak ditemukan data yang diinput oleh admin pada periode ini.
                            </td>
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
        padding: 1rem 0.75rem;
    }

    .rounded-xl {
        border-radius: 1rem !important;
    }

    .tracking-widest {
        letter-spacing: 0.15em;
    }

    .badge-danger {
        background-color: #dc3545 !important;
    }

    .border-lg {
        border-width: 5px !important;
    }

    /* Efek hover untuk baris agar lebih interaktif */
    .table-hover tbody tr:hover {
        background-color: #fff1f2 !important;
        /* Warna merah tipis saat hover */
    }
</style>
@endsection