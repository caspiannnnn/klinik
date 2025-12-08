@extends('layouts.app')

@section('content')
    <h1 class="text-3xl font-bold mb-6 text-blue-600">Edit Profil</h1>

    {{-- <form action="{{ route('profile.update') }}" method="POST" class="bg-white shadow-md rounded-lg p-6"> --}}
        @csrf
        @method('PUT') <!-- Use PUT method for updating -->

        <div class="mb-4">
            <label for="username" class="block text-gray-700 font-semibold mb-2">Username</label>
            <input type="text" id="username" name="username" value="{{ Auth::user()->username }}" class="border border-gray-300 rounded-lg w-full p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div class="mb-4">
            <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
            <input type="email" id="email" name="email" value="{{ Auth::user()->email }}" class="border border-gray-300 rounded-lg w-full p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div class="mb-4">
            <label for="name" class="block text-gray-700 font-semibold mb-2">Nama</label>
            <input type="text" id="name" name="name" value="{{ Auth::user()->name }}" class="border border-gray-300 rounded-lg w-full p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div class="mb-4">
            <label for="password" class="block text-gray-700 font-semibold mb-2">Password</label>
            <input type="password" id="password" name="password" class="border border-gray-300 rounded-lg w-full p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Kosongkan jika tidak ingin mengubah">
        </div>

        <div class="mb-4">
            <label for="alamat" class="block text-gray-700 font-semibold mb-2">Alamat</label>
            <input type="text" id="alamat" name="alamat" value="{{ Auth::user()->alamat }}" class="border border-gray-300 rounded-lg w-full p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div class="mb-4">
            <label for="telepon" class="block text-gray-700 font-semibold mb-2">Telepon</label>
            <input type="text" id="telepon" name="telepon" value="{{ Auth::user()->telepon }}" class="border border-gray-300 rounded-lg w-full p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <button type="submit" class="bg-blue-500 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-600 transition duration-200">Simpan Perubahan</button>
    </form>
@endsection