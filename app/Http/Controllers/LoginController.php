<?php

namespace App\Http\Controllers;

use App\Http\Server\WxXcx;
use function Composer\Autoload\includeFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //

    public function getToken(Request $request)
    {
        $xcx = new WxXcx($request);
        $token = $xcx->login();
        Auth::user();
        return response()->json([
            'token' => $token,
            'userInfo' => Auth::user(),
        ],200);
    }
    public function  getHomeInfo(Request $request)
    {
        $user = auth()->user();
        return response()->json([
           'contacts' => ['13837028118', '17739388881'],
                'gonggao' => '',
                'gzh' => ['微信搜索关注公众号"沙集客运",便捷了解客车资讯,留言乘车意见与建议'],
                'categories' => []
        ],200);
    }
}
