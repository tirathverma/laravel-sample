<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    
    public function handle($request, Closure $next) {
	    if ( ! $request->is('api/*'))
	    {
	        return parent::handle($request, $next);
	    }

	    return $next($request);
	}
     
}
