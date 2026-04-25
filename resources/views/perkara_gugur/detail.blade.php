@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 mt-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 fw-bold">Detail Putusan Perkara</h1>
            <p class="text-muted small mb-0">Satker: <span class="fw-bold text-primary">{{ $satker }}</span> | Tipe: <span class="badge bg-secondary">{{ strtoupper($jenis ?? 'SEMUA') }}</span></p>
        </div>
        <a href="{{ route('perkara_gugur.index', ['tgl_awal'=>$tgl_awal, 'tgl_akhir'=>$tgl_akhir]) }}" class="btn btn-secondary shadow-sm"><i class="fas fa-arrow-left me-1"></i> Kembali</a>
    </div>

    <div class="card shadow border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light border-bottom text-secondary small fw-bold">
                        <tr>
                            <th class="text-center py-3" width="5%">NO</th>
                            <th class="py-3">NOMOR PERKARA</th>
                            <th class="py-3">JENIS PERKARA</th>
                            <th class="text-center py-3">TGL DAFTAR</th>
                            <th class="text-center py-3">TGL PUTUS</th>
                            <th class="text-center py-3" width="15%">STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($details as $item)
                        <tr class="border-bottom">
                            <td class="text-center text-muted small">{{ $loop->iteration }}</td>
                            <td class="fw-bold text-dark">{{ $item->nomor_perkara }}</td>
                            <td class="small">{{ $item->jenis_perkara_nama ?? '-' }}</td>
                            <td class="text-center small"><i class="far fa-calendar-alt text-muted me-1"></i> {{ $item->tanggal_pendaftaran ? date('d/m/Y', strtotime($item->tanggal_pendaftaran)) : '-' }}</td>
                            <td class="text-center small fw-bold"><i class="fas fa-gavel text-muted me-1"></i> {{ $item->tanggal_putusan ? date('d/m/Y', strtotime($item->tanggal_putusan)) : '-' }}</td>
                            <td class="text-center">
                                @php
                                // Kita bersihkan data dari spasi dan paksa jadi huruf kecil untuk pengecekan
                                $statusAsli = strtolower(trim($item->jenis_putusan));
                                @endphp

                                @if($statusAsli == 'gugur')
                                <span class="badge rounded-pill bg-primary px-3 py-2 w-100 shadow-sm">
                                    <i class="fas fa-check-circle me-1"></i> GUGUR
                                </span>
                                @elseif($statusAsli == 'digugurkan')
                                <span class="badge rounded-pill bg-warning text-dark px-3 py-2 w-100 shadow-sm">
                                    <i class="fas fa-exclamation-triangle me-1"></i> DIGUGURKAN
                                </span>
                                @else
                                {{-- Jika ada status lain yang tak terduga, tampilkan teks aslinya --}}
                                <span class="badge rounded-pill bg-secondary px-3 py-2 w-100 shadow-sm">
                                    {{ strtoupper($item->jenis_putusan) }}
                                </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Tidak ada data detail ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white text-muted small">Total: {{ $details->count() }} Data.</div>
    </div>
</div>
@endsection