<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class CashierDashboardUpdated implements ShouldBroadcastNow
{
    public function __construct(public int $tableId) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('cashier.tables'),
            new Channel('table.'.$this->tableId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'cashier.dashboard.updated';
    }
}
