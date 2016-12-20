<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WxTemplateMessage extends Model
{
    //
    protected $fillable=['templateId','openId','data','url','issend','is_delay','opertion_time','errcode','errmsg','msgid','return_status'];
}
