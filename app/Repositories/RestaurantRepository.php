<?php


namespace App\Repositories;

use Illuminate\Container\Container as App;

class RestaurantRepository extends BaseRepository
{
    public function model()
    {
        return \App\Models\Restaurant::class;
    }
    public function __construct(App $app)
    {
        parent::__construct($app);
    }
}
