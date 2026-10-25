<?php

namespace Database\Seeders;

use App\Models\Bill;
use App\Models\Food;
use App\Models\Order;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class BillsOrdersSeeder extends Seeder
{
    public function run(): void
    {
        $tableIds = Table::query()
            ->whereBetween('id', [3, 52])
            ->orderBy('id')
            ->pluck('id')
            ->all();
        $foods = Food::query()
            ->whereBetween('id', [1, 23])
            ->orderBy('id')
            ->get(['id', 'price'])
            ->values();

        if ($tableIds === []) {
            throw new RuntimeException('BillsOrdersSeeder requires tables with IDs from 3 to 52.');
        }

        if ($foods->isEmpty()) {
            throw new RuntimeException('BillsOrdersSeeder requires foods with IDs from 1 to 23.');
        }

        $date = Carbon::create(2026, 1, 1)->startOfDay();
        $lastDate = Carbon::today();
        $dayIndex = 0;
        $billsPerDay = 8;

        DB::transaction(function () use ($date, $lastDate, $tableIds, $foods, &$dayIndex, $billsPerDay): void {
            while ($date->lte($lastDate)) {
                for ($billIndex = 0; $billIndex < $billsPerDay; $billIndex++) {
                    $startTime = $date->copy()
                        ->setTime(10, 0)
                        ->addMinutes(($billIndex * 83 + $dayIndex * 17) % 720);
                    $endTime = $startTime->copy()->addMinutes(45 + (($dayIndex + $billIndex * 11) % 75));
                    $billNumber = 'SEED-2026-' . $date->format('md')
                        . ($billIndex === 0 ? '' : '-' . str_pad((string) ($billIndex + 1), 2, '0', STR_PAD_LEFT));
                    $tableId = $tableIds[($dayIndex * $billsPerDay + $billIndex) % count($tableIds)];
                    $orderCount = min($foods->count(), 3 + (($dayIndex + $billIndex) % 4));
                    $billOrders = [];
                    $billTotal = 0;

                    for ($orderIndex = 0; $orderIndex < $orderCount; $orderIndex++) {
                        $food = $foods[($dayIndex * 3 + $billIndex * 5 + $orderIndex) % $foods->count()];
                        $amount = 1 + (($dayIndex + $billIndex + $orderIndex) % 3);
                        $totalPrice = round((float) $food->price * $amount, 2);
                        $billTotal += $totalPrice;
                        $orderDate = $startTime->copy()->addMinutes(5 + $orderIndex * 7);
                        $billOrders[] = [
                            'food_id' => $food->id,
                            'amount' => $amount,
                            'total_price' => $totalPrice,
                            'status' => '2',
                            'expected_time' => '00:' . str_pad((string) (10 + $orderIndex * 5), 2, '0', STR_PAD_LEFT) . ':00',
                            'created_at' => $orderDate,
                            'updated_at' => $orderDate,
                        ];
                    }

                    $bill = Bill::query()->firstOrCreate(
                        ['number' => $billNumber],
                        [
                            'start_time' => $startTime,
                            'end_time' => $endTime,
                            'code' => 'SEED-' . $date->format('Ymd') . '-' . str_pad((string) ($billIndex + 1), 2, '0', STR_PAD_LEFT),
                            'status' => 'completed',
                            'table_id' => $tableId,
                            'total_price' => $billTotal,
                            'created_at' => $startTime,
                            'updated_at' => $endTime,
                        ],
                    );

                    foreach ($billOrders as $order) {
                        Order::query()->firstOrCreate(
                            [
                                'bill_id' => $bill->id,
                                'food_id' => $order['food_id'],
                            ],
                            [
                                'amount' => $order['amount'],
                                'total_price' => $order['total_price'],
                                'status' => $order['status'],
                                'expected_time' => $order['expected_time'],
                                'created_at' => $order['created_at'],
                                'updated_at' => $order['updated_at'],
                            ],
                        );
                    }
                }

                $date->addDay();
                $dayIndex++;
            }
        });
    }
}
