<?php

namespace App\Http\Controllers\Admin;

use App\Staff;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
class LoginController extends Controller
{
    //
    protected $guard = 'staff';

    public function index()
    {

                return view('admin.login');
    }

    public function login(Request $request)
    {
        $a = [
            'phone' => '17739388881',
            'password' => "123123",

        ];
        $r =Staff::where(['phone' => '17739388881'])->first();
        $password = "123123";
        if (decrypt($r->password )== $password) {
                $token = Auth::guard('staff')->login($r);
            return ['token'=>$token];
        }
    }
}
