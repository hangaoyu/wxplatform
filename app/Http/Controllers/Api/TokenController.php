<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/4
 * Time: 13:34
 */

namespace App\Http\Controllers\Api;


use App\Facades\TokenRepository;
use App\Http\Controllers\Controller;

class TokenController extends Controller
{
    public function get()
    {
        return TokenRepository::getToken();
    }
}