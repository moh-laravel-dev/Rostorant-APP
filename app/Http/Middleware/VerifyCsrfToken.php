<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyCsrfToken
{
    public function handle(Request $request, Closure $next): Response
    {
        if (
            in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE']) &&
            ! $request->isJson() &&
            ! $request->hasValidSignature() &&
            ! $request->session()->token() === $request->input('_token')
        ) {
            abort(Response::HTTP_FORBIDDEN, 'طلب غير صالح.');
        }

        return $next($request);
    }
}
