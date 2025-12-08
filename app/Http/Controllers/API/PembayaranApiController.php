<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Schema;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Pembayaran",
 *     description="Pembayaran & tagihan"
 * )
 */
class PembayaranApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/pembayarans",
     *     tags={"Pembayaran"},
     *     summary="List pembayaran",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer", default=15)),
     *     @OA\Parameter(name="status", in="query", @OA\Schema(type="string")),
     *     @OA\Parameter(name="user_id", in="query", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="OK")
     * )
     */
    public function index(Request $request)
    {
        $model = new Pembayaran();
        $table = $model->getTable();

        $query = Pembayaran::query();

        if ($request->filled('status') && Schema::hasColumn($table, 'status')) {
            $query->where('status', $request->query('status'));
        }

        if ($request->filled('user_id') && Schema::hasColumn($table, 'user_id')) {
            $query->where('user_id', $request->query('user_id'));
        }

        $perPage = (int) $request->query('per_page', 15);
        $perPage = $perPage > 0 ? min($perPage, 100) : 15;

        $results = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'List pembayaran.',
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
     * @OA\Post(
     *     path="/api/pembayarans",
     *     tags={"Pembayaran"},
     *     summary="Buat pembayaran/tagihan baru",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(required=true, @OA\JsonContent()),
     *     @OA\Response(response=201, description="Created"),
     *     @OA\Response(response=422, description="Unprocessable Entity")
     * )
     */
    public function store(Request $request)
    {
        $model = new Pembayaran();
        $data  = $request->only($model->getFillable());

        try {
            $pembayaran = Pembayaran::create($data);
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan pembayaran.',
                'error'   => $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil dibuat.',
            'data'    => $pembayaran,
        ], Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/api/pembayarans/{id}",
     *     tags={"Pembayaran"},
     *     summary="Detail pembayaran",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function show(Pembayaran $pembayaran)
    {
        return response()->json([
            'success' => true,
            'data'    => $pembayaran,
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/pembayarans/{id}",
     *     tags={"Pembayaran"},
     *     summary="Update pembayaran",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=false, @OA\JsonContent()),
     *     @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=422, description="Unprocessable Entity")
     * )
     */
    public function update(Request $request, Pembayaran $pembayaran)
    {
        $data = $request->only($pembayaran->getFillable());

        try {
            $pembayaran->fill($data);
            $pembayaran->save();
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui pembayaran.',
                'error'   => $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil diperbarui.',
            'data'    => $pembayaran,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/pembayarans/{id}",
     *     tags={"Pembayaran"},
     *     summary="Hapus pembayaran",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="No Content")
     * )
     */
    public function destroy(Pembayaran $pembayaran)
    {
        $pembayaran->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @OA\Post(
     *     path="/api/pembayarans/{id}/upload-bukti",
     *     tags={"Pembayaran"},
     *     summary="Upload bukti pembayaran (jika ada kolom bukti_path)",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     description="File bukti pembayaran",
     *                     property="bukti",
     *                     type="string",
     *                     format="binary"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=422, description="Unprocessable Entity")
     * )
     */
    public function uploadBukti(Request $request, Pembayaran $pembayaran)
    {
        $request->validate([
            'bukti' => ['required', 'file', 'max:2048'],
        ]);

        $path = $request->file('bukti')->store('pembayaran', 'public');

        $table = $pembayaran->getTable();

        if (Schema::hasColumn($table, 'bukti_path')) {
            $pembayaran->bukti_path = $path;
        }

        $pembayaran->save();

        return response()->json([
            'success' => true,
            'message' => 'Bukti pembayaran berhasil diupload.',
            'data'    => $pembayaran,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/pembayarans/pasien/{user_id}",
     *     tags={"Pembayaran"},
     *     summary="Pembayaran per pasien (jika ada kolom user_id)",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="user_id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="OK")
     * )
     */
    public function byPatient($userId, Request $request)
    {
        $model = new Pembayaran();
        $table = $model->getTable();

        $query = Pembayaran::query();

        if (Schema::hasColumn($table, 'user_id')) {
            $query->where('user_id', $userId);
        }

        $perPage = (int) $request->query('per_page', 15);
        $perPage = $perPage > 0 ? min($perPage, 100) : 15;

        $results = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran pasien.',
            'data'    => $results->items(),
            'meta'    => [
                'current_page' => $results->currentPage(),
                'per_page'     => $results->perPage(),
                'total'        => $results->total(),
                'last_page'    => $results->lastPage(),
            ],
        ]);
    }
}
