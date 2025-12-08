<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $notifikasis = Notifikasi::where('user_id', $userId)
            ->latest()
            ->get();

        $unreadCount = Notifikasi::where('user_id', $userId)
            ->where('dibaca', false)
            ->count();

        return view('pasien.notifikasi', compact('notifikasis', 'unreadCount'));
    }

    public function read($id)
    {
        $notif = Notifikasi::where('user_id', Auth::id())->findOrFail($id);
        $notif->update(['dibaca' => true]);

        return redirect()->back();
    }

    public function readAll()
    {
        Notifikasi::where('user_id', Auth::id())
            ->where('dibaca', false)
            ->update(['dibaca' => true]);

        return redirect()->back();
    }
}
