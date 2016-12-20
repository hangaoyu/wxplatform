<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * This is the wechat repository facade class
 */
class WxTemplateRepository extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'wxtemplaterepository';
    }
}
