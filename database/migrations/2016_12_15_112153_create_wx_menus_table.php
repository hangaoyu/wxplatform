<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWxMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wx_menus', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('type')->comment('菜单类型');
            $table->integer('level')->comment('菜单等级:1=>1级菜单,2=>2级菜单');
            $table->integer('level_id')->comment('二级菜单对应的一级菜单id');
            $table->string('name')->comment('微信用户id');
            $table->string('key')->comment('菜单Key');
            $table->string('url')->comment('微信用户id');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('wx_menus');
    }
}
