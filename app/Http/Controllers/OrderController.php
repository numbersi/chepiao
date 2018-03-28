<?php

namespace App\Http\Controllers;

use App\Http\Server\wxPayServer;
use App\Order;
use App\Ticket;
use App\User;
use app\Wechat\WxPayUnifiedOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function payT(Request $request)
    {
        $tid = $request->tid ? $request->tid :1 ;
        $t_count = $request->count ? $request->count :1 ;
        $t = Ticket::find($tid);
        $order = $this->createOrder($t,$t_count);
        $wxPay = new wxPayServer($order->order_no,$order->total_price,$t->title."车票",'https://t.numbersi.cn/api/notifyUrl',$order);
        return $wxPay->PaySignature();
    }

    public function getOrderByUser()
    {
        $user = Auth::user();
        $orders_new = $user->orders_new;
        $orders_checked = $user->orders_checked;
        return response(['orders_new'=>$orders_new,'orders_checked'=>$orders_checked],200);
    }



    private function createOrder($t, $t_count)
    {


       $orderNo  = $this->getOrderNo();
       $order = new  Order();
        $order->order_no = $orderNo;
        $order->user_id =  Auth::user()->id;
        $order->t_id = $t->id ;
        $order->t_count = $t_count ;
        $order->origin_price = $t->origin_price ;
        $order->current_price = $t->current_price ;
        $order->total_price =    number_format($t->current_price * $t_count, 2) ;

        $order->save();
        return $order;
        /*
         *
         * origin_price
         * current_price
         * total_price
         */

    }

    private function getOrderNo()
    {
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderSn =
            $yCode[intval(date('Y')) - 2017] . strtoupper(dechex(date('m'))) . date(
                'd') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf(
                '%02d', rand(0, 99));
        return $orderSn;
    }
}
