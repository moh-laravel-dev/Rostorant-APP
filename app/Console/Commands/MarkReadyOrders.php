<?php

namespace App\Console\Commands;

use App\Events\CashierDashboardUpdated;
use App\Models\Order;
use Illuminate\Console\Command;

class MarkReadyOrders extends Command
{
    protected $signature = 'orders:mark-ready';

    protected $description = 'Mark kitchen orders as served after their expected preparation time';

    public function handle(): int
    {
        $now = now();
        $updatedTableIds = [];

        Order::query()
            ->with('bill:id,table_id')
            ->where('status', '1')
            ->whereHas('bill', fn ($query) => $query->where('status', 'active'))
            ->get()
            ->each(function (Order $order) use ($now, &$updatedTableIds): void {
                $durationParts = array_map('intval', explode(':', (string) $order->expected_time));

                if (count($durationParts) !== 3 || $order->created_at === null) {
                    return;
                }

                $expectedSeconds = ($durationParts[0] * 3600)
                    + ($durationParts[1] * 60)
                    + $durationParts[2];

                if (
                    $expectedSeconds < 1 ||
                    $order->created_at->copy()->addSeconds($expectedSeconds)->isAfter($now)
                ) {
                    return;
                }

                $updated = Order::query()
                    ->whereKey($order->getKey())
                    ->where('status', '1')
                    ->where('created_at', $order->created_at)
                    ->update([
                        'status' => '3',
                    ]);

                if ($updated && $order->bill?->table_id !== null) {
                    $updatedTableIds[] = (int) $order->bill->table_id;
                }
            });

        foreach (array_unique($updatedTableIds) as $tableId) {
            event(new CashierDashboardUpdated($tableId));
        }

        $this->info(
            'Updated '.count(array_unique($updatedTableIds)).' table(s) with completed orders.'
        );

        return self::SUCCESS;
    }
}
