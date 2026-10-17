<?php

namespace App\Http\Controllers;

use App\Events\CashierDashboardUpdated;
use App\Models\Order;
use App\Models\Table;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CashierDashboardController extends Controller
{
    public function index(): View
    {
        $tables = Table::with([
            'bills' => fn ($query) => $query
                ->where('status', 'active')
                ->with('orders.food')
                ->orderByDesc('created_at'),
        ])
            ->select('tables.*')
            ->selectSub(
                DB::table('bills')
                    ->select('created_at')
                    ->whereColumn('bills.table_id', 'tables.id')
                    ->where('status', 'active')
                    ->orderByDesc('created_at')
                    ->limit(1),
                'latest_active_bill_at'
            )
            ->selectSub(
                DB::table('orders')
                    ->join('bills', 'bills.id', '=', 'orders.bill_id')
                    ->selectRaw('MAX(orders.updated_at)')
                    ->whereColumn('bills.table_id', 'tables.id')
                    ->where('bills.status', 'active'),
                'latest_active_order_update_at'
            )
            ->orderBy('status')
            ->orderByDesc('latest_active_order_update_at')
            ->orderByDesc('latest_active_bill_at')
            ->orderBy('number')
            ->get();

        return view('cashier.tables', compact('tables'));
    }

    public function confirmOrder(Request $request, Order $order): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'expected_minutes' => ['required', 'integer', 'min:1', 'max:50399'],
        ], [
            'expected_minutes.required' => 'يرجى إدخال الدقائق المتوقعة.',
            'expected_minutes.integer' => 'الدقائق المتوقعة يجب أن تكون رقمًا صحيحًا.',
            'expected_minutes.min' => 'يجب أن تكون مدة التحضير دقيقة واحدة على الأقل.',
            'expected_minutes.max' => 'مدة التحضير لا يمكن أن تتجاوز 50399 دقيقة.',
        ]);

        $tableId = DB::transaction(function () use ($order, $validated): int {
            $pendingOrder = Order::query()
                ->whereKey($order->getKey())
                ->where('status', '0')
                ->whereHas('bill', fn ($query) => $query->where('status', 'active'))
                ->with('bill')
                ->lockForUpdate()
                ->firstOrFail();

            $pendingOrder->update([
                'status' => '1',
                'expected_time' => sprintf(
                    '%02d:%02d:00',
                    intdiv((int) $validated['expected_minutes'], 60),
                    (int) $validated['expected_minutes'] % 60
                ),
            ]);

            return (int) $pendingOrder->bill->table_id;
        });

        event(new CashierDashboardUpdated($tableId));

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم تأكيد الطلب وإرساله إلى المطبخ.',
            ]);
        }

        return redirect()
            ->route('cashier.tables')
            ->with('status', 'تم تأكيد الطلب وإرساله إلى المطبخ.');
    }
}
