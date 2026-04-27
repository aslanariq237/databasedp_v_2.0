@extends('layouts.app')

@section('page-title', 'Detail Surat Jalan - ' . $suratjalan->code)

@section('content')
<div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">View Surat Jalan</h1>            
            <p class="text-sm text-gray-500 font-semibold">Document - Surat Jalan - Lihat Surat Jalan</p>
        </div>
        <div class="flex gap-4">
            <a 
                href="{{ route('surat-jalan.index') }}"
                class="px-6 py-3 border border-slate-700 rounded-lg shadow"
            >
                Kembali
            </a>            
            <a href="{{ route('download.suratjalan.pdf', $suratjalan->suratjalan_id) }}" target="_blank" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                Print PDF
            </a>
        </div>
    </div>
<div class="max-w-7xl mx-auto p-6 bg-white rounded-lg shadow-md">    
    <!-- Info Header -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="bg-gray-50 p-4 rounded-lg border">
            <label class="block text-sm font-medium text-gray-700">Kode SJ</label>
            <p class="mt-1 text-xl font-semibold">{{ $suratjalan->code }}</p>
        </div>
        <div class="bg-gray-50 p-4 rounded-lg border">
            <label class="block text-sm font-medium text-gray-700">Nama / Keterangan</label>
            <p class="mt-1 text-xl font-semibold">{{ $suratjalan->name }}</p>
        </div>
        <div class="bg-gray-50 p-4 rounded-lg border">
            <label class="block text-sm font-medium text-gray-700">Status</label>
            <span class="mt-1 inline-block px-4 py-1 text-sm rounded-full font-medium
                @if($suratjalan->status == 'draft') bg-gray-100 text-gray-800
                @elseif($suratjalan->status == 'sent') bg-blue-100 text-blue-800
                @elseif($suratjalan->status == 'received') bg-green-100 text-green-800
                @elseif($suratjalan->status == 'cancelled') bg-red-100 text-red-800
                @else bg-yellow-100 text-yellow-800
                @endif">
                {{ ucfirst($suratjalan->status) }}
            </span>
        </div>
        <div class="bg-gray-50 p-4 rounded-lg border">
            <label class="block text-sm font-medium text-gray-700">Tanggal Issue</label>
            <p class="mt-1 text-xl font-semibold">{{ $suratjalan->issue_at ? \Carbon\Carbon::parse($suratjalan->issue_at)->format('d M Y') : '-' }}</p>
        </div>
    </div>

    <!-- Customer Info -->
    {{-- <div class="mb-10 mt-4 p-6 bg-blue-50 border border-blue-200 rounded-lg">
        <h2 class="text-xl font-semibold mb-4">Informasi Customer</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama</label>
                <p class="mt-1 text-lg">{{ $suratjalan->customer->name ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Perusahaan</label>
                <p class="mt-1 text-lg">{{ $suratjalan->customer->company ?? '-' }}</p>
            </div>
        </div>
    </div> --}}

    <!-- Detail Barang -->
    <div>
        <h2 class="text-2xl font-bold mb-4 mt-4">Daftar Barang yang Dikirim</h2>
        <div class="overflow-x-auto border rounded-lg shadow-inner">
            <table class="w-full min-w-max">
                <thead class="bg-indigo-600 text-white">
                    <tr>
                        <th class="p-4 text-left">No</th>
                        <th class="p-4 text-left">Nama Barang</th>
                        <th class="p-4 text-left">Serial Number</th>
                        <th class="p-4 text-left">Invoice</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($suratjalan->details as $detail)
                        <tr class="hover:bg-gray-50">
                            <td class="p-4">{{ $loop->iteration }}</td>
                            <td class="p-4 font-medium">{{ $detail->product_name }}</td>
                            <td class="p-4">{{ $detail->serial_number ?? '-' }}</td>
                            <td class="p-4">{{ $detail->invoice->code ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-gray-500">
                                Belum ada barang di Surat Jalan ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>        
    </div>        
</div>
@endsection