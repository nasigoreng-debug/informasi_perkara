@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-md-5 fade-in">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 border-bottom pb-3">
        <div class="d-flex align-items-center">
            <img src="{{ asset('storage/logo-pta.png') }}" alt="Logo PTA" style="width: 55px; height: auto;" class="mr-3">
            <div>
                <h3 class="font-weight-bold text-dark mb-0" style="letter-spacing: 1px; font-family: 'Arial Black', Gadget, sans-serif;">JDIH PTA BANDUNG</h3>
                <p class="text-danger font-weight-bold small mb-0"> JARINGAN DOKUMENTASI DAN INFORMASI HUKUM</p>
            </div>
        </div>

        @if(in_array(auth()->user()->role_id, [1, 2]))
        <div class="mt-3 mt-md-0">
            <a href="{{ route('peraturan.create') }}" class="btn btn-danger btn-sm rounded-pill shadow-sm fw-bold hover-lift">
                <i class="fas fa-plus-circle mr-2"></i> Tambah Dokumen Baru
            </a>
        </div>
        @endif
    </div>

    <div class="row">
        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm sticky-sidebar" style="top: 20px; border-radius: 15px; overflow: hidden;">
                <div class="card-header bg-dark text-white py-3">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-search-plus mr-2"></i> INSTRUMEN PENCARIAN</h6>
                </div>
                <div class="card-body p-4 bg-light">
                    <form action="{{ route('peraturan.index') }}" method="GET" id="filterForm">
                        <div class="form-group mb-4">
                            <label class="text-xs font-weight-bold text-uppercase text-muted">Kata Kunci</label>
                            <div class="input-group shadow-sm">
                                <input type="text" name="search" value="{{ request('search') }}" class="form-control border-0" placeholder="Cari perihal...">
                                <div class="input-group-append">
                                    <button class="btn btn-danger" type="submit"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group mt-4">
                            <label class="text-xs font-weight-bold text-uppercase text-muted mb-3 d-block">Jenis Produk Hukum</label>
                            <div class="custom-radio-container" style="max-height: 400px; overflow-y: auto;">
                                <div class="custom-control custom-radio mb-2">
                                    <input type="radio" id="jenis_all" name="jenis" value="" class="custom-control-input" onchange="this.form.submit()" {{ request('jenis') == '' ? 'checked' : '' }}>
                                    <label class="custom-control-label small" for="jenis_all">Semua Dokumen</label>
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
                                <div class="custom-control custom-radio mb-2">
                                    <input type="radio" id="j_{{$key}}" name="jenis" value="{{ $jenis }}" class="custom-control-input" onchange="this.form.submit()" {{ request('jenis') == $jenis ? 'checked' : '' }}>
                                    <label class="custom-control-label small" for="j_{{$key}}">{{ Str::limit($jenis, 35) }}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        @if(request('search') || request('jenis'))
                        <a href="{{ route('peraturan.index') }}" class="btn btn-outline-dark btn-sm btn-block mt-4 rounded-pill">Bersihkan Filter</a>
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
            $singkatan = $match[1] ?? 'REGULASI';
            $isNew = $item->created_at->diffInDays(now()) <= 7;
                @endphp
                <div class="card border-0 shadow-sm mb-3 jdih-card overflow-hidden">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-9">
                            @if($isNew)
                            <span class="badge bg-warning text-dark mb-2 shadow-sm pulse-animation">
                                <i class="fas fa-star mr-1"></i> DOKUMEN BARU
                            </span>
                            @endif

                            <h4 class="font-weight-bold text-danger mb-2 tracking-tight">
                                {{ strtoupper($singkatan) }} NOMOR {{ $item->no_peraturan }} TAHUN {{ $item->tahun }}
                            </h4>

                            <div class="mb-3">
                                <span class="text-muted small font-weight-bold text-uppercase border-right pr-2 mr-2 border-danger">
                                    {{ $item->jenis_peraturan }}
                                </span>
                                <span class="text-muted small font-weight-bold">
                                    TAHUN {{ $item->tahun }}
                                </span>
                            </div>

                            <p class="text-dark mb-0 text-justify" style="line-height: 1.6;">
                                {{ $item->tentang }}
                        </div>

                        <div class="col-md-3 text-md-right mt-3 mt-md-0 border-left-dashed">
                            <div class="mb-4">
                                <small class="text-muted d-block mb-1">Ditetapkan Pada:</small>
                                <span class="font-weight-bold text-dark">
                                    {{ $item->tgl_peraturan ? \Carbon\Carbon::parse($item->tgl_peraturan)->isoFormat('D MMMM Y') : $item->tahun }}
                                </span>
                            </div>

                            <div class="d-flex flex-md-column gap-2 justify-content-end">
                                @if($item->dokumen)
                                <a href="{{ asset('storage/peraturan/'.$item->dokumen) }}" target="_blank" class="btn btn-danger btn-sm rounded-pill shadow-sm px-3 mb-md-2">
                                    <i class="fas fa-download mr-1"></i> Unduh PDF
                                </a>
                                @endif

                                @if(in_array(auth()->user()->role_id, [1, 2]))
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary btn-sm rounded-pill dropdown-toggle px-3 w-100" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Kelola
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right shadow-lg border-0">
                                        <a class="dropdown-item py-2" href="{{ route('peraturan.edit', $item->id) }}">
                                            <i class="fas fa-edit mr-2 text-primary"></i> Edit
                                        </a>

                                        <button type="button"
                                            class="dropdown-item py-2 text-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteModal"
                                            data-id="{{ $item->id }}"
                                            data-nomor="{{ $item->no_peraturan }}"
                                            data-tentang="{{ $item->tentang }}"
                                            data-tahun="{{ $item->tahun }}">
                                            <i class="fas fa-trash-alt mr-2"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        @empty
        <div class="text-center py-5 bg-white shadow-sm rounded-xl">
            <i class="fas fa-search fa-4x text-light mb-3"></i>
            <h5 class="text-muted">Dokumen tidak ditemukan...</h5>
        </div>
        @endforelse

        <div class="mt-4">
            {{ $data->links() }}
        </div>
    </div>
