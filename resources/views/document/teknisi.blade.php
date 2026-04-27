<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kinerja Teknisi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 20px;
        }
        h1, h2 {
            text-align: center;
            margin-bottom: 10px;
        }
        .header {
            margin-bottom: 30px;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .teknisi-header {            
            font-size: 13px;
            font-weight: bold;
        }
        .category-row {
            background-color: #f9f9f9;
        }
        ul {
            margin: 5px 0;
            padding-left: 20px;
        }
        li {
            margin: 3px 0;
        }
        .footer {
            margin-top: 50px;
            text-align: right;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN KINERJA TEKNISI</h1>
        <h2>
            Periode: 
            {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d/m/Y') : 'Awal' }} 
            s/d 
            {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d/m/Y') : 'Sekarang' }}
        </h2>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d F Y, H:i') }}</p>
    </div>

    @if(empty($reportData))
        <p style="text-align:center; font-size:14px;">Tidak ada data teknisi pada periode ini.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th width="18%">Teknisi</th>
                    <th width="18%">Status</th>
                    <th width="22%">Category</th>
                    <th width="10%">Jumlah Barang</th>
                    <th width="50%">Daftar Barang</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reportData as $data)
                    @php
                        $teknisi = $data['teknisi'];
                        $details = $data['details'];
                        $firstRow = true;
                    @endphp

                    @foreach($details as $productId => $group)
                        <tr>
                            @if($firstRow)
                                <td rowspan="{{ $details->count() }}" class="teknisi-header">
                                    {{ $teknisi->name }}<br>                                    
                                </td>                          
                                <td rowspan="{{ $details->count() }}" class="teknisi-header">
                                    {{ $teknisi->status? "Aktif": "Tidak Aktif" }}<br>                                    
                                </td>      
                                @php $firstRow = false; @endphp
                            @endif

                            <td class="category-row">
                                <strong>
                                    {{ $group->first()->product ? $group->first()->product->name : 'Unknown Category' }}
                                </strong>
                                <br>
                                <small>SN: {{ $group->first()->product ? $group->first()->product->code : 'N/A' }}</small>
                            </td>

                            <td style="text-align:center;">
                                <strong>{{ $group->count() }}</strong>
                            </td>

                            <td>
                                <ul>
                                    @foreach($group as $item)
                                        <li>
                                            <strong>{{ $item->product_name }}</strong>
                                            @if($item->serial_number)
                                                (SN: {{ $item->serial_number }})
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        Laporan ini digenerate otomatis oleh sistem.
    </div>
</body>
</html>