<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJsapiTicketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jsapi_token', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ticket');
            $table->integer('expires_in');
            $table->integer('errcode');
            $table->string('errmsg');
            $table->dateTime('expires');
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
        Schema::drop('jsapi_token');
    }
}
