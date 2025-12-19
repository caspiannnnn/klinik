@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6 text-blue-600">Pendaftaran Saya</h1>

    <div class="bg-white shadow rounded-lg p-6 border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-blue-100 text-blue-800 text-center">
                <tr>
                    <th class="px-4 py-2">No</th>
                    <th class="px-4 py-2">Tanggal/Jam</th>
                    <th class="px-4 py-2">Spesialis</th>
                    <th class="px-4 py-2">Dokter</th>
                    <th class="px-4 py-2">No Antrian</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Aksi</th>
                    <th class="px-4 py-2">Hapus</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-center">
                @forelse ($pendaftarans as $index => $p)
                    @php
                        $status = (string) ($p->status ?? '');
                        $isDiterima = strtolower($status) === 'diterima';
                    @endphp

                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $index + 1 }}</td>
                        <td class="px-4 py-2">
                            {{ optional($p->tanggal_kunjungan)->format('d-m-Y') ?? '-' }}
                            {{ $p->jam_kunjungan ? (' ' . substr((string)$p->jam_kunjungan, 0, 5)) : '' }}
                        </td>
                        <td class="px-4 py-2">{{ $p->spesialis ?? '-' }}</td>
                        <td class="px-4 py-2">{{ optional($p->dokter)->name ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $p->kode_antrian ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $p->status ?? '-' }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('pendaftaran.reschedule.form', $p->id) }}" class="text-blue-600 hover:underline text-sm">Reschedule</a>
                        </td>

                        <td class="px-4 py-2">
                            @if($isDiterima)
                                <button
                                    type="button"
                                    class="text-red-600 hover:underline text-sm"
                                    onclick="openCannotDeleteModal()">
                                    Hapus
                                </button>
                            @else
                                <form action="{{ route('pendaftaran.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pendaftaran ini?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline text-sm">
                                        Hapus
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-4 text-center text-gray-500">Belum ada pendaftaran.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ✅ POPUP ERROR (seperti contoh success modal) --}}
@if (session('error'))
<div id="cannotDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-black/50"></div>

    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md mx-4 p-6">
        <h2 class="text-xl font-bold text-blue-800 mb-2">⚠️ Tidak Bisa Dihapus</h2>
        <p class="text-gray-700 mb-4">{{ session('error') }}</p>

        <div class="text-right">
            <button
                type="button"
                id="closeCannotDeleteModal"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
                OK
            </button>
        </div>
    </div>
</div>

<script>
    function closeCannotDeleteModal() {
        const modal = document.getElementById('cannotDeleteModal');
        if (modal) modal.remove();
    }

    (function () {
        const modal = document.getElementById('cannotDeleteModal');
        const btn = document.getElementById('closeCannotDeleteModal');

        if (btn && modal) {
            btn.addEventListener('click', function () {
                closeCannotDeleteModal();
            });

            // klik area gelap untuk tutup
            modal.addEventListener('click', function (e) {
                if (e.target === modal) {
                    closeCannotDeleteModal();
                }
            });

            // esc untuk tutup
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    closeCannotDeleteModal();
                }
            });
        }
    })();
</script>
@endif

{{-- ✅ Modal versi klik tombol "Hapus" (status diterima) tanpa reload --}}
<script>
    function openCannotDeleteModal() {
        // Kalau modal session error sedang tampil, tidak perlu buat lagi
        if (document.getElementById('cannotDeleteModalInline')) return;

        const modal = document.createElement('div');
        modal.id = 'cannotDeleteModalInline';
        modal.className = 'fixed inset-0 z-50 flex items-center justify-center';
        modal.innerHTML = `
            <div class="absolute inset-0 bg-black/50"></div>

            <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md mx-4 p-6">
                <h2 class="text-xl font-bold text-blue-800 mb-2">⚠️ Tidak Bisa Dihapus</h2>
                <p class="text-gray-700 mb-4">
                    Maaf pendaftaran tidak bisa dihapus, karena sudah diterima oleh dokter!
                    Apabila ada perubahan jadwal silahkan pilih reschedule.
                </p>

                <div class="text-right">
                    <button
                        type="button"
                        id="closeCannotDeleteModalInline"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
                        OK
                    </button>
                </div>
            </div>
        `;

        document.body.appendChild(modal);

        function close() {
            const m = document.getElementById('cannotDeleteModalInline');
            if (m) m.remove();
        }

        const btn = document.getElementById('closeCannotDeleteModalInline');
        if (btn) {
            btn.addEventListener('click', close);
        }

        // klik area gelap untuk tutup
        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                close();
            }
        });

        // esc untuk tutup
        document.addEventListener('keydown', function escHandler(e) {
            if (e.key === 'Escape') {
                close();
                document.removeEventListener('keydown', escHandler);
            }
        });
    }
</script>
@endsection
