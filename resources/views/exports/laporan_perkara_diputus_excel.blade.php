@php
// Inisialisasi variabel total
$totalSisaLalu = 0;
$totalDiterima = 0;
$totalBeban = 0;
$totalDicabut = 0;
$totalDitolak = 0;
$totalTidakDiterima = 0;
$totalGugur = 0;
$totalDicoret = 0;
$totalJmlPutus = 0;
$totalSisaAkhir = 0;

// Inisialisasi array untuk total per jenis perkara
$totalPerJenis = [];
foreach($jenisPerkara as $key => $label) {
$totalPerJenis[$key] = 0;
}

// Hitung Grand Total
foreach($laporan as $row) {
$totalSisaLalu += (int)$row->sisa_tahun_lalu;
$totalDiterima += (int)$row->diterima;
$totalBeban += (int)$row->beban;
$totalDicabut += (int)$row->dicabut;
$totalDitolak += (int)$row->ditolak;
$totalTidakDiterima += (int)$row->tidak_diterima;
$totalGugur += (int)$row->gugur;
$totalDicoret += (int)$row->dicoret;
$totalJmlPutus += (int)$row->jml;
$totalSisaAkhir += (int)$row->sisa;

foreach($jenisPerkara as $key => $label) {
$totalPerJenis[$key] += (int)($row->$key ?? 0);
}
}
@endphp

<table>
    <thead>
        <tr>
            <th colspan="{{ count($jenisPerkara) + 12 }}" style="text-align: center; font-weight: bold;">LAPORAN KEADAAN PERKARA YANG DIPUTUS (RK4)</th>
        </tr>
        <tr>
            <th colspan="{{ count($jenisPerkara) + 12 }}" style="text-align: center;">Periode: {{ $start }} s/d {{ $end }}</th>
        </tr>
        <tr></tr>
        <tr>
            <th rowspan="2" style="border: 1px solid #000; vertical-align: middle; text-align: center; font-weight: bold;">NO</th>
            <th rowspan="2" style="border: 1px solid #000; vertical-align: middle; text-align: center; font-weight: bold;">NAMA PENGADILAN</th>
            <th rowspan="2" style="border: 1px solid #000; vertical-align: middle; text-align: center; font-weight: bold;">SISA BULAN LALU</th>
            <th rowspan="2" style="border: 1px solid #000; vertical-align: middle; text-align: center; font-weight: bold;">DITERIMA</th>
            <th rowspan="2" style="border: 1px solid #000; vertical-align: middle; text-align: center; font-weight: bold;">BEBAN</th>
            <th rowspan="2" style="border: 1px solid #000; vertical-align: middle; text-align: center; font-weight: bold;">DICABUT</th>
            <th colspan="{{ count($jenisPerkara) }}" style="border: 1px solid #000; text-align: center; font-weight: bold;">DIKABULKAN</th>
            <th rowspan="2" style="border: 1px solid #000; vertical-align: middle; text-align: center; font-weight: bold;">DITOLAK</th>
            <th rowspan="2" style="border: 1px solid #000; vertical-align: middle; text-align: center; font-weight: bold;">TIDAK DITERIMA</th>
            <th rowspan="2" style="border: 1px solid #000; vertical-align: middle; text-align: center; font-weight: bold;">GUGUR</th>
            <th rowspan="2" style="border: 1px solid #000; vertical-align: middle; text-align: center; font-weight: bold;">DICORET</th>
            <th rowspan="2" style="border: 1px solid #000; vertical-align: middle; text-align: center; background-color: #0070c0; color: #ffffff; font-weight: bold;">JUMLAH PUTUS</th>
            <th rowspan="2" style="border: 1px solid #000; vertical-align: middle; text-align: center; background-color: #ff0000; color: #ffffff; font-weight: bold;">SISA AKHIR</th>
        </tr>
        <tr>
            @foreach($jenisPerkara as $label)
            <th style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ $label }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($laporan as $row)
        <tr>
            <td style="border: 1px solid #000; text-align: center;">{{ $loop->iteration }}</td>
            <td style="border: 1px solid #000;">{{ $row->satker }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $row->sisa_tahun_lalu }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $row->diterima }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $row->beban }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $row->dicabut }}</td>
            @foreach($jenisPerkara as $key => $label)
            <td style="border: 1px solid #000; text-align: center;">{{ $row->$key ?? 0 }}</td>
            @endforeach
            <td style="border: 1px solid #000; text-align: center;">{{ $row->ditolak }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $row->tidak_diterima }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $row->gugur }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $row->dicoret }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $row->jml }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $row->sisa }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr style="font-weight: bold; background-color: #f2f2f2;">
            <td colspan="2" style="border: 1px solid #000; text-align: center; font-weight: bold;">JUMLAH TOTAL</td>
            <td style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ $totalSisaLalu }}</td>
            <td style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ $totalDiterima }}</td>
            <td style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ $totalBeban }}</td>
            <td style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ $totalDicabut }}</td>

            @foreach($jenisPerkara as $key => $label)
            <td style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ $totalPerJenis[$key] }}</td>
            @endforeach

            <td style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ $totalDitolak }}</td>
            <td style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ $totalTidakDiterima }}</td>
            <td style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ $totalGugur }}</td>
            <td style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ $totalDicoret }}</td>
            <td style="border: 1px solid #000; text-align: center; font-weight: bold; background-color: #0070c0; color: #ffffff;">{{ $totalJmlPutus }}</td>
            <td style="border: 1px solid #000; text-align: center; font-weight: bold; background-color: #ff0000; color: #ffffff;">{{ $totalSisaAkhir }}</td>
        </tr>
    </tfoot>
</table>