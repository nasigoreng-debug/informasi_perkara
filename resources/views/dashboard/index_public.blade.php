<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COMMAND CENTER - PTA BANDUNG</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --bg-dark: #0f172a;
            --card-bg: #1e293b;
            --accent-blue: #38bdf8;
            --accent-green: #10b981;
            --accent-red: #ef4444;
            --accent-yellow: #f59e0b;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-dark);
            color: #f8fafc;
            overflow: hidden;
            height: 100vh;
        }

        .header-nav {
            background: rgba(30, 41, 59, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 3px solid var(--accent-blue);
            padding: 15px 40px;
        }

        .stat-box-main {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 20px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            height: 100%;
            position: relative;
        }

        .label-top {
            font-size: 0.75rem;
            font-weight: 700;
            color: #94a3b8;
            letter-spacing: 2px;
        }

        .value-main {
            font-size: 4rem;
            font-weight: 800;
            line-height: 1;
            margin: 10px 0;
        }

        .progress-thin {
            height: 6px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            margin-top: 10px;
        }

        /* Table High Contrast Styling */
        .table-scroll {
            height: 380px;
            overflow: hidden;
        }

        .table-public-custom {
            width: 100%;
            color: #f8fafc !important;
            background-color: transparent !important;
        }

        .table-public-custom tr {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .table-public-custom tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.02);
        }

        .table-public-custom td {
            padding: 22px 10px;
            font-size: 1.4rem;
            font-weight: 600;
        }

        #clock {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--accent-blue);
        }

        .pulse {
            width: 10px;
            height: 10px;
            background: var(--accent-green);
            border-radius: 50%;
            display: inline-block;
            animation: pulse-dot 2s infinite;
        }

        @keyframes pulse-dot {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.3;
            }

            100% {
                opacity: 1;
            }
        }

        .badge-outline-info {
            border: 1px solid var(--accent-blue);
            color: var(--accent-blue);
            background: rgba(56, 189, 248, 0.1);
            font-size: 10px;
        }
    </style>
</head>

