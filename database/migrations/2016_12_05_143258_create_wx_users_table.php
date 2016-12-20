<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWxUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wx_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('openid')->comment('微信openid')->unique();
            $table->string('nickname')->comment('用户名');
            $table->integer('sex')->comment('性别');
            $table->string('city')->comment('城市');
            $table->string('province')->comment('省份');
            $table->string('subscribe_time')->comment('订阅时间');
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
        Schema::drop('wx_users');
    }
}
