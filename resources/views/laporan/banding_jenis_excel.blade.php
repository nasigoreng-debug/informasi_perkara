<table>
    <thead>
        <tr>
            <th colspan="5" style="text-align: center; font-weight: bold; font-size: 14pt;">REKAP JENIS PERKARA BANDING</th>
        </tr>
        <tr>
            <th colspan="5" style="text-align: center; font-weight: bold;">PENGADILAN AGAMA SE-JAWA BARAT</th>
        </tr>
        <tr>
            <th colspan="5" style="text-align: center;">PERIODE: {{ date('d-m-Y', strtotime($tgl_awal)) }} S/D {{ date('d-m-Y', strtotime($tgl_akhir)) }}</th>
        </tr>
        <tr></tr>
        <tr>
            <th style="border: 1px solid #000; background-color: #d9d9d9; font-weight: bold; text-align: center; width: 50px;">NO</th>
            <th style="border: 1px solid #000; background-color: #d9d9d9; font-weight: bold; text-align: center; width: 300px;">KATEGORI / JENIS PERKARA</th>
            <th style="border: 1px solid #000; background-color: #d9d9d9; font-weight: bold; text-align: center; width: 100px;">TOTAL</th>
            <th style="border: 1px solid #000; background-color: #d9d9d9; font-weight: bold; text-align: center; width: 100px;">E-COURT</th>
            <th style="border: 1px solid #000; background-color: #d9d9d9; font-weight: bold; text-align: center; width: 100px;">MANUAL</th>
        </tr>
    </thead>
    <tbody>
        @php $no = 1; @endphp
        @foreach($results as $row)
        @if($row->kategori != 'TOTAL SELURUH JENIS PERKARA')
        <tr>
            <td style="border: 1px solid #000; text-align: center;">{{ $no++ }}</td>
            <td style="border: 1px solid #000;">{{ $row->kategori }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $row->total }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $row->ecourt }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $row->manual }}</td>
        </tr>
        @endif
        @endforeach
    </tbody>
    <tfoot>
        @foreach($results as $row)
        @if($row->kategori == 'TOTAL SELURUH JENIS PERKARA')
        <tr>
            <th colspan="2" style="border: 1px solid #000; background-color: #1e293b; color: #ffffff; font-weight: bold; text-align: center;">TOTAL KESELURUHAN</th>
            <th style="border: 1px solid #000; background-color: #1e293b; color: #ffffff; font-weight: bold; text-align: center;">{{ $row->total }}</th>
            <th style="border: 1px solid #000; background-color: #1e293b; color: #ffffff; font-weight: bold; text-align: center;">{{ $row->ecourt }}</th>
            <th style="border: 1px solid #000; background-color: #1e293b; color: #ffffff; font-weight: bold; text-align: center;">{{ $row->manual }}</th>
        </tr>
        @endif
        @endforeach
    </tfoot>
</table>