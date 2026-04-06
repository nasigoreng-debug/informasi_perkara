<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIAPPTA 16:9</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --glass: rgba(255, 255, 255, 0.95);
            --glass-border: rgba(255, 255, 255, 0.5);
            --primary-grad: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        }

        /* 16:9 Monitor Optimization */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            height: 100vh;
            width: 100vw;
            overflow: hidden;
            /* Lock scrollbar luar */
            display: flex;
            flex-direction: column;
        }

        /* Nav Header: 12% dari tinggi layar */
        .header-nav {
            height: 12vh;
            padding: 0 4vw;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid var(--glass-border);
        }

        /* Content Area: 82% dari tinggi layar */
        .main-content {
            height: 82vh;
            padding: 2vh 2vw;
            display: flex;
            flex-direction: column;
            gap: 2vh;
        }

        /* Row Top: 22% dari tinggi layar */
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
            font-weight: 700;
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

        /* Row Bottom: Sisanya */
        .row-bottom {
            flex: 1;
            min-height: 0;
        }

        .box-title {
            font-size: 1rem;
            font-weight: 800;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 1vh;
            margin-bottom: 1.5vh;
            display: flex;
            align-items: center;
        }

        /* Table Auto Scroll Area */
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
            background: rgba(248, 250, 252, 0.8);
        }

        .table-modern td {
            padding: 1.8vh 15px;
            font-size: 1.3rem;
            font-weight: 700;
            color: #334155;
        }

        .table-modern td:last-child {
            text-align: right;
            color: #6366f1;
        }

        /* Status Items */
        .status-item {
            padding: 1.5vh 1.5vw;
            border-radius: 18px;
            margin-bottom: 1vh;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
            border: 1px solid #f1f5f9;
            height: 6.8vh;
        }

        #clock {
            font-size: 2.5rem;
            font-weight: 800;
            color: #6366f1;
        }

        /* Footer: 6% dari tinggi layar */
        .footer-nav {
            height: 6vh;
            padding: 0 4vw;
            background: rgba(255, 255, 255, 0.5);
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-weight: 700;
            color: #94a3b8;
            font-size: 0.85rem;
        }
    </style>
</head>

