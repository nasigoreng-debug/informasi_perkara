@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">{{ $title }}</h3>
                    <a href="{{ route('pinjam.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Data Peminjam
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <!-- Show Entries -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2">
                                <label for="per_page" class="form-label mb-0">Show</label>
                                <select id="per_page" class="form-select form-select-sm w-auto" onchange="changePerPage(this.value)">
                                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                </select>
                                <span class="form-label mb-0">entries</span>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr class="bg-light">
                                    <th width="5%">No</th>
                                    <th>Nama Peminjam</th>
                                    <th>Nomor Perkara</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Tanggal Kembali</th>
                                    <th>Lama Pinjam</th>
                                    <th>Keterangan</th>
                                    <th width="15%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pinjam as $key => $item)
                                <tr>
                                    <td class="text-center">{{ $pinjam->firstItem() + $key }}</td>
                                    <td>{{ $item->nama_peminjam }}</td>
                                    <td>{{ $item->no_banding }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tgl_pinjam)->format('d/m/Y') }}</td>
                                    <td>
                                        @if($item->tgl_kembali)
                                        {{ \Carbon\Carbon::parse($item->tgl_kembali)->format('d/m/Y') }}
                                        @else
                                        <span class="badge bg-warning">Belum Kembali</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                        $tglPinjam = \Carbon\Carbon::parse($item->tgl_pinjam);
                                        if($item->tgl_kembali) {
                                        $tglKembali = \Carbon\Carbon::parse($item->tgl_kembali);
                                        $lama = $tglPinjam->diffInDays($tglKembali);
                                        echo "<span class='badge bg-info'>{$lama} Hari</span>";
                                        } else {
                                        $lama = $tglPinjam->diffInDays(\Carbon\Carbon::now());
                                        echo "<span class='badge bg-warning'>{$lama} Hari</span>";
                                        }
                                        @endphp
                                    </td>
                                    <td>{{ $item->keterangan ?? '-' }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('pinjam.show', $item->id) }}" class="btn btn-info btn-sm" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('pinjam.edit', $item->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('pinjam.destroy', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">
                                        <i class="fas fa-database"></i> Belum ada data peminjam
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <small class="text-muted">
                                Menampilkan {{ $pinjam->firstItem() }} sampai {{ $pinjam->lastItem() }} dari {{ $pinjam->total() }} hasil
                            </small>
                        </div>
                        <div>
                            {{ $pinjam->appends(['per_page' => request('per_page')])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function changePerPage(value) {
        var url = new URL(window.location.href);
        url.searchParams.set('per_page', value);
        window.location.href = url.toString();
    }
</script>
@endsection