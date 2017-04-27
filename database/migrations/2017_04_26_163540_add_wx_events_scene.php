<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWxEventsScene extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wx_events', function (Blueprint $table) {
            $table->integer('scene_id')->comment('场景值ID');
            $table->string('scene_str')->comment('字符串形式的ID');
            $table->string('event_type')->comment('事件类型');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wx_events', function (Blueprint $table) {
            $table->dropColumn('scene_id');
            $table->dropColumn('scene_str');
            $table->dropColumn('event_type');
        });
    }
}
