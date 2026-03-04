@extends('layouts.app')

@push('styles')
<style>
    :root {
        --dark-navy: #1e293b;
        --border-color: #e2e8f0;
        --bg-body: #f8fafc;
    }

    body {
        background-color: var(--bg-body);
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: #334155;
    }

    /* Container dikunci agar tidak melar di monitor besar */
    .container-detail {
        max-width: 1100px;
        margin: 0 auto;
        padding: 3rem 0;
    }

    .btn-back {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: white;
        border: 1px solid var(--border-color);
        color: var(--dark-navy);
        text-decoration: none;
        transition: all 0.2s;
        font-size: 1.2rem; /* Memastikan icon memiliki ukuran */
    }

    .btn-back:hover {
        background: #f1f5f9;
        border-color: #94a3b8;
        color: var(--dark-navy);
    }

    /* Fallback jika icon tidak muncul */
    .btn-back:before {
        content: "←"; /* Fallback menggunakan karakter panah */
        font-family: Arial, sans-serif;
        font-weight: bold;
        display: none; /* Default hidden */
    }

    /* Gunakan fallback jika icon font tidak tersedia */
    .btn-back i.bi {
        font-family: 'bootstrap-icons', sans-serif;
    }

    .btn-back i.bi-bi-arrow-left {
        font-style: normal;
    }

    /* Pastikan Bootstrap Icons terload dengan baik */
    @font-face {
        font-family: 'bootstrap-icons';
        src: url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/fonts/bootstrap-icons.woff2') format('woff2'),
             url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/fonts/bootstrap-icons.woff') format('woff');
    }

    .table-container {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    /* Table Layout Fixed agar distribusi lebar kolom konsisten */
    .table-grid {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .table-grid thead th {
        background: #f8fafc;
        border: 1px solid var(--border-color);
        padding: 14px;
        font-size: 0.75rem;
        font-weight: 800;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .table-grid tbody td {
        border: 1px solid var(--border-color);
        padding: 14px;
        font-size: 0.85rem;
        vertical-align: middle;
        word-wrap: break-word;
    }

    .no-perkara {
        color: #2563eb;
        font-weight: 700;
        text-decoration: none;
        transition: color 0.2s;
    }

    .no-perkara:hover {
        color: #1d4ed8;
        text-decoration: underline;
    }

    .search-field {
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 8px 16px;
        font-size: 0.85rem;
        width: 220px;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .search-field:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .status-box {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 6px;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        font-weight: 700;
        font-size: 0.7rem;
        color: #475569;
        white-space: nowrap;
    }

    .badge-satker {
        background: #2563eb;
        color: white;
        font-size: 0.65rem;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
        text-transform: uppercase;
    }

    .btn-excel {
        background: #22c55e;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 6px 16px;
        font-size: 0.85rem;
        font-weight: 700;
        transition: background 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-excel:hover {
        background: #16a34a;
        color: white;
    }

    .btn-print {
        background: #334155;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 6px 16px;
        font-size: 0.85rem;
        font-weight: 700;
        transition: background 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-print:hover {
        background: #1e293b;
        color: white;
    }

    @media print {
        .no-print {
            display: none !important;
        }

        .container-detail {
            max-width: 100%;
            padding: 1rem 0;
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

    @media (max-width: 768px) {
        .container-detail {
            padding: 1.5rem 1rem;
        }

        .table-grid {
            font-size: 0.8rem;
        }

        .table-grid thead th {
            padding: 10px 6px;
            font-size: 0.7rem;
        }

        .table-grid tbody td {
            padding: 10px 6px;
        }

        .status-box {
            padding: 4px 8px;
            font-size: 0.65rem;
        }
        
        .btn-back {
            width: 36px;
            height: 36px;
            font-size: 1rem;
        }
    }

    /* Pastikan semua icon terlihat */
    .bi {
        display: inline-block;
        vertical-align: middle;
    }
</style>
@endpush

@section('content')
<div class="container-detail">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <!-- Tombol Back dengan teks alternatif -->
            <a href="{{ url()->previous() }}" class="btn-back me-3 shadow-sm no-print" title="Kembali">
                <i class="bi bi-arrow-left"></i>
                <span style="display: none;">Kembali</span> <!-- Teks tersembunyi untuk aksesibilitas -->
            </a>
            <div>
                <h2 class="fw-800 mb-1" style="color: var(--dark-navy); font-size: 1.4rem;">
                    Rincian Tunggakan CC
                </h2>
                <span class="badge-satker">{{ $namaSatker }}</span>
            </div>
        </div>
        
        <div class="no-print d-flex gap-2">
            <input type="text" id="tableSearch" class="search-field shadow-sm" 
                   placeholder="Cari data..." aria-label="Search data">
            
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

    <!-- Table Section -->
    <div class="table-container shadow-sm">
        <table class="table-grid text-center" id="detailTable">
            <thead>
                <tr>
                    <th style="width: 50px;">NO</th>
                    <th style="width: 220px;">NOMOR PERKARA</th>
                    <th style="width: 140px;">TGL DAFTAR</th>
                    <th style="width: 350px;">JENIS PERKARA</th>
                    <th style="width: 180px;">PROSES TERAKHIR</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $row)
                <tr>
                    <td class="text-muted fw-bold">{{ $index + 1 }}</td>
                    <td>
                        <a href="#" class="no-perkara" title="Lihat detail perkara">
                            {{ $row->nomor_perkara }}
                        </a>
                    </td>
                    <td>{{ date('d/m/Y', strtotime($row->tanggal_pendaftaran)) }}</td>
                    <td class="fw-600 text-dark">{{ $row->jenis_perkara_nama }}</td>
                    <td>
                        <span class="status-box" title="Status proses terakhir">
                            {{ $row->proses_terakhir_text }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-5 text-muted fw-bold text-center">
                        <i class="bi bi-inbox me-2" style="font-size: 1.2rem;"></i>
                        TIDAK ADA DATA TUNGGAKAN
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    (function() {
        'use strict';

        // Cek apakah Bootstrap Icons tersedia
        function checkBootstrapIcons() {
            var testIcon = document.createElement('i');
            testIcon.className = 'bi bi-arrow-left';
            testIcon.style.position = 'absolute';
            testIcon.style.visibility = 'hidden';
            document.body.appendChild(testIcon);
            
            var iconAvailable = window.getComputedStyle(testIcon).fontFamily.includes('bootstrap-icons');
            document.body.removeChild(testIcon);
            
            if (!iconAvailable) {
                // Fallback: tambahkan panah teks biasa
                var backButton = document.querySelector('.btn-back');
                if (backButton) {
                    backButton.innerHTML = '←';
                    backButton.style.fontSize = '1.5rem';
                    backButton.style.fontWeight = 'bold';
                    backButton.style.lineHeight = '1';
                }
            }
        }

        // Cek saat halaman selesai dimuat
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', checkBootstrapIcons);
        } else {
            checkBootstrapIcons();
        }

        // Search functionality with debounce for better performance
        const searchInput = document.getElementById('tableSearch');
        if (searchInput) {
            let debounceTimer;
            
            searchInput.addEventListener('keyup', function() {
                clearTimeout(debounceTimer);
                
                debounceTimer = setTimeout(() => {
                    const searchTerm = this.value.toLowerCase().trim();
                    const rows = document.querySelectorAll('#detailTable tbody tr');
                    
                    rows.forEach(row => {
                        if (row.cells.length > 1) { // Skip empty state row
                            const textContent = row.textContent.toLowerCase();
                            row.style.display = textContent.includes(searchTerm) ? '' : 'none';
                        }
                    });
                }, 300);
            });

            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    this.value = '';
                    this.dispatchEvent(new Event('keyup'));
                }
            });
        }

        // Handle print optimization
        window.onbeforeprint = function() {
            // Any pre-print preparations can go here
        };

        window.onafterprint = function() {
            // Any post-print cleanup can go here
        };
    })();
</script>
@endpush
@endsection