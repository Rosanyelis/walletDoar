<?php

namespace App;

/**
 * Especificación OpenAPI base para la documentación de las APIs en routes/api/v1.
 *
 * @OA\Info(
 *     title="Wallet Doar API",
 *     version="1.0.0",
 *     description="Documentación de la API REST Wallet Doar. Endpoints definidos en routes/api/v1 (auth, user, global)."
 * )
 * @OA\Server(
 *     url="/api/v1",
 *     description="API v1"
 * )
 * @OA\Tag(name="Auth", description="Registro, login y recuperación de contraseña")
 * @OA\Tag(name="User", description="Perfil, transacciones, billetera y operaciones de usuario")
 * @OA\Tag(name="Global", description="Endpoints globales públicos")
 *
 * @OA\PathItem(
 *     path="/",
 *     @OA\Get(
 *         operationId="apiInfo",
 *         summary="Info API",
 *         description="Información de la API v1.",
 *         tags={"Global"},
 *         @OA\Response(response=200, description="OK")
 *     )
 * )
 */
class OpenApiSpec
{
}
