<?php

namespace App\Http\Middleware;

use Closure;
use Gate;

class CmsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Gate::denies('editor')) {
            abort(404);
        }

        return $next($request);
    }
}
