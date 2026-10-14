<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\CategoryFood;
use App\Models\Food;
use App\Models\Order;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    public function index(string $billNumber): View
    {
        $categories = CategoryFood::query()
            ->where('is_active', 1)
            ->orderBy('name')
            ->with([
                'foods' => function ($query) {
                    $query->where('is_active', 1);
                },
            ])
            ->get();
        $bill = Bill::where('number', $billNumber)->with('orders.food')->first();
        if (!$bill) {
            abort(404, "هذه الفاتورة غير صحيحة");
        }
        $data = [];
        foreach ($bill->orders as $i => $order) {
            $data[$i]['food_name'] = $order->food->name;
            $data[$i]['amount'] = $order->amount;
            $data[$i]['total_price'] = $order->total_price;
            $data[$i]['food_price'] = $order->food->price;
            $data[$i]['status'] = config('status.order.' . $order->status);
            $data[$i]['created_at'] = Carbon::parse($order->created_at)->format('Y-m-d h:i:s A');
            $data[$i]['expected_time'] = $order->expected_time;
        }


        return view('Menu.menu', compact('categories', 'billNumber', 'data'));
    }

    public function createToken(string $code): RedirectResponse|Response
    {
        $table = Table::where('code', '#' . $code)->firstOrFail();

        $ipAddress = request()->ip();

        $tableId = session('table_id');
        $tableCode = session('table_code');
        $expiresAt = session('table_session_expires_at');

        if ($tableId && $tableCode && $expiresAt && now()->timestamp < $expiresAt) {
            $currentTableExists = Table::where('id', $tableId)
                ->where('code', $tableCode)
                ->exists();

            if ($currentTableExists) {
                if (session('table_ip_address') !== $ipAddress) {
                    return response()->view('errors.403', [], 403);
                }
                $bill = Bill::where('table_id', $tableId)->first();

                return redirect()->route('menu.index', $bill->number);
            }
        }

        if ($tableId && $tableCode) {
            session()->forget([
                'table_id',
                'table_code',
                'table_session_expires_at',
                'table_ip_address',
            ]);
        }

        $opened = DB::transaction(function () use ($table) {
            $lockedTable = Table::whereKey($table->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (!$lockedTable->status) {
                return false;
            }

            $lockedTable->update([
                'status' => false,
            ]);

            return true;
        });

        if (!$opened) {
            return response()->view('errors.table-session-active', [], 403);
        }

        session()->regenerate();

        session([
            'table_id' => $table->id,
            'table_code' => $table->code,
            'table_ip_address' => $ipAddress,
            'table_session_expires_at' => now()->addDay()->timestamp,
        ]);
        $bill = Bill::create([
            'start_time' => now(),
            'number' => now()->format('YmdHis') . Str::upper(Str::random(18)),
            'table_id' => $tableId,
        ]);

        return redirect()->route('menu.index', $bill->number);
    }

    public function loginWithCode()
    {
        return "code";
    }

    public function newOrders(Request $request)
    {
        $validated = $request->validate([
            'billNumber' => ['required'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['required', 'integer', 'exists:foods,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        Log::info('New order received', $validated);

        $bill = Bill::where('number', $validated['billNumber'])->first();

        if (!$bill) {
            return response()->json([
                'success' => false,
                'message' => 'هذه الفاتورة غير موجودة.',
            ], 400);
        }

        foreach ($validated['items'] as $item) {
            $food = Food::findOrFail($item['id']);

            $order = Order::firstOrNew([
                'food_id' => $food->id,
                'bill_id' => $bill->id,
            ]);

            $order->amount = ($order->amount ?? 0) + $item['quantity'];
            $order->total_price = $order->amount * $food->price;
            $order->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'تمت إضافة الوجبات إلى الطلب بنجاح.',
        ]);
    }
}