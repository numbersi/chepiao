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
        $t_count  = Order::where(['status' => 1])->get()->sum('t_count');
        $t_money  = Order::where(['status' => 1])->get()->sum('total_price');
        $data = ['count_num' => $t_count, 'count_money' => $t_money];
        return view('admin.index',$data);
    }
}
