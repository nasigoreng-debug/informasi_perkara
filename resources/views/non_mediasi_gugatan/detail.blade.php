@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold text-dark mb-0">Detail Anomali Mediasi: {{ strtoupper($satker) }}</h4>
            <small class="text-muted">Periode Pendaftaran: {{ $tgl_awal }} s.d {{ $tgl_akhir }}</small>
        </div>
        <a href="{{ route('non-mediasi.gugatan', ['tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]) }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali ke Rekap
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-danger text-white py-3">
            <h6 class="mb-0"><i class="fas fa-exclamation-circle me-2"></i>Daftar Perkara Gugatan Belum Input Data Mediasi</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
                    <thead class="table-light text-secondary small">
                        <tr>
                            <th class="ps-3">NO</th>
                            <th>NOMOR PERKARA</th>
                            <th>TANGGAL DAFTAR</th>
                            <th>JENIS PERKARA</th>
                            <th>PARA PIHAK</th>
                            <th>PROSES TERAKHIR</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($perkaraDetail as $index => $p)
                        <tr>
                            <td class="ps-3 fw-bold text-muted">{{ $index + 1 }}</td>
                            <td class="fw-bold text-primary">{{ $p->nomor_perkara }}</td>
                            <td>{{ date('d/m/Y', strtotime($p->tanggal_pendaftaran)) }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $p->jenis_perkara_nama }}</span></td>
                            <td>
                                <div class="small fw-bold text-success">P: {{ Str::limit($p->pihak1_text, 50) }}</div>
                                <div class="small fw-bold text-danger">T: {{ Str::limit($p->pihak2_text, 50) }}</div>
                            </td>
                            <td class="text-muted small italic">{{ $p->proses_terakhir_text ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-check-circle fa-3x mb-3 text-success d-block"></i>
                                Tidak ada data anomali untuk periode ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection