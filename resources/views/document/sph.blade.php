<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Penawaran #{{ $receivedetail->receive->code }}</title>
    <style>
        @page { margin: 20px; }
        body { font-family: Arial, sans-serif; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; }
        th { background: #f0f0f0; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .total-row { font-weight: bold; background: #f5f5f5; }
    </style>
</head>
<body>

<h2 style="text-align:center">SURAT PENAWARAN HARGA</h2>

<p>
    Kepada Yth:<br>
    <strong>{{ $receivedetail->customer_name ?? $receivedetail->customer?->name }}</strong><br>
    Bekasi, {{ \Carbon\Carbon::parse($receivedetail->receive->issue_at)->format('d-m-Y') }}
</p>

<p>
    Bersama ini kami ajukan penawaran harga dengan rincian sebagai berikut:
</p>

<table>
    <thead>
        <tr>
            <th width="5%">No</th>
            <th width="20%">Nama Barang</th>
            <th width="20%">Nama Toko</th>
            <th>Spesifikasi</th>
            <th width="5%">Qty</th>
            <th width="15%">Harga</th>
        </tr>
    </thead>
    <tbody>
        @php $no = 1; @endphp

        @foreach($receivedetail->keluhans as $index => $keluhan)
            <tr>
                {{-- No --}}
                <td class="text-center">
                    {{ $index === 0 ? $no++ : '' }}
                </td>

                {{-- Nama Barang --}}
                <td>
                    {{ $index === 0 ? $receivedetail->product_name : '' }}
                </td>

                {{-- Nama Toko --}}
                <td>
                    {{ $index === 0 ? ($receivedetail->customer?->name ?? '-') : '' }}
                </td>

                {{-- Spesifikasi --}}
                <td>
                    {{ $keluhan->keluhan }}
                </td>

                {{-- Qty --}}
                <td class="text-center">1</td>

                {{-- Harga PER KELUHAN --}}
                <td class="text-right">
                    Rp {{ number_format($keluhan->price, 0, ',', '.') }}
                </td>
            </tr>
        @endforeach

        {{-- SUB TOTAL --}}
        <tr class="total-row">
            <td colspan="5" class="text-right">Sub Total</td>
            <td class="text-right">
                Rp {{ number_format($receivedetail->price, 0, ',', '.') }}
            </td>
        </tr>
        {{-- JASA SERVICE --}}
        @if($receivedetail->receive->jasa_service > 0)
            <tr>
                <td colspan="5" class="text-right">Jasa Service</td>
                <td class="text-right">
                    Rp {{ number_format($receivedetail->receive->jasa_service, 0, ',', '.') }}
                </td>
            </tr>
        @endif        

        {{-- PPN --}}
        <tr class="total-row">
            <td colspan="5" class="text-right">PPN 12%</td>
            <td class="text-right">
                Rp {{ number_format($receivedetail->receive->ppn, 0, ',', '.') }}
            </td>
        </tr>

        {{-- GRAND TOTAL --}}
        <tr class="total-row">
            <td colspan="5" class="text-right">TOTAL</td>
            <td class="text-right">
                Rp {{ number_format($receivedetail->price + $receivedetail->receive->jasa_service + $receivedetail->receive->ppn, 0, ',', '.') }}
            </td>
        </tr>
    </tbody>
</table>

<p style="margin-top:40px">
    Demikian surat penawaran ini kami sampaikan, atas perhatian dan kerja samanya kami ucapkan terima kasih.
</p>

</body>
</html>
