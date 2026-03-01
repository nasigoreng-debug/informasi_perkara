@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-0 text-uppercase text-primary">REKAP SISA PANJAR {{ $label }}</h4>
            <p class="text-muted small mb-0 font-italic">Daftar Satuan Kerja dengan sisa panjar > 6 bulan</p>
        </div>
        <a href="{{ route('sisa.panjar.menu') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 shadow-sm bg-white">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Menu
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-dark text-white text-center text-uppercase" style="font-size: 0.8rem;">
                    <tr>
                        <th class="py-3" width="60">NO</th>
                        <th class="text-start px-4">SATUAN KERJA</th>
                        <th>JUMLAH PERKARA</th>
                        <th>TOTAL SALDO SISA</th>
                        <th width="150">AKSI</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @php $grandTotal = 0; @endphp
                    @foreach($data->groupBy('satker_key') as $satker => $group)
                    @php $grandTotal += $group->sum('sisa'); @endphp
                    <tr>
                        <td class="text-muted small">{{ $loop->iteration }}</td>
                        <td class="text-start px-4 fw-bold text-dark text-uppercase">{{ $satker }}</td>
                        <td><span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-1 rounded-pill">{{ $group->count() }} Perkara</span></td>
                        <td class="fw-bold text-primary">Rp {{ number_format($group->sum('sisa'), 0, ',', '.') }}</td>
                        <td>
                            <a href="{{ route('sisa.panjar.detail', ['satker' => $satker, 'jenis' => $jenis]) }}"
                                class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm fw-bold">
                                <i class="fas fa-search me-1"></i> DETAIL
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-grand-total" style="background-color: #ffff00 !important; font-weight: 800;">
                    <tr class="text-center text-dark">
                        <td colspan="2" class="text-start px-4 py-3">TOTAL SELURUH WILAYAH</td>
                        <td>{{ $data->count() }} Perkara</td>
                        <td>Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection