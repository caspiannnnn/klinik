<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JadwalDokter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Schema;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="JadwalDokter",
 *     description="Jadwal praktik dokter"
 * )
 */
class JadwalDokterApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/jadwal-dokter",
     *     tags={"JadwalDokter"},
     *     summary="List jadwal dokter",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer", default=15)),
     *     @OA\Parameter(name="dokter_id", in="query", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="OK")
     * )
     */
    public function index(Request $request)
    {
        $model = new JadwalDokter();
        $table = $model->getTable();

        $query = JadwalDokter::query();

        if ($request->filled('dokter_id') && Schema::hasColumn($table, 'dokter_id')) {
            $query->where('dokter_id', $request->query('dokter_id'));
        }

        $perPage = (int) $request->query('per_page', 15);
        $perPage = $perPage > 0 ? min($perPage, 100) : 15;

        $results = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'List jadwal dokter.',
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
     *     path="/api/jadwal-dokter",
     *     tags={"JadwalDokter"},
     *     summary="Buat jadwal dokter",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(required=true, @OA\JsonContent()),
     *     @OA\Response(response=201, description="Created"),
     *     @OA\Response(response=422, description="Unprocessable Entity")
     * )
     */
    public function store(Request $request)
    {
        $model = new JadwalDokter();
        $data  = $request->only($model->getFillable());

        try {
            $jadwal = JadwalDokter::create($data);
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan jadwal dokter.',
                'error'   => $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->json([
            'success' => true,
            'message' => 'Jadwal dokter berhasil dibuat.',
            'data'    => $jadwal,
        ], Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/api/jadwal-dokter/{jadwal_dokter}",
     *     tags={"JadwalDokter"},
     *     summary="Detail jadwal dokter",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="jadwal_dokter", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function show(JadwalDokter $jadwal_dokter)
    {
        return response()->json([
            'success' => true,
            'data'    => $jadwal_dokter,
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/jadwal-dokter/{jadwal_dokter}",
     *     tags={"JadwalDokter"},
     *     summary="Update jadwal dokter",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="jadwal_dokter", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=false, @OA\JsonContent()),
     *     @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=422, description="Unprocessable Entity")
     * )
     */
    public function update(Request $request, JadwalDokter $jadwal_dokter)
    {
        $data = $request->only($jadwal_dokter->getFillable());

        try {
            $jadwal_dokter->fill($data);
            $jadwal_dokter->save();
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui jadwal dokter.',
                'error'   => $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->json([
            'success' => true,
            'message' => 'Jadwal dokter berhasil diperbarui.',
            'data'    => $jadwal_dokter,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/jadwal-dokter/{jadwal_dokter}",
     *     tags={"JadwalDokter"},
     *     summary="Hapus jadwal dokter",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="jadwal_dokter", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="No Content")
     * )
     */
    public function destroy(JadwalDokter $jadwal_dokter)
    {
        $jadwal_dokter->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @OA\Get(
     *     path="/api/jadwal-dokter/dokter/{dokter_id}",
     *     tags={"JadwalDokter"},
     *     summary="Jadwal per dokter (jika ada kolom dokter_id)",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="dokter_id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="OK")
     * )
     */
    public function byDoctor($dokter_id, Request $request)
    {
        $model = new JadwalDokter();
        $table = $model->getTable();

        $query = JadwalDokter::query();

        if (Schema::hasColumn($table, 'dokter_id')) {
            $query->where('dokter_id', $dokter_id);
        }

        $perPage = (int) $request->query('per_page', 15);
        $perPage = $perPage > 0 ? min($perPage, 100) : 15;

        $results = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Jadwal dokter.',
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
