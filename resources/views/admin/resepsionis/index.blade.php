@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <h1 class="text-3xl font-bold text-blue-700 mb-6">
        🧾 Daftar Resepsionis
    </h1>

    <div class="mb-4 text-right">
        <a href="{{ route('admin.resepsionis.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg shadow inline-flex items-center">
            <i class="fas fa-plus mr-2"></i> Tambah Resepsionis
        </a>
    </div>

    <div class="bg-white shadow rounded-lg overflow-x-auto">
        <table class="min-w-full table-auto text-sm">
            <thead class="bg-blue-600 text-white text-center">
                <tr>
                    <th class="py-3 px-4">#</th>
                    <th class="py-3 px-4">Nama</th>
                    <th class="py-3 px-4">Email</th>
                    <th class="py-3 px-4">Username</th>
                    <th class="py-3 px-4">Tanggal Daftar</th>
                    <th class="py-3 px-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($resepsionis as $user)
                    <tr class="border-b hover:bg-gray-100 text-center">
                        <td class="py-3 px-4">{{ $loop->iteration }}</td>
                        <td class="py-3 px-4">{{ $user->name }}</td>
                        <td class="py-3 px-4">{{ $user->email }}</td>
                        <td class="py-3 px-4">{{ $user->username }}</td>
                        <td class="py-3 px-4">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="py-3 px-4">
                            <div class="flex items-center justify-center gap-3">
                                <a href="{{ route('admin.resepsionis.edit', $user->id) }}"
                                   class="text-blue-600 hover:underline inline-flex items-center">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>

                                <form action="{{ route('admin.resepsionis.destroy', $user->id) }}" method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus resepsionis ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:underline inline-flex items-center">
                                        <i class="fas fa-trash mr-1"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-4 text-center text-gray-500">
                            Tidak ada data resepsionis.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
