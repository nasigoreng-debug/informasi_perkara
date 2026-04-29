@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-exclamation-triangle"></i> Daftar Pihak NIK Bermasalah (DK) - {{ $satker }}
            </h5>
            <span class="badge bg-light text-danger">
                Total Bermasalah: {{ is_array($data) ? count($data) : $data->count() }}
            </span>
        </div>
        
        <div class="card-body">
            <!-- Tombol Kembali -->
            <div class="mb-3">
                <a href="{{ route('nik.index', ['tgl_awal' => $tgl_awal, 'tgl_akhir' => $tgl_akhir]) }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali ke Rekapitulasi
                </a>
            </div>

            <!-- Dasar Hukum -->
            <div class="alert alert-info mb-4 border-left-info">
                <h6 class="mb-2">
                    <i class="fas fa-gavel"></i> <strong>Dasar Hukum dan Ketentuan NIK</strong>
                </h6>
                <hr class="my-2">
                
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>📖 Undang-Undang Nomor 24 Tahun 2013</strong></p>
                        <p class="mb-2 small text-muted">Tentang Perubahan atas Undang-Undang Nomor 23 Tahun 2006 tentang Administrasi Kependudukan</p>
                        <p class="mb-0"><strong>Pasal 64:</strong></p>
                        <blockquote class="small ms-3">
                            "KTP-el memuat elemen data penduduk, yaitu NIK, nama, tempat tanggal lahir, jenis kelamin, 
                            agama, status perkawinan, golongan darah, alamat, pekerjaan, kewarganegaraan, pas foto, 
                            masa berlaku, tempat dan tanggal dikeluarkan KTP-el, dan tandatangan pemilik KTP-el."
                        </blockquote>
                    </div>
                    
                    <div class="col-md-6">
                        <p class="mb-1"><strong>🔢 Struktur 16 Digit NIK:</strong></p>
                        <ul class="small mb-2">
                            <li><strong>6 Digit Pertama:</strong> Kode wilayah (2 digit Provinsi, 2 digit Kota/Kabupaten, 2 digit Kecamatan)</li>
                            <li><strong>6 Digit Kedua:</strong> Tanggal lahir (DD-MM-YY) - <em class="text-muted">Khusus perempuan, tanggal lahir ditambah angka 40</em></li>
                            <li><strong>4 Digit Terakhir:</strong> Nomor urut yang dimulai dari 0001</li>
                        </ul>
                        
                        <div class="alert alert-warning small mb-0">
                            <i class="fas fa-info-circle"></i> <strong>Catatan:</strong> 
                            NIK yang tidak sesuai dengan struktur di atas atau tidak berjumlah 16 digit dinyatakan <strong>TIDAK VALID</strong>.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Data -->
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="table-detail">
                    <thead>
                        <tr class="table-secondary text-center">
                            <th width="5%">No</th>
                            <th width="20%">Nomor Perkara</th>
                            <th width="12%">Tanggal Daftar</th>
                            <th width="23%">Nama Pihak</th>
                            <th width="25%">NIK SIPP</th>
                            <th width="15%">Status Tidak Valid</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $dataArray = is_array($data) ? $data : $data->toArray(); @endphp
                        @forelse($dataArray as $index => $d)
                        @php 
                            $d = (object) $d;
                            $nik = $d->nik ?? ''; // Ambil nilai dari database, jika NULL jadi string kosong
                            $nikDisplay = $nik === '' || $nik === null ? '' : (string)$nik; // Tampilkan apa adanya
                            $len = strlen($nikDisplay);
                            $isEmpty = ($nikDisplay === '' || $nikDisplay === null);
                            $isValidNumeric = !$isEmpty && preg_match('/^\d+$/', $nikDisplay);
                        @endphp
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $d->nomor_perkara ?? '-' }}</td>
                            <td class="text-center">
                                {{ isset($d->tanggal_pendaftaran) ? date('d-m-Y', strtotime($d->tanggal_pendaftaran)) : '-' }}
                            </td>
                            <td>{{ $d->nama ?? '-' }}</td>
                            <td class="font-monospace {{ !$isEmpty && $len == 16 && $isValidNumeric ? '' : 'text-danger' }}">
                                {{ $nikDisplay === '' ? '(kosong)' : $nikDisplay }}
                            </td>
                            <td class="text-center">
                                @if($isEmpty || $len < 16)
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-exclamation-triangle"></i> Kurang Digit ({{ $isEmpty ? '0' : $len }}/16)
                                    </span>
                                @elseif($len > 16)
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-exclamation-triangle"></i> Kelebihan Digit ({{ $len }}/16)
                                    </span>
                                @elseif(!$isValidNumeric)
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times-circle"></i> Non-Numerik
                                    </span>
                                @else
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle"></i> Valid
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-database fa-2x mb-2 d-block"></i>
                                Tidak ada data NIK bermasalah
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Informasi Footer -->
            @php $totalData = is_array($data) ? count($data) : $data->count(); @endphp
            @if($totalData > 0)
            <div class="alert alert-secondary mt-3 small">
                <i class="fas fa-chart-bar"></i> <strong>Ringkasan:</strong>
                <ul class="mb-0 mt-1">
                    @php
                        $dataCollection = is_array($data) ? collect($data) : $data;
                        
                        $kurangDigit = $dataCollection->filter(function($item) { 
                            $item = (object) $item;
                            $nik = trim($item->nik ?? '');
                            $len = strlen($nik);
                            $isEmpty = empty($nik) && $nik !== '0';
                            return $isEmpty || ($len < 16);
                        })->count();
                        
                        $lebihDigit = $dataCollection->filter(function($item) { 
                            $item = (object) $item;
                            $len = strlen(trim($item->nik ?? ''));
                            return !empty(trim($item->nik ?? '')) && $len > 16;
                        })->count();
                        
                        $nonNumeric = $dataCollection->filter(function($item) { 
                            $item = (object) $item;
                            $nik = trim($item->nik ?? '');
                            return !empty($nik) && !preg_match('/^\d+$/', $nik);
                        })->count();
                    @endphp
                    
                    @if($kurangDigit > 0)
                    <li><span class="badge bg-warning text-dark">Kurang Digit (≤16):</span> {{ $kurangDigit }} data</li>
                    @endif
                    
                    @if($lebihDigit > 0)
                    <li><span class="badge bg-warning text-dark">Kelebihan Digit (>16):</span> {{ $lebihDigit }} data</li>
                    @endif
                    
                    @if($nonNumeric > 0)
                    <li><span class="badge bg-danger">Non-Numerik:</span> {{ $nonNumeric }} data</li>
                    @endif
                </ul>
            </div>
            @endif
        </div>
    </div>
</div>

@section('scripts')
<script>
    $(document).ready(function() {
        $('#table-detail').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'copy',
                    text: '<i class="fas fa-copy"></i> Copy'
                },
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel'
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Print'
                }
            ],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json',
                buttons: {
                    copyTitle: 'Salin ke Clipboard',
                    copySuccess: {
                        _: '%d baris disalin',
                        1: '1 baris disalin'
                    }
                }
            },
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
            order: [[0, 'asc']],
            columnDefs: [
                { orderable: false, targets: [0, 5] }
            ]
        });
    });
</script>
@endsection

@push('styles')
<style>
    .border-left-info {
        border-left: 4px solid #0dcaf0;
    }
    .font-monospace {
        font-family: 'Courier New', monospace;
        font-size: 0.9rem;
    }
    .table > :not(caption) > * > * {
        vertical-align: middle;
    }
    .btn-sm i, .badge i {
        margin-right: 4px;
    }
    blockquote {
        font-size: 0.85rem;
        color: #555;
        border-left: 3px solid #0dcaf0;
        padding-left: 10px;
    }
</style>
@endpush
@endsection