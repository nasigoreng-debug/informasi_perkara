@extends('layouts.app')
@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-primary mb-0">REKAP SISA PANJAR {{ strtoupper($label) }}</h4>
            <p class="text-muted small">Data Satuan Kerja dengan sisa panjar > 6 bulan</p>
        </div>
        <a href="{{ route('sisa.panjar.menu') }}" class="btn btn-secondary btn-sm">Kembali</a>
    </div>

    @if($data->isNotEmpty())
    <div class="alert alert-info py-2 small">
        <i class="fa fa-clock"></i> Sinkronisasi Terakhir:
        <strong>{{ \Carbon\Carbon::parse($data->first()->last_update)->diffForHumans() }}</strong>
    </div>
    @endif

    <div class="card border-0 shadow-sm">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>NO</th>
                    <th>SATUAN KERJA</th>
                    <th class="text-center">JUMLAH PERKARA</th>
                    <th class="text-end">TOTAL SALDO</th>
                    <th class="text-center">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="fw-bold">{{ $row->satker_key }}</td>
                    <td class="text-center">{{ $row->total_perkara }}</td>
                    <td class="text-end text-danger fw-bold">Rp {{ number_format($row->total_sisa, 0, ',', '.') }}</td>
                    <td class="text-center">
                        <a href="{{ route('sisa.panjar.detail', ['satker' => $row->satker_key, 'jenis' => $jenis]) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection