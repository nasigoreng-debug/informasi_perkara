@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Monitoring No. Kutipan Akta Nikah (Invalid)</h3>
        <span class="badge bg-secondary">Periode: {{ $tgl_awal }} s/d {{ $tgl_akhir }}</span>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('no_kutipan.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Tanggal Awal</label>
                    <input type="date" name="tgl_awal" class="form-control" value="{{ $tgl_awal }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" name="tgl_akhir" class="form-control" value="{{ $tgl_akhir }}">
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <div class="btn-group w-100" role="group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Filter Data
                        </button>
                        <!-- Tombol Reset -->
                        <a href="{{ route('no_kutipan.index') }}" class="btn btn-secondary">
                            <i class="fas fa-undo"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th width="50" class="text-center">No</th>
                    <th>Satker</th>
                    <th class="text-center">Total Perkara</th>
                    <th class="text-center">Data Valid</th>
                    <th class="text-center">Data Tidak Valid</th>
                    <th class="text-center">Persentase</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach($reports as $r)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td><strong>{{ $r->SATKER }}</strong></td>
                    <td class="text-center">{{ number_format($r->total_perkara, 0, ',', '.') }}</td>
                    <td class="text-center">{{ number_format($r->data_valid, 0, ',', '.') }}</td>
                    <td class="text-center {{ $r->data_tidak_valid > 0 ? 'text-danger fw-bold' : '' }}">
                        {{ number_format($r->data_tidak_valid, 0, ',', '.') }}
                    </td>
                    <td class="text-center">
                        @if($r->persentase_valid == -1)
                        <span class="badge bg-danger">OFFLINE</span>
                        @else
                        {{ $r->persentase_valid }}%
                        @endif
                    </td>
                    <td class="text-center">
                        @if($r->total_perkara > 0)
                        <a href="{{ route('no_kutipan.detail', [$r->SATKER, 'tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]) }}"
                            class="btn btn-sm btn-info text-white">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="table-secondary fw-bold">
                <tr>
                    <td colspan="2" class="text-center">GRAND TOTAL</td>
                    <td class="text-center">{{ number_format($totals->total_perkara, 0, ',', '.') }}</td>
                    <td class="text-center">{{ number_format($totals->data_valid, 0, ',', '.') }}</td>
                    <td class="text-center text-danger">{{ number_format($totals->data_tidak_valid, 0, ',', '.') }}</td>
                    <td class="text-center">
                        @if($totals->total_perkara > 0)
                        {{ round(($totals->data_valid / $totals->total_perkara) * 100, 2) }}%
                        @else
                        0%
                        @endif
                    </td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection