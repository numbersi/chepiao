<?php

namespace App\Http\Controllers;

use App\Http\Server\WxServer;
use App\Http\Server\WxXcx;
use app\Wechat\WXBizDataCrypt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function getPhone(Request $request)
    {
        $user = Auth::user();
        if (!$user->phoneNumber) {
            $iv = $request->iv;
            $encryptedData = $request->encryptedData;
            $ut = new WxXcx($request);
            $sessionKey = $ut->getSessionKey();
            $pc = new WXBizDataCrypt($ut->wxAppID, $sessionKey);
            $errCode = $pc->decryptData($encryptedData, $iv, $data);
            if ($errCode == 0) {
                $data = json_decode($data, true);
                $user->phoneNumber =  $data['phoneNumber'];
                $user->save();
            } else {
                return response()->json(['status' => false, 'errCode' => $errCode]);
            }
        }
        return response()->json(['status' => true, 'userinfo' => $user,]);


    }
}
