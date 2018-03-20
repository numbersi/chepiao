<?php
/**
 * Created by PhpStorm.
 * User: SI
 * Date: 2018/3/17
 * Time: 17:20
 */

namespace App\Http\Server;


use App\Exceptions\wxException;
use App\User;
use GuzzleHttp\Client;
use App\common;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WxXcx
{
    protected $code;
    public $wxAppID ='' ;
    protected $wxAppSecret='' ;
    protected $wxLoginUrl='';
    protected $name;
    protected $avatarUrl;
    protected $user;
    function __construct(Request $request)
    {
        $this->user = new User();
        $this->name = $request->name?$request->name:'';
        $this->avatarUrl = $request->avatarUrl? $request->avatarUrl:'';
        $this->code = $request->code?$request->code:'';
        $this->wxAppID = env('wxAppID');
        $this->wxAppSecret =env('wxAppSecret');
        $this->wxLoginUrl = sprintf(
            env('wxLoginUrl'),
            $this->wxAppID, $this->wxAppSecret, $this->code);
    }
    public function login()
    {
        $openid = $this->getOpenid();
        $user = User::where('openid',$openid)->with('role')->first();
        if (!$user) {
            $this->user->name = $this->name;
            $this->user->avatarUrl = $this->avatarUrl;
            $this->user->openid = $openid;
            $this->user->avatarUrl = $this->avatarUrl;
            $this->user->save();
            $user = $this->user;
        }
       return Auth::login($user);
    }
    public function  getOpenid(){
        $wxResult = $this->getWxResult();
        return $wxResult['openid'];
    }

    public function getSessionKey(){
        $wxResult = $this->getWxResult();
        return $wxResult['session_key'];
    }

    public function getWxResult()
    {
        $client = new Client();
        $result =common::curl_get($this->wxLoginUrl);
        $wxResult = json_decode($result, true);
        if (empty($wxResult)) {
            return '获取session_key及openID时异常，微信内部错误';
        }else{
            if (array_key_exists('errcode',$wxResult)) {
                throw new  wxException($wxResult['errmsg'],$wxResult['errcode'] );
                return response()->json(['error' =>$wxResult['errcode'] , 500]);
            } else {
                return $wxResult;
            }
        }
    }
}