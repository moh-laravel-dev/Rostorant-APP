<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class CashierEndBill implements ShouldBroadcastNow
{
    public function __construct(public string $billNumber) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('cashier.end.bills'),
            new Channel('bill.'.$this->billNumber),
        ];
    }

    public function broadcastAs(): string
    {
        return 'user.bill.end';
    }
}
