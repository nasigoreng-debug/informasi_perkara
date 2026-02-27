@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow border-0 mb-4 animate__animated animate__fadeIn">
        <div class="card-header bg-indigo text-white d-flex justify-content-between align-items-center" style="background-color: #6610f2;">
            <h5 class="mb-0"><i class="fas fa-balance-scale me-2"></i>Rekap Sisa Panjar Tingkat Pertama (> 6 Bulan)</h5>
            <a href="{{ route('sisa.panjar.menu') }}" class="btn btn-sm btn-light rounded-pill fw-bold">Menu Utama</a>
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
                        @php $totalDuit = 0; @endphp
                        @foreach($data->groupBy('satker_key') as $satker => $group)
                        @php $totalDuit += $group->sum('sisa'); @endphp
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="fw-bold text-uppercase">{{ $satker }}</td>
                            <td class="text-center">{{ $group->count() }} Perkara</td>
                            <td class="text-end fw-bold" style="color: #6610f2;">Rp {{ number_format($group->sum('sisa'), 0, ',', '.') }}</td>
                            <td class="text-center">
                                <button class="btn btn-outline-indigo btn-sm rounded-pill btn-detail-pertama" data-satker="{{ $satker }}" style="color: #6610f2; border-color: #6610f2;">
                                    <i class="fas fa-eye me-1"></i> Detail
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-secondary fw-bold text-center">
                        <tr>
                            <td colspan="3">TOTAL TINGKAT PERTAMA</td>
                            <td class="text-end fs-5" style="color: #6610f2;">Rp {{ number_format($totalDuit, 0, ',', '.') }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div id="section-detail-pertama" style="display: none;" class="card shadow border-0 border-top border-4 animate__animated animate__fadeInUp" style="border-top-color: #6610f2 !important;">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold" style="color: #6610f2;" id="title-detail-pertama">Detail Perkara PA</h5>
            <button type="button" class="btn-close" onclick="document.getElementById('section-detail-pertama').style.display='none'"></button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-light text-center">
                        <tr>
                            <th width="5%">No</th>
                            <th>Nomor Perkara</th>
                            <th>Tgl Putus</th>
                            <th>Tgl PBT</th>
                            <th>Lama</th>
                            <th>Sisa Saldo</th>
                        </tr>
                    </thead>
                    <tbody id="body-detail-pertama"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    // Ambil data PHP ke JSON
    const dataPertama = {
        !!json_encode($data - > groupBy('satker_key')) !!
    };

    document.addEventListener('click', function(e) {
        // Cari tombol detail pertama (pakai closest biar biarpun klik icon tetep dapet)
        const btn = e.target.closest('.btn-detail-pertama');

        if (btn) {
            const satker = btn.getAttribute('data-satker');
            const list = dataPertama[satker];
            if (!list) return;

            document.getElementById('title-detail-pertama').innerHTML = '<i class="fas fa-university me-2"></i>Detail Sisa Panjar Perkara PA ' + satker;

            let rows = '';
            list.forEach((item, index) => {
                rows += `<tr>
                    <td class="text-center">${index + 1}</td>
                    <td class="fw-bold text-primary">${item.nomor_perkara || '-'}</td>
                    <td class="text-center">${item.tgl_putusan || '-'}</td>
                    <td class="text-center text-success fw-bold">${item.tgl_notif || '-'}</td>
                    <td class="text-center text-danger fw-bold">${item.selisih_bulan} Bln</td>
                    <td class="text-end fw-bold">Rp ${new Intl.NumberFormat('id-ID').format(item.sisa)}</td>
                </tr>`;
            });

            document.getElementById('body-detail-pertama').innerHTML = rows;
            const sec = document.getElementById('section-detail-pertama');
            sec.style.display = 'block';

            // Scroll halus ke bagian detail
            window.scrollTo({
                top: sec.offsetTop - 20,
                behavior: 'smooth'
            });
        }
    });
</script>
@endsection