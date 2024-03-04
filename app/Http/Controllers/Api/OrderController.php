<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Service\DishService;
use App\Service\OrderService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function __construct(
        OrderService $orderService
    )
    {
        $this->orderService = $orderService;
    }

    public function save(Request $request) {
        try {
            $input = $request->all();
            $data = $this->orderService->save($input);
            return $this->responseJsonSuccess(__('success'), $data);
        }  catch (Exception $e)
        {
            Log::error($e->getTraceAsString());
            return $this->responseJsonError(__('error'));
        }
    }

    public function show($id) {
        try {
            $data = $this->orderService->show($id);
            return $this->responseJsonSuccess(__('success'), $data);
        }  catch (Exception $e)
        {
            Log::error($e->getTraceAsString());
            return $this->responseJsonError(__('error'));
        }
    }

    public function index() {
        try {
            $data = $this->orderService->index();
            return $this->responseJsonSuccess(__('success'), $data);
        }  catch (Exception $e)
        {
            Log::error($e->getTraceAsString());
            return $this->responseJsonError(__('error'));
        }
    }
}
