<!-- resources/views/reports/transaksi/index.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-center">Report transaksi</h1>
    <form method="GET" action="{{ route('report.transaksi') }}" class="mb-6 bg-white p-4 rounded-lg shadow">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                <input 
                    type="date" 
                    name="start_date" 
                    value="{{ request('start_date') }}" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-2 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                <input 
                    type="date" 
                    name="end_date" 
                    value="{{ request('end_date') }}" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-2 py-2">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Filter
                </button>

                <a href="{{ route('download.transaksi.pdf', request()->query()) }}"
                   class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export PDF
                </a>
            </div>
        </div>
    </form>
    <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
        <thead class="bg-gray-200">
            <tr>
                <th class="py-3 px-4 text-left">No</th>
                <th class="py-3 px-4 text-left">transaksi</th>
                <th class="py-3 px-4 text-left">Status</th>
                <th class="py-3 px-4 text-left">Jumlah Barang yang Dikerjakan</th>
                <th class="py-3 px-4 text-left">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksis as $index => $transaksi)
                <tr class="{{ $index % 2 == 0 ? 'bg-gray-50' : '' }}">
                    <td class="py-3 px-4">{{ $index + 1 }}</td>
                    <td class="py-3 px-4">{{ $transaksi->name }}</td>
                    <td class="py-3 px-4">{{ $transaksi->status? "Aktif" : "Tidak Aktif" }}</td>
                    <td class="py-3 px-4">{{ $transaksi->total_barang }}</td>
                    <td class="py-3 px-4">
                        <a href="{{ route('report.transaksi.show', $transaksi->transaksi_id) }}" class="text-blue-500 hover:underline">Lihat Detail</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if ($transaksis->isEmpty())
        <p class="text-center text-gray-600 mt-4">Tidak ada transaksi ditemukan.</p>
    @endif
</div>
@endsection