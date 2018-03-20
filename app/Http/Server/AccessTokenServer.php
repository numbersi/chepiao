<?php
/**
 * Created by PhpStorm.
 * User: si
 * Date: 2017/8/10
 * Time: 16:21
 */

namespace App\Api\Server;


use Illuminate\Support\Facades\Cache;

class AccessTokenServer
{
    protected $access_token_url ;
    public $token;
    function __construct()
    {
        $this->access_token_url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . env('wxAppID') . '&secret=' . env('wxAppSecret');
        $this->token = $this->getTokenFomeCache();
    }
    /**
     * @return string
     */
    public  function  getToken()
    {
        return $this->getTokenFomeCache();
    }
//    public function getFilesToken()
//    {
//        if (Storage::exists('AccessToken.txt')) {
//            $token = Storage::get('AccessToken.txt');
//            return $this->checkToken($token);
//        }else{
//            return $this->getUrlToken();
//        }
//
//    }

    public function getTokenFomeCache()
    {
        $c =   Cache::store('file');
        $token = $c->get('access_token');
        if ($token) {
            return $token;
        }
        return $this->getUrlToken();
    }

//    public function checkToken($token)
//    {
//        $users = User::get();
//        Storage::disk('local')->put('users.txt', $users);
//        foreach ($users as $user) {
//
//            $url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=' . $token;
//            $params = [
//                "touser" => $user->openid,
//                "msgtype" => "text",
//                "text" => [
//                    "content" => "Hello World"
//                ]
//            ];
//            $request = common::curl_post($url, $params);
//            $request = json_decode($request, true);
//            Storage::disk('local')->put('request.txt', $request);
//            if ($request['errcode'] == '40001' || $request['errcode'] == "42001") {
//                return $this->getUrlToken();
//            } elseif ($request['errcode'] == '45047') {
//                continue;
//            } else {
//                return $token;
//            }
//
//
//        }
//    }
    public function getUrlToken()
    {
        $client =  new \GuzzleHttp\Client();
        $r =$client->request('get', $this->access_token_url,[]);
        $request =$r->getBody();
        $request = json_decode($request,true);
        Cache::store('file')->put('access_token',  $request['access_token'], 110);
        return $request['access_token'];
    }
}