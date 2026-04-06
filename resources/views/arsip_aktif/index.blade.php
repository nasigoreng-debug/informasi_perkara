@extends('layouts.app')

@section('content')
<style>
    .clickable-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .clickable-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }

    .active-filter {
        border: 3px solid #2563eb !important;
        background-color: #f0f7ff;
    }

    .sticky-sidebar {
        position: sticky;
        top: 90px;
        z-index: 1000;
        height: fit-content;
    }

    .box-arsip {
        background: #f8fafc;
        border: 1px solid #eef2f7;
        border-radius: 12px;
        padding: 10px;
    }

    .btn-dl {
        font-size: 0.65rem;
        font-weight: 800;
        border-radius: 6px;
        padding: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        margin-top: 4px;
        text-decoration: none;
    }

    .btn-pdf {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fca5a5;
    }

    .btn-rar {
        background: #fef3c7;
        color: #92400e !important;
        border: 1px solid #fcd34d;
    }

    .section-header {
        font-size: 0.8rem;
        font-weight: 800;
        color: #0f172a;
        border-left: 5px solid #2563eb;
        padding-left: 10px;
        margin-bottom: 15px;
        background: #f8fafc;
        padding-top: 5px;
        padding-bottom: 5px;
        text-transform: uppercase;
    }

    .detail-label {
        font-size: 0.65rem;
        color: #94a3b8;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 0;
    }

    .detail-value {
        font-size: 0.85rem;
        color: #1e293b;
        font-weight: 700;
        border-bottom: 1px dashed #e2e8f0;
        padding-bottom: 2px;
        margin-bottom: 10px;
    }

    .amar-box {
        background: #fff7ed;
        border: 1px solid #ffedd5;
        padding: 12px;
        border-radius: 8px;
        font-size: 0.75rem;
        color: #9a3412;
        line-height: 1.4;
        max-height: 200px;
        overflow-y: auto;
    }

    .sort-link {
        color: inherit;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
    }
</style>

