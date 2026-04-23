@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <a href="{{ route('input.index', ['tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhir]) }}" class="btn btn-outline-dark btn-sm rounded-pill px-4 font-weight-bold">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card shadow border-0 rounded-lg overflow-hidden">
        <div class="card-header bg-dark p-4 text-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0 font-weight-bold">{{ $label }}</h3>
                    <p class="mb-0 text-warning font-weight-bold">{{ $subLabel }}</p>
                </div>
                <div class="bg-white text-dark px-3 py-1 rounded-pill font-weight-bold">
                    {{ $list->count() }} Data
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light small uppercase">
                        <tr>
                            <th class="text-center py-3">No</th>
                            <th>Nomor Perkara</th>
                            <th class="text-center">Tanggal Daftar</th>
                            <th>Jejak User Admin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($list as $item)
                        <tr>
                            <td class="text-center align-middle font-weight-bold text-muted">{{ $loop->iteration }}</td>
                            <td class="align-middle font-weight-bold text-primary">{{ $item->nomor_perkara }}</td>
                            <td class="text-center align-middle">{{ \Carbon\Carbon::parse($item->tanggal_pendaftaran)->format('d/m/Y') }}</td>
                            <td class="align-middle">
                                <div class="d-flex flex-column gap-1">
                                    <span class="badge {{ in_array(strtolower($item->diinput_oleh), ['admin', 'administrator']) ? 'badge-danger' : 'badge-light border text-muted' }} text-left px-2 py-1 text-dark font-weight-bold">
                                        INPUT: {{ strtoupper($item->diinput_oleh) }}
                                    </span>
                                    @if($item->diperbaharui_oleh)
                                    <span class="badge {{ in_array(strtolower($item->diperbaharui_oleh), ['admin', 'administrator']) ? 'badge-warning text-dark' : 'badge-light border text-muted' }} text-left px-2 py-1">
                                        UPDATE: {{ strtoupper($item->diperbaharui_oleh) }}
                                    </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">Data tidak ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection