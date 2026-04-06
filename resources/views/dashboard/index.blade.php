<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COMMAND CENTER PTA BANDUNG - TV 16:9</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --bg-dark: #0f172a; --card-bg: #1e293b; --accent-blue: #38bdf8;
            --accent-green: #10b981; --accent-red: #ef4444; --accent-yellow: #f59e0b;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: var(--bg-dark); 
            color: #f8fafc; 
            height: 100vh; 
            width: 100vw;
            overflow: hidden; 
            display: flex;
            flex-direction: column;
        }
        
        .header-nav { 
            height: 12vh; 
            background: rgba(30, 41, 59, 0.8); 
            backdrop-filter: blur(10px); 
            border-bottom: 3px solid var(--accent-blue); 
            padding: 0 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .main-content { 
            flex: 1; 
            padding: 2vh; 
            display: flex; 
            flex-direction: column; 
            gap: 2vh;
        }

        .row-top { height: 22vh; }
        .stat-box-main { 
            background: var(--card-bg); 
            border-radius: 20px; 
            padding: 1.5vh; 
            border: 1px solid rgba(255,255,255,0.05); 
            height: 100%; 
            display: flex; 
            flex-direction: column; 
            justify-content: center;
            text-align: center;
        }
        
        .label-top { font-size: 0.8rem; font-weight: 700; color: #94a3b8; letter-spacing: 2px; }
        .value-main { font-size: 4.5rem; font-weight: 800; line-height: 1; margin: 0.5vh 0; }
        
        .row-bottom { flex: 1; min-height: 0; } 
        .box-detail { background: var(--card-bg); border-radius: 20px; padding: 2vh; border: 1px solid rgba(255,255,255,0.05); height: 100%; }

        /* Table High Contrast */
        .table-scroll-container { height: 45vh; overflow: hidden; margin-top: 1vh; }
        .table-public-custom { width: 100%; color: #f8fafc !important; border-collapse: collapse; }
        .table-public-custom tr { border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
        .table-public-custom tr:nth-child(even) { background: rgba(255,255,255,0.02); }
        .table-public-custom td { padding: 1.8vh 10px; font-size: 1.4rem; font-weight: 600; }

        #clock { font-size: 2.5rem; font-weight: 800; color: var(--accent-blue); }
        .pulse { width: 12px; height: 12px; background: var(--accent-green); border-radius: 50%; display: inline-block; animation: pulse-dot 2s infinite; }
        @keyframes pulse-dot { 0% { opacity: 1; } 50% { opacity: 0.3; } 100% { opacity: 1; } }

        .footer-bar { height: 6vh; background: #000; padding: 0 40px; display: flex; align-items: center; justify-content: space-between; font-size: 0.8rem; color: #64748b; border-top: 1px solid #1e293b; }
    </style>
</head>
<body>

    <div class="header-nav">
        <div>
            <h2 class="fw-800 mb-0 text-white">SIAPPTA <span class="text-info">COMMAND CENTER</span></h2>
            <div class="small fw-600 opacity-50"><i class="fas fa-university me-1 text-info"></i> PTA BANDUNG</div>
        </div>
        <div class="text-end">
            <div id="clock">00:00:00</div>
            <div class="small fw-700 text-uppercase text-white"><span class="pulse me-1"></span> Monitoring Realtime</div>
        </div>
    </div>

    <div class="main-content">
        <div class="row g-3 row-top text-center">
            @php
                $ratio = $beban > 0 ? round(($cardData->selesai / $beban) * 100, 1) : 0;
                $cards = [
                    ['l' => 'SISA LALU', 'v' => $cardData->sisa_lalu, 'c' => '#94a3b8', 'p' => 100],
                    ['l' => 'DITERIMA', 'v' => $cardData->diterima, 'c' => '#38bdf8', 'p' => 100],
                    ['l' => 'BEBAN KERJA', 'v' => $beban, 'c' => '#818cf8', 'p' => 100],
                    ['l' => 'PUTUSAN SELA', 'v' => $putusanSela, 'c' => '#f472b6', 'p' => 100],
                    ['l' => 'PENYELESAIAN', 'v' => $cardData->selesai, 'c' => '#10b981', 'p' => $ratio],
                    ['l' => 'SISA AKHIR', 'v' => $cardData->sisa, 'c' => '#f59e0b', 'p' => (100-$ratio)],
                ];
            @endphp
            @foreach($cards as $c)
            <div class="col text-center">
                <div class="stat-box-main shadow-lg">
                    <div class="label-top">{{ $c['l'] }}</div>
                    <div class="value-main" style="color: {{ $c['c'] }}">{{ number_format($c['v'] ?? 0) }}</div>
                    <div style="height: 4px; background: rgba(255,255,255,0.1); border-radius: 10px;">
                        <div style="height: 100%; width: {{ $c['p'] }}%; background: {{ $c['c'] }}; border-radius: 10px;"></div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="row g-3 row-bottom">
            <div class="col-md-4">
                <div class="d-flex flex-column h-100 gap-3">
                    <div class="box-detail flex-grow-1">
                        <h5 class="fw-800 mb-3 text-info"><i class="fas fa-gavel me-2"></i>STATUS PUTUSAN</h5>
                        @php
                            $amars = [
                                ['l' => 'Dikuatkan', 'v' => $jenisPutus->dikuatkan, 'c' => '#38bdf8'],
                                ['l' => 'Dibatalkan', 'v' => $jenisPutus->dibatalkan, 'c' => '#ef4444'],
                                ['l' => 'Tidak dapat diterima', 'v' => $jenisPutus->n_o, 'c' => '#2dd4bf'],
                                ['l' => 'Dicabut', 'v' => $jenisPutus->dicabut, 'c' => '#f59e0b'],
                            ];
                        @endphp
                        @foreach($amars as $a)
                        <div class="d-flex justify-content-between align-items-center mb-2 p-3 rounded-4" style="background: rgba(255,255,255,0.03); border-left: 5px solid {{ $a['c'] }}; height: 6.5vh;">
                            <span class="fw-700 fs-5">{{ $a['l'] }}</span>
                            <span class="h2 fw-800 mb-0" style="color: {{ $a['c'] }}">{{ number_format($a['v'] ?? 0) }}</span>
                        </div>
                        @endforeach
                    </div>
                    <div class="box-detail" style="height: 18vh;">
                        <h5 class="fw-800 mb-2 text-info small"><i class="fas fa-laptop-house me-2"></i>E-COURT VS MANUAL</h5>
                        <div class="d-flex justify-content-around align-items-center h-100">
                            <div class="text-center">
                                <div class="h3 fw-800 text-info mb-0">{{ $rekapEcourt->total_ecourt ?? 0 }}</div>
                                <div class="small fw-bold opacity-50" style="font-size: 0.7rem;">E-COURT</div>
                            </div>
                            <div class="vr opacity-20"></div>
                            <div class="text-center">
                                <div class="h3 fw-800 text-secondary mb-0">{{ $rekapEcourt->total_manual ?? 0 }}</div>
                                <div class="small fw-bold opacity-50" style="font-size: 0.7rem;">MANUAL</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="box-detail">
                    <div class="d-flex justify-content-between align-items-center border-bottom border-secondary pb-2">
                        <h5 class="fw-800 text-info mb-0 text-uppercase"><i class="fas fa-list-ul me-2"></i>Beban Per Jenis Perkara</h5>
                        <span class="badge bg-primary rounded-pill px-3 fw-bold">TAHUN {{ $tahun }}</span>
                    </div>
                    <div class="table-scroll-container" id="scroll-box">
                        <table class="table-public-custom">
                            <tbody>
                                @foreach($rekapJenis as $row)
                                <tr>
                                    <td>{{ $row->jenis }}</td>
                                    <td class="text-end fw-800 text-info">{{ $row->total }} <span class="small opacity-50 fw-600" style="font-size: 0.8rem;">PERKARA</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="box-detail d-flex flex-column justify-content-between text-center">
                    <h5 class="fw-800 text-info text-uppercase">Durasi Putus</h5>
                    <div>
                        <div class="display-6 fw-800 text-success mb-0">{{ $zonaWarna->hijau_tua }}</div>
                        <div class="small fw-bold opacity-50">0-30 HARI</div>
                    </div>
                    <div>
                        <div class="display-6 fw-800 text-warning mb-0">{{ $zonaWarna->hijau_muda }}</div>
                        <div class="small fw-bold opacity-50">31-90 HARI</div>
                    </div>
                    <div>
                        <div class="display-6 fw-800 text-danger mb-0">{{ $zonaWarna->merah }}</div>
                        <div class="small fw-bold opacity-50">> 90 HARI</div>
                    </div>
                    <div class="mt-2 p-3 rounded-4 bg-dark">
                        <div class="small text-muted mb-1">PRODUKTIVITAS</div>
                        <div class="h3 fw-800 text-info mb-0">{{ $ratio }}%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bar">
        <span><i class="fas fa-sync-alt fa-spin me-2 text-info"></i>Auto Update Aktif (5m)</span>
        <span class="fw-700 text-white opacity-75 text-uppercase">SIAPPTA Command Center — PTA Bandung</span>
        <span class="text-white">Update: {{ date('H:i:s') }}</span>
    </div>

    <script>
        function updateTime() {
            const now = new Date();
            const options = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
            document.getElementById('clock').innerText = now.toLocaleTimeString('id-ID', options).replace(/\./g, ':');
            setTimeout(updateTime, 1000);
        }
        updateTime();
        setTimeout(() => { location.reload(); }, 300000);
        const box = document.getElementById('scroll-box');
        function startScroll() {
            box.scrollTop += 1;
            if (box.scrollTop + box.clientHeight >= box.scrollHeight) box.scrollTop = 0;
        }
        setInterval(startScroll, 45);
    </script>
</body>
</html>