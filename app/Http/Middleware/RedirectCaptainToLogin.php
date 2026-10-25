<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectCaptainToLogin
{
    public function handle(Request $request, Closure $next): Response
    {
        $guard = auth('captain');
        $captain = $guard->user();

        if (! $captain) {
            return redirect()->route('captain.login');
        }

        if (! $captain->is_active) {
            $guard->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('captain.login')->withErrors([
                'phone' => 'تم إيقاف هذا الحساب. يرجى التواصل مع الإدارة.',
            ]);
        }

        return $next($request);
    }
}
