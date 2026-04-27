<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwitansi #{{ $invoice->code }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 20px;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .border-none {
            border: none !important;
        }
        .logo-box {
            border: 2px solid black;
            width: 100px;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .logo-inner {
            border: 2px solid black;
            width: 92px;
            height: 92px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 75px;
            font-weight: bold;
        }
        .header-title {
            margin-left: 140px;
            margin-top: -10px;
        }
        .line {
            border-bottom: 2px solid black;
            margin: 20px 0;
        }
        .kwitansi-title {
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            margin: 20px 0;
            position: relative;
        }
        h4{
            margin: 0px;
        }
        .kwitansi-title::after {
            content: '';
            border-bottom: 1px solid black;
            width: 300px;
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
        }
        .info-table td {
            padding: 8px 0;
            font-size: 22px;
        }
        .underline {
            border-bottom: 1px solid black;
            display: inline-block;
            width: 100%;
        }
        .jumlah-box {
            border: 1px solid black;
            padding: 10px;
            text-align: right;
            font-size: 22px;
        }
        .signature {
            display: flex;
            justify-content: space-between;
            margin-top: 80px;
            font-size: 22px;
        }
        .signature-line {
            border-bottom: 1px solid black;
            width: 300px;
            margin: 20px auto 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="home">
            <!-- Header dengan Logo DP -->
            <div class="title mb-2" style="text-align: center">                                
                <table style="widht: 100%;margin: 0">
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

            <!-- Judul Kwitansi -->
            <div class="kwitansi-title">KWITANSI</div>
            <p style="text-align: center; font-size: 20px; margin-top: 20px;">No : {{ $invoice->code }}</p>

            <!-- Isi Kwitansi -->
            <table class="info-table border-none" style="margin-top: 30px;">
                <tr>
                    <td style="width: 200px;">Sudah Terima Dari</td>
                    <td>:</td>
                    <td class="underline">
                        {{ $invoice->customer?->name ?? $invoice->customer_name ?? 'Customer Umum' }}
                        @if($invoice->customer_company)
                            <br>{{ $invoice->customer_company }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Banyaknya Uang</td>
                    <td>:</td>
                    <td class="underline">
                        <strong>{{ ucwords(terbilang_rupiah($invoice->grand_total)) }} Rupiah</strong>
                    </td>
                </tr>
                <tr>
                    <td>Uang Pembayaran</td>
                    <td>:</td>
                    <td class="underline ">
                        @foreach($invoice->details as $detail)
                            {{ $detail->product_name }}
                            @if(!$loop->last), @endif
                        @endforeach                    
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td class="underline"></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td class="underline" style="font-size: 22px;">Tanda Terima Barang</td>
                </tr>
            </table>

            <!-- Jumlah Total -->
            <table style="width: 100%; margin-top: 30px;">
                <tr>
                    <td style="width: 70%;">
                        <div class="jumlah-box">
                            Jumlah <span style="float: right;"> Rp. {{ number_format($invoice->grand_total, 0, ',', '.') }}</span>
                        </div>
                    </td>
                    <td style="text-align: center; font-size: 22px;">
                        <p>Bekasi, {{\Carbon\Carbon::parse($invoice->issue_at)->format('Y-m-d')}}</p>
                        <p style="font-weight: bold; margin-top: 40px;">CV. Data Print</p>
                    </td>
                </tr>
            </table>

            <!-- Tanda Tangan -->
            <div class="signature">
                <div></div> <!-- kosong kiri -->
                <div>
                    <div class="signature-line"></div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>