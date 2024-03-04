<?php

namespace App\Service;

use App\Repositories\RestaurantRepository;

class RestaurantService
{
    public function __construct(
        RestaurantRepository $restaurantRepository
    )
    {
        $this->restaurantRepository = $restaurantRepository;
    }

    public function index() {
        return $this->restaurantRepository->all();
    }
    public function show($id) {
        return $this->restaurantRepository->findById($id);
    }
}
