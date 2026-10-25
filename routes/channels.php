<?php

use App\Models\Cashier;
use App\Models\Captain;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('cashier.tables', function (Cashier $cashier): bool {
    return $cashier->is_active;
}, ['guards' => ['cashier']]);

Broadcast::channel('cashier.end.bills', function (Cashier $cashier): bool {
    return $cashier->is_active;
}, ['guards' => ['cashier']]);

Broadcast::channel('captain.{captainId}.tables', function (Captain $captain, int $captainId): bool {
    return $captain->is_active && $captain->id === $captainId;
}, ['guards' => ['captain']]);
