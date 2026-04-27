<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoice->code }}</title>
    <style>
        @page {
            size: A4 landscape;            
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 24px;            
            color: black;
        }
        .container {
            padding: : 20px;
            height: 100vh;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .border-none {
            border: none !important;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .text-bold {
            font-weight: bold;
        }
        .logo-box {
            border: 2px solid black;            
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .logo-inner {
            border: 2px solid black;            
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 70px;
            font-weight: bold;
        }
        .header-title {
            margin-left: 140px;
            margin-top: -10px;
        }
        .lines{
            border: 1px solid gray;
            width: 80px;
            margin-top: 80px;
        }
        .line {
            border-bottom: 2px solid black;
            margin: 20px 0;
        }
        .invoice-title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin: 20px 0;
        }
        .info-table td {
            padding: 5px 0;
            vertical-align: top;
        }
        h4{
            margin: 0px;
        }
        .styled-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 16px;
        }
        .styled-table th,
        .styled-table td {
            border: 1px solid black;
            padding: 5px;
        }
        td{
            font-size: 20px;
        }        
        .styled-table th {
            background-color: #f0f0f0;
            text-align: center;
            font-size: 20px;
        }
        .styled-table ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .styled-table li {
            padding: 4px 0;
            font-size: 20px;
        }
        p{
            margin: 0%;
        }
        ul{
            margin: 0;
            font-size: px;                
        } 
        .signature {
            display: inline-block;
            justify-content: space-between;
            margin-top: 80px;
            font-size: 16px;
        }
        /* .signature-line {
            border-bottom: 1px solid black;
            width: 300px;
            margin: 50px auto 10px auto;
        } */
    </style>
</head>
<body>
    <div class="container">
        <!-- Header dengan Logo DP -->
        <div class="title mb-2" style="text-align: center">                                
            <table style="margin: 0">
                <tr>
                    <td style="border: none; margin: 0">
                        <div style="border: 2px solid black; height: 100px; width: 120px">
                            <div style="border: 2px solid black; text-align:center; margin: 2px">
                                <h1 style="font-size: 75px; margin: 0">DP</h1>
                            </div>
                        </div>
                    </td>
                    <td style="border: none; margin: 0;">
                        <div class="center text-center">
                            <h1 style="font-size: 45px; margin: 0;">CV. DATA PRINT</h1>
                            <h4 style=""><strong>Jl. Cemara Raya No.4 Ruko Harapan Jaya</strong></h4>
                            <h4 style=""><strong>dataprint2012@yahoo.co.id</strong></h4>
                            <h4 style="">Advance : Computer, Printer, Monitor, Accessories, Sparepart, Sales & Services</h4>
                        </div>
                    </td>
                </tr>
            </table>             
        </div>

        <div class="line"></div>

        <!-- Judul Invoice -->
        <div class="invoice-title" style="margin: 0%">INVOICE PEMBAYARAN</div>

        <!-- Info Customer & Nota -->
        <table class="info-table border-none" style="font-size: 16px;">
            <tr class="border-none">
                <td class="border-none" style="width: 50%;">
                    <p style="margin: 0;">Nama Customer:</p>
                    <p class="text-bold" style="font-size: 18px; margin: 5px 0;">
                        {{ $invoice->customer? $invoice->customer?->company : $invoice->name}}
                        {{-- {{ $invoice->customer ?? $invoice?->customer?->name ?? $invoice->customer_company }} --}}
                    </p>
                    @if($invoice->customer_company)
                        <p class="text-bold" style="font-size: 18px; margin: 5px 0;">{{ $invoice->customer_company }}</p>
                    @endif
                </td>
                <td class="border-none text-right" style="width: 50%;">
                    <p>order date: {{\Carbon\Carbon::parse($invoice->issue_at)->format('Y-m-d')}}</p>
                    <p class="text-bold">rek BCA : 0663070468</p>
                    <p class="text-bold">a/n: CV. Data Print</p>
                    <p class="text-bold" style="font-size: 20px;">No.Nota : {{ $invoice->code }}</p>
                </td>
            </tr>
        </table>

        <!-- Table Barang + Keluhan (dengan rowspan jika ada banyak keluhan) -->
        <table class="styled-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody class="margin: 0; padding: 0;">
                @php $no = 1; @endphp
                @foreach($invoice->details->groupBy('product_name') as $productName => $groupedDetails)
                    @foreach($groupedDetails as $index => $detail)
                        <tr style="font-size: 17px margin: 0; padding: 0;">
                            @if($index === 0)
                                <td rowspan="{{ $groupedDetails->count() }}" class="text-center" style="vertical-align: start; margin: 0; padding: 0;">
                                    {{ $no++ }}
                                </td>
                                <td rowspan="{{ $groupedDetails->count() }}" style="vertical-align: middle;">
                                    {{ $detail->product_name }}, sn: {{$detail->serial_number}} (
                                    @if($invoice->customer_id != null)
                                        {{ $invoice->customer?->name}} - {{ $invoice->customer?->kode_toko }}
                                    @else
                                        {{ $invoice->customer_name }} - {{ $invoice->kode_toko }}
                                    @endif
                                    )
                                    <ul>                                        
                                        @foreach($detail->keluhan as $kel)
                                            <li>{{ $kel->keluhan }}</li>
                                        @endforeach                                        
                                    </ul>
                                </td>
                                <td rowspan="{{ $groupedDetails->count() }}" class="text-right">
                                    <ul style="margin: 0; padding: 0 text-align:right">
                                        @foreach($detail->keluhan as $kel)
                                            <li>
                                                Rp. {{ number_format($kel->price)}}
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                            @endif                            
                        </tr>
                    @endforeach
                @endforeach

                <!-- Jasa Service -->
                @if($invoice->jasa_service > 0)
                    <tr>
                        <td></td>
                        <td>Jasa Service</td>
                        <td class="text-right">Rp {{ number_format($invoice->jasa_service, 0, ',', '.') }}</td>
                    </tr>
                @endif

                <!-- Sub Total -->
                <tr style="background-color: #f0f0f0;">
                    <td></td>
                    <td class="text-right text-bold">SubTotal</td>
                    <td class="text-right">Rp {{ number_format($invoice->sub_total, 0, ',', '.') }}</td>
                </tr>

                <!-- Sub DPP (untuk PPN 11%) -->
                <tr style="background-color: #f0f0f0;">
                    <td></td>
                    <td class="text-right text-bold">Sub DPP</td>
                    <td class="text-right">Rp {{ number_format($invoice->sub_total * 100/111, 0, ',', '.') }}</td>
                </tr>

                <!-- PPN -->
                <tr style="background-color: #f0f0f0;">
                    <td></td>
                    <td class="text-right text-bold">PPN</td>
                    <td class="text-right">Rp {{ number_format($invoice->ppn, 0, ',', '.') }}</td>
                </tr>

                <!-- Total Akhir -->
                <tr style="border: 2px solid black; background-color: #e0e0e0;">
                    <td></td>
                    <td class="text-right text-bold" style="font-size: 18px;">Total</td>
                    <td class="text-right text-bold" style="font-size: 18px;">
                        Rp {{ number_format($invoice->grand_total, 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>          
        </table>

        <!-- Tanda Tangan -->
        <table style="width: 100%">
            <tr>
                <td style="border: white solid; text-align: left;">
                    <h3>Hormat Kami</h3>
                    <div class="lines" style="margin-top: 10px;"></div>
                </td>
                <td style="border: white solid; text-align:right;">
                    <h3>Diterima Oleh</h3>
                    <div class="lines" style="float:right; margin-top: 10px"></div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>