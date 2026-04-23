<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIAPPTA COMMAND CENTER 16:9</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --glass: rgba(255, 255, 255, 0.95);
            --glass-border: rgba(255, 255, 255, 0.5);
            --primary-grad: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #f1f5f9 0%, #cbd5e1 100%);
            height: 100vh;
            width: 100vw;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .header-nav {
            height: 12vh;
            padding: 0 4vw;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(15px);
            border-bottom: 2px solid #3b82f6;
        }

        .main-content {
            height: 82vh;
            padding: 2vh 2vw;
            display: flex;
            flex-direction: column;
            gap: 2vh;
        }

        .row-top {
            height: 22vh;
        }

        .glass-card {
            background: var(--glass);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            border: 1px solid var(--glass-border);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 1vh 1vw;
            text-align: center;
        }

        .label-sub {
            font-size: 0.8rem;
            font-weight: 800;
            color: #64748b;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .value-hero {
            font-size: 4rem;
            font-weight: 800;
            line-height: 1;
            margin: 0.5vh 0;
            letter-spacing: -2px;
        }

        .row-bottom {
            flex: 1;
            min-height: 0;
        }

        .box-title {
            font-size: 1.1rem;
            font-weight: 800;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 1vh;
            margin-bottom: 1.5vh;
            display: flex;
            align-items: center;
            color: #1e293b;
        }

        .table-container {
            height: 42vh;
            overflow: hidden;
        }

        .table-modern {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 8px;
        }

        .table-modern tr {
            background: #f8fafc;
            transition: 0.3s;
        }

        .table-modern td {
            padding: 1.5vh 15px;
            font-size: 1.2rem;
            font-weight: 700;
            color: #334155;
            border-radius: 10px;
        }

        .table-modern td:last-child {
            text-align: right;
            color: #2563eb;
        }

        .status-item {
            padding: 1.2vh 1.5vw;
            border-radius: 18px;
            margin-bottom: 0.8vh;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
            border: 1px solid #e2e8f0;
            height: 7vh;
        }

        #clock {
            font-size: 3rem;
            font-weight: 800;
            color: #1e3a8a;
            line-height: 1;
        }

        .durasi-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5vh;
            padding: 0.5vh 10px;
            border-radius: 10px;
        }

        .footer-nav {
            height: 6vh;
            padding: 0 4vw;
            background: #1e293b;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-weight: 700;
            color: #cbd5e1;
            font-size: 0.85rem;
        }
    </style>
</head>

