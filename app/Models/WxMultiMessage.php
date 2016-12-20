<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WxMultiMessage extends Model
{
    public function message(){
        return $this->belongsTo(Message::class);
    }
  
}
