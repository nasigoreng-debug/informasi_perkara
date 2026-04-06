@extends('layouts.app')

@section('content')
<div class="container py-4" style="font-family: 'Inter', sans-serif;">
    <div class="card shadow border-0 rounded-lg overflow-hidden">
        <div class="card-header bg-dark text-white p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 font-weight-bold">RANKING KEDISIPLINAN USER</h4>
                    <p class="mb-0 text-warning small font-weight-bold uppercase tracking-widest">
                        Periode: {{ \Carbon\Carbon::parse($tglAwal)->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::parse($tglAkhir)->translatedFormat('d F Y') }}
                    </p>
                </div>
                <span class="badge badge-success px-3 py-2 shadow-sm">Target: 0% Admin</span>
            </div>
        </div>

        <div class="card-body bg-white p-4">
            <form action="{{ route('input.index') }}" method="GET" class="row g-3 mb-5 bg-light p-3 rounded shadow-sm border">
                <div class="col-md-4">
                    <label class="small font-weight-bold text-muted">Mulai Tanggal</label>
                    <input type="date" name="tgl_awal" class="form-control border-0 shadow-sm" value="{{ $tglAwal }}">
                </div>
                <div class="col-md-4">
                    <label class="small font-weight-bold text-muted">Sampai Tanggal</label>
                    <input type="date" name="tgl_akhir" class="form-control border-0 shadow-sm" value="{{ $tglAkhir }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100 font-weight-bold shadow">🔄 PERBAHARUI DATA</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle border">
                    <thead class="bg-secondary text-white small uppercase">
                        <tr>
                            <th class="text-center py-3">Rank</th>
                            <th class="py-3">Satuan Kerja</th>
                            <th class="text-center">Total Perkara</th>
                            <th class="text-center">Oleh Admin</th>
                            <th class="text-center" width="250">Kualitas Kedisiplinan (%)</th>
                        </tr>
                    </thead>
                    <tbody class="font-medium">
                        @foreach($data as $row)
                        @php
                        $persentase = $row['persentase'];
                        $colorClass = $persentase == 0 ? 'bg-primary' : ($persentase > 15 ? 'bg-danger' : 'bg-success');
                        $textClass = $persentase == 0 ? 'text-primary' : ($persentase > 15 ? 'text-danger' : 'text-success');
                        @endphp
                        <tr class="{{ $persentase == 0 && $row['total_perkara'] > 0 ? 'table-success' : '' }}">
                            <td class="text-center font-weight-bold text-muted">{{ $loop->iteration }}</td>
                            <td class="font-weight-bold text-uppercase text-dark">
                                {{ $row['nama_satker'] }}
                                @if($persentase == 0 && $row['total_perkara'] > 0)
                                <span class="ms-2 badge badge-primary text-dark">🏆 Sempurna</span>
                                @endif
                            </td>
                            <td class="text-center font-weight-bold" style="color:#000;">{{ number_format($row['total_perkara'], 0, ',', '.') }}</td>
                            <td class="text-center">
                                @if($row['total_admin'] > 0)
                                <a href="{{ route('input.detail', ['satker' => $row['koneksi'], 'label' => $row['nama_satker'], 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhir]) }}"
                                    class="btn btn-sm btn-danger rounded-pill px-3 font-weight-bold">
                                    {{ number_format($row['total_admin'], 0, ',', '.') }}
                                </a>
                                @else
                                <span class="text-success font-weight-bold">0 (Bagus)</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height: 10px; border-radius: 10px; background:#e2e8f0;">
                                        <div class="progress-bar progress-bar-striped {{ $colorClass }}"
                                            role="progressbar" style="width: {{ $persentase == 0 && $row['total_perkara'] > 0 ? '100' : $persentase }}%"></div>
                                    </div>
                                    <span class="{{ $textClass }} font-weight-bold" style="min-width: 50px; text-align: right;">{{ round($persentase, 1) }}%</span>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection