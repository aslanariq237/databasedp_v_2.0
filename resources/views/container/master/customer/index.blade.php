@extends('layouts.app')

@section('title', 'Data Customer')

@section('content')
<div class="container mx-auto px-4">
    {{-- import customer --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif
    <div class="flex justify-between items-center mb-6">
        <div class="">
            <h1 class="text-2xl font-bold text-gray-800">Customer</h1>
        <p class="text-sm text-gray-500 font-semibold">Master Data - Customer</p>
        </div>
        
        <div class="flex gap-3">
            <!-- Tombol Import -->
            <button type="button" onclick="document.getElementById('importModal').classList.remove('hidden')" 
                    class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg shadow transition">
                Import dari Excel
            </button>

            <!-- Tombol Tambah -->
            <a href="{{ route('customer.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg shadow transition">
                + Tambah Manual
            </a>
        </div>
    </div>

    <!-- Modal Import Excel -->
    <div id="importModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
            <h3 class="text-xl font-bold mb-4">Import Customer dari Excel</h3>
            
            <div class="mb-4 text-sm text-gray-600">
                <p>Pastikan format Excel sesuai template:</p>
                <a href="{{ asset('templates/customer_template.xlsx') }}?v={{ filemtime(public_path('templates/customer_template.xlsx')) }}" 
                class="text-blue-600 hover:underline font-medium">
                    Download Template Excel
                </a>
            </div>

            <form action="{{ route('customers.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file" accept=".xlsx,.xls" required 
                    class="w-full px-4 py-2 border rounded-lg mb-4">

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')"
                            class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Import
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / kode..."
                   class="border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">            

            <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-900">
                Filter
            </button>
        </form>
    </div>

    <!-- Tabel Customer -->    
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Perusahaan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telepon</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alamat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($customers as $index => $customer)
                        <tr class="{{ $index % 2 == 0 ? 'bg-gray-50' : 'bg-white' }} hover:bg-gray-100 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $loop->iteration + ($customers->currentPage() - 1) * $customers->perPage() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $customer->code ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $customer->name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $customer->company ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $customer->telp ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">
                                {{ $customer->alamat ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('customer.show', $customer->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Lihat</a>
                                <a href="{{ route('customer.edit', $customer->id) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">Edit</a>
                                <!-- Delete form nanti -->
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                Belum ada data customer.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-white px-6 py-4 border-t border-gray-200">
            {{ $customers->links() }}
        </div>
    </div>
    @if(session('import_details'))
        <div class="bg-gray-100 border border-gray-300 rounded-lg p-4 mt-4 text-sm">
            <p class="font-bold mb-2">Detail Import (Debug):</p>
            <ul class="list-disc pl-5 space-y-1 max-h-60 overflow-y-auto">
                @foreach(session('import_details') as $detail)
                    <li>{{ $detail }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
@endsection