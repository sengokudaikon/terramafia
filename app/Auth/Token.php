<?php

use OpenApi\Annotations as OA;

/**
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     description="Авторизация по логину и паролю с получением токена авторизации.",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */

/**
 * @OA\Schema(
 *     schema="RespondWithToken",
 *     title="Ответ с токеном аутентификации.",
 *     @OA\Property(
 *          property="message",
 *          description="Сообщение.",
 *          example="Аутентификация прошла успешно.",
 *          type="string"
 *     ),
 *     @OA\Property(
 *          property="ttl",
 *          description="Время жизни токена (в минутах).",
 *          example="60",
 *          type="integer"
 *     ),
 *     @OA\Property(
 *          property="uuid",
 *          description="Глобальный идентификатор пользователя.",
 *          type="string",
 *          example="1c6383b4-90b7-431e-a9c0-fb398ea3225f"
 *     )
 * )
 */
