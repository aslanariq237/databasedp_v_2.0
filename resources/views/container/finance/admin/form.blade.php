@extends('layouts.app')

@section('page-title', 'Edit Harga Keluhan & Jasa Service - Receive #' . $receive->code)

@section('content')
<div class="max-w-6xl mx-auto">    

    <form action="{{ route('edit.update-receives', $receive) }}" method="POST">
        @csrf
        <div class="flex items-center justify-between">
            <div class=" mb-6">
                <h1 class="text-2xl font-bold">Edit Harga Keluhan & Jasa Service</h1>
                <p class="text-sm text-gray-500 font-semibold">Document - Admin - Edit Keluhan</p>
            </div>
            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.index') }}" class="px-6 py-2 border rounded-lg hover:bg-gray-100">Batal</a>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    Simpan Perubahan
                </button>
            </div>
        </div>
        <!-- Jasa Service Keseluruhan -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <div class="flex items-center">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jasa Service Keseluruhan Receive <span class="text-red-400">*</span>
                    </label>
                    <input type="number" name="jasa_service" value="{{ $receive->jasa_service ?? 0 }}" required min="0"
                           class="w-full border rounded-lg px-4 py-2 focus:ring-indigo-500">
                </div>                
            </div>
        </div>

        <!-- Edit Harga Keluhan per Detail -->
        <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
            @forelse($receive->details as $detail)
                <div class="p-6 border-b">
                    <h3 class="text-lg font-semibold mb-2">Detail #{{ $detail->id_receive_details }}: {{ $detail->product_name }} - {{ $detail->serial_number }}</h3>
                    <div class="flex justify-between items-end">
                        <div class="customer">
                            <p class="text-sm font-semibold text-gray-800">{{ $detail->customer->company ?? $detail->customer_company }}</p>
                            <p class="text-sm text-gray-600 mb-4">{{ $detail->customer->name ?? $detail->customer_name }}</p>
                        </div>
                        <div class="sph">
                            <a 
                                href="{{ route('download.penawaran.pdf', $detail->id_receive_details) }}"
                                class="py-1 bg-green-500 rounded-md px-2 text-white text-sm"
                            >Download SPH</a>
                        </div>
                    </div>

                    <!-- Table Keluhan -->
                    <table class="min-w-full divide-y divide-gray-200 mb-4">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs uppercase">Keluhan</th>
                                <th class="px-6 py-3 text-left text-xs uppercase">Harga Saat Ini</th>
                                <th class="px-6 py-3 text-left text-xs uppercase">Edit Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($detail->keluhans as $keluhan)
                                <tr>
                                    <td class="px-6 py-4 text-sm">{{ $keluhan->keluhan }}</td>
                                    <td class="px-6 py-4 text-sm">Rp {{ number_format($keluhan->price, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4">
                                        <input type="number" name="keluhan[{{ $keluhan->id_keluhan_detail }}][price]"
                                               value="{{ $keluhan->price }}" required min="0"
                                               class="w-full border rounded px-3 py-2 focus:ring-indigo-500">
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center py-8 text-gray-500">Tidak ada keluhan</td></tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Sub Total Per Detail (Display Only) -->
                    <div class="text-right font-medium">
                        Sub Total Detail Ini: Rp {{ number_format($detail->keluhans->sum('price'), 0, ',', '.') }}
                    </div>
                </div>
            @empty
                <p class="text-center py-8 text-gray-500">Tidak ada detail receive</p>
            @endforelse
        </div>        
    </form>
</div>
@endsection