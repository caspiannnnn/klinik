<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notifikasi;

class NotifikasiApiController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $data = Notifikasi::where('user_id', $user->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function show(Notifikasi $notifikasi)
    {
        return response()->json([
            'success' => true,
            'data' => $notifikasi,
        ]);
    }

    public function destroy(Notifikasi $notifikasi)
    {
        $notifikasi->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil dihapus',
        ]);
    }

    public function unreadCount(Request $request)
    {
        $user = $request->user();

        $count = Notifikasi::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'unread_count' => $count,
            ],
        ]);
    }

    public function markAllRead(Request $request)
    {
        $user = $request->user();

        Notifikasi::where('user_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi ditandai sudah dibaca',
        ]);
    }

    public function markRead(Request $request, Notifikasi $notifikasi)
    {
        $notifikasi->is_read = true;
        $notifikasi->save();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi ditandai sudah dibaca',
            'data' => $notifikasi,
        ]);
    }
}
