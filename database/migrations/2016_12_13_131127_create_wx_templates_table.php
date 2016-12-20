<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWxTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wx_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('templateId')->comment('模板消息id');
            $table->string('title')->comment('模板消息标题');
            $table->string('url')->comment('模板消息链接');
            $table->text('content')->comment('模板消息内容');
            $table->integer('status')->comment('模板使用状态:0,已删除使用,1,可以使用');
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
        Schema::drop('wx_templates');
    }
}
