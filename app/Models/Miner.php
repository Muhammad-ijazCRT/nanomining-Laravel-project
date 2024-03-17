<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class Miner extends Model {
    use Searchable;
    protected $guarded = ['id'];
    protected $hidden  = ['created_at', 'updated_at'];

    public function plans() {
        return $this->hasMany(Plan::class);
    }
    public function activePlans() {
        return $this->hasMany(Plan::class)->where('status', Status::ENABLE);
    }

    public function userCoinBalances() {
        return $this->hasOne(UserCoinBalance::class);
    }
}