<body>

    <div class="header-nav">
        <div class="d-flex align-items-center">
            <div class="me-3" style="width: 50px; height: 50px; background: var(--primary-grad); border-radius: 15px; display: flex; align-items: center; justify-content: center; color: white; font-size: 22px;">
                <i class="fas fa-university"></i>
            </div>
            <div>
                <h2 class="fw-800 mb-0" style="letter-spacing: -1px; color: #1e293b;">SIAPPTA <span style="color: #6366f1;">MONITORING PENYELESAIAN PERKARA BANDING</span></h2>
                <div class="small fw-700 text-muted text-uppercase" style="font-size: 0.7rem;">PTA BANDUNG — COMMAND CENTER</div>
            </div>
        </div>
        <div class="text-end">
            <div id="clock">00:00:00</div>
            <div class="small fw-800 text-muted" style="font-size: 0.7rem;"><i class="fas fa-circle text-success me-1"></i> LIVE DATA</div>
        </div>
    </div>

    <div class="main-content">
        <!-- TOP: 6 Kartu Utama -->
        <div class="row g-3 row-top">
            @php
            $cards = [
            ['l' => 'SISA LALU', 'v' => $cardData->sisa_lalu, 'c' => '#64748b'],
            ['l' => 'DITERIMA', 'v' => $cardData->diterima, 'c' => '#0ea5e9'],
            ['l' => 'BEBAN', 'v' => $beban, 'c' => '#6366f1'],
            ['l' => 'PUTUSAN SELA', 'v' => $putusanSela, 'c' => '#f43f5e'],
            ['l' => 'SELESAI', 'v' => $cardData->selesai, 'c' => '#10b981'],
            ['l' => 'SISA AKHIR', 'v' => $cardData->sisa, 'c' => '#f59e0b'],
            ];
            @endphp
            @foreach($cards as $c)
            <div class="col">
                <div class="glass-card">
                    <div class="label-sub">{{ $c['l'] }}</div>
                    <div class="value-hero" style="color: {{ $c['c'] }};">{{ number_format($c['v'] ?? 0) }}</div>
                    <div style="height: 5px; background: #e2e8f0; border-radius: 10px; margin-top: 5px;">
                        <div style="height: 100%; width: 100%; background: {{ $c['c'] }}; border-radius: 10px;"></div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- BOTTOM: Status, Tabel, & Durasi -->
        <div class="row g-3 row-bottom">
            <!-- Kolom Status Putusan -->
            <div class="col-md-4">
                <div class="d-flex flex-column h-100 gap-3">
                    <div class="glass-card flex-grow-1" style="text-align: left; padding: 2vh;">
                        <div class="box-title"><i class="fas fa-balance-scale me-2"></i> STATUS PUTUSAN</div>
                        @php
                        $amars = [
                        ['l' => 'Dikuatkan', 'v' => $jenisPutus->dikuatkan, 'c' => '#6366f1'],
                        ['l' => 'Dibatalkan', 'v' => $jenisPutus->dibatalkan, 'c' => '#f43f5e'],
                        ['l' => 'Tidak dapat diterima', 'v' => $jenisPutus->n_o, 'c' => '#0ea5e9'],
                        ['l' => 'Dicabut', 'v' => $jenisPutus->dicabut, 'c' => '#f59e0b'],
                        ];
                        @endphp
                        @foreach($amars as $a)
                        <div class="status-item shadow-sm">
                            <span class="fw-700 text-secondary">{{ $a['l'] }}</span>
                            <span class="h2 fw-800 mb-0" style="color: {{ $a['c'] }}">{{ number_format($a['v'] ?? 0) }}</span>
                        </div>
                        @endforeach
                    </div>
                    <div class="glass-card" style="height: 15vh; padding: 1.5vh;">
                        <div class="box-title" style="margin-bottom: 1vh;"><i class="fas fa-fingerprint me-2"></i> REGISTRASI PERKARA</div>
                        <div class="d-flex justify-content-around align-items-center">
                            <div class="text-center">
                                <div class="h3 fw-800 text-info mb-0">{{ $rekapEcourt->total_ecourt ?? 0 }}</div>
                                <div class="small fw-bold text-muted" style="font-size: 0.65rem;">E-COURT</div>
                            </div>
                            <div style="width: 1px; height: 30px; background: #e2e8f0;"></div>
                            <div class="text-center">
                                <div class="h3 fw-800 text-secondary mb-0">{{ $rekapEcourt->total_manual ?? 0 }}</div>
                                <div class="small fw-bold text-muted" style="font-size: 0.65rem;">MANUAL</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Tabel Jenis Perkara -->
            <div class="col-md-5">
                <div class="glass-card" style="text-align: left; padding: 2vh;">
                    <div class="box-title d-flex justify-content-between">
                        <span><i class="fas fa-folder-open me-2"></i> BEBAN JENIS PERKARA</span>
                        <span class="badge bg-primary rounded-pill px-3 fw-bold" style="font-size: 0.6rem;">TAHUN {{ $tahun }}</span>
                    </div>
                    <div class="table-container" id="scroll-box">
                        <table class="table-modern">
                            <tbody>
                                @foreach($rekapJenis as $row)
                                <tr>
                                    <td>{{ $row->jenis }}</td>
                                    <td>{{ $row->total }} <span style="font-size: 0.7rem; opacity: 0.6;">PERKARA</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Kolom Durasi -->
            <div class="col-md-3">
                <div class="glass-card text-center d-flex flex-column justify-content-between" style="padding: 2vh;">
                    <div class="box-title justify-content-center"><i class="fas fa-hourglass-half me-2"></i> LAMA PROSES</div>
                    <div>
                        <div class="h1 fw-800 text-success mb-0">{{ $zonaWarna->hijau_tua }}</div>
                        <div class="small fw-bold text-muted" style="font-size: 0.7rem;">0-30 HARI</div>
                    </div>
                    <div>
                        <div class="h1 fw-800 text-warning mb-0">{{ $zonaWarna->hijau_muda }}</div>
                        <div class="small fw-bold text-muted" style="font-size: 0.7rem;">31-90 HARI</div>
                    </div>
                    <div>
                        <div class="h1 fw-800 text-danger mb-0">{{ $zonaWarna->merah }}</div>
                        <div class="small fw-bold text-muted" style="font-size: 0.7rem;">> 90 HARI</div>
                    </div>
                    <div class="mt-2 p-3 rounded-4" style="background: var(--primary-grad); color: white;">
                        <div class="small fw-bold mb-1 opacity-75 text-uppercase" style="font-size: 0.6rem;">Persentase</div>
                        <div class="h2 fw-800 mb-0">{{ $ratio }}%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="footer-nav">
        <span><i class="fas fa-sync-alt fa-spin me-2 text-primary"></i>AUTO REFRESH: 1 MENIT</span>
        <span class="text-uppercase" style="letter-spacing: 1px;">PTA BANDUNG COMMAND CENTER — © {{ date('Y') }}</span>
        <span class="text-dark">{{ date('H:i:s') }}</span>
    </div>

    <script>
        function updateTime() {
            const now = new Date();
            const options = {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            };
            document.getElementById('clock').innerText = now.toLocaleTimeString('id-ID', options).replace(/\./g, ':');
            setTimeout(updateTime, 1000);
        }
        updateTime();

        // REFRESH 1 MENIT
        setTimeout(() => {
            location.reload();
        }, 60000);

        // AUTO SCROLL TABEL
        const box = document.getElementById('scroll-box');

        function startScroll() {
            box.scrollTop += 1;
            if (box.scrollTop + box.clientHeight >= box.scrollHeight) box.scrollTop = 0;
        }
        setInterval(startScroll, 50);
    </script>
</body>

</html>