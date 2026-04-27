@extends('layouts.app')

@section('page-title', 'Keluhan - Reparasi Barang')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Reparasi Barang</h1>
            <p class="text-sm text-gray-500 font-semibold">Keluhan - Tambah Reparasi</p>
        </div>
    </div>

    <!-- Filter & Search Lengkap -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET">
            <div class="grid grid-cols-4 md:grid-cols-4 gap-4 items-end">
                <!-- Tipe Pencarian -->
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Pencarian</label>
                    <select name="search_type" class="w-full border rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">🔍 Semua Field</option>
                        <option value="code" {{ request('search_type') == 'code' ? 'selected' : '' }}>Kode</option>
                        <option value="name" {{ request('search_type') == 'name' ? 'selected' : '' }}>Nama Customer</option>
                        <option value="product_name" {{ request('search_type') == 'product_name' ? 'selected' : '' }}>Nama Barang</option>
                        <option value="serial_number" {{ request('search_type') == 'serial_number' ? 'selected' : '' }}>Serial Number</option>
                    </select>
                </div>

                <!-- Input Pencarian -->
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pencarian</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Ketik kata kunci..."
                           class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <!-- Status Keluhan -->
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Barang</label>
                    <select name="status_keluhan" class="w-full border rounded-lg px-4 py-2 text-sm">
                        <option value="">Status</option>
                        <option value="pending" {{ request('status_keluhan') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="penawaran" {{ request('status_keluhan') == 'penawaran' ? 'selected' : '' }}>Penawaran</option>
                        <option value="cancel" {{ request('status_keluhan') == 'cancel' ? 'selected' : '' }}>Cancel</option>
                        <option value="done" {{ request('status_keluhan') == 'done' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>

                <!-- Status Keluhan (Ada/Tidak) -->
                <div class="md:col-span-1" hidden>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Keluhan</label>
                    <select name="punya_keluhan" class="w-full border rounded-lg px-4 py-2 text-sm">
                        <option value="">Keluhan</option>
                        <option value="yes" {{ request('punya_keluhan') == 'yes' ? 'selected' : '' }}>Ada</option>
                        <option value="no" {{ request('punya_keluhan') == 'no' ? 'selected' : '' }}>Tidak</option>
                    </select>
                </div>
            </div>

            <!-- Tanggal & Tombol -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4 items-end">
                <!-- Dari Tanggal -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}"
                           class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <!-- Sampai Tanggal -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}"
                           class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <!-- Tombol Cari & Reset -->
                <div class="md:col-span-2 flex gap-2">
                    <button type="submit" class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 text-sm">
                        Cari
                    </button>
                    <a href="{{ route('keluhan.index') }}" class="flex-1 text-center bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 text-sm block">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Table Receive -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Kode</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase">Nama Toko</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-500 uppercase">Jumlah Keluhan</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-right text-sm font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($receives as $receive)
                        @if(!$receive->has_invoice)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium">{{ $receive->code }}</td>
                                <td class="px-6 py-4 text-sm">{{ $receive->name }}</td>
                                <td class="px-6 py-4 text-sm text-center">
                                    <span class="px-3 py-1 bg-indigo-100 text-indigo-800 text-xs rounded-full font-semibold">
                                        {{ $receive->keluhan_details_count ?? 0 }} Keluhan
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">{{ $receive->issue_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('keluhan.edit', $receive->receive_id) }}"
                                        class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm transition">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Tambah/Edit Keluhan
                                        </a>
                                </td>
                            </tr>                
                        @else
                            <tr class="bg-gray-500">
                                <td class="px-6 py-4 text-gray-300 text-sm font-medium">{{ $receive->code }}</td>
                                <td class="px-6 py-4 text-sm text-gray-300">{{ $receive->name }}</td>
                                <td class="px-6 py-4 text-sm text-center">
                                    <span class="px-3 py-1 bg-indigo-100 text-indigo-800 text-xs rounded-full font-semibold">
                                        {{ $receive->keluhan_details_count ?? 0 }} Keluhan
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-300">{{ $receive->issue_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-right">                                    
                                    <a href="#"                                                                                
                                        class="cursor-not-allowed inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm transition">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Tambah/Edit Keluhan
                                    </a>                                    
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                Tidak ada data receive dengan keluhan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t bg-gray-50">
            {{ $receives->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection