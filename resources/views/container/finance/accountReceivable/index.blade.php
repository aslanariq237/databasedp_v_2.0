@extends('layouts.app')

@section('page-title', 'Pembayaran')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-4">
        <h1 class="text-2xl font-bold">Pembayaran</h1>
        <p class="text-sm text-gray-500 font-semibold">Finance - Pembayaran</p>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET">
            <div class="grid grid-cols-3 md:grid-cols-3 gap-4 items-end">
                <!-- Tipe Pencarian -->
                <div class="md:col-span-1 hidden">
                    <label class="text-sm font-medium text-gray-700 mb-1">Tipe Pencarian</label>
                    <select name="search_type" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                        <option value="">🔍 Semua Field</option>
                        <option value="code" {{ request('search_type') == 'code' ? 'selected' : '' }}>Kode</option>
                        <option value="name" {{ request('search_type') == 'name' ? 'selected' : '' }}>Nama Customer</option>
                        <option value="product_name" {{ request('search_type') == 'product_name' ? 'selected' : '' }}>Nama Barang</option>
                        <option value="serial_number" {{ request('search_type') == 'serial_number' ? 'selected' : '' }}>Serial Number</option>
                    </select>
                </div>

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

    <div class="bg-white rounded-lg shadow overflow-hidden mt-4">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Invoice</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Receive</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Barang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jasa Service</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Invoice</th>                                                                  
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($invoices as $invoice)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $invoice->code }}</td>
                            <td class="px-6 py-4 text-sm">{{ $invoice->receive->code }}</td>
                            <td class="px-6 py-4 text-sm">
                                {{ $invoice->customer_name ?? $invoice->customer?->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm">Rp {{ number_format($invoice->sub_total, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-sm">Rp {{ number_format($invoice->jasa_service, 0, ',', '.') }}</td>
                            {{-- grand_total --}}
                            <td class="px-6 py-4 text-sm">Rp {{ number_format($invoice->grand_total, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-3 py-1 text-xs rounded-full
                                    {{ $invoice->status_payment == 'full' ? 'bg-green-100 text-green-800' :
                                       ($invoice->status_payment == 'partial' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ $invoice->status_payment == 'full' ? 'Lunas' : ($invoice->status_payment == 'partial' ? 'Sebagian' : 'Belum Bayar') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $invoice->issue_at}}</td>                            
                            <td class="px-6 py-4 text-right text-sm">
                                <a href="{{ route('receivables.show', $invoice) }}" class="inline-flex items-center px-2 py-2.5 bg-blue-700 text-white font-medium rounded-lg shadow-md hover:bg-blue-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200">
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
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                @if(
                                    strlen($invoices) == 0
                                )
                                    <span>belum ada invoice</span>
                                @else
                                    Semua invoice sudah lunas.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t bg-gray-50">
            {{ $invoices->links() }}
        </div>
    </div>
</div>
@endsection