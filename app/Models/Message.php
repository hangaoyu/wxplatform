<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    public function mulitmessage()
    {
        return $this->hasMany(WxMultiMessage::class);
    }

}