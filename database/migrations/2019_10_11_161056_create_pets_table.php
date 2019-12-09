<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_profile_id')->unsigned();
            $table->foreign('client_profile_id')->references('id')->on('client_profiles');
            $table->string('image');
            $table->string('name');
            $table->string('size');
            $table->string('temperament');
            $table->string('race');
            $table->string('description')->nullable();
            $table->string('allergies')->nullable();
            $table->string('feeding')->nullable();
            $table->string('specials_cares')->nullable();
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
        Schema::dropIfExists('pets');
    }
}
