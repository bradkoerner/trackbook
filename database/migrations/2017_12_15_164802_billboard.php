<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Billboard extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billboard', function (Blueprint $table) {
            $table->increments('id');
            $table->string('season');
            $table->string('year');
            $table->enum('metric', ['season', 'semester']);
            $table->integer('rank');
            $table->string('artist');
            $table->string('track');
            $table->string('spotify_id')->nullable();
            $table->integer('count');
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
        Schema::dropIfExists('billboard');
    }
}
