@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto p-6">
        <h1 class="text-3xl font-bold text-blue-700 mb-6">💳 Tagihan & Pembayaran</h1>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white shadow rounded-lg overflow-x-auto">
            <table class="min-w-full table-auto text-sm">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="py-3 px-4 text-left">ID</th>
                        <th class="py-3 px-4 text-left">Total Pembayaran</th>
                        <th class="py-3 px-4 text-left">Status</th>
                        <th class="py-3 px-4 text-left">Bukti</th>
                        <th class="py-3 px-4 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pembayarans as $tagihan)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4">{{ $loop->iteration }}</td>
                            <td class="py-3 px-4">Rp {{ number_format($tagihan->jumlah, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 capitalize">{{ $tagihan->status }}</td>

                            <td class="py-3 px-4">
                                @if($tagihan->bukti_pembayaran)
                                    <button
                                        type="button"
                                        onclick="openModal('{{ route('pembayaran.bukti', $tagihan->id) }}')"
                                        class="text-blue-600 underline hover:text-blue-800 text-sm">
                                        Lihat
                                    </button>
                                @else
                                    <span class="text-gray-500 text-sm">Belum Diupload</span>
                                @endif
                            </td>

                            <td class="py-3 px-4">
                                @if(!$tagihan->bukti_pembayaran)
                                    <form action="{{ route('pasien.upload.bukti', $tagihan->id) }}" method="POST"
                                          enctype="multipart/form-data">
                                        @csrf
                                        <input type="file" name="bukti_pembayaran" accept="image/*" class="mb-2 text-sm" required>
                                        <button type="submit"
                                            class="bg-green-600 hover:bg-green-700 text-white py-1 px-3 rounded text-sm">
                                            Upload Bukti
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-500 text-sm">Sudah Diupload</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-gray-500">Tidak ada tagihan ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6 p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 rounded">
            <p>💳 Silakan transfer ke rekening berikut:</p>
            <p class="font-semibold mt-1">Bank BNI - 1234567890 a.n. Klinik Sehat</p>
        </div>
    </div>

    <!-- Modal -->
    <div id="buktiModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg overflow-hidden shadow-lg max-w-md w-full relative">
            <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-500 hover:text-red-500 text-xl">&times;</button>
            <img id="modalImage" src="" alt="Bukti Pembayaran" class="w-full h-auto">
        </div>
    </div>

    <script>
        function openModal(url) {
            // cache buster biar tidak ambil cache lama
            const finalUrl = url + (url.includes('?') ? '&' : '?') + 't=' + Date.now();

            document.getElementById('modalImage').src = finalUrl;
            document.getElementById('buktiModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('buktiModal').classList.add('hidden');
            document.getElementById('modalImage').src = '';
        }
    </script>
@endsection
