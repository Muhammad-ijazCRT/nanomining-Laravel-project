<?php

namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;

class ActiveMinedCoin extends Authenticatable
{
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    protected $table = 'active_mining_coins';

    public function orders()
    {
        return $this->belongsTo(Order::class);
    }
}
