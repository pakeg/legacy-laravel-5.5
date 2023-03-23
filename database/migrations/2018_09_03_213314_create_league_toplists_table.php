<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeagueToplistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('league_toplists', function (Blueprint $table) {
            $table->integer('country_id')->unsigned();
            $table->integer('goal_points')->default(0)->nullable();
            $table->integer('goals')->default(0)->nullable();
            $table->integer('penalties')->default(0)->nullable();
            $table->integer('matches')->default(0)->nullable();
            $table->integer('minutes_played')->default(0)->nullable();
            $table->integer('substituted_in')->default(0)->nullable();
            $table->integer('player_id')->unsigned();
            $table->integer('team_id')->unsigned();
            $table->integer('assists')->default(0)->nullable();
            $table->integer('red_cards')->default(0)->nullable();
            $table->integer('yellow_cards')->default(0)->nullable();
            $table->timestamps();

            $table->foreign('country_id')
                ->references('id')
                ->on('countries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('league_toplists');
    }
}
