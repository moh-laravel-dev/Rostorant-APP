<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Table extends Model
{
    protected $fillable = [
        'captain_id',
        'name',
        'number',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }

    public function captain(): BelongsTo
    {
        return $this->belongsTo(Captain::class);
    }

    protected static function booted(): void
    {
        static::creating(function (Table $table) {
            do {
                $code = '#' . strtoupper(Str::random(6));
            } while (static::where('code', $code)->exists());
            $number = static::max('number') + 1;
            $table->code = $code;
            $table->number = $number;
        });
    }
}
