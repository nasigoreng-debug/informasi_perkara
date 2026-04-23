@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-12">
            @if(count($errors) > 0)
            <div class="alert alert-warning shadow-sm border-start border-4 border-warning alert-dismissible fade show" role="alert">
                <strong>Informasi:</strong> {{ count($errors) }} Satker tidak dapat diakses ({{ implode(', ', $errors) }}).
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center py-3">
                    <div>
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-file-invoice-dollar me-2"></i> Monitoring Saldo Minus Perkara
                        </h5>
                        <small class="text-white-50">
                            <i class="fas fa-sync-alt fa-xs me-1"></i> Sinkronisasi Terakhir:
                            <span class="text-white">
                                {{ $lastSync ? \Carbon\Carbon::parse($lastSync)->translatedFormat('d F Y H:i') : '--' }} WIB
                            </span>
                        </small>
                    </div>
                    <span class="badge bg-white text-danger px-3 py-2 rounded-pill shadow-sm">
                        {{ count($dataPerkara) }} Perkara Ditemukan
                    </span>
                </div>

                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle text-center" id="tableSaldo">
                            <thead class="table-dark">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="15%">Satker</th>
                                    <th width="20%">Nomor Perkara</th>
                                    <th width="20%">Jenis Perkara</th>
                                    <th width="13%">Penerimaan</th>
                                    <th width="13%">Pengeluaran</th>
                                    <th width="14%">Sisa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dataPerkara as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="fw-bold text-uppercase">{{ $item->satker ?? $item->nama_satker ?? $item->satker_nama }}</td>
                                    <td class="text-start">{{ $item->nomor_perkara }}</td>
                                    <td class="text-start">{{ $item->jenis_perkara_nama }}</td>
                                    <td class="text-end text-success fw-semibold">Rp {{ number_format($item->total_penerimaan, 0, ',', '.') }}</td>
                                    <td class="text-end text-primary fw-semibold">Rp {{ number_format($item->total_pengeluaran, 0, ',', '.') }}</td>
                                    <td class="text-end fw-bold text-danger">Rp {{ number_format($item->sisa_akhir, 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="py-5 text-center text-muted">
                                        <i class="fas fa-check-circle fa-3x mb-3 text-success"></i><br>
                                        <h5>Alhamdulillah, Tidak ada sisa saldo minus ditemukan.</h5>
                                    </td>
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
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#tableSaldo').DataTable({
            pageLength: 25,
            responsive: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json"
            },
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            columnDefs: [{
                    className: "text-center",
                    targets: [0, 1]
                },
                {
                    className: "text-start",
                    targets: [2, 3]
                },
                {
                    className: "text-end",
                    targets: [4, 5, 6]
                }
            ]
        });
    });
</script>
@endpush