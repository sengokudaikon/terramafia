<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

/**
 * @OA\OpenApi(
 *     @OA\Info(
 *          version="1.0.0",
 *          title="Swagger TerraMafia",
 *          description="Api documentation TerraMafia"
 *     ),
 *     @OA\Server(
 *          description="OpenApi host",
 *          url="/"
 *     ),
 *     @OA\ExternalDocumentation(
 *          description="Find out more about Swagger",
 *          url="http://swagger.io"
 *     ),
 *     @OA\PathItem(path="api")
 * )
 *
 * @package App\Http\Controllers
 */
class IndexController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/info",
     *     tags={"App"},
     *     summary="Проверка api.",
     *     description="После запроса будет получена текстовая информация об успешности работы api.",
     *     @OA\Response(
     *          response="200",
     *          description="API работает."
     *     )
     * )
     *
     * @return JsonResponse
     */
    public function info(): JsonResponse
    {
        return $this->successResponse('App works successfully');
    }
}
