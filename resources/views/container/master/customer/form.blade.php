@extends('layouts.app')

@section('page-title', $customer->exists ? 'Edit Customer - ' . $customer->code : 'Tambah Customer Baru')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-center">
            <div class="">
                <h1 class="text-2xl font-bold">{{ isset($customer->code) ? 'Edit' : 'Tambah' }} Customer</h1>
                <p class="text-sm text-gray-500 font-semibold">Receive - Penerimaan Barang - {{isset($receive) ? 'Edit' : 'Tambah'}} Reparasi</p>
            </div>    
            <div class="flex gap-4">
                <a href="{{ route('customer.index') }}" class="px-6 py-2 border rounded-lg hover:bg-gray-100">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    Simpan Customer
                </button>
            </div>
        </div>  

    <!-- Form Card -->
    <div class="bg-white rounded-xl mt-4 shadow-lg overflow-hidden border border-gray-200">        
        <form method="POST" action="{{ $customer->exists ? route('customer.update', $customer->id) : route('customer.store') }}"
              class="p-6 lg:p-8">
            @csrf

            @if($customer->exists)
                @method('PUT')
            @endif

            <!-- Kode Customer (hanya tampil saat edit, readonly) -->
            @if($customer->exists)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-1">
                            Kode Customer
                        </label>
                        <input type="text" id="code" value="{{ old('code', $customer->code) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 bg-gray-50 text-gray-700 cursor-not-allowed"
                               readonly>
                    </div>
                </div>
            @endif

            <!-- Nama & Perusahaan -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Nama Customer <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" required
                           value="{{ old('name', $customer->name ?? '') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="Nama lengkap atau nama kontak">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="company" class="block text-sm font-medium text-gray-700 mb-1">
                        Nama Perusahaan
                    </label>
                    <input type="text" name="company" id="company"
                           value="{{ old('company', $customer->company ?? '') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="Nama perusahaan (opsional)">
                    @error('company')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Kategori & PIC -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">
                        Kategori Customer <span class="text-red-500">*</span>
                    </label>
                    <select name="category" id="category" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="" disabled {{ old('category', $customer->category ?? '') === '' ? 'selected' : '' }}>
                            Pilih Kategori
                        </option>
                        <option value="1" {{ old('category', $customer->category ?? '') == 1 ? 'selected' : '' }}>
                            Reguler
                        </option>
                        <option value="0" {{ old('category', $customer->category ?? '') == 2 ? 'selected' : '' }}>
                            Franchies
                        </option>                        
                        </option>
                    </select>
                    @error('category')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="pic" class="block text-sm font-medium text-gray-700 mb-1">
                        PIC (Person In Charge)
                    </label>
                    <input type="number" name="pic" id="pic"
                           value="{{ old('pic', $customer->pic ?? '') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="ID karyawan PIC (opsional)">
                    @error('pic')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Telepon & Alamat -->
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="telp" class="block text-sm font-medium text-gray-700 mb-1">
                        Nomor Telepon / WhatsApp <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="telp" id="telp" required
                           value="{{ old('telp', $customer->telp ?? '') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="08xx-xxxx-xxxx">
                    @error('telp')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">
                        Alamat Lengkap
                    </label>
                    <textarea name="alamat" id="alamat" rows="4"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                              placeholder="Jalan, nomor, kelurahan, kecamatan, kota, provinsi, kode pos">{{ old('alamat', $customer->alamat ?? '') }}</textarea>
                    @error('alamat')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex flex-col sm:flex-row justify-end gap-4 pt-6 border-t border-gray-200">
                <a href="{{ route('customer.index') }}"
                   class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition text-center">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ $customer->exists ? 'Update Customer' : 'Simpan Customer' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection