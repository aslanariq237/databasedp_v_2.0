@extends('layouts.app')

@section('page-title', 'Daftar Invoice')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div class="">
            <h1 class="text-2xl font-bold text-gray-900">Invoice</h1>
            <p class="text-sm text-gray-500 font-semibold">Document - Invoice</p>
        </div>
        <a href="{{ route('invoices.create') }}" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">
            + Buat Invoice Baru
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET">
            <div class="grid grid-cols-3 md:grid-cols-3 gap-4 items-end">                
                <!-- Input Pencarian -->
                <div class="md:col-span-1">
                    <label class="text-sm font-medium text-gray-700 mb-1">Pencarian</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Ketik kata kunci..."
                        class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>  
                <!-- Dari Tanggal -->
                <div class="md:col-span-1">
                    <label class="text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}"
                        class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        placeholder="Dari Tanggal">
                </div>

                <!-- Sampai Tanggal -->
                <div class="md:col-span-1">
                    <label class="text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}"
                        class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        placeholder="Sampai Tanggal">
                </div>               
            </div>
            <div class="grid grid-cols-3 md:grid-cols-3 gap-4 items-end mt-3">                           
                <!-- Tombol Cari & Reset -->
                <div class="md:col-span-1 flex gap-2">
                    <button type="submit" class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                        Cari
                    </button>
                    <a href="{{ route('receives.index') }}" class="flex-1 text-center bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 block">
                        Reset
                    </a>
                </div>
            </div>                     
        </form>
    </div>

    <!-- Table Invoice -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Invoice</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Receive</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($invoices as $invoice)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium">{{ $invoice->code }}</td>
                        <td class="px-6 py-4 text-sm">{{ $invoice->receive->code ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm">
                            {{ $invoice->customer ? $invoice->customer->name : $invoice->customer_name }} - 
                            {{ $invoice->customer ? $invoice->customer->code : $invoice->kode_toko }}
                        </td>
                        <td class="px-6 py-4 text-sm">Rp {{ number_format($invoice->grand_total, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-3 py-1 text-xs rounded-full {{ $invoice->status_payment == 'full' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $invoice->status_payment == 'full' ? 'Lunas' : 'Belum Lunas' }}
                            </span>
                        </td>                        
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('invoices.show', $invoice) }}" class="inline-flex items-center px-2 py-2.5 bg-blue-700 text-white font-medium rounded-lg shadow-md hover:bg-blue-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Lihat
                            </a>                            
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-500">
                            Belum ada invoice
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="px-6 py-4 border-t bg-gray-50">
            {{ $invoices->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection