<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 9pt;
            line-height: 1.5;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            vertical-align: top;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2 style="margin:0;">LAPORAN ARSIP SURAT MASUK</h2>
        <h3 style="margin:0;">PANMUD HUKUM PTA JAWA BARAT</h3>
        <p style="margin:0;">Periode: {{ request('from_date') ?? 'Semua' }} s/d {{ request('to_date') ?? 'Semua' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Indeks</th>
                <th>Nomor Surat</th>
                <th>Tgl Surat</th>
                <th>Asal Surat</th>
                <th>Perihal</th>
                <th>Tgl Masuk Panmud</th>
                <th>Disposisi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data_surat as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ $item->no_indeks }}</td>
                <td>{{ $item->no_surat }}</td>
                <td class="text-center">{{ $item->tgl_surat }}</td>
                <td>{{ $item->asal_surat }}</td>
                <td>{{ $item->perihal }}</td>
                <td class="text-center">{{ $item->tgl_masuk_pan ?? '-' }}</td>
                <td>{{ $item->disposisi ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>