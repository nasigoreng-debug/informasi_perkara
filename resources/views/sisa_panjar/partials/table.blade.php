<div class="table-responsive">
    <table class="table table-bordered align-middle bg-white shadow-sm">
        <thead class="table-dark text-center">
            <tr>
                <th width="5%">No</th>
                <th>Satker</th>
                <th>Nomor Perkara (Tk.I)</th>
                <th>Nomor Perkara {{ $label }}</th>
                <th>Tgl Putusan {{ $label }}</th> {{-- Kolom Baru --}}
                <th>Pemberitahuan Putusan</th>
                <th>Usia (Bulan)</th>
                <th>Sisa Saldo</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            
            @forelse($data->groupBy('satker_key') as $satker => $group)
                @php 
                    $subTotal = 0; 
                    $no = 1; 
                @endphp
                
                @foreach($group as $row)
                    @php 
                        $subTotal += $row->sisa; 
                        $grandTotal += $row->sisa;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td class="text-center fw-bold">{{ $satker }}</td>
                        <td>{{ $row->nomor_perkara }}</td>
                        <td>{{ $row->nomor_perkara_atas }}</td>
                        <td class="text-center">{{ $row->tgl_putusan }}</td> {{-- Data Tanggal Putusan --}}
                        <td class="text-center">{{ $row->tgl_notif }}</td>
                        <td class="text-center text-danger fw-bold">{{ $row->selisih_bulan }}</td>
                        <td class="text-end text-dark">Rp {{ number_format($row->sisa, 0, ',', '.') }}</td>
                    </tr>
                @endforeach

                {{-- BARIS SUBTOTAL PER SATKER (SUDAH CENTER) --}}
                <tr class="table-warning fw-bold">
                    <td colspan="7" class="text-center text-uppercase py-2">
                        Total Sisa {{ $label }} Satker {{ $satker }}
                    </td>
                    <td class="text-end text-dark">
                        Rp {{ number_format($subTotal, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">Data tidak ditemukan.</td>
                </tr>
            @endforelse
        </tbody>

        {{-- BARIS GRAND TOTAL (SUDAH CENTER) --}}
        @if($grandTotal > 0)
        <tfoot class="table-dark">
            <tr class="fw-bold">
                <td colspan="7" class="text-center text-uppercase py-3">
                    GRAND TOTAL SELURUH WILAYAH
                </td>
                <td class="text-end fs-5 text-warning">
                    Rp {{ number_format($grandTotal, 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
        @endif
    </table>
</div>