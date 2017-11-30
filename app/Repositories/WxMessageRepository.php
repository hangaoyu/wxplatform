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
use App\Models\WxScanLog;
use App\Models\WxTemplate;
use App\Models\WxTemplateMessage;
use App\Models\WxUser;
use Carbon\Carbon;
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
        \Log::info('事件类型' . $event_name);
        switch ($event_name) {
            case 'subscribe':
                return $this->handleSubscribe($message);
            case 'SCAN':
                return $this->handleScanEvent($message);
            case 'TEMPLATESENDJOBFINISH':
                return $this->templateSendFinish($message);
            case 'unsubscribe':
                return $this->unsucribeScanLog($message);
            case 'CLICK':
                return $this->handleclick($message);
        }

    }

    public function handleSubscribe($message)
    {
        $scene_str = $message->EventKey;

        if ($scene_str) {
            \Log::info('微信订阅带二维码参数' . $scene_str);
            $scene_str = substr($scene_str, 8);
            $message->EventKey = $scene_str;
            $event = Event::where('scene_str', $scene_str)->first();

        } else {
            \Log::info('微信订阅其他途径');
            $event = Event::where(['event_type' => 'subscribe'])->first();
        }
        if ($event) {
            return $this->getReturnNews($event, $message);
        }
        $this->scanLog($message);
        return '';
    }

    public function handleScanEvent($message)
    {
        $scene_str = $message->EventKey;
        $ticket = $message->Ticket;
        \Log::info('微信二维码扫描ticket' . $ticket);
        $event = Event::where('scene_str', $scene_str)->first();
        if ($event) {
            return $this->getReturnNews($event, $message);
        }
        $this->scanLog($message);
        return '';

    }

    public function handleClick($message)
    {
        $scene_str = $message->EventKey;
        \Log::info('点击事件');
        $event = Event::where(['scene_str' => $scene_str, 'event_type' => 'CLICK'])->first();
        if ($event) {
//            处理签到
            \Log::info($event['event_name']);
            if ($event['event_name'] == '签到') {

                return $this->handleSignInEvent($message, $event);
            }
            return $this->getReturnNews($event, $message);
        }
        $this->scanLog($message);
        return '';
    }

    public function handleSignInEvent($message, $event)
    {
        $data['openid'] = $message->FromUserName;
        $wechat = app('wechat');
        $userService = $wechat->user;
        $user = $userService->get($data['openid']);
        if ($user->subscribe == 0){
            return '请先关注公众号';
        }
        $ch = curl_init();
        $url = env('WX_SIGNINEVENT_URL');
        \Log::info('点击签到事件:[open_id]' . $data['openid'] . ';发送签到接口:' . $url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, 1); //启用POST提交
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $file_contents = curl_exec($ch);
        curl_close($ch);
        \Log::info('点击签到事件接口返回' .$file_contents);
        $res = json_decode($file_contents, true);
        if ($res['code'] == 200) {
            return $this->getReturnNews($event, $message);
        } else {
            return $res['msg'];
        }
    }

    public function getReturnNews(Event $event, $message)
    {
//        检查事件是否只能在首次扫描的时候返回信息
        $return_flag = $event['return_flag'];
        if ($return_flag == 1) {
            $log_count = WxScanLog::where(['open_id' => $message->FromUserName, 'scene_str' => $message->EventKey])->count();
            $this->scanLog($message);
            if ($log_count > 0) {
                return '';
            }
        } else {
            $this->scanLog($message);
        }

//返回回复信息
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
        $log['open_id'] = $message->FromUserName ? $message->FromUserName : '';
        $log['scene_str'] = $message->EventKey ? $message->EventKey : '';
        $log['event_type'] = $message->Event ? $message->Event : '';
        $log['scan_time'] = $message->CreateTime ? date('Y-m-d H:i:s', $message->CreateTime) : Carbon::now();
        $log['month'] = date("Y-m", $message->CreateTime);
        WxScanLog::create($log);

    }

    public function unsucribeScanLog($message)
    {
        $log['open_id'] = $message->FromUserName ? $message->FromUserName : '';
        $log['scan_time'] = $message->CreateTime ? date('Y-m-d H:i:s', $message->CreateTime) : Carbon::now();
        $log['event_type'] = $message->Event ? $message->Event : '';
        $data = WxScanLog::where(['open_id' => $log['open_id'], 'event_type' => 'subscribe'])
            ->where('created_at', '<', $log['scan_time'])
            ->orderBy('created_at', 'DESC')->first();
        $log['scene_str'] = $data['scene_str'];
        $log['month'] = date("Y-m", $message->CreateTime);
        WxScanLog::create($log);

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