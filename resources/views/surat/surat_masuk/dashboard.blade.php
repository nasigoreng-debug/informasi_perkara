@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="font-weight-bold text-dark m-0">Dashboard Arsip Surat</h4>
        <span class="badge badge-white shadow-sm px-3 py-2 rounded-pill text-primary border">
            <i class="far fa-calendar-alt mr-1"></i> {{ date('d F Y') }}
        </span>
    </div>

    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-0 shadow-sm rounded-lg overflow-hidden hover-elevate bg-gradient-primary text-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-uppercase small mb-1 opacity-75">Total Arsip</p>
                            <h2 class="font-weight-bold mb-0">{{ $totalSurat }}</h2>
                        </div>
                        <i class="fas fa-envelope-open-text fa-3x opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-0 shadow-sm rounded-lg overflow-hidden hover-elevate bg-gradient-info text-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-uppercase small mb-1 opacity-75">Surat Bulan Ini</p>
                            <h2 class="font-weight-bold mb-0">{{ $suratBulanIni }}</h2>
                        </div>
                        <i class="fas fa-calendar-check fa-3x opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-12 mb-4">
            <div class="card border-0 shadow-sm rounded-lg overflow-hidden hover-elevate bg-gradient-success text-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-uppercase small mb-1 opacity-75">Input Hari Ini</p>
                            <h2 class="font-weight-bold mb-0">{{ $suratHariIni }}</h2>
                        </div>
                        <i class="fas fa-clock fa-3x opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded-lg">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-history mr-2"></i> Arsip Terbaru</h6>
                    <a href="{{ route('surat.index') }}" class="btn btn-primary btn-sm rounded-pill px-3">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="bg-light text-xs text-uppercase">
                                <tr>
                                    <th class="pl-4">No. Surat</th>
                                    <th>Asal</th>
                                    <th>Perihal</th>
                                    <th>Status File</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentSurat as $s)
                                <tr>
                                    <td class="pl-4 font-weight-bold text-dark small">{{ $s->no_surat }}</td>
                                    <td class="small">{{ $s->asal_surat }}</td>
                                    <td class="text-truncate small" style="max-width: 250px;">{{ $s->perihal }}</td>
                                    <td>
                                        @if($s->lampiran)
                                            <span class="badge badge-soft-success px-2 py-1"><i class="fas fa-paperclip mr-1"></i> Tersedia</span>
                                        @else
                                            <span class="badge badge-soft-secondary px-2 py-1">Kosong</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted small">Belum ada data terbaru.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-primary { background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); }
    .bg-gradient-info { background: linear-gradient(135deg, #36b9cc 0%, #258391 100%); }
    .bg-gradient-success { background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%); }
    .badge-soft-success { background-color: #eafaf1; color: #2ecc71; border: 1px solid #d4f4e2; }
    .badge-soft-secondary { background-color: #f8f9fc; color: #a1a1a1; border: 1px solid #e3e6f0; }
    .hover-elevate { transition: all 0.3s; }
    .hover-elevate:hover { transform: translateY(-5px); box-shadow: 0 1rem 3rem rgba(0,0,0,0.1) !important; }
</style>
@endsection