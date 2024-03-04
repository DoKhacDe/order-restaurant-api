<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table='orders';

    protected $fillable = [
        'id',
        'meal_id',
        'restaurant_id',
        'people',
        'phone',
        'user_name',
        'email',
    ];

    public function orderDish() {
        return $this->hasMany(OrderDish::class, 'order_id', 'id');
    }

    public function meal() {
        return $this->hasOne(Meal::class, 'id', 'meal_id');
    }

    public function restaurant() {
        return $this->hasOne(Restaurant::class, 'id', 'restaurant_id');
    }
}
