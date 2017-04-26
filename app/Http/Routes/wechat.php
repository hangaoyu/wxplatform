<?php
/**
 * Created by PhpStorm.
 * User: hangaoyu
 * Date: 16/12/1
 * Time: 上午11:15
 */
Route::any('wechat', [
    'as'         => 'wechat',
    'uses'       => 'WechatController@server',
]);

Route::any('weixinApi/callback', [
    'as'         => 'weixinApi/callback',
    'uses'       => 'WechatController@server',
]);
Route::any('test', [
    'as'         => 'test',
    'uses'       => 'WechatController@test',
]);
Route::any('send', [
    'as'         => 'send',
    'uses'       => 'WechatController@sendToUser',
]);
Route::get('menu', [
    'as'         => 'menu',
    'uses'       => 'WechatController@menu',
]);
Route::get('destoryMenu', [
    'as'         => 'destoryMenu',
    'uses'       => 'WechatController@destoryMenu',
]);


Route::any('sendnotice', [
    'as'         => 'send',
    'uses'       => 'WechatController@sendNotice',
]);
Route::any('template', [
    'as'         => 'send',
    'uses'       => 'WechatController@getPrivateTemplates',
]);
Route::post('sendtemplate', [
    'as'         => 'send',
    'uses'       => 'WechatController@sendTemplate',
]);
Route::post('SendDelayTemplate', [
    'as'         => 'send',
    'uses'       => 'WechatController@sendDelayTemplate',
]);
Route::get('getQrCode', [
    'as'         => 'getQrCode',
    'uses'       => 'WechatController@getQrCode',
]);
Route::post('getTicket', [
    'as'         => 'ticket',
    'uses'       => 'WechatController@getTicket',
]);
Route::post('getQrCode', [
    'as'         => 'ticket',
    'uses'       => 'WechatController@getQrCode',
]);