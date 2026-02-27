@extends('layouts.app')

@section('title', 'Monitoring Court Calendar | PTA Bandung')

@section('content')
<div class="container py-4 px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="fw-bold mb-0 text-dark">
                                <i class="fas fa-calendar-alt text-primary me-2"></i>Monitoring Input Court Calendar
                            </h5>
                            <small class="text-muted">Memantau kepatuhan satker dalam pengisian rencana sidang (Court Calendar)</small>
                        </div>
                        {{-- Filter Tanggal --}}
                        <form action="{{ route('court-calendar') }}" method="GET" class="d-flex gap-2">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light border-0">Awal</span>
                                <input type="date" name="tgl_awal" class="form-control border-0 bg-light" value="{{ $tglAwal }}">
                            </div>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light border-0">Akhir</span>
                                <input type="date" name="tgl_akhir" class="form-control border-0 bg-light" value="{{ $tglAkhir }}">
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm px-3 rounded-pill">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="text-muted small fw-bold">
                                <th class="ps-4 py-3" width="50">NO</th>
                                <th class="py-3">SATUAN KERJA</th>
                                <th class="py-3 text-center">JUMLAH BELUM INPUT</th>
                                <th class="py-3 text-center">STATUS KEPATUHAN</th>
                                <th class="py-3 text-center" width="150">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @forelse($data as $row)
                            <tr class="animate__animated animate__fadeInUp" style="animation-delay: {{ $no * 0.05 }}s">
                                <td class="ps-4 text-muted">{{ $no++ }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px;">
                                            <i class="fas fa-landmark small"></i>
                                        </div>
                                        <span class="fw-bold text-dark text-uppercase">{{ $row->satker }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <h5 class="mb-0 fw-bold {{ $row->jumlah > 0 ? 'text-danger' : 'text-success' }}">
                                        {{ number_format($row->jumlah, 0, ',', '.') }}
                                    </h5>
                                    <small class="text-muted small">Perkara</small>
                                </td>
                                <td class="text-center">
                                    @if($row->jumlah > 10)
                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 border border-danger border-opacity-25">
                                        <i class="fas fa-exclamation-triangle me-1"></i> Perlu Teguran
                                    </span>
                                    @elseif($row->jumlah > 0)
                                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2 border border-warning border-opacity-25">
                                        <i class="fas fa-clock me-1"></i> Belum Lengkap
                                    </span>
                                    @else
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 border border-success border-opacity-25">
                                        <i class="fas fa-check-circle me-1"></i> Sempurna
                                    </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('court-calendar.detail', ['satker' => $row->satker, 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhir]) }}" class="btn btn-light btn-sm rounded-pill px-3 border-0">
                                        <i class="fas fa-search me-1"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80" class="opacity-25 mb-3">
                                    <p class="text-muted">Data tidak ditemukan untuk periode ini.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white border-0 py-3 text-center">
                    <p class="small text-muted mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        Data ini menampilkan perkara pendaftaran <strong>{{ date('d M Y', strtotime($tglAwal)) }}</strong> s/d <strong>{{ date('d M Y', strtotime($tglAkhir)) }}</strong> yang belum mengisi Court Calendar.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection