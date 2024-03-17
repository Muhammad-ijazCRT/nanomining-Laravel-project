<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class TestCorn extends Model
{
    protected $table = "corntest";
    use Searchable;
}
