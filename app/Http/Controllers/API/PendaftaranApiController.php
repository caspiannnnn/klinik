<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Schema;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Pendaftaran",
 *     description="Pendaftaran & antrian pasien"
 * )
 */
class PendaftaranApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/pendaftarans",
     *     tags={"Pendaftaran"},
     *     summary="List pendaftaran (paginate)",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Response(response=200, description="OK")
     * )
     */
    public function index(Request $request)
    {
        $model = new Pendaftaran();
        $table = $model->getTable();

        $query = Pendaftaran::query();

        // contoh filter status jika ada kolom status
        if ($request->filled('status') && Schema::hasColumn($table, 'status')) {
            $query->where('status', $request->query('status'));
        }

        // contoh filter berdasarkan user_id jika ada kolom user_id
        if ($request->filled('user_id') && Schema::hasColumn($table, 'user_id')) {
            $query->where('user_id', $request->query('user_id'));
        }

        $perPage = (int) $request->query('per_page', 15);
        $perPage = $perPage > 0 ? min($perPage, 100) : 15;

        $results = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'List pendaftaran.',
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
     *     path="/api/pendaftarans",
     *     tags={"Pendaftaran"},
     *     summary="Buat pendaftaran baru",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             description="Field mengikuti fillable di model Pendaftaran"
     *         )
     *     ),
     *     @OA\Response(response=201, description="Berhasil dibuat"),
     *     @OA\Response(response=422, description="Gagal menyimpan")
     * )
     */
    public function store(Request $request)
    {
        $model = new Pendaftaran();

        $data = $request->only($model->getFillable());

        try {
            $pendaftaran = Pendaftaran::create($data);
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data pendaftaran.',
                'error'   => $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran berhasil dibuat.',
            'data'    => $pendaftaran,
        ], Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/api/pendaftarans/{id}",
     *     tags={"Pendaftaran"},
     *     summary="Detail pendaftaran",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=404, description="Tidak ditemukan")
     * )
     */
    public function show(Pendaftaran $pendaftaran)
    {
        return response()->json([
            'success' => true,
            'data'    => $pendaftaran,
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/pendaftarans/{id}",
     *     tags={"Pendaftaran"},
     *     summary="Update pendaftaran",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(response=200, description="Berhasil diupdate"),
     *     @OA\Response(response=422, description="Gagal menyimpan")
     * )
     */
    public function update(Request $request, Pendaftaran $pendaftaran)
    {
        $data = $request->only($pendaftaran->getFillable());

        try {
            $pendaftaran->fill($data);
            $pendaftaran->save();
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui pendaftaran.',
                'error'   => $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran berhasil diperbarui.',
            'data'    => $pendaftaran,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/pendaftarans/{id}",
     *     tags={"Pendaftaran"},
     *     summary="Hapus pendaftaran",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Berhasil dihapus")
     * )
     */
    public function destroy(Pendaftaran $pendaftaran)
    {
        $pendaftaran->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @OA\Get(
     *     path="/api/pendaftarans/hari-ini",
     *     tags={"Pendaftaran"},
     *     summary="Pendaftaran hari ini (berdasarkan created_at jika ada)",
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="OK")
     * )
     */
    public function today(Request $request)
    {
        $model = new Pendaftaran();
        $table = $model->getTable();

        $query = Pendaftaran::query();

        if (Schema::hasColumn($table, 'created_at')) {
            $query->whereDate('created_at', now()->toDateString());
        }

        $perPage = (int) $request->query('per_page', 15);
        $perPage = $perPage > 0 ? min($perPage, 100) : 15;

        $results = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran untuk hari ini.',
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
     *     path="/api/pendaftarans/{id}/checkin",
     *     tags={"Pendaftaran"},
     *     summary="Check-in pendaftaran (optional, tergantung schema)",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Check-in berhasil")
     * )
     */
    public function checkin(Pendaftaran $pendaftaran)
    {
        $table = $pendaftaran->getTable();

        // misal update status & checkin_at jika kolom ada
        if (Schema::hasColumn($table, 'checkin_at')) {
            $pendaftaran->checkin_at = now();
        }

        if (Schema::hasColumn($table, 'status')) {
            if (defined(Pendaftaran::class . '::STATUS_DATANG')) {
                $pendaftaran->status = Pendaftaran::STATUS_DATANG;
            }
        }

        $pendaftaran->save();

        return response()->json([
            'success' => true,
            'message' => 'Check-in berhasil.',
            'data'    => $pendaftaran,
        ]);
    }
}