<div class="container-fluid py-4 px-md-5 text-uppercase text-dark">
    {{-- Header --}}
    <div class="d-flex align-items-center mb-4 border-bottom pb-3">
        <img src="{{ asset('storage/logo-pta.png') }}" alt="Logo" style="width: 55px;" class="me-3">
        <div>
            <h3 class="fw-bold mb-0">MANAJEMEN ARSIP PERKARA</h3>
            <p class="text-danger fw-bold small mb-0">PTA BANDUNG - DATABASE DIGITALISASI</p>
        </div>
    </div>

    {{-- Statistik Card --}}
    <div class="row g-3 mb-3 text-center">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-dark text-white p-3 rounded-4 clickable-card" onclick="window.location.href='{{ route('arsip-aktif.index', request()->except('status_digital')) }}'">
                <small class="fw-bold opacity-75 small">TOTAL PERKARA</small>
                <h3 class="mb-0 fw-bold">{{ number_format($stats['total']) }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-danger text-white p-3 rounded-4 clickable-card {{ request('status_digital') == 'belum_masuk' ? 'active-filter' : '' }}" onclick="window.location.href='{{ route('arsip-aktif.index', array_merge(request()->query(), ['status_digital' => 'belum_masuk'])) }}'">
                <small class="fw-bold opacity-75 small">BELUM MASUK ARSIP</small>
                <h3 class="mb-0 fw-bold">{{ number_format($stats['belum_masuk']) }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-primary text-white p-3 rounded-4 clickable-card {{ request('status_digital') == 'sudah_pdf' ? 'active-filter' : '' }}" onclick="window.location.href='{{ route('arsip-aktif.index', array_merge(request()->query(), ['status_digital' => 'sudah_pdf'])) }}'">
                <small class="fw-bold opacity-75 small">SUDAH TERUPLOAD PDF</small>
                <h3 class="mb-0 fw-bold">{{ number_format($stats['sudah_pdf']) }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-warning text-dark p-3 rounded-4 clickable-card {{ request('status_digital') == 'lengkap' ? 'active-filter' : '' }}" onclick="window.location.href='{{ route('arsip-aktif.index', array_merge(request()->query(), ['status_digital' => 'lengkap'])) }}'">
                <small class="fw-bold opacity-75 small">LENGKAP DIGITAL</small>
                <h3 class="mb-0 fw-bold">{{ number_format($stats['lengkap']) }}</h3>
            </div>
        </div>
    </div>

    {{-- Progress Card Style Retensi --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm p-4 rounded-4" style="border-left: 5px solid #28a745 !important;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <small class="text-muted fw-bold d-block mb-1">PROGRES LENGKAP DIGITAL</small>
                        <h2 class="mb-0 fw-bold text-success">{{ number_format($stats['lengkap']) }} <span class="fs-6 fw-normal text-muted">Berkas</span></h2>
                        <div class="mt-3">
                            <span class="small fw-bold text-success">{{ $stats['persen_lengkap'] }}% TERCAPAI</span>
                            <div class="progress mt-1" style="height: 8px;">
                                <div class="progress-bar bg-success" style="width: {{ $stats['persen_lengkap'] }}%"></div>
                            </div>
                        </div>
                    </div>
                    <i class="fas fa-file-circle-check fa-3x text-success opacity-25"></i>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm p-4 rounded-4" style="border-left: 5px solid #dc3545 !important;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <small class="text-muted fw-bold d-block mb-1">BELUM DISERAHKAN</small>
                        <h2 class="mb-0 fw-bold text-danger">{{ number_format($stats['belum_masuk']) }} <span class="fs-6 fw-normal text-muted">Berkas</span></h2>
                        <div class="mt-3">
                            <span class="small fw-bold text-danger">{{ $stats['persen_belum'] }}% TERSISA</span>
                            <div class="progress mt-1" style="height: 8px;">
                                <div class="progress-bar bg-danger" style="width: {{ $stats['persen_belum'] }}%"></div>
                            </div>
                        </div>
                    </div>
                    <i class="fas fa-file-circle-exclamation fa-3x text-danger opacity-25"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Sidebar --}}
        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 sticky-sidebar bg-light">
                <form action="{{ route('arsip-aktif.index') }}" method="GET">
                    @if(request('status_digital')) <input type="hidden" name="status_digital" value="{{ request('status_digital') }}"> @endif
                    <input type="hidden" name="per_page" value="{{ $perPage }}">
                    <input type="hidden" name="sort_by" value="{{ $sortBy }}">
                    <input type="hidden" name="sort_order" value="{{ $sortOrder }}">
                    <div class="mb-3"><label class="small fw-bold">NO PERKARA</label><input type="text" name="search" value="{{ request('search') }}" class="form-control border-0 shadow-sm"></div>
                    <div class="mb-3"><label class="small fw-bold">PUTUS DARI</label><input type="date" name="tgl_awal" value="{{ $tgl_awal }}" class="form-control border-0 shadow-sm"></div>
                    <div class="mb-3"><label class="small fw-bold">PUTUS SAMPAI</label><input type="date" name="tgl_akhir" value="{{ $tgl_akhir }}" class="form-control border-0 shadow-sm"></div>
                    <button type="submit" class="btn btn-danger w-100 fw-bold rounded-pill shadow-sm">CARI DATA</button>
                    <a href="{{ route('arsip-aktif.index') }}" class="btn btn-link btn-sm w-100 mt-2 text-decoration-none text-muted small text-center d-block">RESET</a>
                </form>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="small fw-bold">SHOW</span>
                    <select class="form-select form-select-sm border-0 shadow-sm rounded-pill px-3" style="width: 80px;" onchange="updateQuery('per_page', this.value)">
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    <span class="small fw-bold">ENTRIES</span>
                </div>
                <div class="small fw-bold text-muted">Menampilkan {{ $data->firstItem() ?? 0 }} - {{ $data->lastItem() ?? 0 }} dari {{ $data->total() }} data</div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <table class="table table-hover align-middle mb-0 bg-white">
                    <thead class="bg-dark text-white text-center">
                        <tr>
                            <th class="py-3 px-4 text-start">
                                <a href="javascript:void(0)" onclick="applySort('nomor_perkara_banding')" class="sort-link text-dark">NOMOR & PIHAK <i class="fas fa-sort small opacity-50"></i></a>
                            </th>
                            <th class="py-3">
                                <a href="javascript:void(0)" onclick="applySort('tgl_putusan')" class="sort-link text-dark">TANGGAL SIPP <i class="fas fa-sort small opacity-50"></i></a>
                            </th>
                            <th class="py-3">
                                <a href="javascript:void(0)" onclick="applySort('tgl_masuk')" class="sort-link text-dark">ARSIP DIGITAL <i class="fas fa-sort small opacity-50"></i></a>
                            </th>
                            <th class="py-3">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $item)
                        <tr class="text-center">
                            <td class="text-start px-4 py-4">
                                <div class="fw-bold text-danger">{{ $item->nomor_perkara_banding }}</div>
                                <div class="small fw-bold text-dark">{{ Str::limit($item->nama_pembanding, 40) }}</div>
                                <div class="text-danger fw-bold" style="font-size:0.6rem">VS</div>
                                <div class="small text-muted">{{ Str::limit($item->nama_terbanding, 40) }}</div>
                            </td>
                            <td class="small">
                                REG: <b>{{ $item->tgl_register ?? '-' }}</b><br>
                                <span class="text-danger">PUTUS: <b>{{ $item->tgl_putusan ?? '-' }}</b></span>
                            </td>
                            <td>
                                @if($item->tgl_masuk != '-')
                                <div class="box-arsip shadow-sm">
                                    <div class="d-flex flex-column gap-1">
                                        @if($item->file_putusan) <a href="/storage/arsip_aktif/pdf/{{ $item->file_putusan }}" target="_blank" class="btn-dl btn-pdf"><i class="fas fa-file-pdf"></i> PDF</a> @endif
                                        @if($item->file_bundel_b) <a href="/storage/arsip_aktif/rar/{{ $item->file_bundel_b }}" target="_blank" class="btn-dl btn-rar"><i class="fas fa-file-archive"></i> BUNDEL B</a> @endif
                                    </div>
                                    <div class="mt-1 fw-bold text-primary" style="font-size: 0.6rem;">MASUK: {{ $item->tgl_masuk }}</div>
                                </div>
                                @else <span class="badge bg-light text-muted border">BELUM MASUK ARSIP</span> @endif
                            </td>
                            <td>
                                <div class="btn-group border rounded-pill overflow-hidden shadow-sm">
                                    <button class="btn btn-white btn-sm px-3 border-end btn-view-detail" data-item="{{ json_encode($item) }}"><i class="fas fa-eye text-primary"></i></button>
                                    <button class="btn btn-white btn-sm px-3" onclick="openModal('{{ $item->nomor_perkara_banding }}', '{{ $item->penyerah }}', '{{ $item->penerima }}', '{{ $item->no_lemari }}', '{{ $item->no_laci }}', '{{ $item->no_box }}', '{{ $item->tgl_masuk }}')">
                                        <i class="fas {{ $item->tgl_masuk == '-' ? 'fa-plus text-success' : 'fa-edit text-warning' }}"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-5 text-center text-muted">Data Tidak Ditemukan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $data->links() }}</div>
        </div>
    </div>
</div>

{{-- MODAL DETAIL --}}
<div class="modal fade text-uppercase" id="modalDetail" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable text-dark">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-dark text-white border-0 py-3">
                <h5 class="modal-title fw-bold small"><i class="fas fa-info-circle me-2"></i>DETAIL INFORMASI PERKARA LENGKAP</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <div class="row g-4 text-dark">
                    <div class="col-md-8">
                        <div class="card border-0 shadow-sm p-4 mb-3 rounded-4">
                            <div class="section-header">Identitas & Para Pihak</div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="detail-label">No Perkara Banding</div>
                                    <div class="detail-value text-danger fs-5" id="v_no_b"></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="detail-label">No Perkara PA</div>
                                    <div class="detail-value" id="v_no_pa"></div>
                                </div>
                                <div class="col-md-12">
                                    <div class="detail-label">Pembanding</div>
                                    <div class="detail-value fw-bold text-primary" id="v_pem"></div>
                                </div>
                                <div class="col-md-12">
                                    <div class="detail-label">Terbanding</div>
                                    <div class="detail-value text-muted fw-bold" id="v_ter"></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="detail-label">Satker Asal</div>
                                    <div class="detail-value" id="v_satker"></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="detail-label">Jenis Perkara</div>
                                    <div class="detail-value" id="v_jenis"></div>
                                </div>
                            </div>
                        </div>
                        <div class="card border-0 shadow-sm p-4 rounded-4">
                            <div class="section-header">Riwayat & Putusan SIPP</div>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <div class="detail-label">Tgl Mohon</div>
                                    <div class="detail-value" id="v_t_mohon"></div>
                                </div>
                                <div class="col-md-3">
                                    <div class="detail-label">Tgl Regis</div>
                                    <div class="detail-value" id="v_t_reg"></div>
                                </div>
                                <div class="col-md-3">
                                    <div class="detail-label text-success">Tgl Putus</div>
                                    <div class="detail-value text-success fw-bold" id="v_t_putus"></div>
                                </div>
                                <div class="col-md-3">
                                    <div class="detail-label text-primary">Kirim PA</div>
                                    <div class="detail-value text-primary" id="v_t_kpa"></div>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <div class="detail-label">Petugas KMH / PP</div>
                                    <div class="detail-value fw-bold" id="v_petugas"></div>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <div class="detail-label">Amar Putusan</div>
                                    <div class="amar-box" id="v_amar"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm p-4 rounded-4 border-top border-primary border-4 mb-3">
                            <div class="section-header">Lokasi Berkas Fisik</div>
                            <div class="detail-label">Tgl Masuk</div>
                            <div class="detail-value text-primary fw-bold" id="v_tgl_masuk"></div>
                            <div class="detail-label">Posisi Berkas</div>
                            <div class="detail-value fw-bold text-success" id="v_lokasi"></div>
                            <div class="detail-label">Penyerah</div>
                            <div class="detail-value" id="v_f_penyerah"></div>
                            <div class="detail-label">Penerima</div>
                            <div class="detail-value" id="v_f_penerima"></div>
                        </div>
                        <div class="card border-0 shadow-sm p-4 rounded-4 border-top border-danger border-4">
                            <div class="section-header">Arsip Digital</div>
                            <div class="d-grid gap-2">
                                <a href="#" id="m_btn_pdf" target="_blank" class="btn btn-outline-danger fw-bold rounded-pill py-2 shadow-sm"><i class="fas fa-file-pdf me-2"></i>PDF PUTUSAN</a>
                                <a href="#" id="m_btn_rar" target="_blank" class="btn btn-outline-warning text-dark fw-bold rounded-pill py-2 shadow-sm"><i class="fas fa-file-archive me-2"></i>BUNDEL B (RAR)</a>
                                <div id="no_file_info" class="text-center text-muted small mt-2 d-none">BELUM ADA BERKAS DIGITAL.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL UPLOAD --}}
