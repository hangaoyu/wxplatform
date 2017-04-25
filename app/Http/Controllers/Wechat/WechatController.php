<?php

namespace App\Http\Controllers\Wechat;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\WxMenu;
use App\Models\WxSubMenu;
use App\Models\WxTemplate;
use App\Models\WxUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class WechatController extends Controller
{
    /**
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function server( Request $request )
    {
        Log::debug(
            '微信回调原始数据:' . json_encode( $request->all(), JSON_UNESCAPED_UNICODE )
        );
        return app('wxmessagerepository')->server();
    }


    public function test()
    {



//        $content = array(
//            "first" => "恭喜你购买成功！", "name" => "巧克力", "price" => "39.8元", "remark" => "欢迎再次购买！",
//        );
//        $t = json_encode($content,JSON_UNESCAPED_UNICODE);
//
//        $a = json_decode($t, true);
//        dd($t);
//        $i = 0;
//        $star = microtime(true);
//
//            $url = "http://xike.jojin.com:8081/sendtemplate";
//            $post_data = array('opertion_time' => '2016-12-13 11:23:45', 'is_delay' => 0, 'data' => '{"first":"恭喜你购买成功！","name":"巧克力","price":"39.8元","remark":"欢迎再次购买！"}', 'openId' => 'o2VsywNkSxBZ3uffbP-tCl5m90Mc', 'templateId' => 'Q2Z5NbjyV6q3sp9LV-1Y5k2Q3ARb-YGqb-OjQgIWBoY');
//            $ch = curl_init();
//            curl_setopt($ch, CURLOPT_URL, $url);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//            // post数据
//            curl_setopt($ch, CURLOPT_POST, 1);
//            // post的变量
//            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
//            $output = curl_exec($ch);
//            curl_close($ch);
//            //打印获得的数据
//
//            $i++;
//
//        $old = microtime(true);
//        return $old-$star;
    }

//      获取模板列表
    public function getPrivateTemplates()
    {
        return app('wxtemplaterepository')->getPrivateTemplates();

    }

//       发送模板消息
    public function sendTemplate(Request $request)
    {

        return app('wxtemplaterepository')->sendTemplate($request);


    }

    public function sendDelayTemplate(Request $request)
    {

        return app('wxtemplaterepository')->sendDelayTemplate($request);


    }


    public function getUserList()
    {
        app('wxtemplaterepository')->getUserlist();
    }

    public function sendNotice()
    {
        return app('wxmessagerepository')->sendNotice();

    }

    public function menu()
    {
        $wechat = app('wechat');
        $menu = $wechat->menu;
        $menu->destroy();
        $mainButtons = WxMenu::where('level', 1)->orderBy('order','ASC')->get();
        $buttons = collect($mainButtons)->map(function ($mainButton) {
            $submenu = WxMenu::where('level_id', $mainButton->id)->orderBy('order','ASC')->get();
            if (count($submenu) > 0) {
                $subbuttons = collect($submenu)->map(function ($subbutton) {
                    return ['type' => $subbutton->type, 'name' => $subbutton->name, 'url' => $subbutton->url];
                })->toArray();
                return ['name' => $mainButton->name, 'sub_button' => $subbuttons];

            } else {
                return [
                    "type" => $mainButton->type,
                    "name" => $mainButton->name,
                    "url" => $mainButton->url,
                ];
            }
        })->toArray();

        if ($buttons) {
            try {
                $menu->add($buttons);
                return 'menu update';
            } catch (\Exception $e) {
                return (['erro' => $e->getMessage()]);
            }
        }

    }

    public function destoryMenu()
    {
        $wechat = app('wechat');
        $menu = $wechat->menu;
        $menu->destroy();
    }

    public function getQrCode(){

        header("content-type: image/jpeg");
        $scene_id = rand(1,100000);
        Log::info('生成二维码');
        $wechat = app('wechat');
        $qrcode = $wechat->qrcode;
        $result = $qrcode->forever($scene_id);
        $ticket = $result->ticket;
        $url = $qrcode->url($ticket);
        $content = file_get_contents($url);
        echo $content;
//        return $ticket;
    }
}
