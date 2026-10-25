<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Support\Facades\DB;

class CashierDashboardUpdated implements ShouldBroadcastNow
{
    public function __construct(public int $tableId) {}

    public function broadcastOn(): array
    {
        $channels = [
            new PrivateChannel('cashier.tables'),
            new Channel('table.'.$this->tableId),
        ];
        $captainId = DB::table('tables')->where('id', $this->tableId)->value('captain_id');

        if ($captainId !== null) {
            $channels[] = new PrivateChannel('captain.'.$captainId.'.tables');
        }

        return $channels;
    }

    public function broadcastAs(): string
    {
        return 'cashier.dashboard.updated';
    }
}
