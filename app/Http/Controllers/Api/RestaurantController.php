<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Service\RestaurantService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class RestaurantController extends Controller
{
    public function __construct(
        RestaurantService $restaurantService
    )
    {
        $this->restaurantService = $restaurantService;
    }

    public function index(Request $request)
    {
        try {
            $data = $this->restaurantService->index();
            return $this->responseJsonSuccess(__('message.find_success'), $data);
        }  catch (Exception $e)
        {
            Log::error($e->getTraceAsString());
            return $this->responseJsonError(__('message.find_error'));
        }
    }
}
