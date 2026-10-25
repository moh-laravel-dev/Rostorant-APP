<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class GlobalRateLimit
{
    public function handle(
        Request $request,
        Closure $next,
        int $maxAttempts = 60,
        int $decaySeconds = 60
    ): Response {
        $key = 'global-rate-limit:'.(
            $request->user()
                ? 'user:'.$request->user()->getAuthIdentifier()
                : 'ip:'.$request->ip()
        );

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $retryAfter = RateLimiter::availableIn($key);

            $headers = [
                'Retry-After' => $retryAfter,
                'X-RateLimit-Limit' => $maxAttempts,
                'X-RateLimit-Remaining' => 0,
                'Cache-Control' => 'no-store, no-cache, must-revalidate',
            ];

            if (
                $request->expectsJson() ||
                $request->ajax() ||
                $request->is('api/*')
            ) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'لقد تجاوزت الحد المسموح من الطلبات، يرجى المحاولة لاحقًا.',
                    'retry_after' => $retryAfter,
                ], Response::HTTP_TOO_MANY_REQUESTS, $headers);
            }

            return response()->view(
                'errors.rate-limit',
                ['retryAfter' => $retryAfter],
                Response::HTTP_TOO_MANY_REQUESTS,
                $headers
            );
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
