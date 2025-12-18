<?php

namespace App\OpenApi;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *   version="1.0.0",
 *   title="Klinik Gigi API",
 *   description="Dokumentasi API untuk sistem Klinik Gigi"
 * )
 *
 * @OA\Server(
 *   url=L5_SWAGGER_CONST_HOST,
 *   description="Default Server"
 * )
 *
 * @OA\SecurityScheme(
 *   securityScheme="sanctum",
 *   type="apiKey",
 *   in="header",
 *   name="Authorization",
 *   description="Masukkan token dengan format: Bearer {token}"
 * )
 */
final class ApiSpec
{
    // File ini hanya untuk anotasi global OpenAPI
}
