<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 't_id', 'id');
    }
}
