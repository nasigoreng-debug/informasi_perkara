@extends('layouts.app')

@section('content')
<style>
    .table-rk3 {
        font-size: 9px;
        border: 1px solid #000;
        font-family: 'Arial Narrow', Arial, sans-serif;
    }

    .table-rk3 th,
    .table-rk3 td {
        border: 1px solid #000 !important;
        padding: 3px 1px !important;
        vertical-align: middle;
        text-align: center;
    }

    .v-text {
        writing-mode: vertical-rl;
        transform: rotate(180deg);
        white-space: nowrap;
        height: 150px;
        font-weight: bold;
        line-height: 1.1;
    }

    .header-gray {
        background: #f2f2f2;
        font-weight: bold;
    }

    .bg-gray-dark {
        background: #d9d9d9;
    }

    .th-fixed {
        min-width: 25px;
    }
</style>

<div class="container-fluid py-3">
    {{-- HEADER --}}
    <div class="text-center mb-4">
        <h6 class="mb-0 fw-bold">LAPORAN PERKARA YANG DITERIMA</h6>
        <h6 class="mb-0 fw-bold">PADA PENGADILAN AGAMA WILAYAH PENGADILAN TINGGI AGAMA JAWA BARAT</h6>
        <h6 class="mb-0 fw-bold text-uppercase">{{ $periode ?? '' }}</h6>
    </div>

    {{-- FILTER --}}
    <div class="card mb-3 border-0 shadow-sm bg-light d-print-none">
        <div class="card-body py-2">
            <form action="{{ route('laporan.diterima.index') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="small fw-bold">TAHUN</label>
                    <select name="tahun" class="form-select form-select-sm">
                        @for($t = date('Y'); $t >= 2020; $t--)
                        <option value="{{ $t }}" {{ ($tahun ?? date('Y')) == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="small fw-bold">BULAN</label>
                    <select name="bulan" class="form-select form-select-sm" id="bulan">
                        <option value="">-- Pilih Bulan --</option>
                        @foreach([1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'] as $n => $b)
                        <option value="{{ $n }}" {{ ($bulan ?? '') == $n ? 'selected' : '' }}>{{ $b }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="small fw-bold">TRIWULAN</label>
                    <select name="triwulan" class="form-select form-select-sm" id="triwulan">
                        <option value="">-- Pilih Triwulan --</option>
                        <option value="1" {{ ($triwulan ?? '') == 1 ? 'selected' : '' }}>Triwulan I (Jan-Mar)</option>
                        <option value="2" {{ ($triwulan ?? '') == 2 ? 'selected' : '' }}>Triwulan II (Apr-Jun)</option>
                        <option value="3" {{ ($triwulan ?? '') == 3 ? 'selected' : '' }}>Triwulan III (Jul-Sep)</option>
                        <option value="4" {{ ($triwulan ?? '') == 4 ? 'selected' : '' }}>Triwulan IV (Okt-Des)</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-dark btn-sm w-100">FILTER</button>
                </div>

                <div class="col-md-2">
                    <a href="{{ route('laporan.diterima.export') }}?{{ http_build_query(request()->all()) }}"
                        class="btn btn-success btn-sm w-100">
                        <i class="fas fa-file-excel me-1"></i> EXPORT
                    </a>
                </div>

                <div class="col-md-2">
                    <a href="{{ route('laporan.diterima.index') }}" class="btn btn-secondary btn-sm w-100">
                        <i class="fas fa-sync-alt me-1"></i> RESET
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="table-responsive">
        <table class="table table-rk3">
            <thead>
                {{-- Header Utama --}}
                <tr class="header-gray">
                    <th rowspan="2" style="width: 30px;">NO</th>
                    <th rowspan="2" style="min-width: 150px;">PENGADILAN AGAMA</th>
                    <th colspan="23">A. PERKAWINAN</th>
                    <th rowspan="2" class="v-text th-fixed">B. Kewarisan</th>
                    <th rowspan="2" class="v-text th-fixed">C. Wasiat</th>
                    <th rowspan="2" class="v-text th-fixed">D. Hibah</th>
                    <th rowspan="2" class="v-text th-fixed">E. Wakaf</th>
                    <th rowspan="2" class="v-text th-fixed">F. Lain-lain</th>
                    <th rowspan="2" class="v-text th-fixed">G. Ekonomi Syari'ah</th>
                    <th rowspan="2" class="v-text th-fixed">H. P3HP/Penetapan Ahli Waris</th>
                    <th rowspan="2" class="v-text th-fixed">I. Pengampuan</th>
                    <th rowspan="2" class="v-text th-fixed">J. Perkawinan Campuran</th>
                    <th rowspan="2" style="min-width: 50px;">JUMLAH</th>
                </tr>

                {{-- Header Detail Perkawinan --}}
                <tr class="header-gray">
                    <th class="v-text th-fixed">1. Izin Poligami</th>
                    <th class="v-text th-fixed">2. Pencegahan Perkawinan</th>
                    <th class="v-text th-fixed">3. Penolakan Perkawinan</th>
                    <th class="v-text th-fixed">4. Pembatalan Perkawinan</th>
                    <th class="v-text th-fixed">5. Kelalaian Kewajiban Suami/Istri</th>
                    <th class="v-text th-fixed">6. Cerai Talak</th>
                    <th class="v-text th-fixed">7. Cerai Gugat</th>
                    <th class="v-text th-fixed">8. Harta Bersama</th>
                    <th class="v-text th-fixed">9. Penguasaan Anak/Hadhanah</th>
                    <th class="v-text th-fixed">10. Nafkah Anak oleh Ibu</th>
                    <th class="v-text th-fixed">11. Hak-hak bekas istri</th>
                    <th class="v-text th-fixed">12. Pengesahan Anak</th>
                    <th class="v-text th-fixed">13. Pencabutan Kekuasaan Orang Tua</th>
                    <th class="v-text th-fixed">14. Perwalian</th>
                    <th class="v-text th-fixed">15. Pencabutan Kekuasaan Wali</th>
                    <th class="v-text th-fixed">16. Penunjukan orang lain sebagai Wali</th>
                    <th class="v-text th-fixed">17. Ganti Rugi terhadap Wali</th>
                    <th class="v-text th-fixed">18. Asal Usul Anak</th>
                    <th class="v-text th-fixed">19. Penolakan Perkawinan oleh PPN</th>
                    <th class="v-text th-fixed">20. Itsbat Nikah</th>
                    <th class="v-text th-fixed">21. Izin Kawin</th>
                    <th class="v-text th-fixed">22. Dispensasi Kawin</th>
                    <th class="v-text th-fixed">23. Wali Adhol</th>
                </tr>

                {{-- Baris Nomor Kolom --}}
                <tr class="bg-gray-dark fw-bold">
                    <td>1</td>
                    <td>2</td>
                    @for($i = 3; $i <= 35; $i++)
                        <td>{{ $i }}</td>
                        @endfor
                </tr>
            </thead>

            <tbody>
                @php
                // Mapping field sesuai dengan controller
                $kolomPerkawinan = [
                'izin_poligami',
                'pencegahan_perkawinan',
                'penolakan_perkawinan',
                'pembatalan_perkawinan',
                'kelalaian_kewajiban',
                'cerai_talak',
                'cerai_gugat',
                'harta_bersama',
                'penguasaan_anak',
                'nafkah_anak',
                'hak_bekas_istri',
                'asal_usul_anak',
                'pencabutan_kuasa_ortu',
                'perwalian',
                'pencabutan_wali',
                'penunjukan_wali',
                'ganti_rugi_wali',
                'asal_usul_anak', // Untuk kolom 20 (Pengesahan Anak)
                'penolakan_ppn',
                'istbat_nikah',
                'izin_kawin',
                'dispensasi_kawin',
                'wali_adhol'
                ];

                $kolomLain = [
                'kewarisan',
                'wasiat',
                'hibah',
                'wakaf',
                'lain_lain',
                'ekonomi_syariah',
                'p3hp',
                'pengampuan',
                'perkawinan_campuran'
                ];

                // Filter data tanpa baris total
                $dataLaporan = collect($laporan ?? [])->filter(function($item) {
                return $item->satker !== 'JUMLAH KESELURUHAN';
                })->values();

                // Inisialisasi array total
                $totals = [
                'perkawinan' => array_fill(0, 23, 0),
                'lain' => array_fill(0, 9, 0),
                'jumlah' => 0
                ];
                @endphp

                @forelse($dataLaporan as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-start px-2">{{ $row->satker ?? '-' }}</td>

                    {{-- Kolom Perkawinan (23 kolom) --}}
                    @foreach($kolomPerkawinan as $pos => $field)
                    @php
                    $nilai = $row->$field ?? 0;
                    $totals['perkawinan'][$pos] += $nilai;
                    @endphp
                    <td>{{ number_format($nilai, 0, ',', '.') }}</td>
                    @endforeach

                    {{-- Kolom Lainnya (9 kolom) --}}
                    @foreach($kolomLain as $pos => $field)
                    @php
                    $nilai = $row->$field ?? 0;
                    $totals['lain'][$pos] += $nilai;
                    @endphp
                    <td>{{ number_format($nilai, 0, ',', '.') }}</td>
                    @endforeach

                    {{-- Kolom Jumlah --}}
                    @php
                    $totalRow = $row->jml ?? 0;
                    $totals['jumlah'] += $totalRow;
                    @endphp
                    <td class="fw-bold">{{ number_format($totalRow, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="39" class="text-center py-4">
                        <i class="fas fa-database fa-2x mb-2"></i><br>
                        Data tidak ditemukan
                    </td>
                </tr>
                @endforelse
            </tbody>

            @if(isset($totalKeseluruhan) && $dataLaporan->isNotEmpty())
            <tfoot class="header-gray fw-bold">
                <tr>
                    <td colspan="2" class="text-end">JUMLAH KESELURUHAN</td>

                    {{-- Total Perkawinan --}}
                    @foreach($totals['perkawinan'] as $total)
                    <td>{{ number_format($total, 0, ',', '.') }}</td>
                    @endforeach

                    {{-- Total Lainnya --}}
                    @foreach($totals['lain'] as $total)
                    <td>{{ number_format($total, 0, ',', '.') }}</td>
                    @endforeach

                    {{-- Total Jumlah --}}
                    <td>{{ number_format($totals['jumlah'], 0, ',', '.') }}</td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>

    {{-- INFO FOOTER --}}
    <div class="mt-2 small text-muted">
        <i class="fas fa-info-circle me-1"></i>
        Total satker: {{ $dataLaporan->count() }} |
        Total perkara: {{ number_format($totals['jumlah'] ?? 0, 0, ',', '.') }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto clear bulan/triwulan
    document.getElementById('bulan')?.addEventListener('change', function() {
        if (this.value) {
            document.getElementById('triwulan').value = '';
        }
    });

    document.getElementById('triwulan')?.addEventListener('change', function() {
        if (this.value) {
            document.getElementById('bulan').value = '';
        }
    });
</script>
@endpush