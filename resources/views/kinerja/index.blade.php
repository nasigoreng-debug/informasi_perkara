@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800 fw-bold">Kinerja (Hakim & PP)</h1>
        <form action="{{ route('kinerja.index') }}" method="GET" class="d-flex gap-2">
            <input type="date" name="tgl_awal" class="form-control form-control-sm" value="{{ $tgl_awal }}">
            <input type="date" name="tgl_akhir" class="form-control form-control-sm" value="{{ $tgl_akhir }}">
            <button type="submit" class="btn btn-sm btn-primary">Filter</button>
            <a href="{{ route('kinerja.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
        </form>
    </div>

    <div class="alert alert-warning py-2 border-0 shadow-sm mb-4">
        <i class="fas fa-info-circle me-2"></i>
        Beban kerja dihitung dari <strong>Perkara Masuk</strong> di periode terpilih + <strong>Sisa Perkara</strong> yang belum selesai saat periode dimulai.
    </div>

    <div class="row">
        <div class="col-xl-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-gavel me-2"></i>Kinerja Hakim Tinggi</h6>
                    <input type="text" id="searchHakim" class="form-control form-control-sm w-50" placeholder="Cari nama hakim...">
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                        <table class="table table-hover table-striped align-middle mb-0" id="tableHakim" style="font-size: 13px;">
                            <thead class="table-dark sticky-top">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Nama Hakim</th>
                                    <th class="text-center">Beban</th>
                                    <th class="text-center">Putus</th>
                                    <th width="20%">% Persen</th>
                                    <th class="text-center">Rata² Hari</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kinerjaHakim as $index => $h)
                                <tr>
                                    <td class="text-center text-muted">{{ $index + 1 }}</td>
                                    <td class="fw-bold text-dark">{{ $h->nama ?: '-' }}</td>
                                    <td class="text-center">{{ $h->beban }}</td>
                                    <td class="text-center text-success fw-bold">{{ $h->putus }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2" style="height: 10px; border-radius: 5px;">
                                                <div class="progress-bar bg-success" style="width: {{ $h->persen }}%"></div>
                                            </div>
                                            <span class="fw-bold">{{ round($h->persen) }}%</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge {{ $h->rata_hari > 90 ? 'bg-danger' : 'bg-primary' }} p-2">
                                            {{ $h->rata_hari }} Hari
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-user-tie me-2"></i>Kinerja Panitera Pengganti</h6>
                    <input type="text" id="searchPP" class="form-control form-control-sm w-50" placeholder="Cari nama PP...">
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                        <table class="table table-hover table-striped align-middle mb-0" id="tablePP" style="font-size: 13px;">
                            <thead class="table-dark sticky-top">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Nama PP</th>
                                    <th class="text-center">Beban</th>
                                    <th class="text-center">Minutasi</th>
                                    <th width="20%">% Persen</th>
                                    <th class="text-center">Serah Berkas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kinerjaPP as $index => $p)
                                <tr>
                                    <td class="text-center text-muted">{{ $index + 1 }}</td>
                                    <td class="fw-bold text-dark">{{ $p->nama ?: '-' }}</td>
                                    <td class="text-center">{{ $p->beban }}</td>
                                    <td class="text-center text-info fw-bold">{{ $p->minutasi }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2" style="height: 10px; border-radius: 5px;">
                                                <div class="progress-bar bg-info" style="width: {{ $p->persen_minutasi }}%"></div>
                                            </div>
                                            <span class="fw-bold">{{ round($p->persen_minutasi) }}%</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge {{ $p->rata_serah > 1 ? 'bg-warning text-dark' : 'bg-info' }} p-2">
                                            {{ $p->rata_serah }} Hari
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('searchHakim').addEventListener('keyup', function() {
        let filter = this.value.toUpperCase();
        let rows = document.querySelector("#tableHakim tbody").rows;
        for (let i = 0; i < rows.length; i++) {
            let name = rows[i].cells[1].textContent.toUpperCase();
            rows[i].style.display = name.includes(filter) ? "" : "none";
        }
    });

    document.getElementById('searchPP').addEventListener('keyup', function() {
        let filter = this.value.toUpperCase();
        let rows = document.querySelector("#tablePP tbody").rows;
        for (let i = 0; i < rows.length; i++) {
            let name = rows[i].cells[1].textContent.toUpperCase();
            rows[i].style.display = name.includes(filter) ? "" : "none";
        }
    });
</script>

<style>
    .sticky-top {
        top: -1px;
        z-index: 10;
    }

    .table-responsive::-webkit-scrollbar {
        width: 5px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 10px;
    }
</style>
@endsection