</div>
</div>

<!-- Modal Delete -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0 justify-content-end">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body pt-0 px-4 pb-4">
                <div class="text-center mb-4">
                    <div class="bg-light-danger d-inline-block rounded-circle p-4 mb-3" style="background: #fff5f5;">
                        <i class="fas fa-exclamation-circle fa-4x text-danger animate__animated animate__pulse animate__infinite"></i>
                    </div>
                    <h4 class="font-weight-bold text-dark">Konfirmasi Hapus</h4>
                    <p class="text-muted">Apakah Anda yakin ingin menghapus dokumen ini secara permanen?</p>
                </div>

                <div class="card bg-light border-0 mb-3" style="border-radius: 12px; border-left: 4px solid #dc3545 !important;">
                    <div class="card-body p-3">
                        <div class="row small mb-2">
                            <div class="col-4 text-muted font-weight-bold">NOMOR</div>
                            <div class="col-8 text-dark font-weight-bold" id="viewNomor">-</div>
                        </div>
                        <div class="row small mb-2">
                            <div class="col-4 text-muted font-weight-bold">TAHUN</div>
                            <div class="col-8 text-dark font-weight-bold" id="viewTahun">-</div>
                        </div>
                        <div class="row small">
                            <div class="col-4 text-muted font-weight-bold">TENTANG</div>
                            <div class="col-8 text-dark text-justify" id="viewTentang" style="line-height: 1.2;">-</div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-warning border-0 small d-flex align-items-center" style="border-radius: 10px;">
                    <i class="fas fa-info-circle mr-2"></i>
                    <span>Tindakan ini akan menghapus database dan file fisik PDF dari server.</span>
                </div>
            </div>

            <div class="modal-footer border-0 bg-light p-3" style="border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">
                <div class="container-fluid">
                    <div class="row g-2">
                        <div class="col-6">
                            <button type="button" class="btn btn-outline-secondary btn-block rounded-pill font-weight-bold" data-bs-dismiss="modal">BATAL</button>
                        </div>
                        <div class="col-6">
                            <form id="deleteForm" method="POST" action="">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-block rounded-pill shadow-sm font-weight-bold">
                                    <i class="fas fa-trash-alt mr-1"></i> YA, HAPUS
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* TYPOGRAPHY */
    .tracking-tight {
        letter-spacing: -0.03em;
    }

    .text-justify {
        text-align: justify;
    }

    /* CARD SULTAN */
    .jdih-card {
        border-radius: 12px;
        border-left: 6px solid #8b0000 !important;
        transition: 0.3s;
    }

    .jdih-card:hover {
        transform: scale(1.01);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }

    /* SIDEBAR CUSTOM */
    .custom-radio-container::-webkit-scrollbar {
        width: 4px;
    }

    .custom-radio-container::-webkit-scrollbar-thumb {
        background: #dc3545;
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

    /* BORDER DASHED */
    @media (min-width: 768px) {
        .border-left-dashed {
            border-left: 1px dashed #dee2e6;
        }
    }

    /* HOVER LIFT */
    .hover-lift {
        transition: 0.2s;
    }

    .hover-lift:hover {
        transform: translateY(-3px);
    }

    /* FIX NAVBAR VS SIDEBAR */
    .sticky-sidebar {
        position: -webkit-sticky;
        position: sticky;
        top: 85px;
        /* Jarak dari atas (atur sesuai tinggi Navbar Abang) */
        z-index: 1000 !important;
        /* Di bawah Navbar (Navbar biasanya 1030) */
        height: fit-content;
    }

    /* TYPOGRAPHY PROPER UNTUK MODAL */
    .text-sentence {
        text-transform: lowercase;
        display: block;
    }

    .text-sentence::first-letter {
        text-transform: uppercase;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var deleteModal = document.getElementById('deleteModal');

        deleteModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;

            // Ambil data asli
            var tentangAsli = button.getAttribute('data-tentang');

            // Fungsi untuk mengubah teks menjadi Sentence Case
            var tentangRapi = tentangAsli.toLowerCase().replace(/(^\s*\w|[\.\!\?]\s*\w)/g, function(c) {
                return c.toUpperCase();
            });

            // Masukkan ke elemen modal
            deleteModal.querySelector('#viewTentang').innerText = tentangRapi;

            // Sisanya tetap sama
            var id = button.getAttribute('data-id');
            var nomor = button.getAttribute('data-nomor');
            var tahun = button.getAttribute('data-tahun');

            deleteModal.querySelector('#viewNomor').innerText = nomor;
            deleteModal.querySelector('#viewTahun').innerText = tahun;

            var form = deleteModal.querySelector('#deleteForm');
            var actionUrl = "{{ route('peraturan.destroy', ':id') }}";
            form.action = actionUrl.replace(':id', id);
        });
    });
</script>
@endsection