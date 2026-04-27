@extends('layouts.app')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard Overview')

@section('content')
    <div class="flex-1 flex flex-col overflow-hidden lg:ml-0 transition-margin duration-300">
        <main class="flex-1 overflow-y-auto p-6 bg-gray-50">
            <!-- Card Statistik Utama -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <!-- Total Penerimaan Barang -->
                <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Penerimaan Barang</p>
                            <p class="text-3xl font-bold text-indigo-700 mt-2">{{ $totalReceive }}</p>
                            <p class="text-sm text-gray-500 mt-1">
                                <span class="text-green-600 font-semibold">{{ $receiveSudahSJ }}</span> sudah dikirim
                                <span class="text-red-600 font-semibold">({{ $receiveBelumSJ }})</span> belum
                            </p>
                        </div>
                        <div class="bg-indigo-100 p-4 rounded-full">
                            <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Invoice -->
                <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Invoice</p>
                            <p class="text-3xl font-bold text-green-700 mt-2">{{ $totalInvoice }}</p>
                            <p class="text-sm text-gray-500 mt-1">
                                <span class="text-green-600 font-semibold">{{ $invoiceSudahSJ }}</span> sudah SJ
                                <span class="text-red-600 font-semibold">({{ $invoiceBelumSJ }})</span> belum
                            </p>
                        </div>
                        <div class="bg-green-100 p-4 rounded-full">
                            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Surat Jalan -->
                <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Surat Jalan</p>
                            <p class="text-3xl font-bold text-purple-700 mt-2">{{ $totalSuratJalan }}</p>
                            <p class="text-sm text-gray-500 mt-1">
                                <span class="text-purple-600 font-semibold">{{ $sjSent }}</span> dikirim
                                <span class="text-gray-600 font-semibold">({{ $sjDraft }})</span> draft
                            </p>
                        </div>
                        <div class="bg-purple-100 p-4 rounded-full">
                            <svg class="w-10 h-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grafik Aktivitas Bulanan -->
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Grafik Aktivitas Bulanan</h3>
                <canvas id="activityChart" height="100"></canvas>
            </div>

            <!-- Table Aktivitas Terbaru (opsional, bisa diganti dengan data real) -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Aktivitas Terbaru</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- Contoh data, ganti dengan real data nanti -->
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">Receive</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">REC-00123</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">10 Jan 2026</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Sudah SJ</span>
                                </td>
                            </tr>
                            <!-- Tambah row lain sesuai kebutuhan -->
                        </tbody>
                    </table>
                </div>
            </div>
        </main>

        <footer class="bg-white border-t border-gray-200 px-6 py-4 text-center text-sm text-gray-600">
            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </footer>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('activityChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Receive',
                    data: [65, 78, 90, 81, 96, 110, 125, 140, 135, 150, 165, 180],
                    backgroundColor: 'rgba(79, 70, 229, 0.6)',
                }, {
                    label: 'Invoice',
                    data: [45, 55, 70, 60, 80, 95, 105, 120, 115, 130, 145, 160],
                    backgroundColor: 'rgba(34, 197, 94, 0.6)',
                }, {
                    label: 'Surat Jalan',
                    data: [30, 40, 55, 45, 65, 80, 90, 105, 100, 115, 130, 145],
                    backgroundColor: 'rgba(168, 85, 247, 0.6)',
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'top' } },
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>
@endpush