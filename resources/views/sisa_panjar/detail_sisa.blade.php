@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold">Detail Perkara: {{ $satker }} ({{ ucfirst($jenis) }})</h4>

    <div class="table-responsive mt-4">
        <table class="table table-bordered bg-white">
            <thead>
                <tr>
                    <th>No Perkara</th>
                    <th>No Perkara Upaya Hukum</th>
                    <th>Tgl Putusan</th>
                    <th>Tgl Pemberitahuan Putusan</th>
                    <th>Sisa Panjar</th>
                </tr>
            </thead>
            <tbody>
                @foreach($listPerkara as $perkara)
                <tr>
                    <td>{{ $perkara->nomor_perkara }}</td>
                    <td>{{ $perkara->nomor_perkara_atas }}</td>
                    <td>{{ $perkara->tgl_putusan }}</td>
                    <td>{{ $perkara->tgl_notif }}</td>
                    <td class="text-end">Rp {{ number_format($perkara->sisa, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection