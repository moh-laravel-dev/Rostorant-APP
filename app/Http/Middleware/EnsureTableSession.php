<?php

namespace App\Http\Middleware;

use App\Models\Table;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTableSession
{
    public function handle(Request $request, Closure $next): Response
    {
        $tableId = session('table_id');
        $tableCode = session('table_code');

        if (!$tableId || !$tableCode) {
            return response()->view('errors.403', [], 403);
        }

        $table = Table::where('id', $tableId)
            ->where('code', $tableCode)
            ->first();

        if (!$table) {
            session()->forget(['table_id', 'table_code']);

            return response()->view('errors.403', [], 403);
        }

        $request->attributes->set('table', $table);

        return $next($request);
    }
}