<body>

    <div class="d-flex flex-column vh-100">
        {{-- Top Navigation --}}
        <div class="header-nav d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-800 mb-0 text-white">SIAPPTA <span class="text-info">COMMAND CENTER</span></h2>
                <div class="small fw-600 opacity-50"><i class="fas fa-university me-1 text-info"></i> PENGADILAN TINGGI AGAMA BANDUNG</div>
            </div>
            <div class="text-end">
                <div id="clock">00:00:00</div>
                <div class="small fw-700 text-uppercase text-white"><span class="pulse me-1"></span> Monitoring Realtime</div>
            </div>
        </div>

        <div class="p-4 flex-grow-1">
            {{-- Row 1: Kartu Utama (6 Columns) --}}
            <div class="row g-3 mb-4 text-center">
                @php
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
                <div class="col">
                    <div class="stat-box-main shadow-lg">
                        <div class="label-top text-uppercase">{{ $c['l'] }}</div>
                        <div class="value-main" style="color: {{ $c['c'] }}">{{ number_format($c['v'] ?? 0) }}</div>
                        <div class="progress-thin">
                            <div class="progress-bar" style="width: {{ $c['p'] }}%; background-color: {{ $c['c'] }}"></div>
                        </div>
                        @if($c['l'] == 'PENYELESAIAN')
                        <div class="small mt-2 opacity-50 fw-bold" style="font-size: 10px;">RASIO: {{ $ratio }}%</div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Row 2: Informasi Detail --}}
            <div class="row g-4 flex-grow-1">
                {{-- Kolom Kiri: Putusan & E-Court --}}
                <div class="col-md-4">
                    <div class="d-flex flex-column h-100 gap-3">
                        <div class="stat-box-main">
                            <h5 class="fw-800 mb-3 text-info text-uppercase"><i class="fas fa-gavel me-2"></i>Status Putusan</h5>
                            @php
                            $amars = [
                            ['l' => 'Dikuatkan', 'v' => $jenisPutus->dikuatkan, 'c' => '#38bdf8', 'i' => 'fa-check-circle'],
                            ['l' => 'Dibatalkan', 'v' => $jenisPutus->dibatalkan, 'c' => '#ef4444', 'i' => 'fa-times-circle'],
                            ['l' => 'Tidak dapat diterima', 'v' => $jenisPutus->n_o, 'c' => '#2dd4bf', 'i' => 'fa-ban'],
                            ['l' => 'Dicabut', 'v' => $jenisPutus->dicabut, 'c' => '#f59e0b', 'i' => 'fa-undo'],
                            ];
                            @endphp
                            @foreach($amars as $a)
                            <div class="d-flex justify-content-between align-items-center mb-2 p-2 px-3 rounded-4" style="background: rgba(255,255,255,0.03); border-left: 5px solid {{ $a['c'] }}">
                                <span class="fw-700" style="font-size: 0.9rem;">{{ $a['l'] }}</span>
                                <span class="h3 fw-800 mb-0" style="color: {{ $a['c'] }}">{{ number_format($a['v'] ?? 0) }}</span>
                            </div>
                            @endforeach
                        </div>

                        <div class="stat-box-main">
                            <h5 class="fw-800 mb-3 text-info text-uppercase"><i class="fas fa-laptop-house me-2"></i>E-Court vs Manual</h5>
                            <div class="d-flex align-items-center justify-content-around mt-2">
                                <div class="text-center">
                                    <div class="h2 fw-800 text-info mb-0">{{ $rekapEcourt->total_ecourt ?? 0 }}</div>
                                    <div class="small fw-bold opacity-50" style="font-size: 9px;">E-COURT</div>
                                </div>
                                <div class="vr opacity-20"></div>
                                <div class="text-center">
                                    <div class="h2 fw-800 text-secondary mb-0">{{ $rekapEcourt->total_manual ?? 0 }}</div>
                                    <div class="small fw-bold opacity-50" style="font-size: 9px;">MANUAL</div>
                                </div>
                            </div>
                            @php
                            $totalEc = max(($rekapEcourt->total_ecourt ?? 0) + ($rekapEcourt->total_manual ?? 0), 1);
                            $ecPerc = round((($rekapEcourt->total_ecourt ?? 0) / $totalEc) * 100, 1);
                            @endphp
                            <div class="progress-thin mt-3 w-100">
                                <div class="progress-bar bg-info" style="width: {{ $ecPerc }}%"></div>
                            </div>
                            <div class="text-center mt-2 small opacity-50 fw-bold">PEMANFAATAN: {{ $ecPerc }}%</div>
                        </div>
                    </div>
                </div>

                {{-- Kolom Tengah: Jenis Perkara --}}
                <div class="col-md-5">
                    <div class="stat-box-main text-start">
                        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom border-secondary pb-2">
                            <h5 class="fw-800 text-info mb-0 text-uppercase"><i class="fas fa-list-ul me-2"></i>Beban Per Jenis Perkara</h5>
                            <span class="badge bg-primary rounded-pill px-3 fw-bold">TAHUN {{ $tahun }}</span>
                        </div>
                        <div class="table-scroll" id="scroll-box">
                            <table class="table-public-custom">
                                <tbody>
                                    @foreach($rekapJenis as $row)
                                    <tr>
                                        <td class="text-start">{{ $row->jenis }}</td>
                                        <td class="text-end fw-800 text-info">
                                            {{ $row->total }} <span class="small opacity-50 fw-600" style="font-size: 0.8rem;">PERKARA</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: Durasi Penyelesaian --}}
                <div class="col-md-3">
                    <div class="stat-box-main d-flex flex-column justify-content-between text-center">
                        <h5 class="fw-800 mb-0 text-info text-uppercase">Durasi Putus</h5>

                        <div>
                            <div class="h1 fw-800 text-success mb-0">{{ $zonaWarna->hijau_tua }}</div>
                            <div class="small fw-bold opacity-50">0-30 HARI</div>
                        </div>
                        <div class="my-2">
                            <div class="h1 fw-800 text-warning mb-0">{{ $zonaWarna->hijau_muda }}</div>
                            <div class="small fw-bold opacity-50">31-90 HARI</div>
                        </div>
                        <div>
                            <div class="h1 fw-800 text-danger mb-0">{{ $zonaWarna->merah }}</div>
                            <div class="small fw-bold opacity-50">> 90 HARI</div>
                        </div>

                        <div class="mt-3 p-3 rounded-4 bg-dark">
                            <div class="small text-muted mb-1">PRODUKTIVITAS</div>
                            <div class="h3 fw-800 text-info mb-0">{{ $ratio }}%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer Bar --}}
        <div class="px-4 py-2 d-flex justify-content-between align-items-center bg-dark text-muted small border-top border-secondary">
            <span><i class="fas fa-sync-alt fa-spin me-2 text-info"></i>Auto Update Aktif (5m)</span>
            <span class="fw-700">SIAPPTA COMMAND CENTER — PTA BANDUNG — © {{ date('Y') }}</span>
            <span class="text-white fw-600">Update: {{ date('H:i:s') }}</span>
        </div>
    </div>

    <script>
        function updateClock() {
            const now = new Date();
            const options = {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            };
            document.getElementById('clock').innerText = now.toLocaleTimeString('id-ID', options).replace(/\./g, ':');
            setTimeout(updateClock, 1000);
        }
        updateClock();

        // Refresh Halaman tiap 5 menit
        setTimeout(() => {
            location.reload();
        }, 300000);

        // Auto Scroll Tabel Jenis Perkara
        const box = document.getElementById('scroll-box');

        function startScroll() {
            box.scrollTop += 1;
            if (box.scrollTop + box.clientHeight >= box.scrollHeight) {
                box.scrollTop = 0;
            }
        }
        setInterval(startScroll, 40);
    </script>
</body>

</html>