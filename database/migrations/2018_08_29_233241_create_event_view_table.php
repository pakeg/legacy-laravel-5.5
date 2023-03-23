<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventViewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_views', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('event_id')->unsigned();
            $table->timestamp('time')->nullable();
            $table->integer('time_status');
            $table->integer('league_id')->unsigned();
            $table->string('timer');
            $table->json('stats');
            $table->json('events');
            $table->json('away_m');
            $table->json('home_m');
            $table->json('referee');
            $table->integer('round');
            $table->json('stadium');            
            $table->timestamps();

            $table->foreign('league_id')
                ->references('id')
                ->on('leagues');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_views');
    }
}
