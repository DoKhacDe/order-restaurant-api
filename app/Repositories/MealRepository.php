<?php

namespace App\Repositories;

use Illuminate\Container\Container as App;

class MealRepository extends BaseRepository
{
    public function model()
    {
        return \App\Models\Meal::class;
    }
    public function __construct(App $app)
    {
        parent::__construct($app);
    }

    public function listDishes($meal_id, $restaurant_id) {
        $meals = $this->model->where('id', $meal_id)->first();
        if ($meals) {
            $dishes = $meals->dishes()->select('dishes.id', 'dishes.name','dishes.restaurant_id')
                ->where('dishes.restaurant_id', $restaurant_id)
                ->get();
            return $dishes;
        }
    }
}
