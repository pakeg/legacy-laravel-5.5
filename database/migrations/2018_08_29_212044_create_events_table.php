<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('event_id')->unsigned();
            $table->timestamp('time')->nullable();
            $table->integer('time_status');
            $table->integer('league_id')->unsigned();
            $table->integer('home')->unsigned();
            $table->integer('away')->unsigned();
            $table->string('timer');
            $table->integer('scores_home');
            $table->integer('scores_away');
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
        Schema::dropIfExists('events');
    }
}
