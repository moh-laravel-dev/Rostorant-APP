<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class GlobalRateLimit
{
    public function handle(Request $request, Closure $next): Response
    {
        $key = 'global-rate-limit:'.(
            $request->user()
                ? 'user:'.$request->user()->getAuthIdentifier()
                : 'ip:'.$request->ip()
        );

        $maxAttempts = 60;
        $decaySeconds = 60;

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $retryAfter = RateLimiter::availableIn($key);

            return response()->json([
                'status' => 'failed',
                'message' => 'لقد تجاوزت الحد المسموح من الطلبات، يرجى المحاولة لاحقًا.',
                'retry_after' => $retryAfter,
            ], Response::HTTP_TOO_MANY_REQUESTS, [
                'Retry-After' => $retryAfter,
                'X-RateLimit-Limit' => $maxAttempts,
                'X-RateLimit-Remaining' => 0,
            ]);
        }

        RateLimiter::hit($key, $decaySeconds);

        $response = $next($request);

        $response->headers->set('X-RateLimit-Limit', $maxAttempts);
        $response->headers->set(
            'X-RateLimit-Remaining',
            RateLimiter::remaining($key, $maxAttempts)
        );

        return $response;
    }
}
