<?php

use App\Models\Cashier;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('cashier.tables', function (Cashier $cashier): bool {
    return $cashier->is_active;
}, ['guards' => ['cashier']]);
