<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventLineupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_lineups', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('event_id')->unsigned();
            $table->json('home_start');
            $table->json('away_start');
            $table->json('home_subs');
            $table->json('away_subs');
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
        Schema::dropIfExists('event_lineups');
    }
}
