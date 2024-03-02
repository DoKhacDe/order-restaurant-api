<?php

namespace App\Repositories;

use Illuminate\Container\Container as App;

class DishRepository extends BaseRepository
{
    public function model()
    {
        return \App\Models\Dish::class;
    }
    public function __construct(App $app)
    {
        parent::__construct($app);
    }
}
