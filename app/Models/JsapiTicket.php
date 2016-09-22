<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JsapiTicket extends Model
{

    protected $hidden = ['expires_in','created_at','updated_at'];

    /**
     * 限制读取字段
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * [$guarded description]
     *
     * @var string
     */
    protected $table = "jsapi_token";
}
