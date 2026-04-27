@extends('layouts.app')

@section('page-title', 'Daftar product')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <div class="">
            <h1 class="text-2xl font-bold text-gray-800">product</h1>
        <p class="text-sm text-gray-500 font-semibold">Master Data - product</p>
        </div>
        
        <button 
            type="button" 
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg shadow transition flex items-center gap-2"
            onclick="openModal('addprodModal')">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah product
        </button>
    </div>    

    <!-- Tabel Daftar product -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kode</th>                        
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Serial</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($product as $index => $prod)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $product->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $prod->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $prod->code }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $prod->serial == 1 ? $prod->serial : '-' }}
                            </td>                            
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button 
                                    onclick="editprod({{ $prod->id }}, '{{ $prod->name }}', '{{ $prod->status }}')"
                                    class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    Edit
                                </button>
                                <!-- Bisa ditambah tombol hapus jika diperlukan -->
                                <!-- <button class="text-red-600 hover:text-red-900">Hapus</button> -->
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center text-gray-500">
                                Belum ada data product. Klik "Tambah product" untuk menambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 bg-gray-50 border-t">
            {{ $product->links() }}
        </div>
    </div>

    <!-- Modal Tambah / Edit product -->
    <div id="addprodModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">Tambah product Baru</h3>
            </div>

            <form id="prodForm" method="POST" action="{{ route('product.store') }}" class="p-6">
                @csrf
                <input type="hidden" name="prod_id" id="prod_id" value="">

                <div class="space-y-5">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Nama product <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="name" 
                            id="name" 
                            required 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Masukkan nama product"
                        >
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-1 mt-2">
                            Kode product <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="code" 
                            id="code" 
                            required 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Masukkan Kode product"
                        >
                        @error('code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-1 mt-2">
                            Serial product <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="serial" 
                            id="serial" 
                            required 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Masukkan Serial product"
                        >
                        @error('code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <button 
                        type="button" 
                        onclick="closeModal('addprodModal')"
                        class="px-5 py-2.5 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">
                        Batal
                    </button>
                    <button 
                        type="submit" 
                        class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                        Simpan product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript untuk Modal -->
<script>
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
        // Reset form untuk tambah baru
        document.getElementById('prodForm').reset();
        document.getElementById('prodForm').action = "{{ route('product.store') }}";
        document.getElementById('prod_id').value = '';
        document.getElementById('modalTitle').textContent = 'Tambah product Baru';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    function editprod(id, name, kode, serial) {
        openModal('addprodModal');
        
        document.getElementById('prod_id').value = id;
        document.getElementById('name').value = name;
        document.getElementById('kode').value = kode ;
        document.getElementById('serial').value = serial;
        
        document.getElementById('prodForm').action = "{{ route('product.update', ':id') }}".replace(':id', id);
        document.getElementById('modalTitle').textContent = 'Edit product';
        
        // Tambah method PUT (Laravel form spoofing)
        let methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        document.getElementById('prodForm').appendChild(methodInput);
    }

    // Tutup modal jika klik di luar
    document.getElementById('addprodModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal('addprodModal');
        }
    });
</script>
@endsection