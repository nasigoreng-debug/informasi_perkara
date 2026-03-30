@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0">Detail Perkara: {{ $satker }} ({{ ucfirst($jenis) }})</h4>
        <a href="{{ route('sisa.panjar.menu') }}" class="btn btn-secondary btn-sm px-3 shadow-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="table-responsive bg-white rounded shadow-sm border">
        <table class="table table-bordered table-hover mb-0" style="font-size: 13px;">
            <thead class="bg-light">
                <tr class="text-center align-middle">
                    <th style="width: 50px;" class="py-3">No</th>
                    <th style="width: 180px;">No Perkara</th>
                    <th>Jenis Perkara</th>

                    {{-- Hanya muncul jika BUKAN tingkat pertama --}}
                    @if($jenis != 'pertama')
                    <th style="width: 180px;">No Perkara Upaya Hukum</th>
                    @endif

                    <th style="width: 120px;">Tgl Putusan</th>
                    <th style="width: 120px;">Tgl Pemberitahuan</th>
                    <th>Proses Terakhir</th>
                    <th style="width: 150px;" class="text-end">Sisa Panjar</th>
                </tr>
            </thead>
            <tbody class="align-middle">
                @php $totalSisa = 0; @endphp
                @forelse($listPerkara as $index => $perkara)
                @php $totalSisa += $perkara->sisa; @endphp
                <tr>
                    <td class="text-center text-muted">{{ $index + 1 }}</td>
                    <td class="fw-bold text-nowrap text-center">{{ $perkara->nomor_perkara }}</td>
                    <td class="text-center">{{ $perkara->jenis_perkara_nama }}</td>

                    @if($jenis != 'pertama')
                    <td class="text-primary text-center">{{ $perkara->nomor_perkara_atas }}</td>
                    @endif

                    <td class="text-center">{{ \Carbon\Carbon::parse($perkara->tgl_putusan)->format('d-m-Y') }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($perkara->tgl_notif)->format('d-m-Y') }}</td>
                    <td class="text-center">
                        <span class="badge bg-info text-dark border-0 py-2 px-3" style="font-size: 10px; border-radius: 50px;">
                            {{ $perkara->proses_terakhir_text }}
                        </span>
                    </td>
                    <td class="text-end fw-bold text-danger px-3">
                        Rp {{ number_format($perkara->sisa, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ $jenis == 'pertama' ? 7 : 8 }}" class="text-center py-5 text-muted">
                        Data sisa panjar tidak ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($listPerkara->count() > 0)
            <tfoot class="bg-light fw-bold border-top-2">
                <tr class="align-middle">
                    <td colspan="{{ $jenis == 'pertama' ? 6 : 7 }}" class="text-end py-3 px-3">TOTAL KESELURUHAN</td>
                    <td class="text-end text-danger py-3 px-3" style="font-size: 15px;">
                        Rp {{ number_format($totalSisa, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
@endsection