<?php


namespace App\Repositories;

use Illuminate\Container\Container as App;

class OrderDishRepository extends BaseRepository
{
    public function model()
    {
        return \App\Models\OrderDish::class;
    }
    public function __construct(App $app)
    {
        parent::__construct($app);
    }
}
