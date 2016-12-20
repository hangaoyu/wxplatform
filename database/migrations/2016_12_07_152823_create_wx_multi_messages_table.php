<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWxMultiMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wx_multi_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('message_id')->unsigned();
            $table->foreign('message_id')->references('id')->on('wx_messages')->onDelete('cascade');
            $table->text('title')->comment('回复文本内容');
            $table->text('description')->comment('回复文本描述');
            $table->string('image')->comment('回复图片');
            $table->string('content_url')->comment('回复详情链接');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('wx_multi_messages');
    }
}
