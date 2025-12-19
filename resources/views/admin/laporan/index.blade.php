@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <div class="flex items-center justify-between gap-4 mb-6">
        <h1 class="text-3xl font-bold text-blue-700">📊 Laporan Pemasukan</h1>

        <div class="flex items-center gap-3">
            <button onclick="window.print()"
                    class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg shadow inline-flex items-center">
                <i class="fas fa-print mr-2"></i> Print
            </button>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-4 mb-6">
        <form method="GET" action="{{ route('admin.laporan.index') }}" class="flex flex-col md:flex-row md:items-center gap-3">
            <div class="flex items-center gap-2">
                <label for="periode" class="text-sm font-semibold text-gray-700">Filter Periode:</label>
                <select name="periode" id="periode"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring focus:ring-blue-200">
                    <option value="minggu" {{ $periode === 'minggu' ? 'selected' : '' }}>Seminggu</option>
                    <option value="bulan"  {{ $periode === 'bulan' ? 'selected' : '' }}>Sebulan</option>
                    <option value="tahun"  {{ $periode === 'tahun' ? 'selected' : '' }}>Setahun</option>
                    <option value="semua"  {{ $periode === 'semua' ? 'selected' : '' }}>Semua</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow">
                    Terapkan
                </button>

                <label class="inline-flex items-center gap-2 text-sm text-gray-700 select-none">
                    <input type="checkbox" id="autoRefresh" class="rounded border-gray-300">
                    Auto refresh
                </label>
            </div>

            <div class="md:ml-auto text-sm text-gray-600">
                @if($from)
                    Rentang: <span class="font-semibold">{{ $from->format('d M Y') }}</span> - <span class="font-semibold">{{ $to->format('d M Y') }}</span>
                @else
                    Rentang: <span class="font-semibold">Semua waktu</span>
                @endif
            </div>
        </form>
    </div>

    <div class="bg-white shadow rounded-lg overflow-x-auto">
        <table class="min-w-full table-auto text-sm">
            <thead class="bg-blue-600 text-white text-center">
                <tr>
                    <th class="py-3 px-4">#</th>
                    <th class="py-3 px-4">Nama Pasien</th>
                    <th class="py-3 px-4">Kode Tagihan</th>
                    <th class="py-3 px-4">Nominal</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4">Tanggal Bayar</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($laporan as $row)
                    <tr class="border-b hover:bg-gray-100 text-center">
                        <td class="py-3 px-4">{{ $loop->iteration }}</td>
                        <td class="py-3 px-4">{{ $row->user->name ?? 'Tidak diketahui' }}</td>
                        <td class="py-3 px-4">{{ $row->kode_tagihan ?? '-' }}</td>
                        <td class="py-3 px-4">Rp {{ number_format((int) $row->jumlah, 0, ',', '.') }}</td>
                        <td class="py-3 px-4">{{ $row->status ?? '-' }}</td>
                        <td class="py-3 px-4">{{ $row->created_at ? $row->created_at->format('d M Y H:i') : '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-4 text-center text-gray-500">
                            Belum ada pemasukan pada periode ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            @if(!$laporan->isEmpty())
            <tfoot>
                <tr class="bg-blue-50">
                    <td colspan="3" class="py-3 px-4 font-semibold text-blue-800 text-right">Total Keseluruhan:</td>
                    <td class="py-3 px-4 font-bold text-blue-800">Rp {{ number_format((int) $totalKeseluruhan, 0, ',', '.') }}</td>
                    <td class="py-3 px-4"></td>
                    <td class="py-3 px-4"></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>

{{-- CSS khusus print (tidak mengubah style layar) --}}
<style>
@media print {
    /* Biar hanya laporan yang dicetak */
    body {
        background: #fff !important;
    }

    /* Sembunyikan navbar/sidebar/footer dari layout (umum pada template Laravel) */
    header, nav, aside, footer {
        display: none !important;
    }

    /* Sembunyikan tombol/fitur filter saat print */
    button, #autoRefresh, label[for="periode"], select#periode,
    .bg-white.shadow.rounded-lg.p-4.mb-6 form button,
    .bg-white.shadow.rounded-lg.p-4.mb-6 label {
        display: none !important;
    }

    /* Hilangkan card filter (kotak putih) saat print, tapi tetap tampilkan rentang tanggalnya */
    .bg-white.shadow.rounded-lg.p-4.mb-6 {
        background: transparent !important;
        box-shadow: none !important;
        border: 0 !important;
        padding: 0 !important;
        margin-bottom: 10px !important;
    }

    /* Tampilkan hanya teks rentang di kanan */
    .bg-white.shadow.rounded-lg.p-4.mb-6 .md\\:ml-auto {
        display: block !important;
        margin-left: 0 !important;
        color: #111827 !important;
        font-size: 12px !important;
        text-align: left !important;
    }

    /* Rapikan container */
    .max-w-6xl {
        max-width: 100% !important;
    }
    .p-6 {
        padding: 0 !important;
    }

    /* Judul lebih rapi saat print */
    h1 {
        font-size: 18px !important;
        font-weight: 700 !important;
        color: #111827 !important;
        margin: 0 0 6px 0 !important;
    }

    /* Nonaktifkan shadow/rounded saat print biar clean */
    .bg-white.shadow.rounded-lg {
        box-shadow: none !important;
        border-radius: 0 !important;
        border: 0 !important;
    }

    /* Tabel + font rapi */
    table {
        width: 100% !important;
        border-collapse: collapse !important;
        font-size: 12px !important;
    }
    thead tr {
        background: #f3f4f6 !important; /* abu-abu muda */
        color: #111827 !important;
    }

    /* ✅ CENTER header & body */
    th, td {
        border: 1px solid #d1d5db !important;
        padding: 8px 10px !important;
        vertical-align: middle !important;
        text-align: center !important;
    }

    th {
        font-weight: 700 !important;
        text-transform: none !important;
    }

    /* Hindari warna hover */
    tr:hover {
        background: transparent !important;
    }

    /* Footer total lebih tegas */
    tfoot tr {
        background: #f9fafb !important;
    }

    /* Hapus elemen yang bisa mengganggu layout print */
    .overflow-x-auto {
        overflow: visible !important;
    }

    /* Margin halaman print */
    @page {
        margin: 14mm;
    }
}
</style>

<script>
(function () {
    const checkbox = document.getElementById('autoRefresh');
    if (!checkbox) return;

    const key = 'laporan_auto_refresh';
    checkbox.checked = localStorage.getItem(key) === '1';

    checkbox.addEventListener('change', () => {
        localStorage.setItem(key, checkbox.checked ? '1' : '0');
    });

    // auto refresh tiap 20 detik (real-time dari DB)
    setInterval(() => {
        if (checkbox.checked) {
            window.location.reload();
        }
    }, 20000);
})();
</script>
@endsection
