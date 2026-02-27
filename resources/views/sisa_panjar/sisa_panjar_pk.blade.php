@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow border-0 mb-4 animate__animated animate__fadeIn">
        <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Rekap Sisa Panjar {{ $label }} (> 6 Bulan)</h5>
            <a href="{{ route('sisa.panjar.menu') }}" class="btn btn-sm btn-light rounded-pill fw-bold">
                <i class="fas fa-home me-1"></i> Menu Utama
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th width="5%">No</th>
                            <th>Satuan Kerja</th>
                            <th>Jumlah Perkara</th>
                            <th>Total Sisa Saldo</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $grandTotalDuit = 0; @endphp
                        @foreach($data->groupBy('satker_key') as $satker => $group)
                            @php $grandTotalDuit += $group->sum('sisa'); @endphp
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="fw-bold text-uppercase">{{ $satker }}</td>
                                <td class="text-center">{{ $group->count() }} Perkara</td>
                                <td class="text-end fw-bold text-danger">Rp {{ number_format($group->sum('sisa'), 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <button class="btn btn-outline-danger btn-sm rounded-pill btn-detail" data-satker="{{ $satker }}">
                                        <i class="fas fa-eye me-1"></i> Detail
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-secondary fw-bold">
                        <tr>
                            <td colspan="3" class="text-center">TOTAL SELURUH SATKER</td>
                            <td class="text-end text-danger fs-5">Rp {{ number_format($grandTotalDuit, 0, ',', '.') }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div id="section-detail" style="display: none;" class="animate__animated animate__fadeInUp">
        <div class="card shadow border-0 border-top border-danger border-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-danger fw-bold" id="title-detail">Detail Perkara PA</h5>
                <button type="button" class="btn-close" onclick="$('#section-detail').hide()"></button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle">
                        <thead class="table-light text-center">
                            <tr>
                                <th width="5%">No</th>
                                <th>No. Perkara Tk.I</th>
                                <th>No. Perkara {{ $label }}</th>
                                <th>Tgl Putusan</th>
                                <th>Tgl Notif</th>
                                <th>Usia</th>
                                <th>Sisa Saldo</th>
                            </tr>
                        </thead>
                        <tbody id="body-detail">
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script JQuery untuk Interaksi --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Ambil data PHP dan ubah ke JSON aman
    const rawData = {!! json_encode($data->groupBy('satker_key')) !!};

    $(document).on('click', '.btn-detail', function() {
        const satker = $(this).data('satker');
        const listPerkara = rawData[satker];

        // Set Judul
        $('#title-detail').html('<i class="fas fa-university me-2"></i>Detail Sisa Panjar Perkara PA ' + satker);
        
        // Bersihkan tabel detail
        let rows = '';
        listPerkara.forEach((item, index) => {
            rows += `
                <tr>
                    <td class="text-center">${index + 1}</td>
                    <td>${item.nomor_perkara}</td>
                    <td class="fw-bold text-primary">${item.nomor_perkara_atas}</td>
                    <td class="text-center">${item.tgl_putusan}</td>
                    <td class="text-center">${item.tgl_notif}</td>
                    <td class="text-center text-danger fw-bold">${item.selisih_bulan} Bln</td>
                    <td class="text-end fw-bold">Rp ${new Intl.NumberFormat('id-ID').format(item.sisa)}</td>
                </tr>
            `;
        });

        // Masukkan baris ke tabel dan tampilkan section
        $('#body-detail').html(rows);
        $('#section-detail').show();

        // Scroll otomatis ke bagian detail
        $('html, body').animate({
            scrollTop: $("#section-detail").offset().top - 20
        }, 500);
    });
</script>
@endsection