<?php

namespace App\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         title="Klinik API",
 *         version="1.0.0",
 *         description="Dokumentasi API untuk SIM Klinik Gigi."
 *     )
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Klinik API server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Masukkan token dalam format: Bearer {token}"
 * )
 */
class OpenApi
{
    // kosong tidak apa-apa, ini cuma tempat anotasi
}
