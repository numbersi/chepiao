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

        $r =Staff::where(['phone' => $request['phone']])->first();
        $password = $request['password'];
        if (decrypt($r->password )== $password) {
                $token = Auth::guard('staff')->login($r);
            return ['token'=>$token];
        }
    }
}
