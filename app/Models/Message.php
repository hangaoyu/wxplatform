<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'wx_messages';

    public function mulitmessage()
    {
        return $this->hasMany(WxMultiMessage::class);
    }

}
