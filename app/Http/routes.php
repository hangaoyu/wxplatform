<?php
Route::get('/', function () {

    return redirect('backend/index/');
});

/* 后台登录模块 */
Route::group(['namespace' => 'Auth'], function () {
    require_once __DIR__ . '/Routes/auth.php';
});

/* 前端管理模块 */
Route::group(['namespace' => 'Frontend', 'prefix' => 'frontend', ], function () {
    require_once __DIR__ . '/Routes/frontend.php';
});

/* 后台管理模块 */
Route::group([
    'prefix' => 'backend',
    'namespace' => 'Backend',
    'middleware' => ['authenticate', 'authorize'],
], function () {
    require_once __DIR__ . '/Routes/backend.php';
});

/* 接口模块 */
Route::group([
    'prefix' => 'api',
    'namespace' => 'Api',
    'middleware' => ['apiAuthenticate'],
], function () {
    require_once __DIR__ . '/Routes/api.php';
});

/* 微信处理 */
Route::group([

    'namespace' => 'Wechat',

], function () {
    require_once __DIR__ . '/Routes/wechat.php';
});
