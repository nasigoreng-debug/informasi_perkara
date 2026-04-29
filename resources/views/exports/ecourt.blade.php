<table>
    <thead>
        <tr>
            <th colspan="6" style="font-weight: bold; text-align: center; font-size: 14pt;">
                LAPORAN MONITORING PENYELESAIAN PERKARA MELALUI E-COURT
            </th>
        </tr>
        <tr>
            <th colspan="6" style="text-align: center;">
                Periode: {{ \Carbon\Carbon::parse($tgl_awal)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($tgl_akhir)->format('d/m/Y') }}
            </th>
        </tr>
        <tr></tr> <!-- Baris Kosong -->
        <tr style="background-color: #f5f7fa; font-weight: bold; text-align: center; border: 1px solid #000000;">
            <th width="5">NO</th>
            <th width="30">SATUAN KERJA</th>
            <th width="20">TOTAL DITERIMA</th>
            <th width="15">E-COURT</th>
            <th width="15">NON-E-COURT</th>
            <th width="15">PERSENTASE</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reports as $index => $item)
        <tr>
            <td align="center">{{ $index + 1 }}</td>
            <td>{{ $item->SATKER ?? '-' }}</td>
            <td align="center">{{ $item->total ?? 0 }}</td>
            <td align="center">{{ $item->ecourt ?? 0 }}</td>
            <td align="center">{{ $item->non_ecourt ?? 0 }}</td>
            <td align="right">{{ number_format($item->persentase ?? 0, 2, ',', '.') }}%</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr style="font-weight: bold; background-color: #f0f0f0;">
            <td colspan="2" align="right">TOTAL KESELURUHAN</td>
            <td align="center">{{ collect($reports)->sum('total') }}</td>
            <td align="center">{{ collect($reports)->sum('ecourt') }}</td>
            <td align="center">{{ collect($reports)->sum('non_ecourt') }}</td>
            <td align="right">
                @php
                $reportsCol = collect($reports);
                $total_t = $reportsCol->sum('total');
                $total_e = $reportsCol->sum('ecourt');
                $avg = $total_t > 0 ? ($total_e / $total_t) * 100 : 0;
                @endphp
                {{ number_format($avg, 2, ',', '.') }}%
            </td>
        </tr>
    </tfoot>
</table>