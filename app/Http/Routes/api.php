<?php
/**
 * Created by PhpStorm.
 * User: 黄忠羽
 * Date: 2016/8/4
 * Time: 13:15
 */

Route::get('token/get', [
    'as'         => 'api.token.get',
    'uses'       => 'TokenController@get',
]);

Route::get('jstikcet/get', [
    'as'         => 'api.jstikcet.get',
    'uses'       => 'JsapiTicketController@get',
]);
