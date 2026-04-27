@extends('layouts.app')

@section('page-title', 'Invoice #'. $invoice->code)

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Invoice #{{ $invoice->code }}</h1>
            <p class="text-sm text-gray-500 font-semibold">
                Receive: {{ $invoice->receive->code }} - {{ $invoice->receive->name }}
            </p>
        </div>
        <div class="flex space-x-4">
            <a href="{{ route('invoices.index') }}"
               class="px-6 py-2 border rounded-lg hover:bg-gray-100">
                Kembali
            </a>
            <a href="{{ route('download.invoice.pdf', $invoice) }}"
               target="_blank"
               class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Cetak Invoice
            </a>
        </div>
    </div>

    <!-- Info Invoice -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-600">Customer</p>
                <p class="font-semibold text-gray-900">
                    {{ $invoice->customer?->name ?? $invoice->customer_name ?? '-' }}
                    @if($invoice->customer_company)
                        <br><span class="text-sm text-gray-600">{{ $invoice->customer_company }}</span>
                    @endif
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Kode Toko</p>
                <p class="font-semibold text-gray-900">{{ $invoice->kode_toko ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Tanggal Invoice Dibuat</p>
                <p class="font-semibold">
                    {{\Carbon\Carbon::parse($invoice->issue_at)->format('d M Y')}}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Jatuh Tempo Pembayaran</p>
                <p class="font-semibold">
                    {{\Carbon\Carbon::parse($invoice->due_at)->format('d M Y')}}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Status Pembayaran</p>
                <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full
                    {{ $invoice->status_payment == 'full' ? 'bg-green-100 text-green-800' : 
                       ($invoice->status_payment == 'partial' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                    {{ $invoice->status_payment == 'full' ? 'Lunas' : 
                       ($invoice->status_payment == 'partial' ? 'Sebagian' : 'Belum Bayar') }}
                </span>
            </div>
            <div>
                <p class="text-sm text-gray-600">Jasa Service</p>
                <p class="font-semibold text-xl text-indigo-600">
                    Rp {{ number_format($invoice->jasa_service, 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Table Detail Invoice -->
    <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Detail Barang / Jasa</h3>
        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Barang</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Serial Number</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Harga</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($invoice->details as $detail)
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $detail->product_name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $detail->serial_number ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-3 py-1 text-xs rounded-full
                                {{ $detail->status == 'Selesai' ? 'bg-green-100 text-green-800' :
                                   ($detail->status == 'Penawaran' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ $detail->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-right font-medium">
                            Rp {{ number_format($detail->price, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-6 py-12 text-center text-gray-500">Tidak ada detail</td></tr>
                @endforelse
            </tbody>
            <tfoot class="bg-gray-50 border-t">
                <tr>
                    <td colspan="3" class="px-6 py-4 text-right font-medium">Sub Barang</td>
                    <td class="px-6 py-4 text-right">Rp {{ number_format($invoice->sub_total, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="3" class="px-6 py-4 text-right font-medium">Sub Service</td>
                    <td class="px-6 py-4 text-right">Rp {{ number_format($invoice->jasa_service, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="3" class="px-6 py-4 text-right font-medium">PPN (11%)</td>
                    <td class="px-6 py-4 text-right">Rp {{ number_format($invoice->ppn, 0, ',', '.') }}</td>
                </tr>                
                <tr class="text-lg font-bold">
                    <td colspan="3" class="px-6 py-4 text-right">Grand Total</td>
                    <td class="px-6 py-4 text-right text-indigo-600">
                        Rp {{ number_format($invoice->grand_total, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection