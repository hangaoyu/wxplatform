<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    //
    protected $table='wx_events';
    public function mulitevent()
    {
        return $this->hasMany(WxMultiEvent::class);
    }
}