<div class="modal fade text-uppercase" id="modalArsip" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered text-dark">
        <form action="{{ route('arsip-aktif.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header bg-dark text-white border-0 py-3">
                    <h5 class="modal-title fw-bold small">PENGELOLAAN DATA ARSIP</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 bg-light">
                    <div class="row g-3">
                        <div class="col-md-12"><label class="small fw-bold text-muted">NO PERKARA</label><input type="text" name="nomor_perkara" id="m_no" class="form-control bg-white fw-bold text-danger border-0 shadow-sm" readonly></div>
                        <div class="col-md-6"><label class="small fw-bold text-muted">TGL MASUK</label><input type="date" name="tgl_masuk" id="m_tgl" class="form-control border-0 shadow-sm"></div>
                        <div class="col-md-6"><label class="small fw-bold text-muted">PENYERAH</label><input type="text" name="penyerah" id="m_pen" class="form-control border-0 shadow-sm" required></div>
                        <div class="col-md-6"><label class="small fw-bold text-muted">PENERIMA</label><input type="text" name="penerima" id="m_pem_input" class="form-control border-0 shadow-sm" required></div>
                        <div class="col-md-2"><label class="small fw-bold text-muted">LMRI</label><input type="text" name="no_lemari" id="m_lem" class="form-control border-0 shadow-sm text-center fw-bold" required></div>
                        <div class="col-md-2"><label class="small fw-bold text-muted">LACI</label><input type="text" name="no_laci" id="m_lac" class="form-control border-0 shadow-sm text-center fw-bold" required></div>
                        <div class="col-md-2"><label class="small fw-bold text-primary">BOX</label><input type="text" name="no_box" id="m_box" class="form-control border-0 shadow-sm text-center fw-bold text-primary" required></div>
                        <div class="col-md-6"><label class="small fw-bold text-danger">UPLOAD PDF</label><input type="file" name="file_putusan" class="form-control shadow-sm"></div>
                        <div class="col-md-6"><label class="small fw-bold text-warning">UPLOAD RAR</label><input type="file" name="file_bundel_b" class="form-control shadow-sm"></div>
                    </div>
                </div>
                <div class="modal-footer p-4 pt-0 border-0"><button type="submit" class="btn btn-danger w-100 fw-bold rounded-pill shadow-sm py-3">SIMPAN DATA ARSIP</button></div>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function applySort(column) {
        const url = new URL(window.location.href);
        const currentSort = url.searchParams.get('sort_by');
        const currentOrder = url.searchParams.get('sort_order');
        let newOrder = (currentSort === column && currentOrder === 'asc') ? 'desc' : 'asc';
        updateQueryBatch({
            'sort_by': column,
            'sort_order': newOrder,
            'page': 1
        });
    }

    function updateQueryBatch(params) {
        const url = new URL(window.location.href);
        for (const key in params) {
            url.searchParams.set(key, params[key]);
        }
        window.location.href = url.toString();
    }

    function updateQuery(key, val) {
        updateQueryBatch({
            [key]: val,
            'page': 1
        });
    }

    // JS DETAIL
    $(document).on('click', '.btn-view-detail', function() {
        const d = $(this).data('item');
        $('#v_no_b').text(d.nomor_perkara_banding);
        $('#v_no_pa').text(d.nomor_perkara_pa);
        $('#v_pem').text(d.nama_pembanding);
        $('#v_ter').text(d.nama_terbanding);
        $('#v_satker').text(d.nama_satker);
        $('#v_jenis').text(d.jenis_perkara);
        $('#v_t_mohon').text(d.tgl_mohon_banding || '-');
        $('#v_t_reg').text(d.tgl_register || '-');
        $('#v_t_putus').text(d.tgl_putusan || '-');
        $('#v_t_kpa').text(d.tgl_kirim_pa || '-');
        $('#v_petugas').text((d.nama_km || '-') + ' / ' + (d.nama_pp || '-'));
        $('#v_amar').html(d.keterangan || d.amar_putusan || 'AMAR BELUM TERSEDIA.');
        $('#v_tgl_masuk').text(d.tgl_masuk);
        $('#v_lokasi').text('L: ' + d.no_lemari + ' | LACI: ' + d.no_laci + ' | BOX: ' + d.no_box);
        $('#v_f_penyerah').text(d.penyerah);
        $('#v_f_penerima').text(d.penerima);

        let adaFile = false;
        if (d.file_putusan) {
            $('#m_btn_pdf').show().attr('href', '/storage/arsip_aktif/pdf/' + d.file_putusan);
            adaFile = true;
        } else {
            $('#m_btn_pdf').hide();
        }

        if (d.file_bundel_b) {
            $('#m_btn_rar').show().attr('href', '/storage/arsip_aktif/rar/' + d.file_bundel_b);
            adaFile = true;
        } else {
            $('#m_btn_rar').hide();
        }

        if (!adaFile) {
            $('#no_file_info').removeClass('d-none');
        } else {
            $('#no_file_info').addClass('d-none');
        }

        $('#modalDetail').modal('show');
    });

    // JS UPLOAD
    function openModal(no, pen, pem, lem, lac, box, tgl) {
        $('#m_no').val(no);
        $('#m_pen').val(pen !== '-' ? pen : '');
        $('#m_pem_input').val(pem !== '-' ? pem : '');
        $('#m_lem').val(lem !== '-' ? lem : '');
        $('#m_lac').val(lac !== '-' ? lac : '');
        $('#m_box').val(box !== '-' ? box : '');
        if (tgl && tgl !== '-') $('#m_tgl').val(tgl);
        else $('#m_tgl').val(new Date().toISOString().split('T')[0]);
        $('#modalArsip').modal('show');
    }
</script>
@endsection