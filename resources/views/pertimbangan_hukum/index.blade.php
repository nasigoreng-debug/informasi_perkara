@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <h4 class="mb-0 text-dark"><i class="fas fa-chart-line me-2 text-primary"></i> Monitoring Pertimbangan Hukum</h4>
                <form method="GET" action="{{ route('pertimbangan.index') }}" class="row g-2 align-items-center">
                    <div class="col-auto">
                        <input type="date" name="tgl_awal" class="form-control form-control-sm" value="{{ $tgl_awal }}">
                    </div>
                    <div class="col-auto">
                        <input type="date" name="tgl_akhir" class="form-control form-control-sm" value="{{ $tgl_akhir }}">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                        <a href="{{ route('pertimbangan.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center">No</th>
                            <th>Satuan Kerja</th>
                            <th class="text-center">Total Putus</th>
                            <th class="text-center">Sudah Input</th>
                            <th class="text-center">Belum Input</th>
                            <th width="250">Persentase</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $index => $row)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td><span class="fw-bold">{{ $row->SATKER }}</span></td>
                            <td class="text-center">
                                @if($row->persentase == -1)
                                <span class="badge bg-danger">OFFLINE</span>
                                @else
                                <a href="{{ route('pertimbangan.detail', ['satker' => $row->SATKER, 'tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir, 'kategori' => 'semua']) }}" class="btn btn  py-0 fw-bold">
                                    {{ number_format($row->total_putus) }}
                                </a>
                                @endif
                            </td>
                            <td class="text-center text-success fw-bold">{{ $row->persentase == -1 ? '-' : number_format($row->sudah_isi) }}</td>
                            <td class="text-center">
                                @if($row->persentase != -1 && $row->belum_isi > 0)
                                <a href="{{ route('pertimbangan.detail', ['satker' => $row->SATKER, 'tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir, 'kategori' => 'tunggakan']) }}" class="btn btn py-0 fw-bold text-danger">
                                    {{ number_format($row->belum_isi) }}
                                </a>
                                @else
                                <span class="text-muted">{{ $row->persentase == -1 ? '-' : '0' }}</span>
                                @endif
                            </td>
                            <td>
                                @if($row->persentase != -1)
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                        <div class="progress-bar {{ $row->persentase < 100 ? 'bg-warning' : 'bg-success' }}" style="width: {{ $row->persentase }}%"></div>
                                    </div>
                                    <small class="fw-bold">{{ $row->persentase }}%</small>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-secondary fw-bold">
                        <tr>
                            <td colspan="2" class="text-center text-uppercase">Total</td>
                            <td class="text-center">{{ number_format($totals->total_putus) }}</td>
                            <td class="text-center text-success">{{ number_format($totals->sudah_isi) }}</td>
                            <td class="text-center text-danger">{{ number_format($totals->belum_isi) }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1 me-2" style="height: 12px;">
                                        <div class="progress-bar bg-primary" style="width: {{ $totals->persentase }}%"></div>
                                    </div>
                                    <span>{{ $totals->persentase }}%</span>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection