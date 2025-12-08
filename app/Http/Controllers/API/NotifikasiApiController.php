<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Schema;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Notifikasi",
 *     description="Notifikasi untuk user"
 * )
 */
class NotifikasiApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/notifikasis",
     *     tags={"Notifikasi"},
     *     summary="List notifikasi untuk user login",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer", default=15)),
     *     @OA\Response(response=200, description="OK")
     * )
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $model = new Notifikasi();
        $table = $model->getTable();

        $query = Notifikasi::query();

        // Filter by user_id jika ada kolom user_id
        if ($user && Schema::hasColumn($table, 'user_id')) {
            $query->where('user_id', $user->id);
        }

        $perPage = (int) $request->query('per_page', 15);
        $perPage = $perPage > 0 ? min($perPage, 100) : 15;

        $results = $query->orderByDesc('created_at')->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'List notifikasi.',
            'data'    => $results->items(),
            'meta'    => [
                'current_page' => $results->currentPage(),
                'per_page'     => $results->perPage(),
                'total'        => $results->total(),
                'last_page'    => $results->lastPage(),
            ],
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/notifikasis/{id}",
     *     tags={"Notifikasi"},
     *     summary="Detail notifikasi",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function show(Notifikasi $notifikasi)
    {
        return response()->json([
            'success' => true,
            'data'    => $notifikasi,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/notifikasis/{id}",
     *     tags={"Notifikasi"},
     *     summary="Hapus notifikasi",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="No Content")
     * )
     */
    public function destroy(Notifikasi $notifikasi)
    {
        $notifikasi->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @OA\Post(
     *     path="/api/notifikasis/{id}/mark-read",
     *     tags={"Notifikasi"},
     *     summary="Tandai satu notifikasi sebagai sudah dibaca (jika ada kolom read_at / is_read)",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="OK")
     * )
     */
    public function markRead(Notifikasi $notifikasi)
    {
        $table = $notifikasi->getTable();

        if (Schema::hasColumn($table, 'read_at')) {
            $notifikasi->read_at = now();
        }

        if (Schema::hasColumn($table, 'is_read')) {
            $notifikasi->is_read = true;
        }

        $notifikasi->save();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi ditandai sudah dibaca.',
            'data'    => $notifikasi,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/notifikasis/mark-all-read",
     *     tags={"Notifikasi"},
     *     summary="Tandai semua notifikasi user sebagai sudah dibaca",
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="OK")
     * )
     */
    public function markAllRead(Request $request)
    {
        $user = $request->user();

        $model = new Notifikasi();
        $table = $model->getTable();

        if (! $user || (! Schema::hasColumn($table, 'user_id'))) {
            return response()->json([
                'success' => false,
                'message' => 'Skema notifikasi tidak mendukung mark-all-read berdasarkan user_id.',
            ], Response::HTTP_BAD_REQUEST);
        }

        $query = Notifikasi::where('user_id', $user->id);

        $payload = [];

        if (Schema::hasColumn($table, 'read_at')) {
            $payload['read_at'] = now();
        }

        if (Schema::hasColumn($table, 'is_read')) {
            $payload['is_read'] = true;
        }

        if (! empty($payload)) {
            $query->update($payload);
        }

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi ditandai sudah dibaca (jika kolom mendukung).',
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/notifikasis/unread-count",
     *     tags={"Notifikasi"},
     *     summary="Jumlah notifikasi belum dibaca",
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="OK")
     * )
     */
    public function unreadCount(Request $request)
    {
        $user = $request->user();

        $model = new Notifikasi();
        $table = $model->getTable();

        $query = Notifikasi::query();

        if ($user && Schema::hasColumn($table, 'user_id')) {
            $query->where('user_id', $user->id);
        }

        if (Schema::hasColumn($table, 'is_read')) {
            $query->where('is_read', false);
        } elseif (Schema::hasColumn($table, 'read_at')) {
            $query->whereNull('read_at');
        }

        $count = $query->count();

        return response()->json([
            'success' => true,
            'data'    => [
                'unread_count' => $count,
            ],
        ]);
    }
}
