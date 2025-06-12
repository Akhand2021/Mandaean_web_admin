<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *    title="Mandaean Flutter App API",
 *    version="1.0.0",
 * )
 * @OA\SecurityScheme(
 *     type="http",
 *     securityScheme="bearerAuth",
 *     scheme="bearer"
 * )
 * @OA\SecurityScheme(
 *     type="apiKey",
 *     securityScheme="apiKey",
 *     in="header",
 *     name="X-API-KEY"
 * )

 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
