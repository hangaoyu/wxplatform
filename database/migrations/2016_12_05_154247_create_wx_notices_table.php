<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWxNoticesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wx_notices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('notice_name')->comment("通知名称");
            $table->string('openid')->comment('通知对象,没有则为群发');
            $table->integer('return_id')->comment('通知类型,1->文本,2->图文,3->action');
            $table->integer('is_send')->comment('是否已发送,0->未发送,1->已发送');
            $table->integer('mediaId')->comment('素材id');
            $table->text('title')->comment('回复文本内容');
            $table->text('description')->comment('回复文本描述');
            $table->string('image')->comment('回复图片');
            $table->string('content_url')->comment('回复详情链接');
            $table->string('action')->comment('回复处理方法');
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
        Schema::drop('wx_notices');
    }
}
