<?php

namespace App\Http\Controllers\Admin;

use App\Order;
use App\Staff;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
class IndexController extends Controller
{
    //

    public function index()
    {
        $orders_c = Order::where(['status' => 1])->whereNotNull('token')->get();
        $t_count_c  =$orders_c->sum('t_count');
        $t_money_c  =$orders_c->sum('total_price');

        $orders_n = Order::where(['status' => 0])->whereNotNull('token')->get();
        $t_count_n  =$orders_n->sum('t_count');
        $t_money_n  = $orders_n->sum('total_price');

        $orders = Order::whereNotNull('token')->get();
        $t_count    =  $orders->sum('t_count');
        $t_money    =  $orders->sum('total_price');
        $data = [
            'count_orders_c' => $t_count_c,
            'count_orders_money_c' => $t_money_c,
            'count_orders_n' => $t_count_n,
            'count_orders_money_n' => $t_money_n,
            'count_orders' => $t_count,
            'count_orders_money' => $t_money,
        ];
        return view('admin.index',$data);
    }
}
