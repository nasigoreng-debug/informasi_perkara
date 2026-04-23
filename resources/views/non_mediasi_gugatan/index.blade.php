@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow border-0 mb-4">
        <div class="card-body">
            <form action="{{ route('non-mediasi.gugatan') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-bold small text-muted text-uppercase">Tanggal Awal</label>
                    <input type="date" name="tgl_awal" class="form-control" value="{{ $tgl_awal }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold small text-muted text-uppercase">Tanggal Akhir</label>
                    <input type="date" name="tgl_akhir" class="form-control" value="{{ $tgl_akhir }}">
                </div>
                <div class="col-md-6">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1 fw-bold">
                            <i class="fas fa-filter me-2"></i>Filter Rekap
                        </button>
                        <a href="{{ route('non-mediasi.gugatan') }}" class="btn btn-outline-secondary px-4 fw-bold">
                            <i class="fas fa-undo me-2"></i>Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow border-0">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0 fw-bold">Rekap Perkara Gugatan yang Tidak Mediasi</h5>
            <span class="badge bg-light text-dark small">{{ count($rekap) }} Satker Terdata</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-bordered text-center align-middle mb-0">
                <thead class="table-light text-uppercase small fw-bold">
                    <tr>
                        <th rowspan="2" class="align-middle border-bottom">Satuan Kerja</th>
                        <th rowspan="2" class="align-middle border-bottom">Diterima</th>
                        <th colspan="2" class="border-bottom">Klasifikasi</th>
                        <th colspan="4" class="border-bottom">Status Mediasi (Gugatan)</th>
                    </tr>
                    <tr>
                        <th class="border-bottom">Gugatan</th>
                        <th class="border-bottom">Permohonan</th>
                        <th class="text-success border-bottom">Sudah</th>
                        <th class="text-danger border-bottom">Belum/Tidak</th>
                        <th class="border-bottom">% Persentase</th>
                        <th width="100" class="border-bottom">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rekap as $row)
                    @php
                    $persenAnomali = $row->jml_gugatan > 0 ? ($row->belum_mediasi / $row->jml_gugatan) * 100 : 0;
                    @endphp
                    <tr>
                        <td class="text-start fw-bold ps-3">{{ $row->satker }}</td>
                        <td class="fw-bold">{{ number_format($row->total_terima) }}</td>
                        <td>{{ number_format($row->jml_gugatan) }}</td>
                        <td>{{ number_format($row->jml_permohonan) }}</td>
                        <td class="text-success fw-bold">{{ number_format($row->sudah_mediasi) }}</td>
                        <td class="text-danger fw-bold bg-light">
                            {{ number_format($row->belum_mediasi) }}
                        </td>
                        <td style="min-width: 150px;">
                            <div class="progress" style="height: 12px; border-radius: 10px;">
                                <div class="progress-bar {{ $persenAnomali > 10 ? 'bg-danger' : 'bg-success' }}"
                                    role="progressbar" style="width: {{ $persenAnomali }}%">
                                </div>
                            </div>
                            <small class="fw-bold" style="font-size: 10px;">{{ round($persenAnomali, 1) }}% Tidak Mediasi</small>
                        </td>
                        <td>
                            <a href="{{ route('non-mediasi.gugatan.detail', [
                                'satker' => strtolower($row->satker), 
                                'tgl_awal' => $tgl_awal, 
                                'tgl_akhir' => $tgl_akhir
                            ]) }}" class="btn btn-sm btn-dark px-3 shadow-sm rounded-pill">
                                <i class="fas fa-search me-1"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection