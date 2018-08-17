<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMainDevice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('main_device', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('stock_id');
            $table->string('serial_number',255);
            $table->string('send_form',255);
            $table->string('send_detail',255);
            $table->boolean('is_status');
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
        //
    }
}
