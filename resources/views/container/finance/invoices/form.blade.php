@extends('layouts.app')

@section('page-title', 'Buat Invoice Baru')

@section('content')
<div class="max-w-7xl mx-auto">    
    <form action="{{ route('invoices.store') }}" method="POST">
        @csrf
        <div class="flex justify-between items-center mb-4">
            <div class="">
                <h1 class="text-2xl font-bold">Buat Invoice Baru</h1>
                <p class="text-sm text-gray-500 font-semibold">Document - Invoice - Buat Invoice Baru</p>
            </div>
            <div class="flex justify-end">
                <button 
                    type="submit" 
                    id="submit-button"
                    class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700"
                >
                    Simpan & Buat Invoice
                </button>
            </div>
        </div>

        <!-- Pilih Receive -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Receive <span class="text-red-400">*</span></label>
            <select 
                id="receive-select" 
                class="w-full border rounded-lg px-4 py-2"                 
                required
            >
                <option value="">-- Pilih Receive --</option>
                @foreach($receives as $rec)
                    <option value="{{ $rec->receive_id }}">{{ $rec->code }} - {{ $rec->name }}</option>
                @endforeach
            </select>
            <input type="hidden" name="receive_id" id="hidden-receive-id" value="">
        </div>

        <!-- Loading & Table Detail -->
        <div id="detail-container" class="hidden">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold">Detail Barang dari Receive Terpilih</h3>
                </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs uppercase">Pilih</th>
                            <th class="px-6 py-3 text-left text-xs uppercase">Barang</th>
                            <th class="px-6 py-3 text-left text-xs uppercase">Serial Number</th>
                            <th class="px-6 py-3 text-left text-xs uppercase">Harga</th>
                            <th class="px-6 py-3 text-left text-xs uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody id="detail-table-body">
                        <!-- Diisi via JS -->
                    </tbody>
                </table>
            </div>            
        </div>
    </form>
</div>

<script>
    document.getElementById('receive-select').addEventListener('change', function () {
        const receiveId = this.value;
        const container = document.getElementById('detail-container');
        const tbody = document.getElementById('detail-table-body');
        const submitBtn = document.getElementById('submit-button');
        const hiddenReceiveId = document.getElementById('hidden-receive-id');

        tbody.innerHTML = '';
        container.classList.add('hidden');        
        hiddenReceiveId.value = '';

        hiddenReceiveId.value = receiveId;

        if (!receiveId) {            
            return;
        }

        fetch(`{{ route('api.receives.details', ':id') }}`.replace(':id', receiveId))
            .then(res => res.json())
            .then(data => {
                tbody.innerHTML = '';
                
                const filteredDetails = data.details.filter(detail => {
                    return detail.has_invoice != 1;
                });
                
                if (filteredDetails.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="5" class="px-6 py-6 text-center text-gray-500">
                                Semua item sudah memiliki invoice
                            </td>
                        </tr>
                    `;
                    container.classList.remove('hidden');
                    return;
                }

                filteredDetails.forEach((detail, index) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-6 py-4 text-center">
                            <input type="checkbox" name="selected_details[]" 
                                value="${detail.id_receive_details}" class="h-5 w-5">                            
                        </td>

                        <td class="px-6 py-4 text-sm">
                            ${detail.product_name ?? '-'}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            ${detail.serial_number ?? '-'}
                        </td>
                                                    
                        <input type="hidden"
                            name="details[${detail.id_receive_details}][serial_number]"
                            value="${detail.serial_number ?? 0}"
                            required
                            class="w-full border rounded px-3 py-2">                                                

                        <td class="px-6 py-4">
                            <input type="number"
                                name="details[${detail.id_receive_details}][price]"
                                value="${detail.price ?? 0}"
                                required
                                class="w-full border rounded px-3 py-2">
                        </td>

                        <td class="px-6 py-4">
                            <select name="details[${detail.id_receive_details}][status]"
                                    class="w-full border rounded px-3 py-2">
                                <option value="Selesai" ${detail.status === 'Selesai' ? 'selected' : ''}>Selesai</option>
                                <option value="Penawaran" ${detail.status === 'Penawaran' ? 'selected' : ''}>Penawaran</option>
                                <option value="Ditolak" ${detail.status === 'Ditolak' ? 'selected' : ''}>Ditolak</option>
                            </select>
                        </td>
                    `;
                    tbody.appendChild(row);
                });

                container.classList.remove('hidden');
            })
            .catch(err => {
                console.error(err);
                alert('gagal');
            })

    });
</script>
@endsection