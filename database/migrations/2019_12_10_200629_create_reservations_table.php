<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_profile_id')->unsigned();
            $table->integer('care_taker_profile_id')->unsigned();
            $table->foreign('client_profile_id')->references('id')->on('client_profiles');
            $table->integer('home_id')->unsigned();
            $table->foreign('home_id')->references('id')->on('homes');
            $table->foreign('care_taker_profile_id')->references('id')->on('care_taker_profiles');
            $table->string('status');
            $table->date('start_date');
            $table->date('end_date');
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
        Schema::dropIfExists('reservations');
    }
}
