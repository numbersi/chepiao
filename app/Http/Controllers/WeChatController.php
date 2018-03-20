<?php

namespace App\Http\Controllers;

use App\Http\Server\WxServer;
use Illuminate\Http\Request;

class WeChatController extends Controller
{
    //

        /*
         * 支付回调
         */
    public function notifyUrl()
    {
        $wxServer = new WxServer();
        $wxServer->Handle();
    }
}
