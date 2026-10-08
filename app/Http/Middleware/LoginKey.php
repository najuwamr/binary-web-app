<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LoginKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $key = config('fortify.prefix');
        $pathKey = $request->segment(1);

        if (! is_string($key) || $key === '' || ! is_string($pathKey) || ! hash_equals($key, $pathKey)) {
            abort(404);
        }

        return $next($request);
    }
}
