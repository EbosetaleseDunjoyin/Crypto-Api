<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Symfony\Component\HttpFoundation\Response;

class ExcludeFromCsrf extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF protection.
     *
     * @var array
     */
    public $except = [
        'documentation/*',
    ];

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle($request, Closure $next)
    {
        if ($request->is('documentation/*')) {
            return $next($request);  // Pass the request object directly to the next middleware
        }

        return app(VerifyCsrfToken::class)->handle($request);  // Don't pass $next as the second argument here
    }
}
