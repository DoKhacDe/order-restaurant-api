<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDish extends Model
{
    use HasFactory;
    protected $table='order_dishes';

    protected $fillable = [
        'dish_id',
        'order_id',
        'quality',
    ];

    public function dish() {
        return $this->hasOne(Dish::class,'id','dish_id');
    }
}
