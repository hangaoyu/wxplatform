<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * This is the wechat repository facade class
 */
class WxMessageRepository extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'wxmessagerepository';
    }
}
