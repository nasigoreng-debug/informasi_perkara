<table>
    <thead>
        <tr>
            <th colspan="35" style="text-align: center; font-weight: bold; font-size: 14pt;">LAPORAN PERKARA BANDING DITERIMA (RK.1)</th>
        </tr>
        <tr>
            <th colspan="35" style="text-align: center; font-weight: bold;">PENGADILAN AGAMA SE-JAWA BARAT</th>
        </tr>
        <tr>
            <th colspan="35" style="text-align: center;">
                PERIODE: {{ date('d-m-Y', strtotime($tgl_awal ?? date('Y-m-d'))) }} S/D {{ date('d-m-Y', strtotime($tgl_akhir ?? date('Y-m-d'))) }}
            </th>
        </tr>
        <tr></tr>
        @php
        $jenisPerkara = [
        'iz'=>'Izin Poligami','pp'=>'Pencegahan Perkawinan','p_ppn'=>'Penolakan PPN','pb'=>'Pembatalan Perkawinan',
        'lks'=>'Kelalaian Kewajiban','ct'=>'Cerai Talak','cg'=>'Cerai Gugat','hb'=>'Harta Bersama','pa'=>'Penguasaan Anak',
        'nai'=>'Nafkah Anak','hbi'=>'Hak Bekas Isteri','psa'=>'Pengesahan Anak','pkot'=>'Cabut Kuasa Ortu',
        'pw'=>'Perwalian','phw'=>'Cabut Kuasa Wali','pol'=>'Penunjukan Wali','grw'=>'Ganti Rugi Wali',
        'aua'=>'Asal Usul Anak','pkc'=>'Tolak Kawin Campur','isbath'=>'Isbath Nikah','ik'=>'Izin Kawin',
        'dk'=>'Dispensasi Kawin','wa'=>'Wali Adhol','es'=>'Ekonomi Syari','kw'=>'Kewarisan','wst'=>'Wasiat',
        'hb_h'=>'Hibah','wkf'=>'Wakaf','zkt_infq'=>'Zakat/Infaq','p3hp'=>'P3HP/Ahli Waris','ll'=>'Lain-lain'
        ];
        @endphp
        <tr>
            <th style="border: 1px solid #000; background-color: #d9d9d9; font-weight: bold;">NO</th>
            <th style="border: 1px solid #000; background-color: #d9d9d9; font-weight: bold;">PENGADILAN AGAMA</th>
            @foreach($jenisPerkara as $label)
            <th style="border: 1px solid #000; background-color: #d9d9d9; font-weight: bold;">{{ $label }}</th>
            @endforeach
            <th style="border: 1px solid #000; background-color: #4f81bd; color: #ffffff; font-weight: bold;">JUMLAH</th>
        </tr>
    </thead>
    <tbody>
        @foreach($results as $index => $row)
        <tr>
            <td style="border: 1px solid #000; text-align: center;">{{ $row->satker == 'JUMLAH KESELURUHAN' ? '' : $loop->iteration }}</td>
            <td style="border: 1px solid #000;">{{ $row->satker }}</td>
            @foreach(array_keys($jenisPerkara) as $key)
            {{-- PENGAMAN UTAMA: Pakai ?? 0 supaya kalau kolom kosong ngga error --}}
            <td style="border: 1px solid #000; text-align: center;">{{ $row->$key ?? 0 }}</td>
            @endforeach
            <td style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ $row->jml ?? 0 }}</td>
        </tr>
        @endforeach
    </tbody>
</table>