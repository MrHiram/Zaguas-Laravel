<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('homes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('care_taker_profile_id')->unsigned();
            $table->foreign('care_taker_profile_id')->references('id')->on('care_taker_profiles');
            $table->string('image');
            $table->string('description');
            $table->double('price_per_night',8,2);
            $table->string('capacity');
            $table->boolean('walk');
            $table->string('days_available');
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
        Schema::dropIfExists('homes');
    }
}
