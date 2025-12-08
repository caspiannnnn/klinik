<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RekamMedis;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Schema;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="RekamMedis",
 *     description="Rekam medis pasien"
 * )
 */
class RekamMedisApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/rekam-medis",
     *     tags={"RekamMedis"},
     *     summary="List rekam medis",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer", default=15)),
     *     @OA\Parameter(name="user_id", in="query", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="pendaftaran_id", in="query", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="OK")
     * )
     */
    public function index(Request $request)
    {
        $model = new RekamMedis();
        $table = $model->getTable();

        $query = RekamMedis::query();

        if ($request->filled('user_id') && Schema::hasColumn($table, 'user_id')) {
            $query->where('user_id', $request->query('user_id'));
        }

        if ($request->filled('pendaftaran_id') && Schema::hasColumn($table, 'pendaftaran_id')) {
            $query->where('pendaftaran_id', $request->query('pendaftaran_id'));
        }

        $perPage = (int) $request->query('per_page', 15);
        $perPage = $perPage > 0 ? min($perPage, 100) : 15;

        $results = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'List rekam medis.',
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
     *     path="/api/rekam-medis",
     *     tags={"RekamMedis"},
     *     summary="Buat rekam medis baru",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(response=201, description="Created"),
     *     @OA\Response(response=422, description="Unprocessable Entity")
     * )
     */
    public function store(Request $request)
    {
        $model = new RekamMedis();
        $data  = $request->only($model->getFillable());

        try {
            $rekamMedis = RekamMedis::create($data);
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan rekam medis.',
                'error'   => $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->json([
            'success' => true,
            'message' => 'Rekam medis berhasil dibuat.',
            'data'    => $rekamMedis,
        ], Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/api/rekam-medis/{id}",
     *     tags={"RekamMedis"},
     *     summary="Detail rekam medis",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function show(RekamMedis $rekam_medi)
    {
        return response()->json([
            'success' => true,
            'data'    => $rekam_medi,
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/rekam-medis/{id}",
     *     tags={"RekamMedis"},
     *     summary="Update rekam medis",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=false, @OA\JsonContent()),
     *     @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=422, description="Unprocessable Entity")
     * )
     */
    public function update(Request $request, RekamMedis $rekam_medi)
    {
        $data = $request->only($rekam_medi->getFillable());

        try {
            $rekam_medi->fill($data);
            $rekam_medi->save();
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui rekam medis.',
                'error'   => $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->json([
            'success' => true,
            'message' => 'Rekam medis berhasil diperbarui.',
            'data'    => $rekam_medi,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/rekam-medis/{id}",
     *     tags={"RekamMedis"},
     *     summary="Hapus rekam medis",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="No Content")
     * )
     */
    public function destroy(RekamMedis $rekam_medi)
    {
        $rekam_medi->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @OA\Get(
     *     path="/api/rekam-medis/pasien/{user_id}",
     *     tags={"RekamMedis"},
     *     summary="List rekam medis per pasien (jika ada kolom user_id)",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="user_id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="OK")
     * )
     */
    public function byPatient($userId, Request $request)
    {
        $model = new RekamMedis();
        $table = $model->getTable();

        $query = RekamMedis::query();

        if (Schema::hasColumn($table, 'user_id')) {
            $query->where('user_id', $userId);
        }

        $perPage = (int) $request->query('per_page', 15);
        $perPage = $perPage > 0 ? min($perPage, 100) : 15;

        $results = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Rekam medis pasien.',
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
