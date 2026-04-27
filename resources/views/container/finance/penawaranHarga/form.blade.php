@extends('layouts.app')

@section('page-title', 'Buat Penawaran Harga')

@section('content')
<div class="max-w-7xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Buat Penawaran Harga</h1>

    <form action="{{ route('penawaran.store') }}" method="POST">
        @csrf

        <!-- Pilih Receive -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Receive</label>
            <select id="receive-select" class="w-full border rounded-lg px-4 py-2" required>
                <option value="">-- Pilih Receive --</option>
                @foreach($receives as $rec)
                    <option value="{{ $rec->receive_id }}">{{ $rec->code }} - {{ $rec->name }}</option>
                @endforeach
            </select>
        </div>

        <div id="detail-container" class="hidden">
            <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold">Detail Barang untuk Penawaran</h3>
                </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left">Pilih</th>
                            <th class="px-6 py-3 text-left">Barang</th>
                            <th class="px-6 py-3 text-left">Serial Number</th>
                            <th class="px-6 py-3 text-left">Harga Penawaran</th>
                        </tr>
                    </thead>
                    <tbody id="detail-table-body">
                        <!-- Diisi via JS -->
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    Simpan Penawaran
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    document.getElementById('receive-select').addEventListener('change', function () {
        const receiveId = this.value;
        const container = document.getElementById('detail-container');
        const tbody = document.getElementById('detail-table-body');

        tbody.innerHTML = '';
        container.classList.add('hidden');

        if (!receiveId) return;

        fetch(`/api/receives/${receiveId}/details`)
            .then(res => res.json())
            .then(data => {
                data.details.forEach(detail => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-6 py-4 text-center">
                            <input type="checkbox" name="selected_details[]" value="${detail.id_receive_details}" checked>
                            <input type="hidden" name="receive_id" value="${data.receive.receive_id}">
                        </td>
                        <td class="px-6 py-4">${detail.product_name}</td>
                        <td class="px-6 py-4">${detail.serial_number ?? '-'}</td>
                        <td class="px-6 py-4">
                            <input type="number" name="details[${detail.id_receive_details}][price]"
                                   value="${detail.price}" required class="w-full border rounded px-3 py-2">
                        </td>
                    `;
                    tbody.appendChild(row);
                });
                container.classList.remove('hidden');
            });
    });
</script>
@endsection