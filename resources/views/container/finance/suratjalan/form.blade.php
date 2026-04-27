@extends('layouts.app')

@section('page-title', $isEdit ? 'Edit Surat Jalan' : 'Buat Surat Jalan Baru')

@section('content')
<div class="max-w-7xl mx-auto">    
    <form method="POST" action="{{ $isEdit ? route('surat-jalan.update', $suratjalan->suratjalan_id) : route('surat-jalan.store') }}">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif
        <div class="flex justify-between items-center mb-4">
            <div class="">
                <h1 class="text-1xl font-bold">
                    {{ $isEdit ? 'Edit Surat Jalan' : 'Buat Surat Jalan' }}
                </h1>
                <p class="text-sm text-gray-500 font-semibold">Document - Surat Jalan - {{ $isEdit ? 'Edit' : 'Tambah'}} Surat Jalan</p>
            </div>
            <div class="flex justify-end gap-4">
                <a href="{{ route('surat-jalan.index') }}" class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    {{ $isEdit ? 'Update Surat Jalan' : 'Simpan Surat Jalan' }}
                </button>
            </div>
        </div>        

        <!-- Multiple Invoice Select -->
        <div class="p-6 bg-white rounded-lg shadow-md">
            <div class="mb-8">
                <label for="invoice_ids" class="block text-sm font-medium text-gray-700 mb-2">
                    Pilih Invoice (bisa multiple)<span class="text-red-400">*</span>
                </label>
                <select name="invoice_ids[]" id="invoice_ids" multiple class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 h-48" required>
                    @foreach($invoices as $inv)
                        <option value="{{ $inv->invoice_id }}"
                                {{ $isEdit && in_array($inv->id, $selectedInvoiceIds ?? []) ? 'selected' : '' }}>
                            {{ $inv->code }} - {{ $inv->customer->name ?? 'N/A' }} ({{ \Carbon\Carbon::parse($inv->issue_at)->format('d/m/Y') }})
                            {{ $inv->has_sj ? ' (Terpakai)' : '(Belum Terpakai)' }}
                        </option>
                    @endforeach
                </select>
                <p class="text-sm text-gray-600 mt-2">
                    @if($isEdit)
                        Invoice yang sudah dipakai SJ ini otomatis terpilih. Kamu bisa tambah invoice baru jika perlu.
                    @else
                        Tekan Ctrl (Windows) atau Cmd (Mac) untuk memilih lebih dari satu invoice.
                    @endif
                </p>
                @error('invoice_ids')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Header -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kode Surat Jalan<span class="text-red-400">*</span></label>
                    <input type="text" name="code" value="{{ old('code', $suratjalan?->code ?? generateSuratJalanCode()) }}" class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama / Keterangan<span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $suratjalan?->name ?? '') }}" class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal surat jalan dibuat<span class="text-red-400">*</span></label>
                    <input type="date" name="issue_at" value="{{ old('issue_at', $suratjalan?->issue_at ?? date('Y-m-d')) }}" class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Barang keluar</label>
                    <input type="date" name="due_at" value="{{ old('due_at', $suratjalan?->due_at ?? '') }}" class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500" required>
                    <option value="draft" {{ old('status', $suratjalan?->status ?? 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="sented" {{ old('status', $suratjalan?->status ?? 'draft') == 'sented' ? 'selected' : '' }}>Dikirim</option>                
                    <option value="cancelled" {{ old('status', $suratjalan?->status ?? 'draft') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>            
        </div>
    </form>
</div>
@endsection