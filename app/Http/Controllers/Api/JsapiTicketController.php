<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/5
 * Time: 9:24
 */

namespace app\Http\Controllers\Api;


use App\Facades\JsapiTicketRepository;
use App\Http\Controllers\Controller;

class JsapiTicketController extends Controller
{
    public function get()
    {
        //echo __CLASS__, ':', __FUNCTION__;
        return JsapiTicketRepository::getTicket();
    }
}