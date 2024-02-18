<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Symfony\Component\HttpFoundation\Response;

class ExcludeFromCsrf 
{
    /**
     * Handle an incoming request.
     *
     *
     */
    // @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
    public function handle($request, Closure $next)
    {
        if ($request->is('documentation/*')) {
            return $next($request);
        }

        return app(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class)->handle($request, $next);
    }
}
