<?php

namespace App\Http\Controllers\Admin;

use App\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function paginate(Request $request)
    {
        $orders = Order::whereNotNull('pay_at')->with('ticket')->with('checker')->latest('pay_at')->paginate(15);
        return $orders;
    }

    public function check(Request $request)
    {
        $user = Auth::guard('staff')->user();
        $id = $request->id;
        if ($id) {
            $order  = Order::find($id);
            $order->status = 1;
            $order->checker_id = $user->id;
            $order->checked_at = Carbon::now()->format('Y-m-d H:i:s');
            $order->save();

            return response()->json(["status"=>true],200);
        }

    }


    public function checkTicket(Request $request)
    {
        $user = Auth::user();
        $token = $request->token;
        if ($token) {
            try{
                $s = decrypt($token);
            }catch (DecryptException $e){
                return response()->json( ['status'=>false,
                    'message' => '假票~~~！！！',
                ]);
            }
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
                $r = array_add($list = $this->getcheckedList(), 'status', true);
                $r = array_add($r, 'message', '验票成功');
                $r = array_add($r, 'user', $order->user);
                if ($order->save()) {
                    return response()->json($r);
                }
            }else{
                return response()->json( ['status'=>false,
                    'message' => '查无此票,请注意',
                ]);
            }

        }

    }

}
