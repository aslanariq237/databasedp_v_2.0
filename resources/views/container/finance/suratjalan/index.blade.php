@extends('layouts.app')

@section('page-title', 'Surat Jalan')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Surat Jalan</h1>
            <p class="text-sm text-gray-500 font-semibold">Document - Pengiriman Barang</p>
        </div>
        <a href="{{ route('surat-jalan.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
            + Buat Surat Jalan
        </a>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
            <!-- Input Pencarian -->
            <div class="md:col-span-4">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari kode / nama / produk / serial..."
                       class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <!-- Dari Tanggal -->
            <div class="md:col-span-2">
                <input type="date" name="from_date" value="{{ request('from_date') }}"
                       class="w-full border rounded-lg px-4 py-2">
            </div>

            <!-- Sampai Tanggal -->
            <div class="md:col-span-2">
                <input type="date" name="to_date" value="{{ request('to_date') }}"
                       class="w-full border rounded-lg px-4 py-2">
            </div>

            <!-- Status -->
            <div class="md:col-span-2">
                <select name="status" class="w-full border rounded-lg px-4 py-2">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Dikirim</option>
                    <option value="received" {{ request('status') == 'received' ? 'selected' : '' }}>Diterima</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>

            <!-- Tombol -->
            <div class="md:col-span-2 flex gap-2">
                <button type="submit" class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                    Cari
                </button>
                <a href="{{ route('surat-jalan.index') }}" class="flex-1 text-center bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 block">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Tabel Surat Jalan -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Surat Jalan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Issue</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jatuh Tempo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($suratjalans as $sj)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $sj->code }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $sj->name }}</td>
                            <td class="px-6 py-4 text-sm">{{ \Carbon\Carbon::parse($sj->issue_at)->format('d M Y') ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm">{{ \Carbon\Carbon::parse($sj->due_at)->format('d M Y') ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-3 py-1 text-xs rounded-full font-medium
                                    @if($sj->status == 'draft') bg-gray-100 text-gray-800
                                    @elseif($sj->status == 'sented') bg-green-100 text-green-800                                    
                                    @elseif($sj->status == 'cancelled') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800
                                    @endif">
                                    {{ ucfirst($sj->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right text-sm">
                                <a 
                                    href="{{ route('surat-jalan.show', $sj->suratjalan_id) }}" 
                                    class="inline-flex items-center px-2 py-2.5 bg-blue-700 text-white font-medium rounded-lg shadow-md hover:bg-blue-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Lihat
                                </a>                                  
                                <a href="{{ route('surat-jalan.edit', $sj->suratjalan_id) }}" class="inline-flex items-center px-2 py-2.5 bg-yellow-600 text-white font-medium rounded-lg shadow-md hover:bg-yellow-600 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition-all duration-200">
                                    <svg xmlns="www.w3.org" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                    Edit
                                </a>
                                <a href="{{ route('surat-jalan.edit', $sj->suratjalan_id) }}" class="inline-flex items-center px-2 py-2.5 bg-green-600 text-white font-medium rounded-lg shadow-md hover:bg-gree-600 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200">
                                    <svg xmlns="www.w3.org" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                    Print
                                </a>                                                                
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                Belum ada data surat jalan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t bg-gray-50">
            {{ $suratjalans->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection