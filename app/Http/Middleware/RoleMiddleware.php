<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */

  public function handle($request, Closure $next, $roles)
  {
    $user = Auth::user();
    $roles_del_usuario = $user->getRoleNames(); // Devuelve una colección de los nombres de los roles
    $roles_permitidos = explode('|', $roles);
    // regresar los
    if (!$user->hasAnyRole($roles_permitidos)) {
      abort(403, 'No tienes permiso para acceder a este módulo');
    }

    return $next($request);
  }
}
