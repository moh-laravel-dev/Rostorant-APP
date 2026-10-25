<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class BillsController extends Controller
{
    public function index(Request $request): View
    {
        $validator = Validator::make($request->query(), [
            'status' => ['sometimes', 'in:all,active,payment,completed'],
            'search' => ['nullable', 'string', 'max:255'],
            'per_page' => ['sometimes', 'integer', 'in:10,15,25,50,100'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'month' => ['nullable', 'integer', 'min:1', 'max:12'],
            'day' => ['nullable', 'integer', 'min:1', 'max:31'],
            'date_filter' => ['sometimes', 'in:1'],
        ]);

        $validator->after(function ($validator): void {
            $values = $validator->getData();
            if (isset($values['month'], $values['day']) && ! checkdate(
                (int) $values['month'],
                (int) $values['day'],
                isset($values['year']) ? (int) $values['year'] : 2000
            )) {
                $validator->errors()->add('day', 'يجب أن يكون اليوم صالحاً للتاريخ المحدد.');
            }
        });

        $filters = $validator->validate();
        $statusFilter = $filters['status'] ?? 'all';
        $search = trim($filters['search'] ?? '');
        $perPage = (int) ($filters['per_page'] ?? 10);
        $year = $filters['year'] ?? '';
        $month = $filters['month'] ?? '';
        $day = $filters['day'] ?? '';
        $hasDateParts = $request->query->has('year')
            || $request->query->has('month')
            || $request->query->has('day');

        if (! isset($filters['date_filter']) && ! $hasDateParts) {
            $year = now()->year;
            $month = now()->month;
        }

        $preservedFilters = array_filter([
            'search' => $search,
            'year' => $year,
            'month' => $month,
            'day' => $day,
            'per_page' => $perPage,
            'date_filter' => 1,
        ], fn ($value) => $value !== '' && $value !== null);
        // $year = $year !== '' ? $year : now()->year;
        // $month = $month !== '' ? $month : now()->month;
        // $day = $day !== '' ? $day : now()->day;
        $applyDateFilters = function ($query) use ($year, $month, $day) {
            return $query
                ->when($year !== '', fn ($query) => $query->whereYear('created_at', (int) $year))
                ->when($month !== '', fn ($query) => $query->whereMonth('created_at', (int) $month))
                ->when($day !== '', fn ($query) => $query->whereDay('created_at', (int) $day));
        };

        $billStats = $applyDateFilters(Bill::query())
            ->selectRaw(
                'COUNT(*) as total_count,
                COALESCE(SUM(CASE WHEN status = ? THEN 1 ELSE 0 END), 0) as active_count,
                COALESCE(SUM(CASE WHEN status = ? THEN 1 ELSE 0 END), 0) as payment_count,
                COALESCE(SUM(CASE WHEN status = ? THEN 1 ELSE 0 END), 0) as completed_count,
                COALESCE(SUM(CASE WHEN status = ? THEN total_price ELSE 0 END), 0) as total_revenue',
                ['active', 'payment', 'completed', 'completed']
            )
            ->first();
        $bills = $applyDateFilters(Bill::query())
            ->when($statusFilter !== 'all', fn ($query) => $query->where('status', $statusFilter))
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('number', 'like', "%{$search}%")
                        ->orWhere('table_id', 'like', "%{$search}%");
                });
            })
            ->orderBy('id')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.bills.index', compact(
            'bills',
            'billStats',
            'statusFilter',
            'search',
            'perPage',
            'year',
            'month',
            'day',
            'preservedFilters'
        ));
    }

    public function show(Bill $bill): View
    {
        $bill->load(['table', 'orders.food.categoryFood']);

        return view('admin.bills.show', compact('bill'));
    }

    public function edit(Bill $bill): View
    {
        abort_unless($bill->status === 'active', 404);

        $bill->load(['orders.food']);

        return view('admin.bills.edit', compact('bill'));
    }

    public function update(Request $request, Bill $bill): RedirectResponse
    {
        abort_unless($bill->status === 'active', 404);

        $validated = $request->validate([
            'orders' => ['sometimes', 'array'],
            'orders.*' => ['required', 'array:amount'],
            'orders.*.amount' => ['required', 'integer', 'min:1'],
        ]);

        $quantities = $validated['orders'] ?? [];

        DB::transaction(function () use ($bill, $quantities): void {
            $lockedBill = Bill::query()
                ->lockForUpdate()
                ->findOrFail($bill->getKey());

            abort_unless($lockedBill->status === 'active', 404);

            $billOrders = $lockedBill->orders()
                ->with('food')
                ->lockForUpdate()
                ->get();
            $billOrderIds = $billOrders->modelKeys();

            foreach (array_keys($quantities) as $orderId) {
                if (! ctype_digit((string) $orderId) || ! in_array((int) $orderId, $billOrderIds, true)) {
                    throw ValidationException::withMessages([
                        'orders' => 'تحتوي الطلبات المرسلة على عنصر لا يتبع هذه الفاتورة.',
                    ]);
                }
            }

            foreach ($quantities as $orderId => $values) {
                $order = $billOrders->firstWhere('id', (int) $orderId);
                [$wholePrice, $fractionalPrice] = array_pad(
                    explode('.', (string) $order->food->price, 2),
                    2,
                    '0'
                );
                $unitPriceInCents = ((int) $wholePrice * 100)
                    + (int) str_pad(substr($fractionalPrice, 0, 2), 2, '0');
                $lineTotalInCents = $unitPriceInCents * (int) $values['amount'];
                $lineTotal = intdiv($lineTotalInCents, 100)
                    .'.'
                    .str_pad((string) ($lineTotalInCents % 100), 2, '0', STR_PAD_LEFT);

                $order->update([
                    'amount' => (int) $values['amount'],
                    'total_price' => $lineTotal,
                ]);
            }

            $lockedBill->update([
                'total_price' => $lockedBill->orders()->sum('total_price'),
            ]);
        });

        return redirect()
            ->route('admin.bills.index')
            ->with('status', 'تم تحديث الفاتورة #'.$bill->number.' بنجاح.');
    }

    public function destroyOrder(Bill $bill, Order $order): RedirectResponse
    {
        abort_unless($bill->status === 'active', 404);

        DB::transaction(function () use ($bill, $order): void {
            $lockedBill = Bill::query()
                ->lockForUpdate()
                ->findOrFail($bill->getKey());

            abort_unless($lockedBill->status === 'active', 404);

            $billOrder = $lockedBill->orders()
                ->lockForUpdate()
                ->findOrFail($order->getKey());

            $billOrder->delete();

            $lockedBill->update([
                'total_price' => $lockedBill->orders()->sum('total_price'),
            ]);
        });

        return redirect()
            ->route('admin.bills.edit', $bill)
            ->with('status', 'تم حذف الطلب من الفاتورة #'.$bill->number.' بنجاح.');
    }
}
