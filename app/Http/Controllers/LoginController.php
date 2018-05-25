<?php

namespace App\Http\Controllers;

use App\Http\Server\WxXcx;
use App\User;
use function Composer\Autoload\includeFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //

    public function getToken(Request $request)
    {
        config('auth.defaults.guard');
        $xcx = new WxXcx($request);
        $token = $xcx->login();
        return response()->json([
            'token' => $token,
            'userInfo' => Auth::user(),
        ],200);
    }
    public function  getHomeInfo(Request $request)
    {
        $user = auth()->user();
        return response()->json([
                'contacts' => ['13837028118','13606221357'],
                'gonggao' => '',
                'gzh' => ['微信搜索关注公众号"沙集客运",便捷了解客车资讯,留言乘车意见与建议'],
                'categories' => []
        ],200);
    }

    public function getUserInfo()
    {

        $user = Auth::user();
        $user = User::with('role')->find($user->id);
        return response()->json(['userInfo' =>$user]);
    }
}

