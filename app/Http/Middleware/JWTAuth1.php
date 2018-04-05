<?php
/**
 * Created by PhpStorm.
 * User: SI
 * Date: 2018/4/3
 * Time: 16:06
 */

namespace App\Http\Middleware;


use Tymon\JWTAuth\Contracts\Providers\Auth;
use Tymon\JWTAuth\Http\Parser\Parser;
use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Manager;

class JWTAuth1 extends JWTAuth
{

    public function __construct(Manager $manager, Auth $auth, Parser $parser)
    {
        config(['auth.defaults.guard' => "staff"]);//就是他们了
        parent::__construct($manager, $auth, $parser);
    }
}