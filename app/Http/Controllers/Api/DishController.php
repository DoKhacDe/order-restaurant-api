<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DishController extends Controller
{
    public function __construct(
        DishService $dishService
    )
    {
        $this->dishService = $dishService;
    }
}
