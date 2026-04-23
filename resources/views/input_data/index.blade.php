@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow border-0 rounded-lg">
        <div class="card-header bg-dark text-white p-4">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0 font-weight-bold">RANKING KEDISIPLINAN SATKER</h4>
                <span class="badge badge-success px-3 py-2">Target: 0% Admin</span>
            </div>
        </div>

        <div class="card-body bg-white p-4">
            <form action="{{ route('input.index') }}" method="GET" class="row g-3 mb-4 bg-light p-3 rounded border">
                <div class="col-md-4">
                    <label class="small font-weight-bold">Mulai Tanggal</label>
                    <input type="date" name="tgl_awal" class="form-control" value="{{ $tglAwal }}">
                </div>
                <div class="col-md-4">
                    <label class="small font-weight-bold">Sampai Tanggal</label>
                    <input type="date" name="tgl_akhir" class="form-control" value="{{ $tglAkhir }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100 font-weight-bold">🔄 REFRESH DATA</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle border">
                    <thead class="bg-secondary text-white small uppercase">
                        <tr>
                            <th class="text-center">Rank</th>
                            <th>Satuan Kerja</th>
                            <th class="text-center">Total Perkara</th>
                            <th class="text-center bg-danger">Input Admin</th>
                            <th class="text-center bg-warning text-dark">Update Admin</th>
                            <th class="text-center" width="200">Pelanggaran (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $row)
                        @php $p = $row['persentase']; @endphp
                        <tr>
                            <td class="text-center font-weight-bold text-muted">{{ $loop->iteration }}</td>
                            <td class="font-weight-bold text-uppercase">{{ $row['nama_satker'] }}</td>
                            <td class="text-center font-weight-bold">{{ number_format($row['total_perkara'], 0, ',', '.') }}</td>

                            {{-- Klik Angka Input --}}
                            <td class="text-center">
                                @if($row['total_input'] > 0)
                                <a href="{{ route('input.detail', ['satker' => $row['koneksi'], 'label' => $row['nama_satker'], 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhir, 'type' => 'input']) }}"
                                    class="btn btn-danger btn-sm rounded-pill px-3 font-weight-bold">
                                    {{ $row['total_input'] }}
                                </a>
                                @else
                                <span class="text-muted">0</span>
                                @endif
                            </td>

                            {{-- Klik Angka Update --}}
                            <td class="text-center">
                                @if($row['total_update'] > 0)
                                <a href="{{ route('input.detail', ['satker' => $row['koneksi'], 'label' => $row['nama_satker'], 'tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhir, 'type' => 'update']) }}"
                                    class="btn btn-warning btn-sm rounded-pill px-3 font-weight-bold text-dark">
                                    {{ $row['total_update'] }}
                                </a>
                                @else
                                <span class="text-muted">0</span>
                                @endif
                            </td>

                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height: 8px;">
                                        <div class="progress-bar {{ $p == 0 ? 'bg-primary' : ($p > 10 ? 'bg-danger' : 'bg-success') }}" style="width: {{ $p }}%"></div>
                                    </div>
                                    <span class="small font-weight-bold">{{ round($p, 1) }}%</span>
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