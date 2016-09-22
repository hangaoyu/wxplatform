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
        $token = $this->model->where('expires', '>', date('Y-m-d H:i:s', time() - 200 ) )
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