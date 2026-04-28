<table>
    <thead>
        <tr>
            <th colspan="{{ 14 + count($jenisPerkara) }}" style="text-align: center; font-weight: bold;">
                LAPORAN KEADAAN PERKARA YANG DIPUTUS (RK4)
            </th>
        </tr>
        <tr>
            <th colspan="{{ 14 + count($jenisPerkara) }}" style="text-align: center;">
                Periode: {{ \Carbon\Carbon::parse($start)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($end)->format('d/m/Y') }}
            </th>
        </tr>
        <tr></tr>
        <tr style="background-color: #f2f2f2; border: 1px solid #000;">
            <th rowspan="2" style="border: 1px solid #000; vertical-align: middle; text-align: center;">NO</th>
            <th rowspan="2" style="border: 1px solid #000; vertical-align: middle; text-align: center;">NAMA PENGADILAN</th>
            <th rowspan="2" style="border: 1px solid #000; vertical-align: middle; text-align: center;">SISA BULAN LALU</th>
            <th rowspan="2" style="border: 1px solid #000; vertical-align: middle; text-align: center;">DITERIMA BULAN INI</th>
            <th rowspan="2" style="border: 1px solid #000; vertical-align: middle; text-align: center; background-color: #fffde7;">JUMLAH (BEBAN)</th>
            <th rowspan="2" style="border: 1px solid #000; vertical-align: middle; text-align: center;">DICABUT</th>
            <th rowspan="2" style="border: 1px solid #000; vertical-align: middle; text-align: center;">DISMISSAL</th>
            <th colspan="{{ count($jenisPerkara) }}" style="border: 1px solid #000; vertical-align: middle; text-align: center;">DIKABULKAN (PER JENIS PERKARA)</th>
            <th rowspan="2" style="border: 1px solid #000; vertical-align: middle; text-align: center;">DITOLAK</th>
            <th rowspan="2" style="border: 1px solid #000; vertical-align: middle; text-align: center;">TIDAK DITERIMA (NO)</th>
            <th rowspan="2" style="border: 1px solid #000; vertical-align: middle; text-align: center;">GUGUR</th>
            <th rowspan="2" style="border: 1px solid #000; vertical-align: middle; text-align: center;">DIGUGURKAN</th>
            <th rowspan="2" style="border: 1px solid #000; vertical-align: middle; text-align: center;">DICORET DARI REGISTER</th>
            <th rowspan="2" style="border: 1px solid #000; vertical-align: middle; text-align: center; background-color: #007bff; color: #ffffff;">JUMLAH PUTUS</th>
            <th rowspan="2" style="border: 1px solid #000; vertical-align: middle; text-align: center; background-color: #dc3545; color: #ffffff;">SISA AKHIR</th>
        </tr>
        <tr style="background-color: #f2f2f2;">
            @foreach($jenisPerkara as $alias => $label)
            <th style="border: 1px solid #000; text-align: center;">{{ $label }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @php
        $t_sisa_lalu = 0; $t_terima = 0; $t_beban = 0; $t_cabut = 0; $t_dismissal = 0;
        $t_kabul = array_fill_keys(array_keys($jenisPerkara), 0);
        $t_tolak = 0; $t_no = 0; $t_gugur = 0; $t_digugurkan = 0; $t_coret = 0; $t_damai = 0; $t_putus = 0; $t_akhir = 0;
        $no = 1;
        @endphp

        @foreach($laporan as $row)
        <tr>
            <td style="border: 1px solid #000; text-align: center;">{{ $no++ }}</td>
            <td style="border: 1px solid #000;">{{ $row->satker }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $row->sisa_tahun_lalu }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $row->diterima }}</td>
            <td style="border: 1px solid #000; text-align: center; background-color: #fffde7;">{{ $row->beban }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $row->dicabut }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $row->dismissal }}</td>

            @foreach($jenisPerkara as $key => $label)
            @php $t_kabul[$key] += ($row->$key ?? 0); @endphp
            <td style="border: 1px solid #000; text-align: center;">{{ $row->$key ?? 0 }}</td>
            @endforeach

            <td style="border: 1px solid #000; text-align: center;">{{ $row->ditolak }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $row->tidak_diterima }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $row->gugur }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $row->digugurkan }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $row->dicoret }}</td>
            <td style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ $row->jml }}</td>
            <td style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ $row->sisa }}</td>
        </tr>
        @php
        $t_sisa_lalu += $row->sisa_tahun_lalu;
        $t_terima += $row->diterima;
        $t_beban += $row->beban;
        $t_cabut += $row->dicabut;
        $t_dismissal += $row->dismissal;
        $t_tolak += $row->ditolak;
        $t_no += $row->tidak_diterima;
        $t_gugur += $row->gugur;
        $t_digugurkan += $row->digugurkan;
        $t_coret += $row->dicoret;
        $t_damai += $row->perdamaian;
        $t_putus += $row->jml;
        $t_akhir += $row->sisa;
        @endphp
        @endforeach
    </tbody>
    <tfoot>
        <tr style="font-weight: bold; background-color: #f2f2f2;">
            <td colspan="2" style="border: 1px solid #000; text-align: center;">TOTAL</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $t_sisa_lalu }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $t_terima }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $t_beban }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $t_cabut }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $t_dismissal }}</td>
            @foreach($jenisPerkara as $key => $label)
            <td style="border: 1px solid #000; text-align: center;">{{ $t_kabul[$key] }}</td>
            @endforeach
            <td style="border: 1px solid #000; text-align: center;">{{ $t_tolak }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $t_no }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $t_gugur }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $t_digugurkan }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $t_coret }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $t_putus }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $t_akhir }}</td>
        </tr>
    </tfoot>
</table>