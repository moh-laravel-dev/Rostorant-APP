<?php

namespace App\Http\Controllers;

use App\Events\CashierDashboardUpdated;
use App\Models\Bill;
use App\Models\CategoryFood;
use App\Models\Food;
use App\Models\Order;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    private function filePath(): string
    {
        return storage_path('app/table_services.json');
    }

    public function index(string $billNumber)
    {
        $categories = CategoryFood::query()
            // ->where('is_active', 1)
            ->orderBy('name')
            ->with([
                'foods',
            ])
            ->get();

        $bill = Bill::where('number', $billNumber)
            ->with('orders.food')
            ->first();

        if (! $bill) {
            return response()->view('errors.404', [
                'title' => 'عذراً، لم يتم العثور على الفاتورة',
                'message' => 'لا توجد فاتورة نشطة مرتبطة بهذه الطاولة.',
                'route' => 'menu.page',
                'routeName' => 'إدخال رمز الطاولة',
            ], 404);
        }

        $data = $bill->orders
            ->groupBy('food_id')
            ->map(function ($orders) {
                $firstOrder = $orders->first();

                $formatDuration = function ($totalSeconds) {
                    return sprintf(
                        '%02d:%02d:%02d',
                        intdiv((int) $totalSeconds, 3600),
                        intdiv((int) $totalSeconds % 3600, 60),
                        (int) $totalSeconds % 60
                    );
                };

                $getDurationInSeconds = function ($order) {
                    $parts = array_map(
                        'intval',
                        explode(':', (string) $order->expected_time)
                    );

                    if (count($parts) !== 3) {
                        return 0;
                    }

                    return ($parts[0] * 3600)
                        + ($parts[1] * 60)
                        + $parts[2];
                };

                $statuses = $orders
                    ->groupBy('status')
                    ->sortByDesc(fn ($statusOrders) => Carbon::parse(
                        $statusOrders->min('created_at')
                    )->getTimestamp())
                    ->map(function ($statusOrders, $status) use ($formatDuration, $getDurationInSeconds) {
                        $totalSeconds = $statusOrders->sum($getDurationInSeconds);

                        return [
                            'status_code' => (string) $status,
                            'status' => config('status.order.'.$status),
                            'amount' => $statusOrders->sum('amount'),
                            'amount_price' => $statusOrders->sum('total_price'),
                            'expected_time' => $formatDuration($totalSeconds),
                            'created_at' => Carbon::parse(
                                $statusOrders->min('created_at')
                            )->format('Y-m-d h:i:s A'),
                        ];
                    })
                    ->values();

                $totalSeconds = $orders->sum($getDurationInSeconds);

                return [
                    'food_id' => $firstOrder->food_id,
                    'food_name' => $firstOrder->food->name,
                    'food_price' => $firstOrder->food->price,

                    'amount' => $orders->sum('amount'),
                    'total_price' => $orders->sum('total_price'),
                    'notes' => $orders
                        ->pluck('note')
                        ->filter(fn ($note) => filled($note))
                        ->unique()
                        ->values(),
                    'order_notes' => $orders
                        ->filter(fn ($order) => filled($order->note))
                        ->map(fn ($order) => [
                            'amount' => (int) $order->amount,
                            'note' => $order->note,
                        ])
                        ->values(),
                    'expected_time' => $formatDuration($totalSeconds),

                    'statuses' => $statuses,

                    'created_at' => Carbon::parse(
                        $orders->min('created_at')
                    )->format('Y-m-d h:i:s A'),
                ];
            })
            ->values()
            ->toArray();
        $tableName = $bill->table->name;
        $tableNumber = $bill->table->number;
        $tableId = (int) $bill->table_id;
        $billCode = $bill->code;
        $billStatus = $bill->status;
        $path = $this->filePath();

        $services = [];

        if (File::exists($path)) {
            $services = collect(json_decode(File::get($path), true) ?? [])
                ->filter(fn ($service) => $service['active'] ?? false)
                ->map(fn ($service) => [
                    'name' => $service['name'] ?? '',
                    'price' => ($service['value'] ?? 0),
                    'currency' => $service['currency'],
                ])
                ->values()
                ->all();
        }

        return view('Menu.menu', compact('categories', 'services', 'billNumber', 'billStatus', 'billCode', 'tableId', 'tableName', 'tableNumber', 'data'));
    }

    public function createToken(string $code, ?int $from = 0)
    {
        $bill = Bill::where('code', $code)->first();
        $is_new = false;
        if (! $bill) {
            $is_new = true;
            $table = Table::where('code', '#'.$code)->first();
            if (! $table && ($from == '1' || $from == '2')) {
                return back()->withErrors(['code' => 'تحقق من صحة الرمز المدخل']);
            } elseif (! $table) {
                return response()->view('errors.404', [
                    'title' => ' عذراً، لم يتم العثور على الطاولة',
                    'message' => ' رمز الطاولة غير صحيح أو أن الصفحة التي تبحث عنها غير متاحة. تحقق من الرمز وحاول مرة أخرى.',
                    'route' => 'menu.page',
                    'routeName' => 'إدخال رمز الطاولة',
                ], 404);
            }

            $ipAddress = request()->ip();

            $tableId = session('table_id');
            $tableCode = session('table_code');
            $expiresAt = session('table_session_expires_at');
            if (
                $tableId &&
                $tableCode &&
                $expiresAt &&
                now()->timestamp < $expiresAt
            ) {
                $currentTableExists = Table::where('id', $tableId)
                    ->where('code', $tableCode)
                    ->exists();

                if ($currentTableExists) {
                    // منع استخدام QR لطاولة مختلفة عن الطاولة المرتبطة بالجلسة
                    if (
                        (int) $tableId !== (int) $table->id ||
                        $tableCode !== $table->code
                    ) {
                        return response()->view(
                            'errors.table-session-active',
                            ['tableCode' => $code, 'from' => $from],
                            403
                        );
                    }

                    // التحقق من عنوان IP للجلسة الحالية
                    if (session('table_ip_address') !== $ipAddress) {
                        return response()->view('errors.403', [], 403);
                    }

                    $bill = Bill::where('table_id', $table->id)
                        ->whereIn('status', ['active', 'payment'])
                        ->first();

                    if (! $bill) {
                        return response()->view('errors.404', [
                            'title' => 'عذراً، لم يتم العثور على الفاتورة',
                            'message' => 'لا توجد فاتورة نشطة مرتبطة بهذه الطاولة.',
                            'route' => 'menu.page',
                            'routeName' => 'إدخال رمز الطاولة',
                        ], 404);
                    }

                    return redirect()->route('menu.index', $bill->number);
                }
            }

            $opened = DB::transaction(function () use ($table) {
                $lockedTable = Table::whereKey($table->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (! $lockedTable->status) {
                    return false;
                }

                $lockedTable->update([
                    'status' => false,
                ]);

                return true;
            });

            if (! $opened) {
                return response()->view(
                    'errors.table-session-active',
                    ['tableCode' => $code, 'from' => $from],
                    403
                );
            }

            session()->regenerate();

            session([
                'table_id' => $table->id,
                'table_code' => $table->code,
                'table_ip_address' => $ipAddress,
                'table_session_expires_at' => now()->addDay()->timestamp,
            ]);
            session()->save();

            DB::table('sessions')
                ->where('id', session()->getId())
                ->update([
                    'table_id' => $table->id,
                ]);
            $bill = Bill::create([
                'start_time' => now(),
                'code' => Str::upper(Str::random(12)),
                'number' => now()->format('YmdHis').Str::upper(Str::random(18)),
                'table_id' => $table->id,
            ]);
            event(new CashierDashboardUpdated((int) $bill->table_id));
            Log::info('token', [session('_token')]);

            return redirect()->route('menu.index', $bill->number);
        }
        $table = Table::find($bill->table_id);
        $ipAddress = request()->ip();

        session()->regenerate();

        session([
            'table_id' => $table->id,
            'table_code' => $table->code,
            'table_ip_address' => $ipAddress,
            'table_session_expires_at' => now()->addDay()->timestamp,
        ]);

        session()->save();

        DB::table('sessions')
            ->where('id', session()->getId())
            ->update([
                'table_id' => $table->id,
            ]);
        if ($is_new) {
            event(new CashierDashboardUpdated((int) $bill->table_id));
        }

        return redirect()->route('menu.index', $bill->number);
    }

    public function newOrders(Request $request)
    {
        $validated = $request->validate([
            'billNumber' => ['required'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['required', 'integer', 'exists:foods,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.note' => ['nullable', 'string', 'max:1000'],
        ]);

        $bill = Bill::where('number', $validated['billNumber'])->first();

        if (! $bill) {
            return response()->json([
                'success' => false,
                'message' => 'هذه الفاتورة غير موجودة.',
            ], 400);
        }
        if ($bill->status === 'payment') {
            return response()->json([
                'success' => false,
                'message' => 'عذراً، لقد طلبت الحساب بالفعل! لا يمكنك إضافة طلبات جديدة إلى هذه الفاتورة.',
            ], 400);
        }

        DB::transaction(function () use ($validated, $bill): void {
            foreach ($validated['items'] as $item) {
                $food = Food::findOrFail($item['id']);
                Order::create([
                    'food_id' => $food->id,
                    'bill_id' => $bill->id,
                    'status' => '0',
                    'amount' => $item['quantity'],
                    'total_price' => $item['quantity'] * $food->price,
                    'note' => filled($item['note'] ?? null) ? trim($item['note']) : null,
                ]);
            }
        });

        event(new CashierDashboardUpdated((int) $bill->table_id));

        return response()->json([
            'success' => true,
            'message' => 'تمت إضافة الوجبات إلى الطلب بنجاح.',
        ]);
    }

    public function updateData(string $number)
    {
        $bill = Bill::where('number', $number)->firstOrFail();

        $data = $bill->orders
            ->groupBy('food_id')
            ->sortByDesc(fn ($statusOrders) => Carbon::parse(
                $statusOrders->min('created_at')
            )->getTimestamp())
            ->map(function ($orders) {
                $firstOrder = $orders->first();

                $formatDuration = function ($totalSeconds) {
                    return sprintf(
                        '%02d:%02d:%02d',
                        intdiv((int) $totalSeconds, 3600),
                        intdiv((int) $totalSeconds % 3600, 60),
                        (int) $totalSeconds % 60
                    );
                };

                $getDurationInSeconds = function ($order) {
                    $parts = array_map(
                        'intval',
                        explode(':', (string) $order->expected_time)
                    );

                    if (count($parts) !== 3) {
                        return 0;
                    }

                    return ($parts[0] * 3600)
                        + ($parts[1] * 60)
                        + $parts[2];
                };

                $statuses = $orders
                    ->groupBy('status')
                    ->sortByDesc(fn ($statusOrders) => Carbon::parse(
                        $statusOrders->min('created_at')
                    )->getTimestamp())
                    ->map(function ($statusOrders, $status) use ($formatDuration, $getDurationInSeconds) {
                        $totalSeconds = $statusOrders->sum($getDurationInSeconds);

                        return [
                            'status_code' => (string) $status,
                            'status' => config('status.order.'.$status),
                            'amount' => $statusOrders->sum('amount'),
                            'amount_price' => $statusOrders->sum('total_price'),
                            'expected_time' => $formatDuration($totalSeconds),
                            'created_at' => Carbon::parse(
                                $statusOrders->min('created_at')
                            )->format('Y-m-d h:i:s A'),
                        ];
                    })
                    ->values();

                $totalSeconds = $orders->sum($getDurationInSeconds);

                return [
                    'food_id' => $firstOrder->food_id,
                    'food_name' => $firstOrder->food->name,
                    'food_price' => $firstOrder->food->price,

                    'amount' => $orders->sum('amount'),
                    'total_price' => $orders->sum('total_price'),
                    'notes' => $orders
                        ->pluck('note')
                        ->filter(fn ($note) => filled($note))
                        ->unique()
                        ->values(),
                    'order_notes' => $orders
                        ->filter(fn ($order) => filled($order->note))
                        ->map(fn ($order) => [
                            'amount' => (int) $order->amount,
                            'note' => $order->note,
                        ])
                        ->values(),
                    'expected_time' => $formatDuration($totalSeconds),

                    'statuses' => $statuses,

                    'created_at' => Carbon::parse(
                        $orders->min('created_at')
                    )->format('Y-m-d h:i:s A'),
                ];
            })
            ->values()
            ->toArray();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function menuPage(Request $request)
    {
        $code = $request->query('code');

        if (filled($code)) {
            $segments = is_array($code) ? $code : [$code];
            $rawCode = strtoupper(implode('', array_map(static fn ($segment) => (string) $segment, $segments)));
            $normalizedCode = preg_replace('/^#/', '', $rawCode);

            if (! is_string($normalizedCode) || ! preg_match('/^[A-Z0-9]{6}$/', $normalizedCode)) {
                return redirect()->route('menu.page')->withErrors([
                    'code' => 'يرجى إدخال رمز طاولة صالح مكوّن من 6 خانات.',
                ]);
            }

            return redirect()->route('menu.login', ['code' => $normalizedCode]);
        }

        return view('Menu.join-to-menu');
    }
}
