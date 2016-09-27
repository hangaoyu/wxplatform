<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/4
 * Time: 17:57
 */

namespace App\Repositories;


use App\Facades\TokenRepository;

class JsapiTicketRepository extends CommonRepository
{
    public function getLast()
    {
        $tikcet = $this->model->where('expires', '>', date('Y-m-d H:i:s', time() - 60*10 ) )
            ->orderBy('id', 'desc')
            ->Limit(1)
            ->first();
        return $tikcet;
    }

    public function getNewTikcet(){
        $token = TokenRepository::getToken();
        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".$token['access_token']."&type=jsapi";
        $data = (array)json_decode(file_get_contents($url));
        return $data;
    }

    public function getTicket()
    {
        $lastTicket = $this->getLast();
        if( !$lastTicket ){
            $data = $this->getNewTikcet();
            if( $data['errcode'] == 0 ){
                $time = time() + $data['expires_in'];
                $data['expires'] = date('Y-m-d H:i:s', $time);
                $this->create($data);
                $lastTicket = $this->getLast();
            }
            else{
                $this->create($data);
                $lastTicket = $data;
            }
        }
        return $lastTicket;
    }
}