<?php
/**
 * Created by PhpStorm.
 * User: SI
 * Date: 2018/3/19
 * Time: 12:41
 */

namespace App\Http\Server;
use App\Api\Server\AccessTokenServer;
use App\common;
use App\Order;
use App\User;
use app\Wechat\WxPayNotify;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class WxServer extends WxPayNotify
{
    public function NotifyProcess($data, &$msg)
    {
        if ($data['result_code'] == 'SUCCESS') {
            $no = $data['out_trade_no'];
            $token = $this->getToken($no);
            $order = Order::where(['order_no' => $no, 'pay_at' => null])->first();
            if ($order) {
                $order->token = $token;
                $order->pay_at = Carbon::now()->format('Y-m-d H:i:s');
                $order->save();
                $this->senMoMessage($order);
            } else {
                return true;
            }
        } else {
            return true;
        }

    }
    public function getToken($tno)
    {
        return encrypt('NumberSi0102' . $tno);
    }
    public function senMoMessage($order)
    {
        $accessTokenServer = new AccessTokenServer();
        Storage::disk('local')->put('accessTokenServer.txt', $accessTokenServer);

        $url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=' . $accessTokenServer->token;
        $user = User::find($order->user_id);
        $params = [
            'touser' => $user->openid,
            'template_id' => 'IXLD8bxIF_YMjQ2cnJW1oIVSjVCXVVl50goeJhLqnLw',
            'page' => 'pages/me/me',
            'form_id' => $order->prepay_id,
            "data" => [
                "keyword1" => [
                    "value" => "沙集客运微信票",
                    "color" => "#173177"
                ],
                "keyword2" => [
                    "value" => $order->order_no,
                    "color" => "#173177"
                ],
                "keyword3" => [
                    "value" => "请近日乘车,以免过期",
                    "color" => "#173177"
                ],
                "keyword4" => [
                    "value" => "每天最晚8点发车",
                    "color" => "#173177"
                ],
                "keyword5" => [
                    "value" => "上车请出票,请勿让他人获取二维码",
                    "color" => "#173177"
                ],
                "keyword6" => [
                    "value" => $order->t_count . '张,共' . $order->total_price . ' 元',
                    "color" => "#173177"
                ],
                "keyword7" => [
                    "value" => "乘车旅途中如果遇到问题,请拨打13837028118",
                    "color" => "#173177"
                ],
            ],

        ];

        common::curl_post($url, $params);

    }


}