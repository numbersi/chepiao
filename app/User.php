<?php

namespace App;


use Laravel\Passport\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'aut', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function orders($whereParams){
        return $this->hasMany(Order::class,'user_id','id')->where($whereParams);

    }

    public function orders_new()
    {
        $whereParams = [
            'status' => 0,
            'checked_at'=>null
        ];
        return $this->orders($whereParams)->whereNotNull('token')->latest('pay_at');

    }
    public function orders_checked()
    {
        $whereParams = [
            'status' => 1,
        ];
            return $this->orders($whereParams)->whereNotNull('checked_at');
    }

    public function checkOrders()
    {
            $date  =   date('y-m-d', time());

        $whereParams = [
            'status' => 1,
        ];
        return $this->hasMany(Order::class, 'checker_id', 'id')
            ->whereNotNull('token')
            ->whereNotNull('pay_at')
            ->where($whereParams)
            ->latest('checked_at')
            ->with('user')
            ->whereDate('updated_at',$date);
    }





    public function role()
    {
        return $this->belongsTo(Role::class,'role_id','id');
    }


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

}
