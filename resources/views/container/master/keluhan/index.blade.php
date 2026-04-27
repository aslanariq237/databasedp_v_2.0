@extends('layouts.app')

@section('page-title', 'Master Jenis Keluhan')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Master Jenis Keluhan</h1>
        <button type="button" onclick="document.getElementById('addModal').classList.remove('hidden')" 
                class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition">
            + Tambah Jenis Keluhan
        </button>
    </div>

    <!-- Tabel Master Keluhan -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Nama Keluhan</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                    <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($keluhans as $keluhan)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm">{{ $keluhan->id }}</td>
                        <td class="px-6 py-4 font-medium">{{ $keluhan->name }}</td>
                        <td class="px-6 py-4 text-sm">{{ number_format($keluhan->price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('keluh.destroy', $keluhan->id) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('Yakin hapus jenis keluhan ini?')" class="text-red-600 hover:text-red-900">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                            Belum ada jenis keluhan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="px-6 py-4 border-t">
            {{ $keluhans->links() }}
        </div>
    </div>

    <!-- Modal Tambah Keluhan -->
    <div id="addModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl p-8 w-full max-w-md">
            <h3 class="text-2xl font-bold mb-6">Tambah Jenis Keluhan Baru</h3>

            <form method="POST" action="{{ route('keluh.store') }}">
                @csrf

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Keluhan</label>
                    <input type="text" name="name" required class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga</label>
                    <input type="number" name="price" min="0" step="0.01" required class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500">
                </div>

                <div class="flex justify-end gap-4">
                    <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" 
                            class="px-6 py-3 bg-gray-300 rounded-lg hover:bg-gray-400">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection