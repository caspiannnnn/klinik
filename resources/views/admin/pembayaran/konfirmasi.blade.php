@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <h1 class="text-3xl font-bold text-blue-700 mb-6">Konfirmasi Pembayaran</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if($pembayarans->isEmpty())
        <p class="text-gray-600">Tidak ada pembayaran yang menunggu konfirmasi.</p>
    @else
        <div class="bg-white shadow rounded-lg overflow-x-auto">
            <table class="min-w-full table-auto text-sm">
                <thead class="bg-blue-600 text-white text-center">
                    <tr>
                        <th class="py-3 px-4">#</th>
                        <th class="py-3 px-4">Pasien</th>
                        <th class="py-3 px-4">Kode Tagihan</th>
                        <th class="py-3 px-4">Jumlah</th>
                        <th class="py-3 px-4">Bukti Pembayaran</th>
                        <th class="py-3 px-4">Tanggal Upload</th>
                        <th class="py-3 px-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach ($pembayarans as $pembayaran)
                        <tr class="border-b hover:bg-gray-100">
                            <td class="py-3 px-4">{{ $loop->iteration }}</td>
                            <td class="py-3 px-4">{{ $pembayaran->user->name }}</td>
                            <td class="py-3 px-4">{{ $pembayaran->kode_tagihan }}</td>
                            <td class="py-3 px-4">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</td>

                            <td class="py-3 px-4">
                                @if($pembayaran->bukti_pembayaran)
                                    {{-- ✅ Pakai route streaming (anti 403) --}}
                                    <button
                                        type="button"
                                        onclick="openBuktiModal('{{ route('pembayaran.bukti', $pembayaran->id) }}')"
                                        class="text-blue-600 underline hover:text-blue-800">
                                        Lihat Bukti
                                    </button>
                                @else
                                    <span class="text-gray-500">Belum ada</span>
                                @endif
                            </td>

                            <td class="py-3 px-4">
                                {{ optional($pembayaran->updated_at)->format('d M Y H:i') }}
                            </td>

                            <td class="py-3 px-4">
                                <form action="{{ route('admin.pembayaran.konfirmasi.update', $pembayaran->id) }}"
                                      method="POST"
                                      class="flex items-center gap-2">
                                    @csrf
                                    <select name="status" class="border rounded px-2 py-1" required>
                                        <option value="menunggu konfirmasi" {{ $pembayaran->status === 'menunggu konfirmasi' ? 'selected' : '' }}>
                                            Menunggu Konfirmasi
                                        </option>
                                        <option value="lunas" {{ $pembayaran->status === 'lunas' ? 'selected' : '' }}>
                                            Lunas
                                        </option>
                                        <option value="ditolak" {{ $pembayaran->status === 'ditolak' ? 'selected' : '' }}>
                                            Ditolak
                                        </option>
                                    </select>
                                    <button type="submit"
                                            class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition">
                                        Update
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- ✅ Modal Preview Bukti --}}
<div id="buktiModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg max-w-3xl w-full mx-4 overflow-hidden relative">
        <button type="button"
                onclick="closeBuktiModal()"
                class="absolute top-2 right-3 text-2xl text-gray-500 hover:text-red-600">
            &times;
        </button>

        <div class="p-4 border-b">
            <h2 class="text-lg font-semibold text-gray-800">Bukti Pembayaran</h2>
        </div>

        <div class="p-4">
            <img id="buktiImage"
                 src=""
                 alt="Bukti Pembayaran"
                 class="w-full h-auto rounded border" />

            <div id="buktiError" class="hidden mt-3 p-3 bg-red-50 text-red-700 rounded text-sm">
                Gambar gagal dimuat.
            </div>
        </div>
    </div>
</div>

<script>
    function openBuktiModal(url) {
        const modal = document.getElementById('buktiModal');
        const img = document.getElementById('buktiImage');
        const err = document.getElementById('buktiError');

        // reset error + event
        err.classList.add('hidden');
        img.onerror = () => err.classList.remove('hidden');

        // anti-cache
        const busted = url + (url.includes('?') ? '&' : '?') + 't=' + Date.now();
        img.src = busted;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeBuktiModal() {
        const modal = document.getElementById('buktiModal');
        const img = document.getElementById('buktiImage');

        img.src = '';
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // klik area gelap untuk tutup
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('buktiModal');
        if (!modal.classList.contains('hidden') && e.target === modal) {
            closeBuktiModal();
        }
    });
</script>
@endsection
