@extends('layouts.app')

@section('page-title', 'Penawaran Harga')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div class="">
            <h1 class="text-2xl font-bold text-gray-900">Penawaran Harga</h1>
            <p class="text-sm text-gray-500 font-semibold">Document - Penawaran Harga</p>
        </div>  
        <a href="{{ route('penawaran.create') }}" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">
            + Buat SPH Baru
        </a>              
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / kode..."
                   class="border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">

            <select name="status_payment" class="border rounded-lg px-4 py-2">
                <option value="">Semua Status Pembayaran</option>
                <option value="unpaid" {{ request('status_payment') == 'unpaid' ? 'selected' : '' }}>Belum Bayar</option>
                <option value="partial" {{ request('status_payment') == 'partial' ? 'selected' : '' }}>Sebagian</option>
                <option value="full" {{ request('status_payment') == 'full' ? 'selected' : '' }}>Lunas</option>
            </select>

            <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-900">
                Filter
            </button>
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
                        <td class="px-6 py-4 flex items-end justify-end text-sm">                            
                            @if (!$receive->has_invoice)
                                <a 
                                    href="{{ route('edit.edit-receives', $receive->receive_id) }}" 
                                    class="text-indigo-600 mr-3 flex text-center"
                                >
                                    Lihat
                                </a>
                            @else
                                <span class="text-gray-400 mr-3 flex text-center cursor-not-allowed">
                                    Lihat
                                </span>
                            @endif                          
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