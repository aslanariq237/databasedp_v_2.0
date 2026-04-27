@extends('layouts.app')

@section('page-title', 'Detail Penerimaan Barang - ' . $receive->code)

@section('content')
<div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Lihat Penerimaan Barang</h1>            
            <p class="text-sm text-gray-500 font-semibold">Receive - Penerimaan Barang - Lihat Penerimaan Barang</p>
        </div>
        <div class="flex gap-4">
            <a href="{{ route('receives.index') }}" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                Kembali
            </a>
            @if(!$receive->has_invoice)
                <a href="{{ route('receives.edit', $receive->receive_id) }}" class="px-6 py-3 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
                    Edit
                </a>
            @endif
            <form action="{{ route('receives.destroy', $receive->receive_id) }}" method="POST" class="inline">
                @csrf @method('DELETE')
                <button type="submit" onclick="return confirm('Yakin hapus data ini?')" class="px-6 py-3 bg-red-700 text-white rounded-lg hover:bg-red-800 transition">
                    Hapus
                </button>
            </form>
        </div>
    </div>
<div class="max-w-7xl mx-auto p-6 bg-white rounded-lg shadow-md">    
    <!-- Info Header -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
        <div class="bg-gray-50 p-4 rounded-lg border">
            <label class="block text-sm font-medium text-gray-700">Kode Receive</label>
            <p class="mt-1 text-xl font-semibold">{{ $receive->code }}</p>
        </div>
        <div class="bg-gray-50 p-4 rounded-lg border">
            <label class="block text-sm font-medium text-gray-700">Nama Perusahaan/ Customer</label>
            <p class="mt-1 text-xl font-semibold">{{ $receive->name }}</p>
        </div>
        <div class="bg-gray-50 p-4 rounded-lg border">
            <label class="block text-sm font-medium text-gray-700">Tanggal Issue</label>
            <p class="mt-1 text-xl font-semibold">{{ $receive->issue_at ? $receive->issue_at->format('d/m/Y') : '-' }}</p>
        </div>        
    </div>

    <!-- Detail Barang -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold mb-4">Daftar Barang yang Diterima</h2>
        <div class="overflow-x-auto border rounded-lg shadow-inner">
            <table class="w-full min-w-max">
                <thead class="bg-indigo-600 text-white">
                    <tr>
                        <th class="p-4 text-left">No</th>
                        <th class="p-4 text-left">Kode Customer</th>
                        <th class="p-4 text-left">Nama Barang</th>                        
                        <th class="p-4 text-left">Status Pengiriman</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($receive->details as $detail)
                        <tr class="hover:bg-gray-50">
                            <td class="p-4">{{ $loop->iteration }}</td>
                            <td class="p-4">{{ $detail?->customer ? $detail?->customer?->name : $detail->customer_name  }} - {{ $detail?->customer ? $detail?->customer?->code : ''}}</td>
                            <td class="p-4 font-medium">{{ $detail->product_name }} - {{ $detail->serial_number ?? '-' }}</td>                            
                            <td class="p-4">
                                <span class="px-3 py-1 text-xs rounded-full font-medium
                                    @if($detail->has_sj) bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ $detail->has_sj ? 'Sudah Dikirim' : 'Belum Dikirim' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-gray-500">
                                Belum ada detail barang.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>        
    </div>
</div>
@endsection