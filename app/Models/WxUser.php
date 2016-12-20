<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WxUser extends Model
{
    //
    protected $fillable=['openid','nickname','sex','city','province','subscribe_time'];
}
