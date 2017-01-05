<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/4
 * Time: 14:40
 */

namespace App\Repositories;


class TokenRepository extends CommonRepository
{

    public function getLast()
    {
        $token = $this->model->where('expires', '>', date('Y-m-d H:i:s', time() - 60*10 ) )
            ->orderBy('id', 'desc')
            ->Limit(1)
            ->first();
        return $token;
    }

    public function getNewToken(){
        $appId = config('weixin.AppID');
        $AppSecret = config('weixin.AppSecret');
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appId}&secret={$AppSecret}";
        $data = (array)json_decode(file_get_contents($url));
        return $data;
    }
    
    public function getToken()
    {
        //使用Easy WeChat管理access token
        $wechat = app('wechat');
        // 获取 access token 实例
        $accessToken = $wechat->access_token; // EasyWeChat\Core\AccessToken 实例
        $token = $accessToken->getToken(); // token 字符串
        return [
            'id' => 0,
            'access_token' => $token,
            'errcode' => 0,
            'errmsg' => '',
            'expires' => ''
        ];

        $lastToken = $this->getLast();
        if( !$lastToken ){
            $data = $this->getNewToken();
            if( !in_array( 'errcode', $data) ){
                $time = time() + $data['expires_in'];
                $data['expires'] = date('Y-m-d H:i:s', $time);
                $this->create($data);
                $lastToken = $this->getLast();
            }
            else{
                $this->create($data);
                $lastToken = $data;
            }
        }
        return $lastToken;
    }
}