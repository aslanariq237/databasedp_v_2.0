@extends('layouts.app')

@section('page-title', 'Keluhan Reparasi - Receive #' . $receive->code)

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Keluhan Reparasi Barang</h1>
            <p class="text-sm text-gray-500 font-semibold">Receive: {{ $receive->code }} - {{ $receive->name }}</p>
        </div>
        <a href="{{ route('keluhan.index') }}" class="px-6 py-2 border rounded-lg hover:bg-gray-100">
            Kembali
        </a>
    </div>

    <!-- Table Detail Receive -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Detail Barang</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Barang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>                        
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Garansi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Keluhan</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($receive->details as $detail)
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $detail->product_name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">                                
                                @if ($detail->customer)
                                    {{ $detail->customer->name }} - {{ $detail->customer->code }}
                                @else
                                    {{ $detail->customer_name }}                                    
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $detail->has_garansi ? 'Ya' : 'Tidak' }}</td>
                            <td class="px-6 py-4 text-sm text-center">
                                <span class="px-3 py-1 bg-indigo-100 text-indigo-800 text-xs rounded-full">
                                    {{ $detail->keluhans->count() }} <span class="text-gray-600">Keluhan</span>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button type="button"
                                        class="tambah-keluhan inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700"
                                        data-detail-id="{{ $detail->id_receive_details }}"
                                        data-teknisi-id="{{ $detail->teknisi_id ?? '' }}">
                                    Kelola Keluhan
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">Tidak ada detail barang</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Keluhan -->
<div id="keluhan-modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4 max-h-screen overflow-y-auto">
        <div class="px-6 py-4 border-b border-gray-200 sticky top-0 bg-white">
            <h2 class="text-xl font-bold text-gray-900">Kelola Keluhan Barang</h2>
        </div>
        <div class="px-6 mt-5 flex justify-end space-x-4">
                <button type="button" id="close-modal" class="px-6 py-2 border rounded-lg hover:bg-gray-100">
                    Batal
                </button>
                <button type="button" id="simpan-semua"
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    Simpan Semua Keluhan
                </button>
            </div>
        <div class="px-6">
            <input type="hidden" name="id_receive_details" id="modal-detail-id">

            <!-- Teknisi -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Teknisi <span class="text-red-400">*</span>
                </label>
                <select id="teknisi-select" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                    <option value="">Pilih Teknisi...</option>
                    @foreach($teknisi as $tek)
                        <option value="{{ $tek->teknisi_id }}">{{ $tek->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Form Tambah Keluhan Baru -->
            <div class="bg-gray-50 rounded-lg p-6 mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Tambah Keluhan Baru</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                    <!-- Multiple Select Keluhan Umum -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Keluhan Umum (bisa pilih banyak)</label>
                        <select id="keluhan-multi" multiple class="w-full border rounded-lg px-4 py-2 h-32">
                            <option value="">-- Pilih keluhan --</option>
                            @foreach($keluhan as $kel)
                                <option value="{{ $kel->id }}">{{ $kel->name }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Tekan Ctrl/Cmd untuk pilih banyak</p>
                    </div>

                    <!-- Deskripsi Custom -->
                    <input type="hidden" name="receive_id" id="modal-receive-id" value="{{$receive->receive_id}}">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Keluhan Custom <span class="text-gray-500 text-xs">(pisah koma untuk multiple)</span>
                        </label>
                        <textarea id="keluhan-deskripsi" rows="5" placeholder="Contoh: Layar retak, speaker mati, baterai kembung"
                                  class="w-full border rounded-lg px-4 py-2"></textarea>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="button" id="tambah-keluhan-baru"
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Tambah ke Daftar
                    </button>
                </div>
            </div>

            <!-- Daftar Keluhan (Existing + Baru) -->
            <h3 class="text-lg font-medium text-gray-900 mb-4">Daftar Keluhan</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keluhan</th>                            
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Selesai</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="keluhan-table-body" class="bg-white divide-y divide-gray-200">
                        <!-- Keluhan existing akan di-load via JS -->
                    </tbody>
                </table>
            </div>            
        </div>
    </div>
</div>

<script>
    let currentDetailId = null;
    let existingKeluhans = []; // Untuk simpan data existing

    // Buka modal
    document.querySelectorAll('.tambah-keluhan').forEach(btn => {
        btn.addEventListener('click', function () {
            currentDetailId = this.dataset.detailId;
            document.getElementById('modal-detail-id').value = currentDetailId;

            // Set teknisi jika sudah ada
            const teknisiId = this.dataset.teknisiId;
            if (teknisiId) {
                document.getElementById('teknisi-select').value = teknisiId;
            }

            // Load keluhan existing via AJAX
            fetch(`{{ route('api.keluhan.data') }}?detail_id=${currentDetailId}`)
                .then(res => res.json())
                .then(data => {
                    existingKeluhans = data;
                    renderKeluhanTable();
                });

            document.getElementById('keluhan-modal').classList.remove('hidden');
        });
    });

    // Tutup modal
    document.getElementById('close-modal').addEventListener('click', closeModal);
    document.getElementById('keluhan-modal').addEventListener('click', e => {
        if (e.target === document.getElementById('keluhan-modal')) closeModal();
    });

    function closeModal() {
        document.getElementById('keluhan-modal').classList.add('hidden');
        document.getElementById('keluhan-deskripsi').value = '';
        document.getElementById('keluhan-multi').selectedIndex = -1;
    }

    // Tambah keluhan baru ke tabel (temporary)
    document.getElementById('tambah-keluhan-baru').addEventListener('click', function () {
        const multiSelect = document.getElementById('keluhan-multi');
        const selectedOptions = Array.from(multiSelect.selectedOptions);
        const customText = document.getElementById('keluhan-deskripsi').value.trim();

        let newKeluhans = [];

        // Dari multiple select
        selectedOptions.forEach(opt => {
            const name = opt.textContent;
            if (!isKeluhanExist(name)) {
                newKeluhans.push({
                    id: null,
                    keluhan_id: opt.value,
                    keluhan: name,
                    price: 0,
                    has_done: false,
                    is_new: true
                });
            }
        });

        // Dari textarea koma
        if (customText) {
            const customs = customText.split(',').map(s => s.trim()).filter(s => s);
            customs.forEach(text => {
                if (!isKeluhanExist(text)) {
                    newKeluhans.push({
                        id: null,
                        keluhan_id: null,
                        keluhan: text,
                        price: 0,
                        has_done: false,
                        is_new: true
                    });
                }
            });
        }

        existingKeluhans = existingKeluhans.concat(newKeluhans);
        renderKeluhanTable();

        // Reset input
        document.getElementById('keluhan-deskripsi').value = '';
        multiSelect.selectedIndex = -1;
    });

    function isKeluhanExist(keluhanText) {
        return existingKeluhans.some(k => k.keluhan.toLowerCase() === keluhanText.toLowerCase());
    }

    // Render table keluhan
    function renderKeluhanTable() {
        const tbody = document.getElementById('keluhan-table-body');
        tbody.innerHTML = '';

        existingKeluhans.forEach((kel, index) => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="px-6 py-4 text-sm">
                    <input type="text" value="${kel.keluhan}" class="keluhan-text w-full border rounded px-2 py-1" data-index="${index}">
                </td>                
                <td class="px-6 py-4 text-sm text-center">
                    <input type="checkbox" ${kel.has_done ? 'checked' : ''} class="keluhan-done h-5 w-5" data-index="${index}">
                </td>
                <td class="px-6 py-4 text-right">
                    <button type="button" class="text-red-600 hover:text-red-800 hapus-keluhan" data-index="${index}">Hapus</button>
                </td>
            `;
            tbody.appendChild(row);
        });

        // Event hapus
        document.querySelectorAll('.hapus-keluhan').forEach(btn => {
            btn.addEventListener('click', function () {
                const idx = this.dataset.index;
                existingKeluhans.splice(idx, 1);
                renderKeluhanTable();
            });
        });
    }

    // Simpan semua (update teknisi + sync keluhan)
    document.getElementById('simpan-semua').addEventListener('click', function () {
        const teknisiId = document.getElementById('teknisi-select').value;
        if (!teknisiId) {
            alert('Pilih teknisi terlebih dahulu');
            return;
        }

        // Update semua data keluhan dari input
        document.querySelectorAll('.keluhan-text').forEach(input => {
            const idx = input.dataset.index;
            existingKeluhans[idx].keluhan = input.value;
        });
        document.querySelectorAll('.keluhan-price').forEach(input => {
            const idx = input.dataset.index;
            existingKeluhans[idx].price = input.value || 0;
        });
        document.querySelectorAll('.keluhan-done').forEach(cb => {
            const idx = cb.dataset.index;
            existingKeluhans[idx].has_done = cb.checked;
        });

        fetch('{{ route('keluhan.store', $receive) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                receive_id: document.getElementById('modal-receive-id').value,
                id_receive_details: currentDetailId,
                teknisi_id: teknisiId,
                keluhans: existingKeluhans
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('Keluhan berhasil disimpan');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(err => alert('Gagal: ' + err));
    });
</script>
@endsection