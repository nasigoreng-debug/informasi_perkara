@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow border-0 mb-4 animate__animated animate__fadeIn">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-gavel me-2"></i>Rekap Sisa Panjar Kasasi (> 6 Bulan)</h5>
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
                        @php $totalKasasi = 0; @endphp
                        @forelse($data->groupBy('satker_key') as $satker => $group)
                            @php $totalKasasi += $group->sum('sisa'); @endphp
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="fw-bold text-uppercase">{{ $satker }}</td>
                                <td class="text-center">{{ $group->count() }} Perkara</td>
                                <td class="text-end fw-bold text-success">Rp {{ number_format($group->sum('sisa'), 0, ',', '.') }}</td>
                                <td class="text-center">
                                    {{-- Tombol dengan Icon Mata --}}
                                    <button class="btn btn-outline-success btn-sm rounded-pill btn-detail-ks" data-satker="{{ $satker }}">
                                        <i class="fas fa-eye me-1"></i> Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-4">Data Kasasi tidak ditemukan.</td></tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-secondary fw-bold text-center">
                        <tr>
                            <td colspan="3">GRAND TOTAL SELURUH SATKER</td>
                            <td class="text-end text-success fs-5">Rp {{ number_format($totalKasasi, 0, ',', '.') }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div id="section-detail-ks" style="display: none;" class="card shadow border-0 border-top border-success border-4 animate__animated animate__fadeInUp">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-success fw-bold" id="title-detail-ks">Detail Perkara</h5>
            <button type="button" class="btn-close" onclick="document.getElementById('section-detail-ks').style.display='none'"></button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-light text-center">
                        <tr>
                            <th width="5%">No</th>
                            <th>No. Perkara Tk.I</th>
                            <th>No. Perkara Kasasi</th>
                            <th>Tgl Putusan</th>
                            <th>Tgl Notif</th>
                            <th>Usia</th>
                            <th>Sisa Saldo</th>
                        </tr>
                    </thead>
                    <tbody id="body-detail-ks"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    const dataKasasi = {!! json_encode($data->groupBy('satker_key')) !!};

    document.addEventListener('click', function (e) {
        // INI KUNCINYA: Pakai closest biar biarpun klik icon mata, tetep kedeteksi tombolnya
        const btn = e.target.closest('.btn-detail-ks');
        
        if (btn) {
            const satker = btn.getAttribute('data-satker');
            const list = dataKasasi[satker];
            if (!list) return;

            document.getElementById('title-detail-ks').innerHTML = '<i class="fas fa-university me-2"></i>Detail Kasasi Satker ' + satker;
            let rows = '';
            list.forEach((item, index) => {
                rows += `<tr>
                    <td class="text-center">${index + 1}</td>
                    <td>${item.nomor_perkara || '-'}</td>
                    <td class="fw-bold text-success">${item.nomor_perkara_atas || '-'}</td>
                    <td class="text-center">${item.tgl_putusan || '-'}</td>
                    <td class="text-center">${item.tgl_notif || '-'}</td>
                    <td class="text-center text-danger fw-bold">${item.selisih_bulan} Bln</td>
                    <td class="text-end fw-bold">Rp ${new Intl.NumberFormat('id-ID').format(item.sisa)}</td>
                </tr>`;
            });

            document.getElementById('body-detail-ks').innerHTML = rows;
            const sec = document.getElementById('section-detail-ks');
            sec.style.display = 'block';
            
            // Scroll halus biar gak kaget
            window.scrollTo({
                top: sec.offsetTop - 20,
                behavior: 'smooth'
            });
        }
    });
</script>
@endsection