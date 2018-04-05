<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Staff extends Authenticatable implements JWTSubject
{
    use HasApiTokens, Notifiable;
    //
    protected $table = 'staffs';
    protected $fillable = ['name', 'phone', 'password'];
    protected $hidden = ['password', 'remember_token'];
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {

        // TODO: Implement getJWTIdentifier() method.
        return $this->getKey(); // Eloquent model method

    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        // TODO: Implement getJWTCustomClaims() method.
        return []; // Eloquent model method

    }
}
