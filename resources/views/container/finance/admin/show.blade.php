@extends('layouts.app')

@section('page-title', 'Detail Keluhan Receive - ' . $receive->code)

@section('content')
<div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Lihat Keluhan Barang</h1>
            <p class="text-sm text-gray-500 font-semibold">Document - Admin - Lihat Keluhan</p>
        </div>
        <div class="flex gap-4">
            <a href="{{ route('admin.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                Kembali ke List
            </a>
        </div>
    </div>
<div class="max-w-7xl mx-auto p-6 bg-white rounded-lg shadow-md">    

    <!-- Info Header Receive -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-gray-50 p-6 rounded-xl border border-gray-100">
            <label class="block text-sm font-medium text-gray-700 mb-2">Kode Receive</label>
            <p class="text-2xl font-bold text-indigo-700">{{ $receive->code }}</p>
        </div>
        <div class="bg-gray-50 p-6 rounded-xl border border-gray-100">
            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Perusahaan/ Customer</label>
            <p class="text-2xl font-bold text-indigo-700">{{ $receive->name ?? 'N/A' }}</p>            
        </div>
        <div class="bg-gray-50 p-6 rounded-xl border border-gray-100">
            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Barang Diterima</label>
            <p class="text-2xl font-bold text-indigo-700">{{ $receive->issue_at->format('d/m/Y') }}</p>
        </div>
    </div>

    <!-- Daftar Receivedetail & Keluhan -->
    <div>
        <h2 class="text-2xl font-bold mb-6">Detail Barang & Keluhan</h2>

        @forelse($receive->details as $detail)
            <div class="bg-gray-50 rounded-xl border border-gray-200 p-6 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800">{{ $detail->product_name }}</h3>
                        <p class="text-sm text-gray-600">Serial: {{ $detail->serial_number ?? '-' }}</p>
                    </div>
                    <div class="flex gap-4">
                        @if(!$receive->has_invoice)
                            <a href="{{ route('keluhan.edit', $detail->id_receive_details) }}" 
                                class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm">
                                Edit Keluhan
                            </a>  
                        @endif
                        <a href="{{ route('download.penawaran.pdf', $detail->id_receive_details) }}" target="_blank" 
                        class="px-5 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm">
                            Download SPH
                        </a>
                    </div>
                </div>

                <!-- Status Keluhan per Detail -->
                <div class="mb-4">
                    <div class="flex justify-between">
                        <span 
                            class="inline-block py-1 text-sm rounded-full font-medium"
                        >
                            {{ $detail->customer? $detail->customer->name : $detail->customer_name }}
                        </span>
                        <span class="inline-block px-4 py-1 text-sm rounded-full font-medium
                            @if($detail->keluhan_count > 0) bg-green-100 text-green-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ count($detail->keluhans) }} Keluhan
                        </span>
                    </div>
                </div>

                <!-- List Keluhan -->
                @if(count($detail->keluhans) > 0)
                    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                        <table class="w-full">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keluhan</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($detail->keluhans as $keluhanDetail)
                                    <tr>
                                        <td class="px-4 py-3 text-sm">{{ $keluhanDetail->keluhan ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-sm">{{ $keluhanDetail->price ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            <span class="px-3 py-1 text-xs rounded-full font-medium                                                
                                                @if($keluhanDetail->has_done == 0) bg-red-100 text-red-800
                                                @elseif($keluhanDetail->has_done == 1) bg-green-100 text-green-800
                                                @endif">
                                                {{ ucfirst($keluhanDetail->has_done ? "Selesai" : 'Cancel') }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 italic">Belum ada keluhan untuk barang ini.</p>
                @endif
            </div>
        @empty
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-8 text-center text-yellow-800">
                Tidak ada detail barang pada receive ini.
            </div>
        @endforelse
    </div>    
</div>
@endsection