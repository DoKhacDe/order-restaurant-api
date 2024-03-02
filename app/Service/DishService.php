<?php

namespace App\Service;

use App\Repositories\DishRepository;

class DishService
{
    public function __construct(
        DishRepository $dishRepository
    )
    {
        $this->dishRepository = $dishRepository;
    }

}
