<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'food_id',
        'bill_id',
        'amount',
        'status',
        'expected_time',
        'total_price',
        'note',
    ];

    protected $casts = [
        'amount' => 'integer',
        'total_price' => 'decimal:2',
    ];

    public function food(): BelongsTo
    {
        return $this->belongsTo(Food::class);
    }

    public function bill(): BelongsTo
    {
        return $this->belongsTo(Bill::class);
    }
}
