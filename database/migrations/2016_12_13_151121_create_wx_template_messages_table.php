<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWxTemplateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wx_template_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('templateId')->comment('模板消息id');
            $table->string('openId')->comment('微信用户id');
            $table->text('data')->comment('消息内容');
            $table->string('url')->comment('消息链接');

            $table->integer('issend')->comment('是否已经发送:0 未发送;1 已经发送');
            $table->integer('is_delay')->comment('是否延迟发送:0 立即发送 ;1 延迟发送');
            $table->timestamp('opertion_time')->comment('发送时间');

            $table->integer('errcode')->comment('错误状态');
            $table->string('errmsg')->comment('返回信息');
            $table->string('msgid')->comment('发送id');

            $table->string('return_status')->comment('时间推送的返回值');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('wx_template_messages');
    }
}
