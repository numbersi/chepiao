<?php
/**
 * Created by PhpStorm.
 * User: SI
 * Date: 2018/3/19
 * Time: 12:18
 */

namespace App\Http\Server;


use app\Wechat\WxPayApi;
use app\Wechat\WxPayJsApiPay;
use app\Wechat\WxPayUnifiedOrder;
use Illuminate\Support\Facades\Auth;

class wxPayServer
{
    protected $trade_no;
    protected $price;
    protected $body;
    protected $notify_url;
    protected $user;
    protected $order;
    public function __construct($no, $price, $body, $notify_url, $order)
    {
        $this->trade_no = $no;
        $this->price = $price;
        $this->body = $body;
        $this->notify_url = $notify_url;
        $this->user = Auth::user();
        $this->order = $order;
    }

    public function PaySignature()
    {

        $wxOrderData = new WxPayUnifiedOrder();
        $wxOrderData->SetOut_trade_no($this->trade_no);
        $wxOrderData->SetTrade_type("JSAPI");
        $wxOrderData->SetTotal_fee($this->price * 100);
        $wxOrderData->SetBody($this->body);
        $wxOrderData->SetOpenid($this->user->openid);
        $wxOrderData->SetNotify_url($this->notify_url);
        return $this->getPaySignature($wxOrderData);
    }

    private function getPaySignature($wxOrderData)
    {


        $wxOrder = WxPayApi::unifiedOrder($wxOrderData);


        if ($wxOrder['return_code'] != 'SUCCESS' ||
            $wxOrder['result_code'] != 'SUCCESS'
        ) {
            return [];
        }
        $signature = $this->sign($wxOrder);

        $this->recordPreOrder($wxOrder['prepay_id']);


        return $signature;

    }

    private function sign($wxOrder)
    {
        $jsApiPayData = new WxPayJsApiPay();
        $jsApiPayData->SetAppid(env('wxAppID'));
        $jsApiPayData->SetTimeStamp((string)time());
        $rand = md5(time() . mt_rand(0, 11111));
        $jsApiPayData->SetNonceStr($rand);
        $jsApiPayData->SetPackage('prepay_id=' . $wxOrder['prepay_id']);
        $jsApiPayData->SetSignType('md5');
        $sign = $jsApiPayData->MakeSign();
        $rawData = $jsApiPayData->GetValues();
        $rawData['paySign'] = $sign;
        return $rawData;
    }

    private function recordPreOrder($prepay_id)
    {
        $this->order->prepay_id = $prepay_id;
        $this->order->save();
    }
    public function notifyUrl()
    {

        $wxServer = new WxServer();

        $wxServer->Handle();


    }

}