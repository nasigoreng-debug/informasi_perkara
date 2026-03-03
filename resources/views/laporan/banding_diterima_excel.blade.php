<table>
    <thead>
        <tr>
            <th colspan="35" style="text-align: center; font-weight: bold; font-size: 14pt;">LAPORAN PERKARA BANDING DITERIMA (RK.1)</th>
        </tr>
        <tr>
            <th colspan="35" style="text-align: center; font-weight: bold;">PENGADILAN AGAMA SE-JAWA BARAT</th>
        </tr>
        <tr>
            <th colspan="35" style="text-align: center;">PERIODE: {{ date('d-m-Y', strtotime($tgl_awal)) }} S/D {{ date('d-m-Y', strtotime($tgl_akhir)) }}</th>
        </tr>
        <tr></tr>
        <tr height="40">
            <th rowspan="2" style="border: 2px solid #000; background-color: #d9d9d9; text-align: center; vertical-align: middle; width: 30px;">NO</th>
            <th rowspan="2" style="border: 2px solid #000; background-color: #d9d9d9; text-align: center; vertical-align: middle; width: 250px;">PENGADILAN AGAMA</th>
            <th colspan="23" style="border: 2px solid #000; background-color: #d9d9d9; text-align: center; vertical-align: middle;">A. PERKAWINAN</th>
            <th rowspan="2" style="border: 2px solid #000; background-color: #d9d9d9; text-align: center; vertical-align: middle;">B. EKONOMI SYARIAH</th>
            <th rowspan="2" style="border: 2px solid #000; background-color: #d9d9d9; text-align: center; vertical-align: middle;">C. KEWARISAN</th>
            <th rowspan="2" style="border: 2px solid #000; background-color: #d9d9d9; text-align: center; vertical-align: middle;">D. WASIAT</th>
            <th rowspan="2" style="border: 2px solid #000; background-color: #d9d9d9; text-align: center; vertical-align: middle;">E. HIBAH</th>
            <th rowspan="2" style="border: 2px solid #000; background-color: #d9d9d9; text-align: center; vertical-align: middle;">F. WAKAF</th>
            <th rowspan="2" style="border: 2px solid #000; background-color: #d9d9d9; text-align: center; vertical-align: middle;">G. ZAKAT/INFAQ</th>
            <th rowspan="2" style="border: 2px solid #000; background-color: #d9d9d9; text-align: center; vertical-align: middle;">H. P3HP/AHLI WARIS</th>
            <th rowspan="2" style="border: 2px solid #000; background-color: #d9d9d9; text-align: center; vertical-align: middle;">I. LAIN-LAIN</th>
            <th rowspan="2" style="border: 2px solid #000; background-color: #4f81bd; color: #ffffff; text-align: center; vertical-align: middle;">JUMLAH</th>
        </tr>

        <tr height="180">
            @php
            $sub = ['Izin Poligami','Pencegahan Perkawinan','Penolakan Perkawinan oleh PPN','Pembatalan Perkawinan','Kelalaian Kewajiban Suami/Isteri','Cerai Talak','Cerai Gugat','Harta Bersama','Penguasaan Anak','Nafkah Anak oleh Ibu','Hak-hak Bekas Isteri','Pengesahan/Pengangkatan Anak','Pencabutan Kekuasaan Orang Tua','Perwalian','Pencabutan Kekuasaan Wali','Penunjukan Orang Lain Sebagai Wali','Ganti Rugi Terhadap Wali','Asal Usul Anak','Penolakan Kawin Campuran','Isbath Nikah','Izin Kawin','Dispensasi Kawin','Wali Adhol'];
            @endphp
            @foreach($sub as $s) <th style="border: 2px solid #000; text-align: center; vertical-align: bottom;">{{$s}}</th> @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($results as $index => $row)
        @php $isT = ($row->satker == 'JUMLAH KESELURUHAN'); @endphp
        <tr>
            <td style="border: 1px solid #000; text-align: center;">{{ $isT ? '' : $index+1 }}</td>
            <td style="border: 1px solid #000; font-weight: {{ $isT ? 'bold' : 'normal' }}; background-color: {{ $isT ? '#f2f2f2' : '#ffffff' }};">{{ $row->satker }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{$row->iz}}</td>
            <td style="border: 1px solid #000; text-align: center;">{{$row->pp}}</td>
            <td style="border: 1px solid #000; text-align: center;">{{$row->p_ppn}}</td>
            <td style="border: 1px solid #000; text-align: center;">{{$row->pb}}</td>
            <td style="border: 1px solid #000; text-align: center;">{{$row->lks}}</td>
            <td style="border: 1px solid #000; text-align: center;">{{$row->ct}}</td>
            <td style="border: 1px solid #000; text-align: center;">{{$row->cg}}</td>
            <td style="border: 1px solid #000; text-align: center;">{{$row->hb}}</td>
            <td style="border: 1px solid #000; text-align: center;">{{$row->pa}}</td>
            <td style="border: 1px solid #000; text-align: center;">{{$row->nai}}</td>
            <td style="border: 1px solid #000; text-align: center;">{{$row->hbi}}</td>
            <td style="border: 1px solid #000; text-align: center;">{{$row->psa}}</td>
            <td style="border: 1px solid #000; text-align: center;">{{$row->pkot}}</td>
            <td style="border: 1px solid #000; text-align: center;">{{$row->pw}}</td>
            <td style="border: 1px solid #000; text-align: center;">{{$row->phw}}</td>
            <td style="border: 1px solid #000; text-align: center;">{{$row->pol}}</td>
            <td style="border: 1px solid #000; text-align: center;">{{$row->grw}}</td>
            <td style="border: 1px solid #000; text-align: center;">{{$row->aua}}</td>
            <td style="border: 1px solid #000; text-align: center;">{{$row->pkc}}</td>
            <td style="border: 1px solid #000; text-align: center;">{{$row->isbath}}</td>
            <td style="border: 1px solid #000; text-align: center;">{{$row->ik}}</td>
            <td style="border: 1px solid #000; text-align: center;">{{$row->dk}}</td>
            <td style="border: 1px solid #000; text-align: center;">{{$row->wa}}</td>
            <td style="border: 1px solid #000; text-align: center;">{{$row->es}}</td>
            <td style="border: 1px solid #000; text-align: center;">{{$row->kw}}</td>
            <td style="border: 1px solid #000; text-align: center;">{{$row->wst}}</td>
            <td style="border: 1px solid #000; text-align: center;">{{$row->hb_h}}</td>
            <td style="border: 1px solid #000; text-align: center;">{{$row->wkf}}</td>
            <td style="border: 1px solid #000; text-align: center;">{{$row->zkt+$row->infq}}</td>
            <td style="border: 1px solid #000; text-align: center;">{{$row->p3hp}}</td>
            <td style="border: 1px solid #000; text-align: center;">{{$row->ll}}</td>
            <td style="border: 1px solid #000; text-align: center; font-weight: bold; background-color: #d9e1f2;">{{ $row->jml }}</td>
        </tr>
        @endforeach
    </tbody>
</table>