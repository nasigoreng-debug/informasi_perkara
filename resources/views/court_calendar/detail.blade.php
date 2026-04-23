@extends('layouts.app')

@push('styles')
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
<style>
    :root {
        --dark-navy: #1e293b;
        --border-color: #e2e8f0;
        --bg-body: #f8fafc;
    }

    body {
        background-color: var(--bg-body);
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        color: #334155;
        -webkit-tap-highlight-color: transparent;
    }

    /* Container responsif untuk mobile */
    .container-detail {
        width: 100%;
        max-width: 100%;
        margin: 0;
        padding: 1rem;
    }

    /* Tombol back yang lebih besar untuk mobile */
    .btn-back {
        width: 44px;
        height: 44px;
        min-width: 44px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: white;
        border: 1px solid var(--border-color);
        color: var(--dark-navy);
        text-decoration: none;
        transition: all 0.2s;
        font-size: 1.3rem;
    }

    .btn-back:active {
        background: #f1f5f9;
        transform: scale(0.96);
    }

    /* Header section untuk mobile */
    .header-mobile {
        flex-direction: column;
        gap: 1rem;
    }

    .header-top {
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .title-section h2 {
        font-size: 1.25rem;
        margin-bottom: 0.25rem;
    }

    .badge-satker {
        background: #2563eb;
        color: white;
        font-size: 0.7rem;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 20px;
        text-transform: uppercase;
        display: inline-block;
    }

    /* Toolbar untuk mobile */
    .toolbar-mobile {
        width: 100%;
        display: flex;
        gap: 0.75rem;
        margin-top: 0.5rem;
    }

    .search-field {
        flex: 1;
        border: 1px solid var(--border-color);
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 0.9rem;
        width: auto;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        -webkit-appearance: none;
    }

    .search-field:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .btn-excel,
    .btn-print {
        padding: 8px 14px;
        font-size: 0.85rem;
        white-space: nowrap;
    }

    .btn-excel span,
    .btn-print span {
        display: inline-block;
    }

    /* Container tabel dengan overflow horizontal */
    .table-container {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        overflow-x: auto;
        overflow-y: visible;
        -webkit-overflow-scrolling: touch;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        position: relative;
    }

    /* Indikator scroll untuk mobile */
    .table-container::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        width: 30px;
        background: linear-gradient(to right, transparent, rgba(0, 0, 0, 0.02));
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .table-container:hover::after {
        opacity: 1;
    }

    /* Table dengan lebar minimal untuk konten */
    .table-grid {
        width: 100%;
        min-width: 600px;
        border-collapse: collapse;
        table-layout: auto;
    }

    .table-grid thead th {
        background: #f8fafc;
        border: 1px solid var(--border-color);
        padding: 12px 10px;
        font-size: 0.7rem;
        font-weight: 800;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        position: sticky;
        top: 0;
        background-color: #f8fafc;
        z-index: 10;
    }

    .table-grid tbody td {
        border: 1px solid var(--border-color);
        padding: 12px 10px;
        font-size: 0.8rem;
        vertical-align: middle;
        word-break: break-word;
    }

    /* Kolom dengan lebar spesifik untuk mobile */
    .table-grid th:first-child,
    .table-grid td:first-child {
        width: 55px;
        min-width: 55px;
    }

    .table-grid th:nth-child(2),
    .table-grid td:nth-child(2) {
        min-width: 180px;
    }

    .table-grid th:nth-child(3),
    .table-grid td:nth-child(3) {
        min-width: 110px;
    }

    .table-grid th:nth-child(4),
    .table-grid td:nth-child(4) {
        min-width: 200px;
    }

    .no-perkara {
        color: #2563eb;
        font-weight: 700;
        text-decoration: none;
        word-break: break-word;
        display: inline-block;
    }

    .no-perkara:active {
        color: #1d4ed8;
    }

    .status-box {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 6px;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        font-weight: 700;
        font-size: 0.7rem;
        color: #475569;
        white-space: normal;
        word-break: break-word;
        max-width: 100%;
    }

    /* Pesan kosong */
    .empty-state {
        padding: 2rem 1rem;
    }

    .empty-state i {
        font-size: 2rem;
        display: block;
        margin-bottom: 0.5rem;
    }

    /* Tombol dengan ukuran touch-friendly */
    button,
    .btn-excel,
    .btn-print,
    .btn-back {
        cursor: pointer;
        touch-action: manipulation;
    }

    /* Print styles */
    @media print {
        .no-print {
            display: none !important;
        }

        .container-detail {
            padding: 0;
            margin: 0;
        }

        .table-container {
            overflow: visible;
            border: 1px solid #ddd;
        }

        .table-grid {
            min-width: 100%;
        }

        .table-grid thead th {
            background: #f8fafc !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .status-box {
            background: #f1f5f9 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }

    /* Tablet dan desktop */
    @media (min-width: 768px) {
        .container-detail {
            max-width: 1100px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .header-mobile {
            flex-direction: row;
            justify-content: space-between;
            align-items: flex-start;
        }

        .header-top {
            width: auto;
        }

        .toolbar-mobile {
            width: auto;
            margin-top: 0;
        }

        .table-container {
            overflow-x: visible;
        }

        .table-grid {
            min-width: 100%;
            table-layout: fixed;
        }

        .table-grid th:first-child {
            width: 60px;
        }

        .table-grid th:nth-child(2) {
            width: 220px;
        }

        .table-grid th:nth-child(3) {
            width: 140px;
        }

        .table-grid th:nth-child(4) {
            width: auto;
        }

        .table-grid th:nth-child(5) {
            width: 180px;
        }
    }

    /* Mobile kecil */
    @media (max-width: 480px) {
        .container-detail {
            padding: 0.75rem;
        }

        .btn-back {
            width: 40px;
            height: 40px;
            min-width: 40px;
        }

        .title-section h2 {
            font-size: 1.1rem;
        }

        .badge-satker {
            font-size: 0.65rem;
            padding: 3px 10px;
        }

        .btn-excel span,
        .btn-print span {
            display: none;
        }

        .btn-excel,
        .btn-print {
            padding: 8px 12px;
        }

        .btn-excel i,
        .btn-print i {
            font-size: 1.1rem;
            margin: 0;
        }

        .search-field {
            font-size: 0.85rem;
            padding: 8px 12px;
        }

        .table-grid tbody td {
            padding: 10px 6px;
            font-size: 0.75rem;
        }

        .table-grid thead th {
            padding: 10px 6px;
            font-size: 0.65rem;
        }

        .status-box {
            padding: 4px 8px;
            font-size: 0.65rem;
        }
    }

    /* Loading state untuk search */
    .search-loading {
        position: relative;
    }

    .search-loading::after {
        content: '';
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        width: 16px;
        height: 16px;
        border: 2px solid #e2e8f0;
        border-top-color: #3b82f6;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
    }

    @keyframes spin {
        to {
            transform: translateY(-50%) rotate(360deg);
        }
    }
</style>
@endpush

@section('content')
<div class="container-detail">
    <!-- Header Section Responsive -->
    <div class="header-mobile d-flex mb-3">
        <div class="header-top">
            <div class="d-flex align-items-center">
                <a href="{{ url()->previous() }}" class="btn-back me-2 shadow-sm no-print" title="Kembali">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="title-section">
                    <h2 class="fw-bold mb-1" style="color: var(--dark-navy);">
                        Rincian Tunggakan
                    </h2>
                    <span class="badge-satker">{{ $namaSatker }}</span>
                </div>
            </div>
        </div>

        <div class="toolbar-mobile no-print">
            <input type="text" id="tableSearch" class="search-field shadow-sm"
                placeholder="Cari perkara..." aria-label="Search data">

            <a href="{{ route('court-calendar.export-detail', [
                'satker' => request()->route('satker'),
                'tgl_awal' => request('tgl_awal'),
                'tgl_akhir' => request('tgl_akhir')
            ]) }}"
                class="btn-excel shadow-sm text-decoration-none">
                <i class="bi bi-file-earmark-excel"></i>
                <span>EXCEL</span>
            </a>

            <button onclick="window.print()" class="btn-print shadow-sm border-0">
                <i class="bi bi-printer"></i>
                <span>CETAK</span>
            </button>
        </div>
    </div>

    <!-- Table Section dengan overflow horizontal -->
    <div class="table-container shadow-sm">
        <table class="table-grid" id="detailTable">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>NOMOR PERKARA</th>
                    <th>TGL DAFTAR</th>
                    <th>JENIS PERKARA</th>
                    <th>PROSES TERAKHIR</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $row)
                <tr>
                    <td class="text-muted fw-bold text-center">{{ $index + 1 }}</td>
                    <td>
                        <a href="#" class="no-perkara" title="Lihat detail perkara">
                            {{ $row->nomor_perkara }}
                        </a>
                    </td>
                    <td class="text-center">{{ date('d/m/Y', strtotime($row->tanggal_pendaftaran)) }}</td>
                    <td class="fw-600 text-dark">{{ $row->jenis_perkara_nama }}</td>
                    <td class="text-center">
                        <span class="status-box" title="Status proses terakhir">
                            {{ $row->proses_terakhir_text }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="empty-state text-muted fw-bold text-center">
                        <i class="bi bi-inbox"></i>
                        <div>TIDAK ADA DATA TUNGGAKAN</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Informasi jumlah data untuk mobile -->
    @if($data->count() > 0)
    <div class="text-center text-muted small mt-3 no-print">
        <i class="bi bi-database"></i> Total {{ $data->count() }} data tunggakan
    </div>
    @endif
</div>

@push('scripts')
<script>
    (function() {
        'use strict';

        // Search functionality with debounce dan loading indicator
        const searchInput = document.getElementById('tableSearch');
        if (searchInput) {
            let debounceTimer;
            let isSearching = false;

            const performSearch = () => {
                const searchTerm = searchInput.value.toLowerCase().trim();
                const rows = document.querySelectorAll('#detailTable tbody tr');
                let visibleCount = 0;

                rows.forEach(row => {
                    if (row.cells.length > 1 && row.cells[0].colSpan !== 5) {
                        const textContent = row.textContent.toLowerCase();
                        const isVisible = textContent.includes(searchTerm);
                        row.style.display = isVisible ? '' : 'none';
                        if (isVisible) visibleCount++;
                    }
                });

                // Update info jumlah hasil pencarian
                updateSearchInfo(visibleCount, searchTerm);

                // Remove loading indicator
                if (isSearching) {
                    searchInput.classList.remove('search-loading');
                    isSearching = false;
                }
            };

            const updateSearchInfo = (count, term) => {
                let infoDiv = document.getElementById('searchInfo');
                if (!infoDiv && term) {
                    infoDiv = document.createElement('div');
                    infoDiv.id = 'searchInfo';
                    infoDiv.className = 'text-center text-muted small mt-2 no-print';
                    searchInput.parentNode.insertBefore(infoDiv, searchInput.nextSibling);
                }

                if (infoDiv) {
                    if (term) {
                        infoDiv.innerHTML = `<i class="bi bi-search"></i> Ditemukan ${count} data dari "${term}"`;
                        if (count === 0) {
                            infoDiv.style.color = '#ef4444';
                        } else {
                            infoDiv.style.color = '#64748b';
                        }
                    } else if (infoDiv && !term) {
                        infoDiv.remove();
                    }
                }
            };

            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);

                if (!isSearching) {
                    searchInput.classList.add('search-loading');
                    isSearching = true;
                }

                debounceTimer = setTimeout(performSearch, 300);
            });

            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    this.value = '';
                    performSearch();
                    this.blur();
                }
            });

            // Clear search info when input is cleared
            searchInput.addEventListener('search', function() {
                if (!this.value) {
                    performSearch();
                }
            });
        }

        // Touch feedback untuk tombol di mobile
        const buttons = document.querySelectorAll('.btn-back, .btn-excel, .btn-print');
        buttons.forEach(btn => {
            btn.addEventListener('touchstart', function() {
                this.style.opacity = '0.7';
            });
            btn.addEventListener('touchend', function() {
                this.style.opacity = '1';
            });
            btn.addEventListener('touchcancel', function() {
                this.style.opacity = '1';
            });
        });

        // Cegah zoom berlebih pada input focus di mobile
        const inputs = document.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                if (window.innerWidth <= 768) {
                    setTimeout(() => {
                        window.scrollTo(0, this.getBoundingClientRect().top + window.pageYOffset - 20);
                    }, 300);
                }
            });
        });

        // Optimasi print
        window.onbeforeprint = function() {
            document.body.style.zoom = '100%';
        };

        window.onafterprint = function() {
            // Kembalikan ke normal
        };

        // Handle orientation change
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                // Refresh tabel jika perlu
                const table = document.getElementById('detailTable');
                if (table) {
                    table.style.opacity = '0.99';
                    setTimeout(() => {
                        table.style.opacity = '1';
                    }, 10);
                }
            }, 250);
        });

        // Deteksi swipe untuk scroll horizontal
        let touchStartX = 0;
        const tableContainer = document.querySelector('.table-container');
        if (tableContainer) {
            tableContainer.addEventListener('touchstart', (e) => {
                touchStartX = e.touches[0].clientX;
            });
        }
    })();
</script>
@endpush
@endsection