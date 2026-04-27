<!-- resources/views/reports/teknisi/show.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-center">Detail Report Teknisi: {{ $teknisi->name }}</h1>
    <p class="text-center text-gray-600 mb-4">{{ $teknisi->status? "Aktif" : "Tidak Aktif" }}</p>

    @if ($details->isNotEmpty())
        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead class="bg-gray-200">
                <tr>
                    <th class="py-3 px-4 text-left">Category</th>
                    <th class="py-3 px-4 text-left">Jumlah yang Dikerjakan</th>
                    <th class="py-3 px-4 text-left">Barang yang Dikerjakan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($details as $productId => $group)
                    <tr class="{{ $loop->index % 2 == 0 ? 'bg-gray-50' : '' }}">
                        <td class="py-3 px-4">
                            {{ $group->first()->product ? $group->first()->product->name : 'Unknown Category' }} 
                            (Code: {{ $group->first()->product ? $group->first()->product->code : 'N/A' }})
                        </td>
                        <td class="py-3 px-4">{{ $group->count() }}</td>
                        <td class="py-3 px-4">
                            <ul class="list-none pl-5 ">
                                @foreach ($group as $detail)
                                    <li>
                                        {{ $detail->product_name }} -
                                        {{ $detail->serial_number }}
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-center text-gray-600 mt-4">Tidak ada product yang dikerjakan oleh teknisi ini.</p>
    @endif

    <div class="mt-6 text-center">
        <a href="{{ route('report.teknisi') }}" class="text-blue-500 hover:underline">Kembali ke Index</a>
    </div>
</div>
@endsection