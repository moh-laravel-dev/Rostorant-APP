<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;

    // Add this line to explicitly point to the plural table name
    protected $table = 'foods';

    protected $fillable = [
        'category_food_id',
        'name',
        'description',
        'price',
        'image',
        'is_active',
    ];

    public function categoryFood()
    {
        return $this->belongsTo(CategoryFood::class, 'category_food_id');
    }
}