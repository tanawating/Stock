<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMainStock extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('main_stock', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id');
            $table->integer('treasury_id');
            $table->string('get_no',255);
            $table->datetime('get_time');
            $table->string('get_form',255);
            $table->string('get_name_form',255);
            $table->string('qty_start',255);
            $table->string('qty_total',255);
            $table->string('detail',255);
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
