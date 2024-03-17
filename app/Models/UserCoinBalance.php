<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCoinBalance extends Model
{
    protected $guarded = ['id'];


    public function miner()
    {
        return $this->belongsTo(Miner::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userCoinBalanceMiner1()
    {
        return $this->belongsTo(UserCoinBalance::class, 'user_id', 'user_id')->where('miner_id', 1);
    }

    public function userCoinBalanceMiner2()
    {
        return $this->belongsTo(UserCoinBalance::class, 'user_id', 'user_id')->where('miner_id', 2);
    }

    // public function order()
    // {
    //     return $this->belongsTo(Order::class);
    // }

    public function order()
    {
        return $this->hasMany(Order::class);
    }
}
