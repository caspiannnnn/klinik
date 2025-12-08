@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <h1 class="text-3xl font-bold text-blue-700 mb-6">📄 Data Tagihan Pembayaran</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow rounded-lg overflow-x-auto">
        <table class="min-w-full table-auto text-sm">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="py-3 px-4 text-left">No</th>
                    <th class="py-3 px-4 text-left">Pasien</th>
                    <th class="py-3 px-4 text-left">Kode Tagihan</th>
                    <th class="py-3 px-4 text-left">Jumlah</th>
                    <th class="py-3 px-4 text-left">Status</th>
                    <th class="py-3 px-4 text-left">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pembayarans as $item)
                    <tr class="border-b hover:bg-gray-100">
                        <td class="py-3 px-4">{{ $loop->iteration }}</td>
                        <td class="py-3 px-4">{{ $item->user->name }}</td>
                        <td class="py-3 px-4">{{ $item->kode_tagihan }}</td>
                        <td class="py-3 px-4">Rp{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                        <td class="py-3 px-4">
                            @if($item->status === 'lunas')
                                <span class="text-green-600 font-semibold">Lunas</span>
                            @else
                                <span class="text-red-600 font-semibold">Belum Dibayar</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">{{ $item->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-gray-500">Belum ada data tagihan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
