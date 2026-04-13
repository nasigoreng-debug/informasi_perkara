<table border="1">
    <thead>
        <tr>
            <th colspan="13" style="font-weight:bold; font-size:14pt;">REKAPITULASI MEDIASI SE-JAWA BARAT</th>
        </tr>
        <tr>
            <th colspan="13">Periode: {{ $tgl_awal }} s/d {{ $tgl_akhir }}</th>
        </tr>
        <tr>
            <th>No</th>
            <th>Satuan Kerja</th>
            <th>Sisa Lalu</th>
            <th>Masuk</th>
            <th>Jumlah</th>
            <th>Akta</th>
            <th>Sebagian</th>
            <th>Cabut</th>
            <th>Total Berhasil</th>
            <th>Tdk Berhasil</th>
            <th>Tdk Dapat</th>
            <th>Berjalan</th>
            <th>%</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reports as $i => $row)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $row->SATKER }}</td>
            @if(isset($row->error))
            <td colspan="11">{{ $row->error }}</td>
            @else
            <td>{{ $row->sisa_lalu }}</td>
            <td>{{ $row->masuk }}</td>
            <td>{{ $row->jml_dimediasi }}</td>
            <td>{{ $row->b_akta }}</td>
            <td>{{ $row->b_sebagian }}</td>
            <td>{{ $row->b_cabut }}</td>
            <td>{{ $row->total_berhasil }}</td>
            <td>{{ $row->t_berhasil }}</td>
            <td>{{ $row->t_dapat }}</td>
            <td>{{ $row->sisa_akhir }}</td>
            <td>{{ number_format($row->persentase, 2) }}%</td>
            @endif
        </tr>
        @endforeach
    </tbody>
</table>