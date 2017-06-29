<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/4
 * Time: 14:40
 */

namespace App\Repositories;


use App\Models\Event;
use App\Models\Message;

use App\Models\PointsLog;

use App\Models\WxScanLog;
use App\Models\WxTemplate;
use App\Models\WxTemplateMessage;
use App\Models\WxUser;
use EasyWeChat\Message\News;

class WxMessageRepository extends CommonRepository
{


    public function server()
    {
        $wechat = app('wechat');
        $wechat->server->setMessageHandler(function ($message) {
            switch ($message->MsgType) {
                case 'event':
                    return $this->handleEvent($message);
                case 'text':
                    return $this->handleMessage($message);
                case 'image':
                    return $this->handleImage($message);
            }
        });
        return $wechat->server->serve();

    }

    public function handleEvent($message)
    {
        $event_name = $message->Event;
        switch ($event_name) {
            case 'subscribe':
                return $this->handleSubscribe($message);
            case 'SCAN':
                return $this->handleScanEvent($message);
            case 'TEMPLATESENDJOBFINISH':
                return $this->templateSendFinish($message);
        }

    }

    public function handleSubscribe($message)
    {
        $point_send = $this->handleSubscribePoints($message->FromUserName);
        $scene_str = $message->EventKey;
        if ($scene_str) {
            $this->scanLog($message);
            \Log::info('微信订阅带二维码参数' . $scene_str);
            $scene_str = substr($scene_str, 8);
            $event = Event::where('scene_str', $scene_str)->first();
        } else {
            \Log::info('微信订阅其他途径');
            $event = Event::where(['event_type' => 'subscribe', 'scene_str' => ''])->first();
        }
        if ($event) {
            return $this->getReturnNews($event);
        }
        return '';
    }

    public function handleScanEvent($message)
    {
        $scene_str = $message->EventKey;
        $this->scanLog($message);
        \Log::info('微信二维码扫描id' . $scene_str);
        $event = Event::where('scene_str', $scene_str)->first();
        if ($event) {
            return $this->getReturnNews($event);
        }
        return '';

    }

    public function getReturnNews(Event $event)
    {
        if (!$event) {
            return '';
        } else {
            switch ($event->return_id) {
                case 1:
                    return $event->description;
                case 2:
                    $news = new News([
                        'title' => $event->title,
                        'description' => $event->description,
                        'url' => $event->content_url,
                        'image' => $event->image,
                        // ...
                    ]);
                    return $news;
                case 3:
                    $mulitnews = [];
                    $mulitevents = $event->mulitevent;
                    foreach ($mulitevents as $key => $event) {
                        $news = new News([
                            'title' => $event->title,
                            'description' => $event->description,
                            'url' => $event->content_url,
                            'image' => $event->image,

                        ]);
                        array_push($mulitnews, $news);
                    }
                    return $mulitnews;
            }
        }
    }

    public function templateSendFinish($message)
    {
        $msgId = $message->MsgID;
        $status = $message->Status;
        $template_mesaage = WxTemplateMessage::where('msgid', $msgId)->first();
        if ($template_mesaage) {
            $template_mesaage->update(['return_status' => $status]);
        }
        if ($status !== 'success') {
            return '发送模板消息失败';
        } else {
            return '模板消息发送成功';
        }
    }

    public function handleMessage($user_message)
    {
        $user_message_name = $user_message->Content;
        $message = Message::where('message_name', $user_message_name)->first();
        if ($message) {
            switch ($message->return_id) {
                case 1:
                    return $message->description;
                case 2:
                    $news = new News([
                        'title' => $message->title,
                        'description' => $message->description,
                        'url' => $message->content_url,
                        'image' => $message->image,
                        // ...
                    ]);

                    return $news;
                case 3:
                    $mulitnews = [];
                    $mulitmessages = $message->mulitmessage;
                    foreach ($mulitmessages as $key => $message) {
                        $news = new News([
                            'title' => $message->title,
                            'description' => $message->description,
                            'url' => $message->content_url,
//                        'image' => $message->image,

                        ]);
                        array_push($mulitnews, $news);
                    }
                    return $mulitnews;
            }
        }
    }

    public function handleImage($message)
    {
        return $message->PicUrl;
    }
    public function scanLog($message)
    {
        $log['open_id'] = $message->FromUserName;
        $log['scene_str'] = $message->EventKey;
        $log['scan_time'] = date('Y-m-d H:i:s', $message->CreateTime);
        WxScanLog::create($log);

    }

    public function handleSubscribePoints($open_id)
    {
        $member = WxUser::where(['openid' => $open_id])->first();
        if (!$member) {
            \Log::info('订阅微信用户：' . $open_id . '未找到');
            return false;
        }
        $add_points = env('WECHAT_FIRST_SCUBSCRIBE_POINTS_ADD');
        if ($add_points == 0) {
            \Log::info('配置赠送积分为0');
            return false;
        }
        if ($member['subscribe_flag'] == 1) {
            $data['point'] = $member['point'] + $add_points;
            $data['subscribe_flag'] = 2;
            $member->update($data);

            $log['type'] = 2;
            $log['uid'] = $member['id'];
            $log['money'] = $add_points;
            $log['content'] = "用户：{{$member['nickname']}} 首次关注收入" . $add_points . '积分';
            PointsLog::create($log);
            return true;
        }
    }




//    获取用户列表
    public function getUserlist()
    {

        $wechat = app('wechat');
        $userService = $wechat->user;
        $user_data = $userService->lists()->data;
        $users = $user_data['openid'];
        foreach ($users as $key => $userid) {
            $user = $userService->get($userid)->toArray();
            $res = WxUser::where('openid', $userid)->first();
            if (!$res)
                WxUser::create($user);
        }

    }

    public function sendNotice()
    {
        $wechat = app('wechat');
        $broadcast = $wechat->broadcast;
        $broadcast->sendText("大家好！欢迎使用 EasyWeChat。");
    }


}