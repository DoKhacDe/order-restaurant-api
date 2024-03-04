<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    use HasFactory;
    protected $table = 'meals';

    protected $fillable = [
        'id',
        'name',
    ];
    public function dishes()
    {
        return $this->belongsToMany(Dish::class, 'meal_dishes', 'meal_id', 'dish_id');
    }
}
