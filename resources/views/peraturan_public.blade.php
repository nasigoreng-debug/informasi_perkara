<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JDIH PTA BANDUNG - Portal Informasi Hukum</title>

    <link rel="icon" type="image/png" href="{{ asset('storage/logo-pta.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, system-ui, sans-serif;
        }

        .tracking-tight {
            letter-spacing: -0.03em;
        }

        .text-justify {
            text-align: justify;
        }

        /* NAVBAR FIX */
        .navbar-custom {
            background: white;
            padding: 12px 0;
            border-bottom: 3px solid #8b0000;
            position: sticky;
            top: 0;
            z-index: 1060;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        /* CARD SULTAN */
        .jdih-card {
            border-radius: 16px;
            border-left: 6px solid #8b0000 !important;
            border: none;
            background: white;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .jdih-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 30px rgba(139, 0, 0, 0.08) !important;
        }

        /* SIDEBAR STICKY */
        .sticky-sidebar {
            position: -webkit-sticky;
            position: sticky;
            top: 90px;
            z-index: 1000;
            height: fit-content;
        }

        /* CUSTOM SCROLLBAR SIDEBAR */
        .custom-radio-container::-webkit-scrollbar {
            width: 4px;
        }

        .custom-radio-container::-webkit-scrollbar-thumb {
            background: #8b0000;
            border-radius: 10px;
        }

        /* ANIMATION PULSE */
        .pulse-animation {
            animation: pulse-red 2s infinite;
        }

        @keyframes pulse-red {
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.7);
            }

            70% {
                transform: scale(1);
                box-shadow: 0 0 0 10px rgba(255, 193, 7, 0);
            }

            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(255, 193, 7, 0);
            }
        }

        @media (min-width: 768px) {
            .border-left-dashed {
                border-left: 1px dashed #dee2e6;
            }
        }

        /* SHARE BUTTONS */
        .btn-share {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #f1f3f5;
            color: #495057;
            transition: 0.2s;
            text-decoration: none;
        }

        .btn-share:hover {
            background: #8b0000;
            color: white;
            transform: scale(1.1);
        }

        .btn-unduh {
            min-width: 180px;
        }

        .hover-bg-light:hover {
            background-color: rgba(139, 0, 0, 0.04);
            cursor: pointer;
        }
    </style>
</head>

