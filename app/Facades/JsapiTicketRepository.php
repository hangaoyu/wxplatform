<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/4
 * Time: 17:58
 */

namespace App\Facades;


use Illuminate\Support\Facades\Facade;

class JsapiTicketRepository extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'jsapiticketrepository';
    }
}