@extends('layouts.app')

@section('page-title', 'Detail Pembayaran - Invoice #' . $invoice->code)

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div class="">
            <h1 class="text-2xl font-bold">Detail Pembayaran </h1>
            <p class="text-sm text-gray-500 font-semibold">Finance - Pembayaran</p>
        </div>        
        <!-- Tombol Aksi -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('receivables.index') }}" class="px-6 py-2 border rounded-lg hover:bg-gray-100">
                Kembali
            </a>
            <button type="button" id="open-bayar-modal"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Bayar Invoice
            </button>
            <a href="{{ route('download.invoice.pdf', $invoice) }}" target="_blank"
            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                Cetak Invoice
            </a>
            <a href="{{ route('download.kwitansi.pdf', $invoice) }}" target="_blank"
            class="px-6 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">
                Cetak Kwitansi
            </a>
        </div>
    </div>

    <!-- Info Invoice -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <p class="text-sm text-gray-600">Invoice</p>
                <p class="font-semibold text-lg">
                    {{ $invoice->code}}
                </p>                
            </div>
            <div>
                <p class="text-sm text-gray-600">Customer</p>
                <p class="font-semibold text-lg">
                    {{ $invoice->customer_name ?? $invoice->customer?->name ?? '-' }}
                </p>
                @if($invoice->customer_company)
                    <p class="text-sm text-gray-600">{{ $invoice->customer_company }}</p>
                @endif
            </div>            
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-4 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">            
            <div>
                <p class="text-sm text-gray-600">Tanggal Invoice</p>
                <p class="font-semibold">{{\Carbon\Carbon::parse($invoice->issue_at)->format('d M Y')}}</p>                
            </div>
            <div>
                <p class="text-sm text-gray-600">Jatuh Tempo</p>
                <p class="font-semibold">{{\Carbon\Carbon::parse($invoice->issue_at)->format('d M Y')}}</p>                
            </div>
            <div>
                <p class="text-sm text-gray-600">Status Pembayaran</p>
                <span class="inline-flex px-4 py-2 text-sm font-medium rounded-full
                    {{ $invoice->status_payment == 'full' ? 'bg-green-100 text-green-800' :
                       ($invoice->status_payment == 'partial' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                    {{ $invoice->status_payment == 'full' ? 'Lunas' : ($invoice->status_payment == 'partial' ? 'Sebagian' : 'Belum Bayar') }}
                </span>
            </div>
        </div>
    </div>

    <!-- Table Detail -->
    <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Barang</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Serial Number</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Harga</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($invoice->details as $detail)
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium">{{ $detail->product_name }}</td>
                        <td class="px-6 py-4 text-sm">{{ $detail->serial_number ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-3 py-1 text-xs rounded-full
                                {{ $detail->status == 'Selesai' ? 'bg-green-100 text-green-800' :
                                   ($detail->status == 'Penawaran' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ $detail->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-right">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center py-8 text-gray-500">Tidak ada detail</td></tr>
                @endforelse
            </tbody>
            <tfoot class="bg-gray-50">
                <tr>
                    <td colspan="3" class="px-6 py-4 text-right font-medium">Sub Total</td>
                    <td class="px-6 py-4 text-right">Rp {{ number_format($invoice->sub_total, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="3" class="px-6 py-4 text-right font-medium">PPN (11%)</td>
                    <td class="px-6 py-4 text-right">Rp {{ number_format($invoice->ppn, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="3" class="px-6 py-4 text-right font-medium">Jasa Service</td>
                    <td class="px-6 py-4 text-right">Rp {{ number_format($invoice->jasa_service, 0, ',', '.') }}</td>
                </tr>
                <tr
                 class="font-bold text-lg">
                    <td colspan="3" class="px-6 py-4 text-right">Grand Total</td>
                    <td class="px-6 py-4 text-right text-indigo-600">
                        Rp {{ number_format($invoice->grand_total, 0, ',', '.') }}
                    </td>
                </tr>
                <tr class="border border-2 border-slate-300">                    
                </tr>
                <tr class="font-bold text-lg">
                    <td colspan="3" class="px-6 py-4 text-right">Terbayar</td>
                    <td class="px-6 py-4 text-right text-indigo-600">
                        Rp {{ number_format($invoice->deposit, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>    
</div>

<!-- Modal Bayar -->
<div id="bayar-modal" class="fixed inset-0 bg-transparant bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-xl p-8 max-w-md w-full">
        <h2 class="text-2xl font-bold mb-6 text-center">Pembayaran Invoice</h2>
        <p class="text-center text-gray-600 mb-6">
            Invoice #{{ $invoice->code }}<br>
            Grand Total: <strong>Rp {{ number_format($invoice->grand_total, 0, ',', '.') }}</strong>
        </p>

        <form action="{{ route('receivables.bayar', $invoice) }}" method="POST">
            @csrf
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Jumlah yang Dibayar <span class="text-red-400">*</span>
                </label>
                <input type="number" name="jumlah_bayar" required min="0"
                       placeholder="0"
                       class="w-full border rounded-lg px-4 py-3 text-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex justify-end space-x-4">
                <button type="button" id="close-bayar-modal"
                        class="px-6 py-2 border rounded-lg hover:bg-gray-100">
                    Batal
                </button>
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Konfirmasi Pembayaran
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('bayar-modal');
    const openBtn = document.getElementById('open-bayar-modal');
    const closeBtn = document.getElementById('close-bayar-modal');

    openBtn.addEventListener('click', () => modal.classList.remove('hidden'));
    closeBtn.addEventListener('click', () => modal.classList.add('hidden'));
    modal.addEventListener('click', (e) => {
        if (e.target === modal) modal.classList.add('hidden');
    });
</script>
@endsection