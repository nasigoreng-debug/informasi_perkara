@php
$totalPerJenis = [];
$grandTotalBaris = 0;
foreach($jenisPerkara as $key => $label) {
$totalPerJenis[$key] = 0;
}

foreach($laporan as $row) {
// AMBIL LANGSUNG DARI total_baris (Angka Fisik DB)
$grandTotalBaris += (int)$row->total_baris;

foreach($jenisPerkara as $key => $label) {
$totalPerJenis[$key] += (int)($row->$key ?? 0);
}
}
@endphp

<table>
    <thead>
        <tr>
            <th colspan="{{ count($jenisPerkara) + 2 }}" style="text-align: center; font-weight: bold;">LAPORAN JENIS PERKARA YANG DITERIMA (RK3)</th>
        </tr>
        <tr>
            <th colspan="{{ count($jenisPerkara) + 2 }}" style="text-align: center;">Periode: {{ $start }} s/d {{ $end }}</th>
        </tr>
        <tr></tr>
        <tr>
            <th style="border: 1px solid #000; text-align: center; font-weight: bold; background-color: #f2f2f2;">NO</th>
            <th style="border: 1px solid #000; text-align: center; font-weight: bold; background-color: #f2f2f2;">NAMA PENGADILAN</th>
            @foreach($jenisPerkara as $label)
            <th style="border: 1px solid #000; text-align: center; font-weight: bold; background-color: #f2f2f2;">{{ $label }}</th>
            @endforeach
            <th style="border: 1px solid #000; text-align: center; font-weight: bold; background-color: #0070c0; color: #ffffff;">JUMLAH</th>
        </tr>
    </thead>
    <tbody>
        @foreach($laporan as $row)
        <tr>
            <td style="border: 1px solid #000; text-align: center;">{{ $loop->iteration }}</td>
            <td style="border: 1px solid #000;">{{ $row->satker }}</td>
            @foreach($jenisPerkara as $key => $label)
            <td style="border: 1px solid #000; text-align: center;">{{ $row->$key ?? 0 }}</td>
            @endforeach
            <td style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ $row->total_baris }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr style="font-weight: bold;">
            <td colspan="2" style="border: 1px solid #000; text-align: center; background-color: #f2f2f2;">JUMLAH TOTAL</td>
            @foreach($jenisPerkara as $key => $label)
            <td style="border: 1px solid #000; text-align: center; background-color: #f2f2f2;">{{ $totalPerJenis[$key] }}</td>
            @endforeach
            <td style="border: 1px solid #000; text-align: center; background-color: #0070c0; color: #ffffff;">{{ $grandTotalBaris }}</td>
        </tr>
    </tfoot>
</table>