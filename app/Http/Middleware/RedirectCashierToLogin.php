<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectCashierToLogin
{
    public function handle(Request $request, Closure $next): Response
    {
        $guard = auth('cashier');
        $cashier = $guard->user();

        if (!$cashier) {
            return redirect()->route('cashier.login');
        }

        if (!$cashier->is_active) {
            $guard->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('cashier.login')->withErrors([
                'phone' => 'تم إيقاف هذا الحساب. يرجى التواصل مع الإدارة.',
            ]);
        }

        return $next($request);
    }
}
