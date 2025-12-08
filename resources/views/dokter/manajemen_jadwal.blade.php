@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6 text-blue-700">Manajemen Jadwal Dokter</h1>

    {{-- Notifikasi sukses --}}
    @if(session('success'))
        <div class="mb-4 bg-green-100 text-green-800 px-4 py-2 rounded">
            {{ session('success') }}
        </div>
    @endif

    {{-- Form Tambah Jadwal --}}
    <div class="bg-white p-6 rounded shadow mb-8">
        <h2 class="text-lg font-semibold mb-4">Tambah Jadwal Baru</h2>
        <form method="POST" action="{{ route('dokter.jadwal.store') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block mb-1 font-medium">Hari</label>
                    <select name="hari" class="w-full border-gray-300 rounded px-3 py-2" required>
                        <option value="">Pilih Hari</option>
                        @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $hari)
                            <option value="{{ $hari }}">{{ $hari }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block mb-1 font-medium">Jam Mulai</label>
                    <input type="time" name="jam_mulai" class="w-full border-gray-300 rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block mb-1 font-medium">Jam Selesai</label>
                    <input type="time" name="jam_selesai" class="w-full border-gray-300 rounded px-3 py-2" required>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    Simpan Jadwal
                </button>
            </div>
        </form>
    </div>

    {{-- Daftar Jadwal Dokter --}}
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-lg font-semibold mb-4">Jadwal Saya</h2>
        <table class="w-full table-auto border text-center">
            <thead class="bg-blue-100 text-blue-800">
                <tr>
                    <th class="p-2 border">Hari</th>
                    <th class="p-2 border">Jam Mulai</th>
                    <th class="p-2 border">Jam Selesai</th>
                    <th class="p-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jadwals as $jadwal)
                    <tr>
                        <td class="border p-2">{{ $jadwal->hari }}</td>
                        <td class="border p-2">{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}</td>
                        <td class="border p-2">{{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</td>
                        <td class="border p-2">
                            <form method="POST" action="{{ route('dokter.jadwal.destroy', $jadwal->id) }}" onsubmit="return confirm('Hapus jadwal ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-4 text-gray-500">Belum ada jadwal.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
