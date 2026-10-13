<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Table extends Model
{
    protected $fillable = [
        'name',
        'number',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

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
