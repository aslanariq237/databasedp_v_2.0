@extends('layouts.app')

@section('page-title', isset($receive) ? 'Edit Penerimaan' : 'Tambah Penerimaan Barang')

@section('content')
<div class="max-w-6xl mx-auto">    
    <form 
        action="{{ isset($receive) ? route('receives.update', $receive) : route('receives.store') }}" 
        method="POST" 
        id="receive-form"        
    >
        @csrf
        @if(isset($receive)) @method('PUT') @endif
        <div class="flex justify-between items-center">
            <div class="">
                <h1 class="text-2xl font-bold">{{ isset($receive) ? 'Edit' : 'Tambah' }} Penerimaan Barang</h1>
                <p class="text-sm text-gray-500 font-semibold">Receive - Penerimaan Barang - {{isset($receive) ? 'Edit' : 'Tambah'}} Reparasi</p>
            </div>    
            <div class="flex gap-4">
                <a href="{{ route('receives.index') }}" class="px-6 py-2 border rounded-lg hover:bg-gray-100">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    Simpan Penerimaan
                </button>
            </div>
        </div>  

        <!-- Header Form -->
        <div class="bg-white rounded-lg shadow p-6 mb-6 mt-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kode<span class="text-red-400">*</span></label>
                    <input 
                        type="text" 
                        name="code" value="{{ old('code', $receive->code ?? generateReceiveCode()) }}" required
                        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 @error('code') border-red-500 @enderror">
                    @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Perusahaan/ Customer<span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $receive->name ?? '') }}" required
                           class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Barang Masuk</label>
                    <input type="date" name="issue_at" value="{{ old('issue_at', isset($receive) ? \Carbon\Carbon::parse($receive->issue_at)->format('Y-m-d') : now()->format('Y-m-d')) }}" required
                           class="w-full border rounded-lg px-4 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Maksimal Pengerjaan<span class="text-red-400">*</span></label>
                    <input type="date" name="due_at" value="{{ old('due_at', isset($receive) ? \Carbon\Carbon::parse($receive->due_at)->format('Y-m-d') : '') }}" required
                           class="w-full border rounded-lg px-4 py-2">
                    <input type="hidden" name="deposit" value="0">
                </div>
            </div>
        </div>

        <!-- Detail Barang -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Detail Barang / Jasa</h3>

            <div id="details-container">
                @php
                    $existing = (isset($receive) && $receive->details->count()) ? $receive->details->toArray() : [[]];
                    $details = old('details', $existing);
                    $detailIndex = count($details);
                @endphp

                @foreach($details as $index => $detail)
                <div class="detail-row grid grid-cols-1 lg:grid-cols-12 gap-4 mb-6 p-4 border rounded-lg bg-gray-50">
                    <!-- Product Select (Kategori + Barang) -->
                    <div class="lg:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Produk<span class="text-red-400">*</span></label>
                            <div class="relative">
                                <input type="text" 
                                    placeholder="Ketik nama produk atau kode..." 
                                    class="product-search w-full border rounded-lg px-4 py-2" 
                                    name="details[{{ $index }}][product_name]" 
                                    value="{{ $detail['product_name'] ?? '' }}"
                                    autocomplete="off"
                                    required>
                                <div class="product-results absolute z-10 w-full mt-1 bg-white border rounded-lg shadow-lg hidden max-h-60 overflow-y-auto"></div>
                                <input type="hidden" 
                                    name="details[{{ $index }}][product_id]" 
                                    class="product-id-input" 
                                    value="{{ $detail['product_id'] ?? '' }}">
                            </div>
                        </div>
                    {{-- <div class="lg:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori<span class="text-red-400">*</span></label>
                        <select name="details[{{ $index }}][product_id]" 
                                class="product-select w-full border rounded-lg px-4 py-2" 
                                data-index="{{ $index }}" required>
                            <option value="">Pilih Produk...</option>
                            @if(!empty($detail['product_id']))
                                <option value="{{ $detail['product_id'] }}" selected>
                                    {{ $detail['product_name'] ?? 'Produk #' . $detail['product_id'] }}
                                </option>
                            @endif
                        </select>
                    </div> --}}

                    <!-- Input Manual Nama Barang (tetap ada) -->
                    <div class="lg:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Barang<span class="text-red-400">*</span></label>
                        <input type="text" 
                               placeholder="Nama Barang" 
                               value="{{ $detail['product_name'] ?? '' }}"
                               name="details[{{ $index }}][product_name]" 
                               class="product-name-display w-full border rounded px-3 py-2" 
                               required>                        
                    </div>
                    <div class="lg:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Serial Number<span class="text-red-400">*</span></label>
                        <input type="text" 
                               placeholder="Serial Number" 
                               value="{{ $detail['serial_number'] ?? '' }}"
                               name="details[{{ $index }}][serial_number]" 
                               class="product-name-display w-full border rounded px-3 py-2" 
                               required>                        
                    </div>

                    <!-- Customer Search -->
                    <div class="lg:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kode Customer<span class="text-red-400">*</span></label>
                        <div class="relative">
                            <input type="text" 
                                   placeholder="Ketik nama customer..." 
                                   class="customer-search w-full border rounded-lg px-4 py-2" 
                                   name="details[{{ $index }}][customer_name]" 
                                   value="{{ $detail['customer_name'] ?? '' }}"
                                   autocomplete="off"
                            >
                            <div class="customer-results absolute z-10 w-full mt-1 bg-white border rounded-lg shadow-lg hidden max-h-60 overflow-y-auto"></div>
                            <input type="hidden" name="details[{{ $index }}][customer_id]" class="customer-id-input" value="{{ $detail['customer_id'] ?? '' }}">                            
                        </div>
                    </div>                

                    <!-- Garansi -->
                    <div class="lg:col-span-2">                        
                        <label class="block text-sm font-medium text-gray-700 mb-1">Garansi<span class="text-red-400">*</span></label>
                        <input type="checkbox" 
                            name="details[{{ $index }}][has_garansi]" 
                            value="1"
                            {{ ($detail['has_garansi'] ?? false) ? 'checked' : '' }} 
                            class="h-5 w-5 text-indigo-600">                                                    
                    </div>
                    <input type="hidden" 
                           name="details[{{ $index }}][id_receive_details]" 
                           value="{{ $detail['id_receive_details'] ?? '' }}">
                    <input type="hidden" 
                           name="details[{{ $index }}][jasa_service]" 
                           value="0">

                    <!-- Hapus -->
                    <div class="lg:col-span-1 flex items-end justify-end">
                        <button type="button" class="remove-detail text-red-600 hover:text-red-800 font-medium">Hapus</button>
                    </div>
                </div>
                @endforeach
            </div>

            <button type="button" id="add-detail" class="mt-4 text-indigo-600 hover:text-indigo-800 font-medium">
                + Tambah Baris Detail
            </button>
        </div>        
    </form>
