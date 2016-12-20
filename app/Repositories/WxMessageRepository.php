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
        if ($event_name == 'TEMPLATESENDJOBFINISH') {
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
        try {
            $event = Event::where('event_name', $event_name)->first();
            switch ($event->return_id) {
                case 1:
                    return $event->description;
                case 2:
                    $news = new News([
                        'title' => $event->title,
                        'description' => $event->description,
//                        'url' => $event->content_url,
                        'image' => $event->image,
                        // ...
                    ]);
                    return $news;
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function handleMessage($user_message)
    {
        $user_message_name = $user_message->Content;
        $message = Message::where('message_name', $user_message_name)->first();
        switch ($message->return_id) {
            case 1:
                return $message->description;
            case 2:
                $news = new News([
                    'title' => $message->title,
                    'description' => $message->description,
                    'url' => $message->content_url,
//                    'image' => $message->image,
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

    public function handleImage($message)
    {

        return $message->PicUrl;
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