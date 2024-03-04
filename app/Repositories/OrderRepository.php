<?php


namespace App\Repositories;

use Illuminate\Container\Container as App;

class OrderRepository extends BaseRepository
{
    public function model()
    {
        return \App\Models\Order::class;
    }
    public function __construct(App $app)
    {
        parent::__construct($app);
    }

    public function OrderDetail($id) {
        return $this->model
            ->where('id', $id)
            ->with(['orderDish' => function($q) {
                $q->select('dish_id', 'order_id','quality')
                    ->with(['dish' => function($qq) {
                        $qq->select('id','name');
                    }]);
            }])
            ->with(['meal' => function($q) {
                $q->select('id','name');
            }])
            ->with(['restaurant' => function($q) {
                $q->select('id','name');
            }])
            ->first();
    }
    public function OrderList() {
        return $this->model
            ->with(['meal' => function($q) {
                $q->select('id','name');
            }])
            ->with(['restaurant' => function($q) {
                $q->select('id','name');
            }])
            ->orderBy('created_at','desc')
            ->get();
    }
}
