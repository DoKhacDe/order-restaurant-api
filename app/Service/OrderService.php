<?php

namespace App\Service;

use App\Http\Controllers\Controller;
use App\Repositories\OrderDishRepository;
use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{
    public function __construct(
        OrderRepository     $orderRepository,
        OrderDishRepository $orderDishRepository
    )
    {
        $this->orderRepository = $orderRepository;
        $this->orderDishRepository = $orderDishRepository;
    }

    public function save($input)
    {
        try {
            DB::beginTransaction();
            $data = [
                'meal_id' => $input['meal_id'],
                'restaurant_id' => $input['restaurant_id'],
                'people' => $input['people'],
                'user_name' => $input['user_name'],
                'phone' => $input['phone'],
                'email' => $input['email'],
            ];

            $dishes = json_decode($input['dishes']);
            $order = $this->orderRepository->create($data);
            if ($order) {
                foreach ($dishes as $item) {
                    $orderDetail = [
                        'order_id' => $order->id,
                        'dish_id' => $item->id,
                        'quality' => $item->quality,
                    ];
                    $this->orderDishRepository->create($orderDetail);
                }
            }
            DB::commit();
            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            return null;
        }
    }

    public function show($id) {
        return $this->orderRepository->OrderDetail($id);
    }
    public function index() {
        return $this->orderRepository->OrderList();
    }
}
