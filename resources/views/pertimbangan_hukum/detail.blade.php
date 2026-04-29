@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0 text-primary">Detail Pertimbangan Hukum: {{ strtoupper($satker) }}</h4>
                        <p class="text-muted mb-0">Periode Pendaftaran: {{ $tgl_awal }} s.d {{ $tgl_akhir }} (Kategori: {{ ucfirst($kategori) }})</p>
                    </div>
                    <a href="{{ route('pertimbangan.index', ['tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center">No</th>
                            <th>Nomor Perkara</th>
                            <th>Jenis Perkara</th>
                            <th class="text-center">Tgl Daftar</th>
                            <th class="text-center">Tgl Putusan</th>
                            <th>Status Putusan</th>
                            <th class="text-center">Pertimbangan Hukum</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="fw-bold">{{ $item->nomor_perkara }}</td>
                            <td>{{ $item->jenis_perkara_nama }}</td>
                            <td class="text-center">{{ $item->tanggal_pendaftaran }}</td>
                            <td class="text-center">{{ $item->tanggal_putusan }}</td>
                            <td>
                                <span class="badge bg-info text-dark">
                                    {{ $item->nama_status_putusan ?? 'Tidak Terdeteksi' }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($item->status_ph == 'Sudah')
                                <span class="badge bg-success"><i class="fas fa-check"></i> Sudah Isi</span>
                                @else
                                <span class="badge bg-danger"><i class="fas fa-times"></i> Belum Isi</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Tidak ada data ditemukan untuk periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection