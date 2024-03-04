<?php

namespace App\Service;

use App\Repositories\MealRepository;

class DishService
{
    public function __construct(
        MealRepository $mealRepository
    )
    {
        $this->mealRepository = $mealRepository;
    }

    public function index($meal_id, $restaurant_id) {
        return $this->mealRepository->listDishes($meal_id, $restaurant_id);
    }
}
