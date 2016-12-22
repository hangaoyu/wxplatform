<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderWxmenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wx_menus', function (Blueprint $table) {
            $table->integer('order')->comment('菜单排序');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wx_menus', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
}
