<?php

namespace App\Http\Controllers;

use App\Order;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckOrderController extends Controller
{
    //

    public function getChecked(Request $request)
    {
        $list = $this->getcheckedList();

        return response()->json($list);

    }

    public function getcheckedList()
    {
        $user = Auth::user();
        $checkOrders = $user->checkOrders;
        $moneyCount = $checkOrders->sum('total_price');
        return [
            'checkOrders' => $checkOrders,
            'moneyCount' => $moneyCount
        ];
    }
    public function checkTicket(Request $request)
    {
        $user = Auth::user();
        $token = $request->token;

        if ($token) {
            $s = decrypt($token);
            $no = str_after( $s ,'NumberSi0102');

            $order = Order::where('order_no',$no)->first();
            if ($order) {
                if (      $order->status ==1 ){
                    return response()->json(['status'=>false,
                        'message' => '此票已验,检验时间 :'.$order->checked_at,
                    ]);
                }elseif(      $order->status ==2 ){
                    return response()->json(['status'=>false,
                        'message' => '此票已退 ,退票时间:'.$order->updated_at,
                    ]);
                }
                $order->status = 1;
                $order->checker_id = $user->id;
                $order->checked_at = Carbon::now()->format('Y-m-d H:i:s');
                if ($order->save()) {
                    return response()->json(array_add($list = $this->getcheckedList(), 'status', true));
                }
            }else{
                return response()->json( ['status'=>false,
                    'message' => '查无此票,请注意',
                ]);
            }

        }

    }

}
