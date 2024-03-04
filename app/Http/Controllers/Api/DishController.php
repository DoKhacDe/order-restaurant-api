<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Service\DishService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DishController extends Controller
{
    public function __construct(
        DishService $dishService
    )
    {
        $this->dishService = $dishService;
    }

    public function index(Request $request) {
        try {
            $meal_id = $request->meal_id;
            $restaurant_id = $request->restaurant_id;
            $data = $this->dishService->index($meal_id, $restaurant_id);
            return $this->responseJsonSuccess(__('message.find_success'), $data);
        }  catch (Exception $e)
        {
            Log::error($e->getTraceAsString());
            return $this->responseJsonError(__('message.find_error'));
        }
    }
}
