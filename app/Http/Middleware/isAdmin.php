<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            abort(403, "Akses ditolak!! silahkan login terlebih dahulu.");
        }

        if (auth()->user()->role !== "admin") {
            abort(403, "Akses ditolak!! anda tidak memiliki izin untuk mengakses halaman ini.");
        }

        return $next($request);
    }
}
