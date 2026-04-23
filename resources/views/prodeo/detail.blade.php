@extends('layouts.app')

@section('content')
<style>
    /* Styling Mewah & Clean */
    .card-detail {
        border-radius: 15px;
        border: none;
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .bg-gradient-header {
        background: linear-gradient(135deg, #1a4d2e 0%, #2d5a3f 100%);
    }

    .table-detail thead th {
        background: #f8fbf9;
        color: #1a4d2e;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 1px;
        padding: 15px;
    }

    .badge-no {
        background: #f1f3f5;
        color: #34a853;
        font-weight: 800;
        padding: 5px 10px;
        border-radius: 8px;
    }

    .text-nomor {
        color: #1a4d2e;
        font-weight: 700;
        font-size: 0.9rem;
    }

    .pihak-box {
        line-height: 1.6;
        color: #555;
    }
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="font-weight-bold text-dark mb-0">📋 Detail Perkara Prodeo</h4>
            <p class="text-muted small">Satker: <strong>{{ $satker }}</strong> | Periode: {{ date('d/m/Y', strtotime($tglAwal)) }} - {{ date('d/m/Y', strtotime($tglAkhir)) }}</p>
        </div>
        <a href="{{ route('prodeo.index', ['tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhir]) }}" class="btn btn-white btn-sm rounded-pill shadow-sm px-3">
            <i class="fas fa-arrow-left mr-1"></i> Kembali 
        </a>
    </div>

    <div class="card card-detail">
        <div class="card-header bg-gradient-header py-3">
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-white font-weight-bold"><i class="fas fa-list-ol mr-2"></i> Daftar Perkara Gratis (Prodeo)</span>
                <span class="badge badge-light px-3 py-2 rounded-pill font-weight-bold text-light">{{ $data->count() }} Perkara</span>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 table-detail">
                    <thead>
                        <tr class="text-center">
                            <th width="5%">No</th>
                            <th class="text-left" width="20%">Nomor Perkara</th>
                            <th class="text-left" width="15%">Jenis Perkara</th>
                            <th width="15%">Tanggal Daftar</th>
                            <th class="text-left px-4">Para Pihak</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $index => $row)
                        <tr>
                            <td class="text-center">
                                <span class="badge-no">{{ $index + 1 }}</span>
                            </td>
                            <td class="text-left">
                                <span class="text-nomor">{{ $row->nomor_perkara }}</span>
                            </td>
                            <td class="text-left">
                                <span class="badge badge-outline-primary small text-dark">{{ $row->jenis_perkara_nama }}</span>
                            </td>
                            <td class="text-center text-muted">
                                {{ date('d-m-Y', strtotime($row->tanggal_pendaftaran)) }}
                            </td>
                            <td class="text-left px-4 pihak-box">
                                {{-- SOLUSI: Menghilangkan <br /> dan merapikan teks pihak --}}
                                {!! nl2br(e($row->para_pihak)) !!}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80" class="mb-3 opacity-3" alt="no-data">
                                <p class="text-muted font-italic">Tidak ditemukan data perkara prodeo pada periode ini.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-light border-0 d-md-none text-center">
            <p class="small text-muted mb-0 font-italic">Menampilkan {{ $data->count() }} data perkara prodeo.</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Inisialisasi DataTables untuk pencarian cepat di detail
        var detailTable = $('.table').DataTable({
            "dom": 'frtip',
            "pageLength": 50,
            "language": {
                "search": "",
                "searchPlaceholder": "Cari data di sini..."
            }
        });

        // Mempercantik input search
        $('.dataTables_filter input').addClass('form-control-sm border-0 shadow-sm px-3').css({
            'background': '#fff',
            'border-radius': '20px',
            'margin-bottom': '15px'
        });
    });
</script>
@endpush