</div>

@push('scripts')
<script>
    let detailIndex = {{ $detailIndex }};

    // Load produk ke select
    function loadProducts(selectElement, selectedId = null) {
        fetch('{{ route('api.products.search') }}')
            .then(response => response.json())
            .then(data => {
                selectElement.innerHTML = '<option value="">Pilih Produk...</option>';
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item.text;
                    if (item.id == selectedId) option.selected = true;
                    selectElement.appendChild(option);
                });
            });
    }    
    //function product search
    function initProductSearch(inputElement) {
        const resultsDiv = inputElement.parentElement.querySelector('.product-results');
        const hiddenIdInput = inputElement.parentElement.querySelector('.product-id-input');

        inputElement.addEventListener('input', function () {
            const query = this.value.trim();
            resultsDiv.innerHTML = '';
            resultsDiv.classList.add('hidden');

            if (query.length < 2) return;

            // Route yang sudah kamu buat: products.search            
            fetch(`{{ route('api.products.search') }}?q=${encodeURIComponent(query)}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (!Array.isArray(data) || data.length === 0) {
                        resultsDiv.innerHTML = '<div class="px-4 py-2 text-gray-500 text-sm">Produk tidak ditemukan</div>';
                    } else {
                        data.forEach(item => {
                            const div = document.createElement('div');
                            div.className = 'px-4 py-2 hover:bg-indigo-100 cursor-pointer text-sm flex justify-between items-center';
                            
                            // Format tampilan: bisa disesuaikan dengan response API kamu
                            // Misal: "Kode - Nama Produk (Stok: 45)"
                            const displayText = `${item.code ?? ''} - ${item.nama ?? item.text ?? 'Produk #' + item.id}`;                            
                            
                            div.innerHTML = `
                                <span>${displayText}</span>
                            `;

                            div.addEventListener('click', () => {
                                inputElement.value = item.nama || item.text || displayText;
                                hiddenIdInput.value = item.id;
                                resultsDiv.classList.add('hidden');
                            });

                            resultsDiv.appendChild(div);
                        });
                    }
                    resultsDiv.classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error fetching products:', error);
                    resultsDiv.innerHTML = '<div class="px-4 py-2 text-red-500 text-sm">Terjadi kesalahan saat mencari produk</div>';
                    resultsDiv.classList.remove('hidden');
                });
        });

        // Tutup hasil jika klik di luar area
        document.addEventListener('click', function (e) {
            if (!inputElement.parentElement.contains(e.target)) {
                resultsDiv.classList.add('hidden');
            }
        });

        // Optional: jika user menghapus input, kosongkan hidden ID juga
        inputElement.addEventListener('change', function () {
            if (!this.value.trim()) {
                hiddenIdInput.value = '';
            }
        });
    }

    // Customer Search
    function initCustomerSearch(inputElement) {
        const resultsDiv = inputElement.parentElement.querySelector('.customer-results');
        const hiddenId = inputElement.parentElement.querySelector('.customer-id-input');
        const hiddenName = inputElement.parentElement.querySelector('.customer-name-input');

        inputElement.addEventListener('input', function () {
            const query = this.value.trim();
            resultsDiv.innerHTML = '';
            resultsDiv.classList.add('hidden');

            if (query.length < 2) return;

            fetch(`{{ route('api.customers.search') }}?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length === 0) {
                        resultsDiv.innerHTML = '<div class="px-4 py-2 text-gray-500 text-sm">Tidak ditemukan</div>';
                    } else {
                        data.forEach(item => {
                            const div = document.createElement('div');
                            div.className = 'px-4 py-2 hover:bg-indigo-100 cursor-pointer text-sm';
                            const displayText = `${item.code ?? ''} - ${item.name ?? item.text}`;
                            div.innerHTML = `
                                <span>${displayText}</span>
                            `;
                            // div.textContent = item.text;
                            div.addEventListener('click', () => {
                                inputElement.value = item.text;
                                hiddenId.value = item.id;
                                hiddenName.value = item.text;
                                resultsDiv.classList.add('hidden');
                            });
                            resultsDiv.appendChild(div);
                        });
                    }
                    resultsDiv.classList.remove('hidden');
                });
        });

        // Klik luar = tutup
        document.addEventListener('click', (e) => {
            if (!inputElement.parentElement.contains(e.target)) {
                resultsDiv.classList.add('hidden');
            }
        });
    }

    // Tambah baris baru
    document.getElementById('add-detail').addEventListener('click', function () {
        const container = document.getElementById('details-container');
        const row = document.createElement('div');
        row.className = 'detail-row grid grid-cols-1 lg:grid-cols-12 gap-4 mb-6 p-4 border rounded-lg bg-gray-50';
        row.innerHTML = `
            <div class="lg:col-span-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori<span class="text-red-400">*</span></label>
                <select name="details[${detailIndex}][product_id]" class="product-select w-full border rounded-lg px-4 py-2" required>
                    <option value="">Pilih Kategori...</option>
                </select>
            </div>
            <div class="lg:col-span-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Barang<span class="text-red-400">*</span></label>
                <input type="text" placeholder="Nama Barang" name="details[${detailIndex}][product_name]" class="product-name-display w-full border rounded px-3 py-2" required>                
            </div>
            <div class="lg:col-span-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Serial Number<span class="text-red-400">*</span></label>
                <input type="text" placeholder="Serial Number" name="details[${detailIndex}][serial_number]" class="product-name-display w-full border rounded px-3 py-2" required>                
            </div>
            <div class="lg:col-span-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Customer<span class="text-red-400">*</span></label>
                <div class="relative">
                    <input type="text" placeholder="Ketik nama customer..." name="details[${detailIndex}][customer_name]" class="customer-search w-full border rounded-lg px-4 py-2">
                    <div class="customer-results absolute z-10 w-full mt-1 bg-white border rounded-lg shadow-lg hidden max-h-60 overflow-y-auto"></div>
                    <input type="hidden" name="details[${detailIndex}][customer_id]" class="customer-id-input">                    
                </div>
            </div>            
            <div class="lg:col-span-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Garansi<span class="text-red-400">*</span></label>
                <input type="checkbox" name="details[${detailIndex}][has_garansi]" value="1" class="h-5 w-5 text-indigo-600">                                    
            </div>
            <div class="lg:col-span-1 flex items-end justify-end">
                <button type="button" class="remove-detail text-red-600 hover:text-red-800 font-medium">Hapus</button>
            </div>
        `;
        container.appendChild(row);
        detailIndex++;

        const newSelect = row.querySelector('.product-select');
        const newSearch = row.querySelector('.customer-search');

        loadProducts(newSelect);
        initCustomerSearch(newSearch);

        newSelect.addEventListener('change', () => syncProductName(newSelect));
    });

    // Hapus baris
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-detail')) {
            e.target.closest('.detail-row').remove();
        }
    });

    // Inisialisasi saat load
    document.querySelectorAll('.product-select').forEach(select => {
        const selectedId = select.querySelector('option[selected]')?.value;
        loadProducts(select, selectedId);
        select.addEventListener('change', () => syncProductName(select));
    });

    document.querySelectorAll('.customer-search').forEach(initCustomerSearch);
    document.querySelectorAll('.product-search').forEach(initProductSearch);
</script>
@endpush
@endsection