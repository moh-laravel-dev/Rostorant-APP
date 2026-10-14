<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryFood extends Model
{
    protected $table = 'category_foods';

    protected $fillable = [
        'name',
        'photo',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function foods()
    {
        return $this->hasMany(Food::class, 'category_food_id');
    }
}
