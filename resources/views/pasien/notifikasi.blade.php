@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-blue-700">🔔 Notifikasi</h1>

        <div class="flex items-center gap-3">
            <span class="text-sm text-gray-600">
                Belum dibaca:
                <span class="font-semibold text-blue-700">{{ $unreadCount }}</span>
            </span>

            <form action="{{ route('pasien.notifikasi.readAll') }}" method="POST">
                @csrf
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg shadow transition">
                    Tandai semua dibaca
                </button>
            </form>
        </div>
    </div>

    @if($notifikasis->isEmpty())
        <div class="bg-white shadow rounded-lg p-6 border border-gray-200">
            <p class="text-gray-600">Belum ada notifikasi.</p>
        </div>
    @else
        <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200">
            <ul class="divide-y divide-gray-200">
                @foreach($notifikasis as $notif)
                    <li class="p-5 hover:bg-gray-50 transition">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <h3 class="text-base font-semibold {{ $notif->dibaca ? 'text-gray-700' : 'text-blue-700' }}">
                                        {{ $notif->judul }}
                                    </h3>

                                    @if(!$notif->dibaca)
                                        <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full">
                                            Baru
                                        </span>
                                    @endif
                                </div>

                                <p class="mt-2 text-sm text-gray-700">
                                    {{ $notif->pesan }}
                                </p>

                                <div class="mt-3 flex flex-wrap items-center gap-3 text-xs text-gray-500">
                                    <span>{{ $notif->created_at->format('d-m-Y H:i') }}</span>

                                    @if($notif->tipe)
                                        <span class="px-2 py-1 bg-gray-100 rounded-full text-gray-600">
                                            {{ $notif->tipe }}
                                        </span>
                                    @endif

                                    {{-- ✅ kalau link kosong/null, tidak tampil sama sekali --}}
                                    @if(!empty($notif->link))
                                        @php
                                            $link = $notif->link;

                                            // ✅ khusus notifikasi status pendaftaran -> arahkan ke Pendaftaran Saya
                                            if (($notif->judul ?? '') === 'Status Pendaftaran') {
                                                $link = route('pendaftaran.saya');
                                            }
                                        @endphp
                                        <a href="{{ $link }}" class="text-blue-600 hover:underline">
                                            Lihat detail
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <div class="shrink-0">
                                @if(!$notif->dibaca)
                                    <form action="{{ route('pasien.notifikasi.read', $notif->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="text-sm text-blue-600 hover:underline">
                                            Tandai dibaca
                                        </button>
                                    </form>
                                @else
                                    <span class="text-sm text-gray-400">Dibaca</span>
                                @endif
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
@endsection
