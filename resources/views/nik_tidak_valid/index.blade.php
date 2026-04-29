@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Rekapitulasi NIK Tidak Valid (Dispensasi Kawin)</h5>
            <span class="badge bg-warning text-dark">Total Masalah: {{ number_format($totals->invalid_dk) }}</span>
        </div>

        <div class="card-body">
            <div class="alert alert-info">
                <strong>Dasar Hukum:</strong> Undang-undang (UU) Nomor 24 Tahun 2013 tentang Perubahan atas Undang-Undang Nomor 23 Tahun 2006 tentang Administrasi Kependudukan Pasal 64:
                <br>
                <em>"KTP-el memuat elemen data penduduk, yaitu NIK, nama, tempat tanggal lahir, jenis kelamin, agama, status perkawinan, golongan darah, alamat, pekerjaan, kewarganegaraan, pas foto, masa berlaku, tempat dan tanggal dikeluarkan KTP-el, dan tandatangan pemilik KTP-el."</em>
            </div>

            <div class="alert alert-secondary">
                <strong>Struktur 16 Digit NIK:</strong>
                <ul class="mb-0">
                    <li><strong>6 Digit Pertama:</strong> Kode wilayah (2 digit Provinsi, 2 digit Kota/Kabupaten, 2 digit Kecamatan)</li>
                    <li><strong>6 Digit Kedua:</strong> Tanggal lahir (DD-MM-YY) - <em>Khusus perempuan, tanggal lahir ditambah angka 40</em></li>
                    <li><strong>4 Digit Terakhir:</strong> Nomor urut yang dimulai dari 0001</li>
                </ul>
            </div>
        </div>

        <div class="card-body pt-0">
            <form method="GET" action="{{ route('nik.index') }}" class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label">Tanggal Awal</label>
                    <input type="date" name="tgl_awal" class="form-control" value="{{ $tgl_awal }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" name="tgl_akhir" class="form-control" value="{{ $tgl_akhir }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <div>
                        <button type="submit" class="btn btn-primary px-4">Proses</button>
                        <a href="{{ route('nik.index') }}" class="btn btn-outline-secondary">Reset</a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th style="width: 5%">No</th>
                            <th style="width: 35%">Satuan Kerja</th>
                            <th style="width: 20%">Total Perkara DK</th>
                            <th style="width: 20%">NIK Tidak Valid</th>
                            <th style="width: 20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $r)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td><strong>{{ $r->SATKER }}</strong></td>
                            <td class="text-center">{{ number_format($r->total_dk) }}</td>
                            <td class="text-center">
                                <span class="badge {{ $r->jumlah_invalid > 0 ? 'bg-danger' : 'bg-success' }}">
                                    {{ number_format($r->jumlah_invalid) }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if(!isset($r->error))
                                <a href="{{ route('nik.detail', [$r->SATKER, 'tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    Lihat Detail
                                </a>
                                @else
                                <span class="badge bg-secondary">OFFLINE</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection