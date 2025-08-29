<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * @OA\Info(
 * title="Roles Middleware API",
 * version="1.0.0",
 * description="API para la gestión de roles de usuario",
 * )
 */
class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * 
    */

    /**
     * @OA\Get(
     *     path="/api/admin/dashboard",
     *     summary="Obtener el dashboard del administrador",
     *     description="Requiere que el usuario esté autenticado y tenga el rol de 'admin'.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Bienvenido, administrador.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Acceso denegado. El usuario no tiene el rol 'admin'."
     *     )
     * )
     */
    public function handle($request, Closure $next, $role) {

        if ($request->user()->role !== $role) {
            abort(403);
        }
        return $next($request);
    }
}
