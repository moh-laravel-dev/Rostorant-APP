<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class BillRateLimit
{
    public function handle(Request $request, Closure $next): Response
    {
        $key = 'bill-finalize:'.($request->session()->getId());

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'يرجى الانتظار قبل المحاولة مرة أخرى.',
            ], Response::HTTP_TOO_MANY_REQUESTS);
        }

        RateLimiter::hit($key, 60);

        return $next($request);
    }
}