<body>

    <div class="header-nav">
        <div class="d-flex align-items-center">
            <div class="me-3" style="width: 60px; height: 60px; background: var(--primary-grad); border-radius: 18px; display: flex; align-items: center; justify-content: center; color: white; font-size: 28px; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);">
                <i class="fas fa-chart-line"></i>
            </div>
            <div>
                <h2 class="fw-800 mb-0" style="letter-spacing: -1px; color: #1e293b;">SIAPPTA <span style="color: #2563eb;">COMMAND CENTER</span></h2>
                <div class="small fw-800 text-muted text-uppercase" style="font-size: 0.8rem;">PENGADILAN TINGGI AGAMA BANDUNG — PROVINSI JAWA BARAT</div>
            </div>
        </div>
        <div class="text-end">
            <div id="clock">00:00:00</div>
            <div class="small fw-800 text-primary" style="font-size: 0.8rem;"><i class="fas fa-circle-dot fa-fade me-1 text-danger"></i> REAL-TIME MONITORING</div>
        </div>
    </div>

    <div class="main-content">
        <div class="row g-3 row-top">
            @php
            $cards = [
            ['l' => 'SISA LALU', 'v' => $cardData->sisa_lalu, 'c' => '#64748b'],
            ['l' => 'DITERIMA', 'v' => $cardData->diterima, 'c' => '#0284c7'],
            ['l' => 'BEBAN PERKARA', 'v' => $beban, 'c' => '#4f46e5'],
            ['l' => 'PUTUSAN SELA', 'v' => $putusanSela, 'c' => '#dc2626'],
            ['l' => 'PUTUS', 'v' => $cardData->selesai, 'c' => '#059669'],
            ['l' => 'SISA AKHIR', 'v' => $cardData->sisa, 'c' => '#d97706'],
            ];
            @endphp
            @foreach($cards as $c)
            <div class="col">
                <div class="glass-card shadow-lg">
                    <div class="label-sub">{{ $c['l'] }}</div>
                    <div class="value-hero" style="color: {{ $c['c'] }};">{{ number_format($c['v'] ?? 0) }}</div>
                    <div style="height: 6px; background: #f1f5f9; border-radius: 10px; margin-top: 5px; overflow: hidden;">
                        <div style="height: 100%; width: 100%; background: {{ $c['c'] }};"></div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="row g-3 row-bottom">
            <div class="col-md-4">
                <div class="d-flex flex-column h-100 gap-3">
                    <div class="glass-card flex-grow-1" style="text-align: left; padding: 2vh;">
                        <div class="box-title"><i class="fas fa-balance-scale me-2"></i> STATUS PUTUSAN</div>
                        @php
                        $amars = [
                        ['l' => 'Dikuatkan', 'v' => $jenisPutus->dikuatkan, 'c' => '#2563eb'],
                        ['l' => 'Dibatalkan', 'v' => $jenisPutus->dibatalkan, 'c' => '#ef4444'],
                        ['l' => 'Tidak dapat diterima', 'v' => $jenisPutus->n_o, 'c' => '#06b6d4'],
                        ['l' => 'Dicabut', 'v' => $jenisPutus->dicabut, 'c' => '#f59e0b'],
                        ];
                        @endphp
                        @foreach($amars as $a)
                        <div class="status-item">
                            <span class="fw-700 text-dark">{{ $a['l'] }}</span>
                            <span class="h2 fw-800 mb-0" style="color: {{ $a['c'] }}">{{ number_format($a['v'] ?? 0) }}</span>
                        </div>
                        @endforeach
                    </div>

                    <div class="glass-card" style="height: 12vh; padding: 1.5vh;">
                        <div class="box-title" style="margin-bottom: 1vh;"><i class="fas fa-desktop me-2"></i> REGISTRASI</div>
                        <div class="d-flex justify-content-around align-items-center">
                            <div class="text-center">
                                <div class="h3 fw-800 text-primary mb-0">{{ $rekapEcourt->total_ecourt ?? 0 }}</div>
                                <div class="small fw-800 text-muted" style="font-size: 0.7rem;">E-COURT</div>
                            </div>
                            <div style="width: 2px; height: 40px; background: #f1f5f9;"></div>
                            <div class="text-center">
                                <div class="h3 fw-800 text-secondary mb-0">{{ $rekapEcourt->total_manual ?? 0 }}</div>
                                <div class="small fw-800 text-muted" style="font-size: 0.7rem;">MANUAL</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="glass-card" style="text-align: left; padding: 2vh;">
                    <div class="box-title d-flex justify-content-between">
                        <span><i class="fas fa-folder-tree me-2"></i> BEBAN PER JENIS PERKARA</span>
                        <span class="badge bg-dark rounded-pill px-3 fw-bold" style="font-size: 0.7rem;">{{ $tahun }}</span>
                    </div>
                    <div class="table-container" id="scroll-box">
                        <table class="table-modern">
                            <tbody>
                                @foreach($rekapJenis as $row)
                                <tr>
                                    <td>{{ $row->jenis }}</td>
                                    <td>{{ $row->total }} <small style="font-size: 0.7rem; opacity: 0.7;">PKR</small></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="glass-card text-center d-flex flex-column justify-content-between" style="padding: 2vh;">
                    <div class="box-title justify-content-center"><i class="fas fa-clock me-2"></i>LAMA PROSES</div>

                    <div class="durasi-item bg-success bg-opacity-10 border border-success border-opacity-25">
                        <span class="fw-800 text-success small">0-30 HARI</span>
                        <span class="h2 fw-800 text-success mb-0">{{ $zonaWarna->hijau_tua }}</span>
                    </div>

                    <div class="durasi-item bg-warning bg-opacity-10 border border-warning border-opacity-25">
                        <span class="fw-800 text-warning small text-dark">31-60 HARI</span>
                        <span class="h2 fw-800 text-warning mb-0 text-dark">{{ $zonaWarna->hijau_muda }}</span>
                    </div>

                    <div class="durasi-item bg-warning bg-opacity-25 border border-warning border-opacity-50">
                        <span class="fw-800 text-dark small">61-90 HARI</span>
                        <span class="h2 fw-800 text-dark mb-0">{{ $zonaWarna->kuning }}</span>
                    </div>

                    <div class="durasi-item bg-danger bg-opacity-10 border border-danger border-opacity-25">
                        <span class="fw-800 text-danger small">> 90 HARI</span>
                        <span class="h2 fw-800 text-danger mb-0">{{ $zonaWarna->merah }}</span>
                    </div>

                    <div class="mt-2 p-3 rounded-4 shadow-sm" style="background: var(--primary-grad); color: white;">
                        <div class="small fw-bold mb-1 opacity-75 text-uppercase" style="font-size: 0.7rem;">RASIO SELESAI</div>
                        <div class="h1 fw-800 mb-0">{{ $ratio }}%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-nav">
        <span><i class="fas fa-refresh fa-spin me-2 text-info"></i> AUTO REFRESH DASHBOARD — SETIAP 1 MENIT</span>
        <span class="text-uppercase" style="letter-spacing: 2px;">PTA BANDUNG COMMAND CENTER — UNIT IT</span>
        <span id="date-now">{{ date('d F Y') }} — {{ date('H:i:s') }}</span>
    </div>

    <script>
        function updateTime() {
            const now = new Date();
            const timeStr = now.toLocaleTimeString('id-ID', {
                hour12: false,
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            }).replace(/\./g, ':');
            document.getElementById('clock').innerText = timeStr;
            setTimeout(updateTime, 1000);
        }
        updateTime();

        // Refresh tiap 1 menit
        setTimeout(() => location.reload(), 60000);

        // Auto Scroll Tabel
        const box = document.getElementById('scroll-box');
        let scrollSpeed = 0.8;

        function startScroll() {
            box.scrollTop += scrollSpeed;
            if (box.scrollTop + box.clientHeight >= box.scrollHeight) {
                setTimeout(() => {
                    box.scrollTop = 0;
                }, 2000);
            }
        }
        setInterval(startScroll, 50);
    </script>
</body>

</html>