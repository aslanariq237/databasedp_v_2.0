@extends('layouts.app')

@section('page-title', 'Penerimaan Barang')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div class="">
            <h1 class="text-2xl font-bold text-gray-900">Penerimaan Barang</h1>
            <p class="text-sm text-gray-500 font-semibold">Receive - Penerimaan Barang</p>
        </div>        
        <a href="{{ route('receives.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
            + Tambah Penerimaan
        </a>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET">
            <div class="grid grid-cols-3 md:grid-cols-3 gap-4 items-end">
                <!-- Tipe Pencarian -->
                <div class="md:col-span-1">
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

                <!-- Status Payment -->
                <div class="md:col-span-1">
                    <label class="text-sm font-medium text-gray-700 mb-1">Garansi</label>
                    <select name="garansi" class="w-full border rounded-lg px-4 py-2 text-sm">
                        <option value="">Semua Garansi</option>
                        <option value="yes" {{ request('garansi') == 'yes' ? 'selected' : '' }}>Ada Garansi</option>
                        <option value="no" {{ request('garansi') == 'no' ? 'selected' : '' }}>Tidak Ada Garansi</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-3 md:grid-cols-3 gap-4 items-end mt-3">
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

                <!-- Tombol Cari & Reset -->
                <div class="md:col-span-1 flex gap-2">
                    <button type="submit" class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                        Filter
                    </button>
                    <a href="{{ route('receives.index') }}" class="flex-1 text-center bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 block">
                        Reset
                    </a>
                </div>
            </div>                     
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>                                                
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($receives as $receive)
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $receive->code }}</td>
                        <td class="px-6 py-4 text-sm">{{ $receive->name }}</td>                                                
                        <td class="px-6 py-4 text-sm">{{ $receive->issue_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 flex items-end justify-end text-sm gap-2">
                            {{-- <a href="" class="flex items-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                <!-- Heroicon edit icon (example SVG) -->
                                <svg xmlns="www.w3.org" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                                Edit
                            </a> --}}
                            <a href="{{ route('receives.show', $receive->receive_id) }}" class="inline-flex items-center px-2 py-2.5 bg-blue-700 text-white font-medium rounded-lg shadow-md hover:bg-blue-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Lihat
                            </a>
                            @if(!$receive->has_invoice)
                                <a href="{{ route('receives.edit', $receive->receive_id) }}" class="inline-flex items-center px-2 py-2.5 bg-yellow-600 text-white font-medium rounded-lg shadow-md hover:bg-yellow-600 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition-all duration-200">
                                    <svg xmlns="www.w3.org" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                    Edit
                                </a>
                            @endif
                            <form action="{{ route('receives.destroy', $receive) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('Yakin ingin menghapus data ini?')" class="inline-flex items-center px-2 py-2.5 bg-red-700 text-white font-medium rounded-lg shadow-md hover:bg-red-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">Tidak ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t">
            {{ $receives->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection