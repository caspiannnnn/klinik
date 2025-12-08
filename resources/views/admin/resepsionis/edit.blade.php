@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto p-6">
    <h1 class="text-3xl font-bold text-blue-700 mb-6 text-center">
        ✏️ Edit Resepsionis
    </h1>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form method="POST" action="{{ route('admin.resepsionis.update', $user->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="nama" class="block font-semibold text-sm mb-1">Nama Lengkap</label>
                <input type="text" name="nama" id="nama" required
                       value="{{ old('nama', $user->name) }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-blue-200 text-sm"
                       placeholder="Nama lengkap">
            </div>

            <div class="mb-4">
                <label for="email" class="block font-semibold text-sm mb-1">Email</label>
                <input type="email" name="email" id="email" required
                       value="{{ old('email', $user->email) }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-blue-200 text-sm"
                       placeholder="Email aktif">
            </div>

            <div class="mb-4">
                <label for="username" class="block font-semibold text-sm mb-1">Username</label>
                <input type="text" name="username" id="username" required
                       value="{{ old('username', $user->username) }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-blue-200 text-sm"
                       placeholder="Username unik">
            </div>

            <div class="mb-4">
                <label for="password" class="block font-semibold text-sm mb-1">Password</label>
                <input type="password" name="password" id="password"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-blue-200 text-sm"
                       placeholder="Kosongkan jika tidak ingin mengubah password">
            </div>

            <div class="text-center">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-full shadow transition duration-200">
                    <i class="fas fa-save mr-2"></i> Update Resepsionis
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
