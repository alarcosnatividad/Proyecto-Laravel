<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Importante: Primero verificamos si el usuario ha iniciado sesión
    // 2. Comprobamos si su campo 'role' es igual a 'admin'
    if (auth()->check() && auth()->user()->role === 'admin') {
        return $next($request); // Si es admin, le dejamos pasar
    }

    // Si no es admin (es decir, si es 'client' o no está logueado), lo echamos
    return redirect('/')->with('error', 'No tienes permisos de administrador.');
    }
}
