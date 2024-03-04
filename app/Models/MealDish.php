<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealDish extends Model
{
    use HasFactory;

    protected $table = "meal_dishes";
    protected $fillable = [
        'id',
        'dish_id',
        'meal_id',
    ];
    protected $hidden = ['pivot'];
}
