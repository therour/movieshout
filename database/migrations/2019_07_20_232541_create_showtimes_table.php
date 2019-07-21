<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShowtimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('showtimes', function (Blueprint $table) {
            $table->string('provider_id')->index();
            $table->string('provider')->index();
            $table->dateTime('showtime')->index();
            $table->string('movie_id')->index();
            $table->string('cinema_id')->index();
            $table->json('properties')->nullable();
            $table->timestamps();

            $table->primary(['provider_id', 'provider']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('showtimes');
    }
}
