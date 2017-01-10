<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/4
 * Time: 14:40
 */

namespace App\Repositories;


use App\Models\WxTemplate;
use App\Models\WxTemplateMessage;
use Illuminate\Http\Request;


class WxTemplateRepository extends CommonRepository
{


//同步模板消息列表
    public function getPrivateTemplates()
    {
        $db_templates = WxTemplate::lists('templateId')->toArray();
        $wechat = app('wechat');
        $notice = $wechat->notice->getPrivateTemplates()->toArray();
        $templates = [];
        foreach ($notice['template_list'] as $key => $template) {
            $res = WxTemplate::where('templateId', $template['template_id'])->first();
            if (!$res) {
                WxTemplate::create(['templateId' => $template['template_id'], 'status' => 1, 'title' => $template['title'], 'content' => $template['content']]);
            }
            array_push($templates, $template['template_id']);
        }
        $diff1 = array_diff($db_templates, $templates);
        //            db:delete
        foreach ($diff1 as $key => $templeteId) {
            $template = WxTemplate::where('templateId', $templeteId)->first();
            $template->status = 0;
            $template->update();
        }
    }

//发送即时模板消息
    public function sendTemplate(Request $request)
    {
        try {
            $data = $request->all();
            $this->checkParam(['openId', 'templateId', 'is_delay', 'opertion_time', 'data'], $data);
            $delay = $data['is_delay'];
//请求如果是立即发送直接发送
            if ($delay === '0') {
                $result = $this->send($data);
                $data['issend'] = 1;
                $res = WxTemplateMessage::create(array_merge($data, $result));
                return ['ret_msg'=>$result['errmsg']];
            } //请求延迟发送赛如数据库然后等平台来检测调用
            else {
                $data['issend'] = 0;
                $res = WxTemplateMessage::create(array_merge($data));
                return ['ret_msg'=>'delay is Ok'];
            }
        } catch (\Exception $e) {
            return (['ret_msg' => $e->getMessage()]);
        }
    }


//请求来源于管理平台
    public function sendDelayTemplate(Request $request)
    {
        try {
            $this->checkParam(['id'], $request->all());

            $temple = WxTemplateMessage::where('id', $request->get('id'))->first();
            if ($temple){
                $data = $temple->toArray();
                $result = $this->send($data);
                if ($result['errmsg'] =='ok'){
                    $res = $temple->update(array_merge($result, ['issend' => 1]));
                }
                return $result['errmsg'];
            }
            else{
                return 'not found id';
            }


        } catch (\Exception $e) {
            $id = $request->get('id');
            $temple = WxTemplateMessage::where('id', $request->get('id'))->first();
            if ($temple){
                $temple->update(['errmsg'=>$e->getMessage()]);
            }
            return (['retDesc' => $e->getMessage()]);
        }
    }

    public function send($data)
    {
        $wechat = app('wechat');
        $notice = $wechat->notice;
        $userId = $data['openId'];
        $templateId = $data['templateId'];
        $content = json_decode($data['data'], true);
        $result = $notice->uses($templateId)->andData($content)->andReceiver($userId)->send();
        return json_decode($result, true);
    }

//    检查参数
    private function checkParam(array $need_params, $data)
    {
        foreach ($need_params as $k => $v) {
            if (!isset($data[$v]) || $data[$v] == '') {
                throw new \Exception('缺少参数：' . $v);
            }
        }
    }

}