<body>

    <nav class="navbar-custom px-md-5 px-3">
        <div class="container-fluid d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <img src="{{ asset('storage/logo-pta.png') }}" alt="Logo PTA" style="width: 50px;" class="me-3">
                <div>
                    <h4 class="fw-bold text-dark mb-0 d-none d-md-block" style="letter-spacing: 1px;">JDIH PTA BANDUNG</h4>
                    <p class="text-danger fw-bold small mb-0 d-none d-md-block">JARINGAN DOKUMENTASI DAN INFORMASI HUKUM</p>
                    <h5 class="fw-bold text-dark mb-0 d-md-none">JDIH PTA BANDUNG</h5>
                </div>
            </div>
            <a href="{{ route('login') }}" class="btn btn-outline-danger btn-sm rounded-pill px-4 fw-bold">
                <i class="fas fa-lock me-2"></i>LOGIN
            </a>
        </div>
    </nav>

    <div class="container-fluid py-4 px-md-5">
        <div class="row g-4">

            <div class="col-lg-3">
                <div class="card border-0 shadow-sm sticky-sidebar" style="border-radius: 20px; overflow: hidden;">
                    <div class="card-header bg-dark text-white py-3 border-0 text-center">
                        <h6 class="m-0 fw-bold small text-uppercase">Instrumen Pencarian</h6>
                    </div>

                    <div class="card-body p-4 bg-light">
                        <form action="{{ route('peraturan.public') }}" method="GET" id="filterForm">
                            <div class="mb-4">
                                <label class="text-uppercase text-muted fw-bold mb-2 small">Kata Kunci</label>
                                <div class="input-group shadow-sm mt-1">
                                    <input type="text" name="search" value="{{ request('search') }}" class="form-control border-0" placeholder="Cari perihal/nomor...">
                                    <button class="btn btn-danger px-3" type="submit"><i class="fas fa-search"></i></button>
                                </div>
                            </div>

                            <hr class="opacity-25">

                            <div class="mt-4">
                                <label class="text-uppercase text-muted fw-bold mb-3 d-block small">Jenis Produk Hukum</label>
                                <div class="custom-radio-container pe-2" style="max-height: 400px; overflow-y: auto;">

                                    <div class="form-check mb-2 p-1 hover-bg-light">
                                        <input type="radio" id="jenis_all" name="jenis" value="" class="form-check-input" onchange="this.form.submit()" {{ request('jenis') == '' ? 'checked' : '' }}>
                                        <label class="form-check-label small fw-medium ms-1" for="jenis_all">Semua Dokumen</label>
                                    </div>

                                    @php
                                    $list_jenis = [
                                    "Undang-Undang (UU)", "Peraturan Pemerintah Pengganti Undang-undang (PERPU)",
                                    "Peraturan Pemerintah (PP)", "Instruksi Presiden (INPRES)",
                                    "Peraturan Mahkamah Agung (PERMA)", "Surat Edaran Mahkamah Agung (SEMA)",
                                    "Surat Keputusan Ketua Mahkamah Agung (SK KMA)", "Surat Keputusan Sekretaris Mahkamah Agung (SK SEKMA)",
                                    "Surat Edaran Direktur Jenderal Badan Peradilan Agama (SE Dirjen Badilag)",
                                    "Surat Keputusan Direktur Jenderal Badan Peradilan Agama (SK Dirjen Badilag)",
                                    "Surat Edaran Ketua Pengadilan Tinggi Agama Bandung (SE KPTA Bandung)",
                                    "Surat Keputusan Ketua Pengadilan Tinggi Agama Bandung (SK KPTA Bandung)",
                                    "Peraturan lainnya"
                                    ];
                                    @endphp

                                    @foreach($list_jenis as $key => $jenis)
                                    <div class="form-check mb-2 p-1 hover-bg-light">
                                        <input type="radio" id="j_{{$key}}" name="jenis" value="{{ $jenis }}" class="form-check-input" onchange="this.form.submit()" {{ request('jenis') == $jenis ? 'checked' : '' }}>
                                        <label class="form-check-label small ms-1" for="j_{{$key}}">{{ Str::limit($jenis, 35) }}</label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            @if(request('search') || request('jenis'))
                            <div class="mt-4">
                                <a href="{{ route('peraturan.public') }}" class="btn btn-outline-danger w-100 rounded-pill btn-sm fw-bold">BERSIHKAN FILTER</a>
                            </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <span class="text-muted small">Ditemukan <strong>{{ $data->total() }}</strong> berkas hukum</span>
                </div>

                @forelse($data as $item)
                @php
                preg_match('/\((.*?)\)/', $item->jenis_peraturan, $match);
                $singkatan = $match[1] ?? 'REG';
                $isNew = $item->created_at->diffInDays(now()) <= 7;
                    @endphp

                    <div class="card shadow-sm mb-4 jdih-card border-0 overflow-hidden">
                    <div class="card-body p-4">
                        <div class="row g-4 align-items-center">
                            <div class="col-md-8">
                                @if($isNew)
                                <span class="badge bg-warning text-dark mb-2 shadow-sm pulse-animation px-3">BARU</span>
                                @endif

                                <h4 class="fw-bold text-danger mb-1 tracking-tight text-uppercase">
                                    {{ $singkatan }} NOMOR {{ $item->no_peraturan }} TAHUN {{ $item->tahun }}
                                </h4>

                                <div class="mb-3">
                                    <span class="text-muted small fw-bold text-uppercase border-start border-danger border-3 ps-2">
                                        {{ $item->jenis_peraturan }}
                                    </span>
                                </div>

                                <p class="text-dark mb-0 text-justify text-uppercase" style="line-height: 1.6; font-size: 0.95rem;">
                                    {{ $item->tentang }}
                                </p>
                            </div>

                            <div class="col-md-4 border-left-dashed ps-md-4 text-center">
                                <div class="mb-3">
                                    <small class="text-muted d-block mb-1 text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;">
                                        Ditetapkan Pada
                                    </small>
                                    <span class="fw-bold text-dark fs-5">
                                        {{ $item->tgl_peraturan ? \Carbon\Carbon::parse($item->tgl_peraturan)->isoFormat('D MMMM Y') : $item->tahun }}
                                    </span>
                                </div>

                                <div class="d-grid gap-2 justify-content-center">
                                    @if($item->dokumen)
                                    <a href="{{ asset('public/storage/peraturan/'.$item->dokumen) }}" target="_blank"
                                        class="btn btn-danger rounded-pill shadow-sm py-2 fw-bold btn-unduh">
                                        <i class="fas fa-file-pdf me-2"></i> UNDUH PDF
                                    </a>
                                    @endif

                                    <div class="d-flex justify-content-center gap-2 mt-2">
                                        <a href="https://wa.me/?text={{ urlencode($item->tentang) }}" target="_blank" class="btn-share"><i class="fab fa-whatsapp"></i></a>
                                        <a href="https://t.me/share/url?url={{ urlencode(asset('storage/peraturan/'.$item->dokumen)) }}&text={{ urlencode($item->tentang) }}" target="_blank" class="btn-share"><i class="fab fa-telegram"></i></a>
                                        <button class="btn-share border-0" onclick="navigator.clipboard.writeText('{{ asset('storage/peraturan/'.$item->dokumen) }}'); alert('Tautan disalin!')"><i class="fas fa-link"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            @empty
            <div class="text-center py-5 bg-white shadow-sm rounded-4 border">
                <i class="fas fa-file-circle-xmark fa-4x text-light mb-3"></i>
                <h5 class="text-muted">Dokumen tidak ditemukan...</h5>
                <a href="{{ route('peraturan.public') }}" class="btn btn-sm btn-outline-danger mt-3 rounded-pill">Lihat Semua</a>
            </div>
            @endforelse

            <div class="mt-4 d-flex justify-content-center">
                {{ $data->links() }}
            </div>
        </div>
    </div>
    </div>

    <footer class="bg-dark text-white-50 py-4 mt-5">
        <div class="container text-center">
            <small>&copy; {{ date('Y') }} JDIH PTA Bandung. Sistem Informasi Dokumen Hukum.</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.querySelectorAll('.form-check-input').forEach(radio => {
            radio.addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
        });
    </script>

</body>

</html>