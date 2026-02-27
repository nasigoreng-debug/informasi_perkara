@extends('layouts.app')

@section('title', 'Detail Court Calendar | ' . $satker)

@section('content')
<div class="container py-4 px-4">
    <div class="row">
        <div class="col-12 text-center mb-4">
            <div class="animate__animated animate__fadeInDown">
                <h3 class="fw-bold text-dark mb-1">DETAIL PERKARA BELUM INPUT COURT CALENDAR</h3>
                <h5 class="text-primary text-uppercase">SATKER: {{ $satker }}</h5>
                <p class="text-muted">Periode Putus: {{ date('d-m-Y', strtotime($tglAwal)) }} s/d {{ date('d-m-Y', strtotime($tglAkhir)) }}</p>
            </div>
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden animate__animated animate__fadeIn">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0"><i class="fas fa-list text-primary me-2"></i>Daftar Nomor Perkara</h6>
                    <a href="{{ route('court-calendar', ['tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhir]) }}" class="btn btn-secondary btn-sm rounded-pill px-3">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Rekap
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="text-muted small fw-bold text-uppercase">
                                <th class="ps-4 py-3">No</th>
                                <th class="py-3">Nomor Perkara</th>
                                <th class="py-3 text-center">Tgl Daftar</th>
                                <th class="py-3 text-center">Tgl Putus</th>
                                <th class="py-3">Status Terakhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $index => $row)
                            <tr class="animate__animated animate__fadeInUp" style="animation-delay: {{ $index * 0.05 }}s">
                                <td class="ps-4 text-muted">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded px-2 py-1 fw-bold border border-primary border-opacity-25" style="font-size: 0.9rem;">
                                            {{ $row->nomor_perkara }}
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center text-muted">{{ date('d-m-Y', strtotime($row->tanggal_pendaftaran)) }}</td>
                                <td class="text-center fw-bold text-dark">{{ date('d-m-Y', strtotime($row->tanggal_putusan)) }}</td>
                                <td>
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 rounded-pill px-3">
                                        {{ $row->proses_terakhir_text }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="60" class="opacity-25 mb-3 d-block mx-auto">
                                    <h6 class="text-muted">Alhamdulillah, tidak ada tunggakan detail untuk satker ini.</h6>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white border-0 py-3 text-end">
                    <button onclick="window.print()" class="btn btn-sm btn-outline-dark rounded-pill px-3 no-loader">
                        <i class="fas fa-print me-1"></i> Cetak Daftar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection