@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow">
        <div class="card-header {{ in_array($status, ['tepat', '3_bulan']) ? 'bg-success' : 'bg-danger' }} text-white">
            <h3 class="card-title">
                @php
                $judulStatus = [
                'total' => 'Total Putus',
                '3_bulan' => 'Putus s/d 3 Bulan',
                '3_5_bulan' => 'Putus 3-5 Bulan',
                'lebih_5_bulan' => 'Putus Lebih dari 5 Bulan',
                'belum_putus' => 'Belum Putus Lebih dari 5 Bulan'
                ];
                @endphp
                Detail {{ $judulStatus[$status] ?? 'Perkara' }} - {{ $satker }}
            </h3>
            <div class="card-tools float-right">
                <a href="{{ route('perkara.tepat_waktu', ['tgl_awal' => $tglAwal, 'tgl_akhir' => $tglAkhir, 'batas_hari' => $batasHari]) }}"
                    class="btn btn-light btn-sm font-weight-bold">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="alert alert-info shadow-sm">
                <i class="fas fa-info-circle"></i> Periode Filter: <strong>{{ date('d-m-Y', strtotime($tglAwal)) }}</strong>
                s/d <strong>{{ date('d-m-Y', strtotime($tglAkhir)) }}</strong>
                | Batas Waktu: <strong>{{ $batasHari }} Hari</strong>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover shadow-sm">
                    <thead class="bg-light text-center">
                        <tr>
                            <th width="50">No</th>
                            <th>Nomor Perkara</th>
                            <th>Jenis Perkara</th> {{-- Kolom Baru --}}
                            <th>Tgl Pendaftaran</th>
                            <th>Tgl Putusan</th>
                            <th width="120">Durasi (Hari)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($details as $index => $row)
                        @php
                        $tglDaftar = \Carbon\Carbon::parse($row->tanggal_pendaftaran);
                        // Jika belum putus, gunakan tanggal hari ini atau tglAkhir untuk hitung durasi berjalan
                        $tglAkhirHitung = $row->tanggal_putusan ? \Carbon\Carbon::parse($row->tanggal_putusan) : \Carbon\Carbon::now();
                        $durasi = $tglDaftar->diffInDays($tglAkhirHitung);
                        @endphp
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="font-weight-bold">{{ $row->nomor_perkara }}</td>
                            <td>{{ $row->jenis_perkara_nama ?? '-' }}</td> {{-- Data Kolom Baru --}}
                            <td class="text-center">{{ $tglDaftar->format('d/m/Y') }}</td>
                            <td class="text-center">
                                {{ $row->tanggal_putusan ? \Carbon\Carbon::parse($row->tanggal_putusan)->format('d/m/Y') : '-' }}
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $durasi <= 90 ? 'badge-success' : ($durasi <= $batasHari ? 'badge-primary' : 'badge-danger') }} p-2 text-dark">
                                    {{ $durasi }} Hari
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Data tidak ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection